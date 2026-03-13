<?php

declare(strict_types=1);

namespace App\Providers;

use App\Controllers\Admin\UserController as AdminUserController;
use App\Controllers\Api\ArticleApiController;
use App\Controllers\Api\CategoryApiController;
use App\Controllers\AuthController;
use App\Controllers\DocsController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\UserController;
use App\Dao\ArticleDao;
use App\Dao\CategoryDao;
use App\Dao\UserDao;
use App\Events\RoleChanged;
use App\Events\UserLoggedIn;
use App\Events\UserRegistered;
use App\Listeners\LogRoleChange;
use App\Listeners\LogUserActivity;
use App\Listeners\SendWelcomeEmail;
use Core\Auth\Auth;
use Core\Container;
use Core\Events\EventDispatcher;
use Core\Logger;
use Core\Mailer\Mailer;
use Core\Middleware\BearerTokenMiddleware;
use Core\Request;
use Core\ServiceProviderInterface;
use Core\Session;
use Core\View;

/**
 * Enregistre les services applicatifs : DAOs et contrôleurs.
 *
 * C'est ici qu'il faut ajouter tout nouveau DAO ou contrôleur
 * sans toucher aux providers du framework.
 */
final class AppServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container, array $appConfig, array $dbConfig): void
    {
        // -------------------------------------------------------------------------
        // Events & Listeners
        // -------------------------------------------------------------------------

        /** @var EventDispatcher $dispatcher */
        $dispatcher = $container->make(EventDispatcher::class);

        $dispatcher->listen(UserRegistered::class, SendWelcomeEmail::class);
        $dispatcher->listen(UserRegistered::class, LogUserActivity::class);
        $dispatcher->listen(UserLoggedIn::class,   LogUserActivity::class);
        $dispatcher->listen(RoleChanged::class,    LogRoleChange::class);

        // ── Middlewares API ───────────────────────────────────────────────────
        $container->bind(BearerTokenMiddleware::class,
            fn() => new BearerTokenMiddleware((string) ($appConfig['api_key'] ?? '')));

        // -------------------------------------------------------------------------
        // DAOs / Repositories
        // -------------------------------------------------------------------------

        $container->singleton(ArticleDao::class,
            fn(Container $c) => new ArticleDao($c->make(\PDO::class)));

        $container->singleton(CategoryDao::class,
            fn(Container $c) => new CategoryDao($c->make(\PDO::class)));

        $container->singleton(UserDao::class, function (Container $c) use ($dbConfig): UserDao {
            $dao = new UserDao($c->make(\PDO::class));

            if (($dbConfig['driver'] ?? 'sqlite') === 'sqlite') {
                $dao->createTableIfNotExists();
            }

            return $dao;
        });

        // -------------------------------------------------------------------------
        // Services
        // -------------------------------------------------------------------------

        $container->singleton(Mailer::class, function () use ($appConfig): Mailer {
            /** @var array<string, mixed> $mail */
            $mail = $appConfig['mail'] ?? [];

            return new Mailer(
                driver:     (string) ($mail['driver']     ?? 'mail'),
                host:       (string) ($mail['host']       ?? 'localhost'),
                port:       (int)    ($mail['port']       ?? 25),
                username:   (string) ($mail['username']   ?? ''),
                password:   (string) ($mail['password']   ?? ''),
                encryption: (string) ($mail['encryption'] ?? ''),
                from:       (string) ($mail['from']       ?? 'noreply@localhost'),
                fromName:   (string) ($mail['from_name']  ?? 'Mini-MVC'),
            );
        });

        // -------------------------------------------------------------------------
        // Contrôleurs
        // -------------------------------------------------------------------------

        $container->bind(HomeController::class, fn(Container $c) => new HomeController(
            view:    $c->make(View::class),
            userDao: $c->make(UserDao::class),
            version: (string) ($appConfig['version'] ?? '1.0.0'),
        ));

        $container->bind(UserController::class, fn(Container $c) => new UserController(
            view:    $c->make(View::class),
            request: $c->make(Request::class),
            userDao: $c->make(UserDao::class),
            session: $c->make(Session::class),
            auth:    $c->make(Auth::class),
        ));

        $container->bind(DocsController::class, fn(Container $c) => new DocsController(
            view: $c->make(View::class),
        ));

        $container->bind(ProfileController::class, fn(Container $c) => new ProfileController(
            view:    $c->make(View::class),
            request: $c->make(Request::class),
            auth:    $c->make(Auth::class),
            userDao: $c->make(UserDao::class),
            session: $c->make(Session::class),
        ));

        // ── Contrôleurs API ───────────────────────────────────────────────────
        $container->bind(ArticleApiController::class, fn(Container $c) => new ArticleApiController(
            articleDao:  $c->make(ArticleDao::class),
            categoryDao: $c->make(CategoryDao::class),
            request:     $c->make(Request::class),
        ));

        $container->bind(CategoryApiController::class, fn(Container $c) => new CategoryApiController(
            categoryDao: $c->make(CategoryDao::class),
            request:     $c->make(Request::class),
        ));

        $container->bind(AdminUserController::class, fn(Container $c) => new AdminUserController(
            view:       $c->make(View::class),
            request:    $c->make(Request::class),
            userDao:    $c->make(UserDao::class),
            session:    $c->make(Session::class),
            auth:       $c->make(Auth::class),
            dispatcher: $c->make(EventDispatcher::class),
        ));

        $container->bind(AuthController::class, fn(Container $c) => new AuthController(
            view:              $c->make(View::class),
            request:           $c->make(Request::class),
            auth:              $c->make(Auth::class),
            userDao:           $c->make(UserDao::class),
            session:           $c->make(Session::class),
            mailer:            $c->make(Mailer::class),
            dispatcher:        $c->make(EventDispatcher::class),
            registrationMode:  (string) ($appConfig['auth_registration'] ?? 'direct'),
            appBaseUrl:        (string) ($appConfig['base_url'] ?? ''),
        ));
    }
}
