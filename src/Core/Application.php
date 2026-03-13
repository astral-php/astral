<?php

declare(strict_types=1);

namespace Core;

use Core\Auth\Auth;
use Core\Exception\AuthorizationException;
use Core\Exception\CsrfException;
use Core\Exception\NotFoundException;
use Core\Http\ApiResponse;
use Core\ServiceProviderInterface;
use Throwable;

/**
 * Point d'entrée de l'application.
 *
 * Orchestre dans l'ordre :
 *   1. Logger             — disponible dès le début pour capturer les erreurs
 *   2. Environnement      — timezone, affichage d'erreurs
 *   3. Base de données    — création du dossier SQLite si absent
 *   4. Conteneur DI       — via config/dependencies.php
 *   5. Session            — démarrée avant tout rendu
 *   6. Partages de vue    — $session, $csrf et $auth disponibles dans toutes les vues
 *   7. Routeur            — via config/routes.php
 *   8. Dispatch           — résolution + pipeline middleware + contrôleur
 *
 * Seule la constante BASE_PATH doit être définie avant d'appeler run().
 */
final class Application
{
    /** @var array<string, mixed> */
    private array $appConfig = [];

    private string $basePath;
    private Logger $logger;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    // -------------------------------------------------------------------------
    // Point d'entrée public
    // -------------------------------------------------------------------------

    public function run(): void
    {
        $this->loadDotEnv();
        $this->ensureStorageDirs();

        $this->logger    = new Logger($this->basePath . '/storage/logs');
        $this->appConfig = require $this->basePath . '/config/app.php';
        $dbConfig        = require $this->basePath . '/config/database.php';

        $this->bootEnvironment($this->appConfig);
        $this->ensureDatabase($dbConfig);

        $container = new Container();
        $this->loadDependencies($container, $this->appConfig, $dbConfig);

        // Session — doit être démarrée avant tout rendu de vue
        /** @var Session $session */
        $session = $container->make(Session::class);
        $session->start();

        // Partage global dans toutes les vues
        /** @var View $view */
        $view = $container->make(View::class);
        $view->share('session', $session);
        $view->share('csrf', $container->make(CsrfGuard::class));
        $view->share('auth', $container->make(Auth::class));

        $request = $container->make(Request::class);
        $router  = new Router(request: $request, container: $container);
        $this->loadRoutes($router);

        $isApiRequest = str_starts_with($request->uri, '/api/');

        try {
            $router->dispatch();
        } catch (CsrfException | AuthorizationException $e) {
            $isApiRequest
                ? ApiResponse::forbidden($e->getMessage())->send()
                : $this->handleForbidden($e, $view);
        } catch (NotFoundException $e) {
            $isApiRequest
                ? ApiResponse::notFound($e->getMessage())->send()
                : $this->handleNotFound($e, $view);
        } catch (Throwable $e) {
            $isApiRequest
                ? $this->handleApiServerError($e)
                : $this->handleServerError($e, $view);
        }
    }

    // -------------------------------------------------------------------------
    // Bootstrap
    // -------------------------------------------------------------------------

    /**
     * Charge le fichier .env via vlucas/phpdotenv.
     * Utilise safeLoad() : aucune exception si .env est absent
     * (utile en production où les variables sont injectées par le serveur).
     */
    private function loadDotEnv(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable($this->basePath);
        $dotenv->safeLoad();
    }

    /** @param array<string, mixed> $config */
    private function bootEnvironment(array $config): void
    {
        date_default_timezone_set((string) ($config['timezone'] ?? 'UTC'));

        if ($config['debug'] ?? false) {
            ini_set('display_errors', '1');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', '0');
            error_reporting(0);
        }
    }

    /** Crée les répertoires storage/ nécessaires s'ils n'existent pas. */
    private function ensureStorageDirs(): void
    {
        foreach (['storage/logs', 'storage/cache'] as $dir) {
            $path = $this->basePath . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }

    /** @param array<string, mixed> $dbConfig */
    private function ensureDatabase(array $dbConfig): void
    {
        if (($dbConfig['driver'] ?? '') === 'sqlite') {
            $dir = dirname((string) $dbConfig['database']);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }

    // -------------------------------------------------------------------------
    // Chargement des fichiers de configuration
    // -------------------------------------------------------------------------

    /**
     * Charge la liste des Service Providers depuis config/dependencies.php
     * et appelle register() sur chacun d'eux.
     *
     * @param array<string, mixed> $appConfig
     * @param array<string, mixed> $dbConfig
     */
    private function loadDependencies(Container $container, array $appConfig, array $dbConfig): void
    {
        /** @var list<class-string<ServiceProviderInterface>> $providers */
        $providers = require $this->basePath . '/config/dependencies.php';

        foreach ($providers as $providerClass) {
            (new $providerClass())->register($container, $appConfig, $dbConfig);
        }
    }

    private function loadRoutes(Router $router): void
    {
        $register = require $this->basePath . '/config/routes.php';
        $register($router);
    }

    // -------------------------------------------------------------------------
    // Gestion des erreurs HTTP
    // -------------------------------------------------------------------------

    private function handleForbidden(Throwable $e, View $view): void
    {
        http_response_code(403);
        $this->logger->warning('CSRF: ' . $e->getMessage());
        echo $view->render('errors/403', [
            'title'   => '403 — Accès refusé',
            'message' => $e->getMessage(),
        ]);
    }

    private function handleNotFound(NotFoundException $e, View $view): void
    {
        http_response_code(404);
        echo $view->render('errors/404', [
            'title'   => '404 — Page introuvable',
            'message' => $e->getMessage(),
        ]);
    }

    private function handleApiServerError(Throwable $e): void
    {
        $this->logger->error($e->getMessage(), [
            'class' => get_class($e),
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
        ]);

        $message = ($this->appConfig['debug'] ?? false)
            ? $e->getMessage()
            : 'Erreur interne du serveur.';

        ApiResponse::error('SERVER_ERROR', $message, 500)->send();
    }

    private function handleServerError(Throwable $e, View $view): void
    {
        http_response_code(500);

        $this->logger->error($e->getMessage(), [
            'class' => get_class($e),
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
        ]);

        if ($this->appConfig['debug'] ?? false) {
            echo '<pre style="background:#1e1e2e;color:#cdd6f4;padding:2rem;font-size:13px;border-radius:8px;margin:2rem;line-height:1.6">';
            echo '<strong style="color:#f38ba8">⚠ ' . get_class($e) . '</strong>' . "\n\n";
            echo htmlspecialchars($e->getMessage(), ENT_QUOTES) . "\n\n";
            echo htmlspecialchars($e->getTraceAsString(), ENT_QUOTES);
            echo '</pre>';
        } else {
            echo $view->render('errors/500', [
                'title'   => 'Erreur serveur',
                'message' => 'Une erreur interne est survenue.',
            ]);
        }
    }
}
