<!-- Vue : docs/index — Documentation Astral MVC -->

<!-- Sidebar + contenu côte à côte -->
<div class="flex gap-8 items-start">

    <!-- ─── Sidebar de navigation ─── -->
    <aside class="hidden lg:block w-56 shrink-0 sticky top-6">
        <nav class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-2">Sommaire</p>
            <ul class="space-y-0.5">
                <?php
                $sections = [
                    'intro'       => 'Introduction',
                    'structure'   => 'Structure',
                    'router'      => 'Router',
                    'container'   => 'Container (DI)',
                    'controller'  => 'Contrôleurs',
                    'view'        => 'Vues',
                    'request'     => 'Request',
                    'response'    => 'Response',
                    'session'     => 'Session & Flash',
                    'validator'   => 'Validator',
                    'csrf'        => 'CSRF Guard',
                    'middleware'  => 'Middleware',
                    'auth'        => 'Auth & Rôles',
                    'dao'         => 'AbstractDao & DAO',
                    'migrations'  => 'Migrations DB',
                    'scaffolding' => 'Scaffolding',
                    'events'      => 'Events & Listeners',
                    'mailer'      => 'Mailer',
                    'logger'      => 'Logger',
                    'cache'       => 'Cache',
                    'console'     => 'Console CLI',
                    'providers'   => 'Service Providers',
                    'env'         => '.env & Config',
                    'admin'       => 'Admin — Rôles',
                    'relations'   => 'Relations (ORM léger)',
                    'api'         => 'API REST JSON',
                    'new-module'  => 'Créer un module',
                    'from-scratch'=> 'Projet from scratch',
                    'ecosystem'   => 'Écosystème Composer',
                    'evolutions'  => 'Évolutions futures',
                ];
                foreach ($sections as $id => $label):
                ?>
                <li>
                    <a href="#<?= $id ?>"
                       class="block px-2 py-1.5 rounded-lg text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition">
                        <?= htmlspecialchars($label) ?>
                    </a>
                </li>
                <?php endforeach ?>
            </ul>
        </nav>
    </aside>

    <!-- ─── Corps de la documentation ─── -->
    <div class="flex-1 min-w-0 space-y-16 prose prose-gray max-w-none">

        <?php
        // Helper : afficher un bloc de code
        function codeBlock(string $lang, string $code): void {
            echo '<div class="bg-gray-900 text-gray-100 rounded-xl overflow-x-auto text-xs font-mono mt-3">';
            echo '<div class="flex items-center gap-2 bg-gray-800 px-4 py-2 text-gray-400 text-xs">';
            echo '<span class="w-2 h-2 bg-red-400 rounded-full"></span>';
            echo '<span class="w-2 h-2 bg-yellow-400 rounded-full"></span>';
            echo '<span class="w-2 h-2 bg-green-400 rounded-full"></span>';
            echo '<span class="ml-2">' . htmlspecialchars($lang) . '</span>';
            echo '</div>';
            echo '<pre class="p-4 overflow-x-auto">' . htmlspecialchars(trim($code)) . '</pre>';
            echo '</div>';
        }
        ?>

        <!-- ─────────────────── INTRO ─────────────────── -->
        <section id="intro">
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 text-white rounded-2xl p-8 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-3xl font-mono font-bold bg-white/20 px-3 py-1 rounded-lg">✦</span>
                    <div>
                        <h1 class="text-3xl font-bold">Astral MVC</h1>
                        <p class="text-indigo-200 text-sm">Framework PHP 8.x &mdash; Minimaliste &bull; Moderne &bull; Orienté objet</p>
                    </div>
                </div>
                <p class="text-indigo-100 leading-relaxed">
                    Astral MVC est un micro-framework PHP 8.x sans dépendance externe inutile.
                    Son objectif : vous donner <strong class="text-white">tous les outils essentiels</strong> d'une vraie application web
                    (routeur, DI, auth, validateur, mailer, cache, CLI…) dans une architecture
                    simple à comprendre, à étendre et à tester.
                    <span class="block mt-1 text-xs text-indigo-200">
                        Documentation à jour pour Astral MVC v1.1.2 (écosystème Composer : astral-form, astral-vite).
                    </span>
                </p>
            </div>

            <h2 class="text-xl font-bold text-gray-900 mb-3" id="intro-install">Installation rapide</h2>
            <?php codeBlock('bash', <<<'CODE'
git clone https://github.com/vous/astral-mvc.git
cd astral-mvc
composer install
cp .env.example .env
# éditer .env puis :
php -S localhost:8080 -t public
CODE) ?>
        </section>

        <!-- ─────────────────── STRUCTURE ─────────────────── -->
        <section id="structure">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Structure du projet</h2>
            <?php codeBlock('text', <<<'CODE'
astral-mvc/
├── bin/
│   └── console                      # Entrée CLI
├── config/
│   ├── app.php                      # Config applicative (lit le .env)
│   ├── database.php                 # Config base de données
│   ├── dependencies.php             # Liste des ServiceProviders
│   └── routes.php                   # Déclaration de toutes les routes
├── database/
│   └── migrations/                  # Fichiers de migration (YYYY_MM_DD_HHMMSS_nom.php)
├── public/
│   └── index.php                    # Front controller (minimaliste)
├── src/
│   ├── Controller/
│   │   └── AbstractController.php
│   ├── Core/
│   │   ├── Application.php          # Orchestrateur principal
│   │   ├── Auth/                    # Auth, Role, Middleware (Auth/Admin/Guest)
│   │   ├── Cache.php
│   │   ├── Console/
│   │   │   ├── Console.php          # Dispatcher + helpers ANSI
│   │   │   ├── CommandInterface.php
│   │   │   └── Commands/
│   │   │       ├── ClearCacheCommand.php
│   │   │       ├── MigrateCommand.php           # migrate
│   │   │       ├── MigrateRollbackCommand.php   # migrate:rollback
│   │   │       ├── MigrateStatusCommand.php     # migrate:status
│   │   │       └── MakeMigrationCommand.php     # make:migration
│   │   ├── Container.php            # Conteneur DI
│   │   ├── CsrfGuard.php
│   │   ├── Exception/               # CsrfException, NotFoundException, …
│   │   ├── Http/                    # Response, JsonResponse, RedirectResponse
│   │   ├── Logger.php
│   │   ├── Mailer/Mailer.php
│   │   ├── Middleware/              # MiddlewareInterface, CsrfMiddleware
│   │   ├── Providers/               # FrameworkServiceProvider, DatabaseServiceProvider
│   │   ├── Request.php
│   │   ├── Router.php
│   │   ├── ServiceProviderInterface.php
│   │   ├── Session.php
│   │   ├── Validator.php
│   │   └── View.php
│   └── Database/
│       ├── AbstractDao.php
│       ├── Connection.php           # Singleton PDO (SQLite / MySQL)
│       ├── Exception/DatabaseException.php
│       └── Migration/
│           ├── Migration.php        # Classe abstraite (up / down)
│           └── Migrator.php         # Moteur : run, rollback, status
├── app/
│   ├── Controllers/                 # Contrôleurs applicatifs
│   ├── Dao/                         # DAOs applicatifs
│   ├── Models/                      # Modèles (User, …)
│   └── Providers/AppServiceProvider.php
├── views/
│   ├── auth/                        # login, register, verify-pending, …
│   ├── docs/                        # Documentation (cette page)
│   ├── errors/                      # 403, 404, 500
│   ├── home/
│   ├── layout/main.php              # Layout principal (Tailwind CSS)
│   ├── profile/
│   └── user/
├── storage/
│   ├── cache/
│   └── logs/
├── .env                             # Variables d'environnement (ignoré par git)
└── .env.example                     # Modèle .env
CODE) ?>
        </section>

        <!-- ─────────────────── ROUTER ─────────────────── -->
        <section id="router">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Router</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Router</code> — Routeur HTTP à expressions régulières.
                Supporte tous les verbes HTTP, les paramètres dynamiques, les middlewares par route et les groupes.
            </p>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Enregistrer des routes</h3>
            <?php codeBlock('php', <<<'CODE'
// config/routes.php
return function (Router $router): void {

    // GET simple
    $router->get('/', HomeController::class, 'index');

    // Paramètre dynamique
    $router->get('/users/:id', UserController::class, 'show');

    // Tous les verbes HTTP
    $router->post('/users',           UserController::class, 'store');
    $router->put('/users/:id',        UserController::class, 'update');
    $router->patch('/users/:id',      UserController::class, 'partialUpdate');
    $router->delete('/users/:id',     UserController::class, 'destroy');

    // Middleware par route
    $router->get('/dashboard', DashboardController::class, 'index')
           ->middleware(AuthMiddleware::class);

    // Groupe avec préfixe et middleware partagés
    $router->group('/admin', function (Router $r): void {
        $r->get('/users', AdminController::class, 'users');
        $r->get('/stats', AdminController::class, 'stats');
    }, [AdminMiddleware::class]);
};
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Verb spoofing (formulaires HTML)</h3>
            <?php codeBlock('html', <<<'CODE'
<form method="POST" action="/users/42">
    <input type="hidden" name="_method" value="DELETE">
    <!-- … -->
</form>
CODE) ?>
        </section>

        <!-- ─────────────────── CONTAINER ─────────────────── -->
        <section id="container">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Container (Injection de dépendances)</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Container</code> — Conteneur IoC léger
                avec support du autowiring, singletons et liaisons personnalisées.
            </p>
            <?php codeBlock('php', <<<'CODE'
// Singleton : une seule instance pour toute la requête
$container->singleton(Database::class, function (): Database {
    return new Database(dsn: 'sqlite:/path/app.sqlite');
});

// Bind : nouvelle instance à chaque résolution
$container->bind(MyService::class, fn(Container $c) => new MyService(
    $c->make(Database::class)
));

// Résolution automatique (autowiring)
$service = $container->make(MyService::class);

// Vérifier une liaison
if ($container->has(SomeInterface::class)) { … }
CODE) ?>
        </section>

        <!-- ─────────────────── CONTROLLER ─────────────────── -->
        <section id="controller">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Contrôleurs</h2>
            <p class="text-gray-600 mb-4">
                Étendre <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Controller\AbstractController</code>
                pour bénéficier des helpers <code>render</code>, <code>redirect</code>, <code>json</code> et <code>validate</code>.
            </p>
            <?php codeBlock('php', <<<'CODE'
namespace App\Controllers;

use Controller\AbstractController;
use Core\Http\Response;
use Core\Request;
use Core\Session;
use Core\View;

final class ArticleController extends AbstractController
{
    public function __construct(
        View            $view,
        private Request $request,
        private Session $session,
    ) {
        parent::__construct($view);
    }

    public function index(): Response
    {
        return $this->render('article/index', [
            'title'    => 'Articles',
            'articles' => [],
        ]);
    }

    public function store(): Response
    {
        $v = $this->validate($this->request->post(), [
            'title'   => 'required|min:3|max:200',
            'content' => 'required|min:10',
        ]);

        if ($v->fails()) {
            return $this->render('article/create', [
                'title'  => 'Nouvel article',
                'errors' => $v->errors(),
                'old'    => $this->request->post(),
            ]);
        }

        // … sauvegarder l'article …

        $this->session->flash('success', 'Article créé !');
        return $this->redirect('/articles');
    }

    public function show(string $id): Response
    {
        // Réponse JSON
        return $this->json(['id' => $id, 'title' => 'Mon article']);
    }
}
CODE) ?>
        </section>

        <!-- ─────────────────── VIEW ─────────────────── -->
        <section id="view">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Vues</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\View</code> — Moteur de templates PHP natif.
                Les vues sont chargées depuis <code>views/</code> et utilisent le layout <code>views/layout/main.php</code>.
                Les variables passées à <code>render()</code> sont disponibles directement dans le template.
            </p>
            <?php codeBlock('php', <<<'CODE'
<!-- views/article/index.php -->
<h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
<?php foreach ($articles as $article): ?>
    <p><?= htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') ?></p>
<?php endforeach ?>
CODE) ?>
            <p class="text-gray-500 text-sm mt-3">
                Dans le layout, les variables globales <code>$auth</code>, <code>$csrf</code>, <code>$session</code>, <code>$viewEngine</code> sont injectées automatiquement.
            </p>

            <h3 class="font-semibold text-gray-800 mt-6 mb-2">Partials (vues réutilisables)</h3>
            <p class="text-gray-600 mb-2">
                <code>renderPartial()</code> et l’alias <code>partial()</code> permettent d’inclure une vue sans layout. La variable <code>$viewEngine</code> est disponible dans toutes les vues (nom dédié pour éviter tout conflit avec une donnée <code>view</code>) : utilisez <code>$viewEngine->partial('partials/nom', $data)</code> pour insérer un bloc réutilisable (flash, erreur de champ, pagination, etc.).
            </p>
            <p class="text-gray-600 mb-2">Convention : <code>views/partials/</code>. Partials fournis (Tailwind CSS) :</p>
            <ul class="list-disc list-inside text-gray-600 text-sm mb-3 space-y-0.5">
                <li><code>partials/flash</code> — messages flash success/error (utilise <code>$session</code>)</li>
                <li><code>partials/validation-errors</code> — liste globale d’erreurs (<code>$errors</code>)</li>
                <li><code>partials/field-error</code> — message sous un champ (<code>$field</code>, <code>$errors</code>)</li>
                <li><code>partials/pagination</code> — liens de pagination (<code>$current</code>, <code>$pages</code>, <code>$baseUrl</code>, <code>$mode</code> optionnel : <code>simple</code>, <code>numbers</code>, <code>elastic</code>)</li>
            </ul>
            <?php codeBlock('php', <<<'CODE'
<!-- Dans une vue ou le layout -->
<?= $viewEngine->partial('partials/flash') ?>
<?= $viewEngine->partial('partials/field-error', ['field' => 'email', 'errors' => $errors ?? []]) ?>
<?= $viewEngine->partial('partials/pagination', ['current' => $current, 'pages' => $pages, 'baseUrl' => '/users', 'mode' => 'numbers']) ?>
<!-- mode: 'simple' (Précédent/Suivant), 'numbers' (toutes les pages), 'elastic' (1 … 5 6 7 … 42) -->
CODE) ?>
        </section>

        <!-- ─────────────────── REQUEST ─────────────────── -->
        <section id="request">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Request</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Request</code> — Encapsule la requête HTTP courante.
            </p>
            <?php codeBlock('php', <<<'CODE'
$request->method();          // 'GET', 'POST', 'PUT', 'DELETE', …
$request->uri();             // '/users/42'
$request->get('page', 1);   // $_GET['page'] ?? 1
$request->post('email');     // $_POST['email'] ?? null
$request->post();            // tout le tableau $_POST
$request->file('avatar');    // $_FILES['avatar']
$request->header('Accept');  // valeur d'un en-tête HTTP
$request->isJson();          // true si Content-Type: application/json
$request->isXhr();           // true si X-Requested-With: XMLHttpRequest
CODE) ?>
        </section>

        <!-- ─────────────────── RESPONSE ─────────────────── -->
        <section id="response">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Response</h2>
            <p class="text-gray-600 mb-4">
                Trois classes dans <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Http\</code> :
                <code>Response</code>, <code>JsonResponse</code>, <code>RedirectResponse</code>.
                Les retourner depuis un contrôleur — le Router se charge de les envoyer.
            </p>
            <?php codeBlock('php', <<<'CODE'
// HTML (via AbstractController::render)
return $this->render('home/index', ['title' => 'Accueil']);

// JSON
return $this->json(['ok' => true, 'data' => $items], 201);

// Redirection
return $this->redirect('/dashboard');
return $this->redirect('/login', 302);

// Directement
use Core\Http\Response;
return new Response('<h1>Hello</h1>', 200, ['X-Custom' => 'value']);
CODE) ?>
        </section>

        <!-- ─────────────────── SESSION ─────────────────── -->
        <section id="session">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Session &amp; Messages flash</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Session</code> — Wrapper de session avec messages flash.
            </p>
            <?php codeBlock('php', <<<'CODE'
$session->set('cart', $items);
$session->get('cart', []);
$session->has('cart');
$session->remove('cart');
$session->destroy();

// Messages flash (survivent à une seule redirection)
$session->flash('success', 'Enregistrement réussi !');
$session->flash('error',   'Une erreur est survenue.');

if ($session->hasFlash('success')) {
    echo $session->getFlash('success');  // consomme le message
}
CODE) ?>
            <p class="text-gray-500 text-sm mt-3">
                Les flash <code>success</code> et <code>error</code> sont affichés par le layout via le partial <code>partials/flash</code>.
            </p>
        </section>

        <!-- ─────────────────── VALIDATOR ─────────────────── -->
        <section id="validator">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Validator</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Validator</code> — Validation déclarative des données entrantes.
            </p>
            <?php codeBlock('php', <<<'CODE'
$v = Validator::make($data, [
    'name'     => 'required|min:2|max:100',
    'email'    => 'required|email',
    'age'      => 'required|integer|min:18',
    'password' => 'required|min:8|confirmed',  // attend password_confirmation
    'website'  => 'url',
    'role'     => 'in:admin,user,guest',
]);

if ($v->fails()) {
    // Retourner les erreurs dans la vue
    return $this->render('form', ['errors' => $v->errors()]);
}
// Dans la vue :
// $errors['name'][0]  → premier message d'erreur pour le champ name
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Règles disponibles</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Règle</th>
                            <th class="px-4 py-2 text-left">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ([
                            ['required',     'Champ obligatoire (non vide)'],
                            ['min:N',        'Longueur (string) ou valeur (int) ≥ N'],
                            ['max:N',        'Longueur (string) ou valeur (int) ≤ N'],
                            ['email',        'Format e-mail valide'],
                            ['url',          'URL valide (http/https)'],
                            ['integer',      'Valeur entière'],
                            ['numeric',      'Valeur numérique'],
                            ['confirmed',    'Doit correspondre au champ field_confirmation'],
                            ['in:a,b,c',     'Valeur dans la liste donnée'],
                            ['regex:/pat/',  'Correspond à l\'expression régulière'],
                        ] as [$r, $d]): ?>
                        <tr>
                            <td class="px-4 py-2 font-mono text-indigo-600"><?= htmlspecialchars($r) ?></td>
                            <td class="px-4 py-2 text-gray-600"><?= htmlspecialchars($d) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ─────────────────── CSRF ─────────────────── -->
        <section id="csrf">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Protection CSRF</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\CsrfGuard</code> — Protection automatique contre les attaques CSRF.
                Le middleware <code>CsrfMiddleware</code> vérifie chaque requête <code>POST/PUT/PATCH/DELETE</code>.
            </p>
            <?php codeBlock('html', <<<'CODE'
<!-- Dans tout formulaire POST -->
<form method="POST" action="/articles">
    <?= $csrf->field() ?>   <!-- <input type="hidden" name="_token" value="…"> -->
    …
</form>
CODE) ?>
            <?php codeBlock('php', <<<'CODE'
// Vérifier manuellement
$csrf->verifyRequest($request);   // lance CsrfException si invalide

// Obtenir uniquement le token
$token = $csrf->token();
CODE) ?>
        </section>

        <!-- ─────────────────── MIDDLEWARE ─────────────────── -->
        <section id="middleware">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Middleware</h2>
            <p class="text-gray-600 mb-4">
                Créer une classe implémentant <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Middleware\MiddlewareInterface</code>.
            </p>
            <?php codeBlock('php', <<<'CODE'
namespace App\Middleware;

use Core\Http\Response;
use Core\Middleware\MiddlewareInterface;
use Core\Request;

final class ThrottleMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        // Avant le contrôleur
        if ($this->tooManyRequests($request)) {
            return new Response('Too Many Requests', 429);
        }

        $response = $next($request);   // appel du contrôleur

        // Après le contrôleur (ajouter des headers, etc.)
        return $response;
    }
}
CODE) ?>
            <?php codeBlock('php', <<<'CODE'
// Attacher à une route
$router->get('/api/data', ApiController::class, 'data')
       ->middleware(AuthMiddleware::class)
       ->middleware(ThrottleMiddleware::class);

// Ou via un groupe
$router->group('/api', function (Router $r) { … }, [
    AuthMiddleware::class,
    ThrottleMiddleware::class,
]);
CODE) ?>
        </section>

        <!-- ─────────────────── AUTH ─────────────────── -->
        <section id="auth">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Auth &amp; Rôles</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Auth\Auth</code> —
                Service d'authentification par session avec gestion des rôles.
            </p>

            <?php codeBlock('php', <<<'CODE'
// Connecter / déconnecter
$auth->login($user);
$auth->logout();

// Vérifications
$auth->check();           // bool — utilisateur connecté ?
$auth->guest();           // bool — invité ?
$auth->id();              // int|null
$auth->name();            // string
$auth->email();           // string
$auth->role();            // string ('admin', 'user', …)
$auth->is(Role::ADMIN);   // bool
$auth->can('delete-user'); // bool (basé sur le rôle)
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Rôles disponibles</h3>
            <?php codeBlock('php', <<<'CODE'
use Core\Auth\Role;

Role::ADMIN  // 'admin' — tous les droits
Role::USER   // 'user'  — droits standard
Role::GUEST  // 'guest' — non connecté
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Middlewares d'authentification</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Middleware</th>
                            <th class="px-4 py-2 text-left">Effet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ([
                            ['AuthMiddleware',  'Redirige vers /login si non connecté'],
                            ['AdminMiddleware', 'Redirige vers 403 si non admin'],
                            ['GuestMiddleware', 'Redirige vers / si déjà connecté'],
                        ] as [$m, $d]): ?>
                        <tr>
                            <td class="px-4 py-2 font-mono text-indigo-600"><?= htmlspecialchars($m) ?></td>
                            <td class="px-4 py-2 text-gray-600"><?= htmlspecialchars($d) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Flux d'inscription</h3>
            <p class="text-gray-600 text-sm">
                Contrôlé par <code>AUTH_REGISTRATION</code> dans le <code>.env</code> :
            </p>
            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1 mt-2">
                <li><code class="bg-gray-100 px-1 rounded">direct</code> — accès immédiat après inscription</li>
                <li><code class="bg-gray-100 px-1 rounded">confirm</code> — l'utilisateur doit confirmer son e-mail</li>
            </ul>
        </section>

        <!-- ─────────────────── DAO ─────────────────── -->
        <section id="dao">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">AbstractDao &amp; DAOs</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Database\AbstractDao</code> — Base pour tous les DAOs.
                Fournit les opérations CRUD et la pagination via PDO.
            </p>
            <?php codeBlock('php', <<<'CODE'
namespace App\Dao;

use App\Models\Article;
use Database\AbstractDao;

final class ArticleDao extends AbstractDao
{
    protected function getTable(): string  { return 'articles'; }
    protected function getClass(): string  { return Article::class; }

    // Méthodes héritées disponibles :
    // findAll(): array
    // findById(int $id): ?object
    // insert(array $data): int
    // update(int $id, array $data): void
    // delete(int $id): void
    // count(): int
    // paginate(int $page, int $perPage): array  → ['data'=>[], 'total'=>int, 'pages'=>int, 'current'=>int]

    // Méthode personnalisée
    public function findPublished(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM articles WHERE published = 1 ORDER BY created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Article::class);
    }
}
CODE) ?>
            <?php codeBlock('php', <<<'CODE'
// Dans un contrôleur
$result = $this->articleDao->paginate(page: (int) $request->get('page', 1), perPage: 10);
// $result = ['data' => [...], 'total' => 42, 'pages' => 5, 'current' => 1]

return $this->render('article/index', [
    'articles' => $result['data'],
    'total'    => $result['total'],
    'pages'    => $result['pages'],
    'current'  => $result['current'],
]);
CODE) ?>
        </section>

        <!-- ─────────────────── MIGRATIONS ─────────────────── -->
        <section id="migrations">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Migrations DB</h2>
            <p class="text-gray-600 mb-4">
                Le système de migrations versionne le schéma de base de données via des fichiers PHP
                dans <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">database/migrations/</code>.
                La table <code>migrations</code> est créée automatiquement et est compatible <strong>SQLite</strong> et <strong>MySQL</strong>.
            </p>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Convention de nommage</h3>
            <p class="text-sm text-gray-600 mb-3">
                Chaque fichier suit le pattern <code class="bg-gray-100 px-1 rounded">YYYY_MM_DD_HHMMSS_nom_en_snake_case.php</code>
                et définit une classe dont le nom est le <strong>StudlyCase du suffixe</strong>.
            </p>
            <?php codeBlock('text', <<<'CODE'
2026_03_11_100000_create_articles_table.php  →  class CreateArticlesTable
2026_03_12_083000_add_slug_to_articles_table.php  →  class AddSlugToArticlesTable
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Commandes CLI</h3>
            <?php codeBlock('bash', <<<'CODE'
# Générer un nouveau fichier de migration (stub automatique)
php bin/console make:migration create_articles_table

# Appliquer toutes les migrations en attente
php bin/console migrate

# Vérifier l'état de toutes les migrations
php bin/console migrate:status

# Annuler le dernier batch de migrations
php bin/console migrate:rollback
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Anatomie d'un fichier de migration</h3>
            <?php codeBlock('php', <<<'CODE'
<?php
declare(strict_types=1);

use Database\Migration\Migration;

class CreateArticlesTable extends Migration
{
    public function up(\PDO $pdo): void
    {
        $driver = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS articles (
                    id         INTEGER  PRIMARY KEY,
                    title      TEXT     NOT NULL,
                    content    TEXT     NOT NULL,
                    user_id    INTEGER  NOT NULL,
                    created_at TEXT     NOT NULL DEFAULT (datetime('now'))
                )
            SQL);
        } else {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS `articles` (
                    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
                    `title`      VARCHAR(255)  NOT NULL,
                    `content`    TEXT          NOT NULL,
                    `user_id`    INT UNSIGNED  NOT NULL,
                    `created_at` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            SQL);
        }
    }

    public function down(\PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS articles');
    }
}
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Résultat de <code>migrate:status</code></h3>
            <?php codeBlock('text', <<<'CODE'
+---------------------------------------------------+---------+-------+---------------------+
| Migration                                         | Statut  | Batch | Exécutée le         |
+---------------------------------------------------+---------+-------+---------------------+
| 2026_03_11_000001_create_users_table.php          | applied | 1     | 2026-03-11 10:00:00 |
| 2026_03_11_100000_create_articles_table.php       | pending | -     | -                   |
+---------------------------------------------------+---------+-------+---------------------+
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Statuts</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Statut</th>
                            <th class="px-4 py-2 text-left">Signification</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ([
                            ['applied', 'text-green-600',  'Migration exécutée et enregistrée en base'],
                            ['pending', 'text-yellow-600', 'Fichier présent, pas encore appliqué'],
                            ['orphan',  'text-red-600',    'Enregistrement en base mais fichier supprimé'],
                        ] as [$s, $color, $d]): ?>
                        <tr>
                            <td class="px-4 py-2 font-mono <?= $color ?> font-semibold"><?= htmlspecialchars($s) ?></td>
                            <td class="px-4 py-2 text-gray-600"><?= htmlspecialchars($d) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Système de batches</h3>
            <p class="text-sm text-gray-600">
                Chaque exécution de <code>migrate</code> crée un nouveau <strong>batch</strong> numéroté.
                <code>migrate:rollback</code> annule <em>toutes</em> les migrations du dernier batch en ordre inverse,
                garantissant un retour propre même si plusieurs migrations ont été appliquées ensemble.
            </p>
        </section>

        <!-- ─────────────────── SCAFFOLDING ─────────────────── -->
        <section id="scaffolding">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Scaffolding — Générateur de code</h2>
            <p class="text-gray-600 mb-4">
                Le générateur crée automatiquement le squelette d'un module complet
                (Modèle, DAO, Contrôleur, Migration) en une seule commande, avec un
                <strong>mode interactif guidé</strong> ou un <strong>mode direct</strong>.
            </p>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Mode interactif <span class="text-xs font-normal text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full ml-1">recommandé</span></h3>
            <?php codeBlock('bash', <<<'CODE'
php bin/console make:module
CODE) ?>
            <p class="text-sm text-gray-600 mt-3 mb-3">Le prompt guide étape par étape :</p>
            <?php codeBlock('text', <<<'CODE'
✦ Astral MVC — Générateur de module

? Nom de l'entité (PascalCase) [Article] : Article

? Que souhaitez-vous générer ?
  ▶ [a] Tout (Model + DAO + Controller + Migration)
    [m] Model seulement
    [d] DAO seulement
    [c] Controller seulement
    [g] Migration seulement
  Votre choix [a] :

? Type de contrôleur
  ▶ [resource] Web CRUD (HTML + vues)
    [api]      API REST (JSON)
    [empty]    Vide (à compléter)
  Votre choix [resource] :

? Confirmer la génération ? [O/n] :

  ✓ app/Models/Article.php
  ✓ app/Dao/ArticleDao.php
  ✓ app/Controllers/ArticleController.php
  ✓ database/migrations/…_create_articles_table.php
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-6 mb-2">Mode direct</h3>
            <?php codeBlock('bash', <<<'CODE'
# Module complet (web CRUD par défaut)
php bin/console make:module Article

# Contrôleur API JSON
php bin/console make:module Article --api

# Sans migration (table déjà existante)
php bin/console make:module Article --no-migrate

# Fichiers individuels
php bin/console make:model Article
php bin/console make:dao Article
php bin/console make:controller Article --resource
php bin/console make:controller Article --api
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-6 mb-3">Ce que génère chaque commande</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Commande</th>
                            <th class="px-4 py-2 text-left">Fichier généré</th>
                            <th class="px-4 py-2 text-left">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ([
                            ['make:model Article',                  'app/Models/Article.php',             'Modèle anémique avec propriétés typées'],
                            ['make:dao Article',                    'app/Dao/ArticleDao.php',              'DAO héritant d\'AbstractDao, table auto-déduite'],
                            ['make:controller Article --resource',  'app/Controllers/ArticleController.php','7 actions CRUD (index, show, create, store, edit, update, destroy)'],
                            ['make:controller Article --api',       'app/Controllers/ArticleController.php','5 actions JSON avec codes HTTP corrects'],
                            ['make:migration create_articles_table','database/migrations/….php',           'Migration CREATE TABLE SQLite + MySQL'],
                            ['make:module Article',                 'Les 4 fichiers ci-dessus',            'Module complet en une commande'],
                        ] as [$cmd, $file, $desc]): ?>
                        <tr>
                            <td class="px-4 py-2 font-mono text-indigo-600 text-xs"><?= htmlspecialchars($cmd) ?></td>
                            <td class="px-4 py-2 text-gray-500 text-xs"><?= htmlspecialchars($file) ?></td>
                            <td class="px-4 py-2 text-gray-600 text-xs"><?= htmlspecialchars($desc) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <h3 class="font-semibold text-gray-800 mt-6 mb-2">Après la génération — étapes manuelles</h3>
            <?php codeBlock('bash', <<<'CODE'
# 1. Appliquer la migration
php bin/console migrate
CODE) ?>
            <?php codeBlock('php', <<<'CODE'
// 2. Enregistrer dans app/Providers/AppServiceProvider.php
$container->singleton(ArticleDao::class, fn($c) => new ArticleDao($c->make(PDO::class)));
$container->bind(ArticleController::class, fn($c) => new ArticleController(
    view:       $c->make(View::class),
    request:    $c->make(Request::class),
    session:    $c->make(Session::class),
    articleDao: $c->make(ArticleDao::class),
));
CODE) ?>
            <?php codeBlock('php', <<<'CODE'
// 3. Ajouter les routes dans config/routes.php
$router->group('', function (Router $r): void {
    $r->get('/articles',             ArticleController::class, 'index');
    $r->get('/articles/create',      ArticleController::class, 'create');
    $r->post('/articles',            ArticleController::class, 'store');
    $r->get('/articles/:id',         ArticleController::class, 'show');
    $r->get('/articles/:id/edit',    ArticleController::class, 'edit');
    $r->put('/articles/:id',         ArticleController::class, 'update');
    $r->post('/articles/:id/delete', ArticleController::class, 'destroy');
}, [AuthMiddleware::class, CsrfMiddleware::class]);
CODE) ?>
        </section>

        <!-- ─────────────────── EVENTS & LISTENERS ─────────────────── -->
        <section id="events">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Events &amp; Listeners</h2>
            <p class="text-gray-600 mb-4">
                Le système d'événements découple les <strong>effets de bord</strong> (emails, logs, notifications)
                des contrôleurs. <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Events\EventDispatcher</code>
                est synchrone, sans dépendance externe, et s'intègre au Container DI.
            </p>

            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Interfaces</h3>
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-700"><tr>
                        <th class="px-4 py-2 text-left font-semibold">Interface</th>
                        <th class="px-4 py-2 text-left font-semibold">Rôle</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr><td class="px-4 py-2"><code>EventInterface</code></td><td class="px-4 py-2">Marqueur commun à tous les événements</td></tr>
                        <tr class="bg-gray-50"><td class="px-4 py-2"><code>ListenerInterface</code></td><td class="px-4 py-2">Contrat <code>handle(EventInterface): void</code></td></tr>
                        <tr><td class="px-4 py-2"><code>SubscriberInterface</code></td><td class="px-4 py-2">Regroupe plusieurs listeners dans une seule classe</td></tr>
                    </tbody>
                </table>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Créer un événement</h3>
            <?php codeBlock('PHP', <<<'CODE'
// app/Events/OrderPlaced.php
final class OrderPlaced implements EventInterface
{
    public function __construct(
        public Order $order,
        public User  $customer,
    ) {}
}
CODE) ?>

            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Créer un listener</h3>
            <?php codeBlock('PHP', <<<'CODE'
// app/Listeners/SendOrderConfirmation.php
final class SendOrderConfirmation implements ListenerInterface
{
    public function __construct(private Mailer $mailer) {}

    public function handle(EventInterface $event): void
    {
        assert($event instanceof OrderPlaced);
        $this->mailer->send($event->customer->email, 'Commande reçue', '…');
    }
}
CODE) ?>

            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Enregistrer &amp; dispatcher</h3>
            <?php codeBlock('PHP', <<<'CODE'
// Dans AppServiceProvider::register()
$dispatcher->listen(OrderPlaced::class, SendOrderConfirmation::class);

// Listener callable (ad hoc)
$dispatcher->listen(OrderPlaced::class, fn(OrderPlaced $e) => logger()->info('commande', ['id' => $e->order->id]));

// Depuis un contrôleur
$this->dispatcher->dispatch(new OrderPlaced($order, $user));
CODE) ?>

            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Subscriber (regroupement)</h3>
            <?php codeBlock('PHP', <<<'CODE'
final class ShopSubscriber implements SubscriberInterface
{
    public function subscribe(EventDispatcher $dispatcher): void
    {
        $dispatcher->listen(OrderPlaced::class,  SendOrderConfirmation::class);
        $dispatcher->listen(OrderShipped::class, SendShippingNotification::class);
    }
}

// Dans AppServiceProvider :
$dispatcher->subscribe(ShopSubscriber::class);
CODE) ?>

            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Événements fournis</h3>
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-700"><tr>
                        <th class="px-4 py-2 text-left font-semibold">Événement</th>
                        <th class="px-4 py-2 text-left font-semibold">Déclenché par</th>
                        <th class="px-4 py-2 text-left font-semibold">Listeners actifs</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr><td class="px-4 py-2"><code>UserRegistered</code></td><td class="px-4 py-2"><code>AuthController::register()</code></td><td class="px-4 py-2"><code>SendWelcomeEmail</code>, <code>LogUserActivity</code></td></tr>
                        <tr class="bg-gray-50"><td class="px-4 py-2"><code>UserLoggedIn</code></td><td class="px-4 py-2"><code>AuthController::login()</code></td><td class="px-4 py-2"><code>LogUserActivity</code></td></tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-4 text-sm text-blue-800">
                <strong>Bonne pratique :</strong> les listeners ne doivent jamais bloquer l'utilisateur.
                Encapsulez les opérations risquées (email, I/O) dans un <code>try/catch</code> et
                loguez l'erreur plutôt que de la propager.
            </div>
        </section>

        <!-- ─────────────────── MAILER ─────────────────── -->
        <section id="mailer">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Mailer</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Mailer\Mailer</code> — Envoi d'e-mails via PHPMailer (SMTP) ou <code>mail()</code> natif.
            </p>
            <?php codeBlock('php', <<<'CODE'
// Injecter Mailer via le constructeur
final class NotifController extends AbstractController
{
    public function __construct(
        View          $view,
        private Mailer $mailer,
    ) {
        parent::__construct($view);
    }

    public function notify(): Response
    {
        $this->mailer->send(
            to:      'user@example.com',
            subject: 'Bienvenue !',
            html:    '<h1>Bonjour</h1><p>Votre compte est créé.</p>',
        );

        return $this->redirect('/');
    }
}
CODE) ?>
            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Configuration .env</h3>
            <?php codeBlock('env', <<<'CODE'
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=votre_user
MAIL_PASSWORD=votre_pass
MAIL_ENCRYPTION=tls
MAIL_FROM=noreply@monsite.com
MAIL_FROM_NAME="Astral MVC"
CODE) ?>
        </section>

        <!-- ─────────────────── LOGGER ─────────────────── -->
        <section id="logger">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Logger</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Logger</code> — Logger fichier par jour dans <code>storage/logs/</code>.
            </p>
            <?php codeBlock('php', <<<'CODE'
$logger->debug('Détail technique', ['query' => $sql]);
$logger->info('Utilisateur connecté', ['id' => $userId]);
$logger->warning('Tentative de connexion échouée', ['email' => $email]);
$logger->error('PDOException', ['message' => $e->getMessage()]);

// Format de log : [2026-03-09 14:32:11] [ERROR] PDOException {"message":"…"}
// Fichier : storage/logs/2026-03-09.log
CODE) ?>
        </section>

        <!-- ─────────────────── CACHE ─────────────────── -->
        <section id="cache">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Cache fichier</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Cache</code> — Cache clé/valeur en fichier avec TTL.
                Stockage dans <code>storage/cache/</code>.
            </p>
            <?php codeBlock('php', <<<'CODE'
// Lire ou calculer (pattern cache-aside)
$data = $cache->remember('homepage_stats', ttl: 300, callback: function () {
    return $this->statsDao->getAll();
});

$cache->set('key', $value, ttl: 60);  // 60 secondes
$value = $cache->get('key', 'default');
$cache->has('key');
$cache->forget('key');
$cache->flush();                       // vider tout le cache

// CLI
php bin/console cache:clear
CODE) ?>
        </section>

        <!-- ─────────────────── CONSOLE ─────────────────── -->
        <section id="console">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Console CLI</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\Console\Console</code> — Mini-console extensible avec helpers ANSI colorés.
            </p>

            <h3 class="font-semibold text-gray-800 mt-4 mb-2">Commandes intégrées</h3>
            <?php codeBlock('bash', <<<'CODE'
# Lister toutes les commandes
php bin/console list

# ── Scaffolding ──────────────────────────────────────────────
php bin/console make:module                     # mode interactif guidé
php bin/console make:module Article             # module complet
php bin/console make:module Article --api       # module API JSON
php bin/console make:model Article
php bin/console make:dao Article
php bin/console make:controller Article --resource
php bin/console make:controller Article --api
php bin/console make:migration create_articles_table

# ── Migrations ───────────────────────────────────────────────
php bin/console migrate
php bin/console migrate:status
php bin/console migrate:rollback

# ── Cache ─────────────────────────────────────────────────────
php bin/console cache:clear
CODE) ?>

            <h3 class="font-semibold text-gray-800 mt-5 mb-2">Créer une commande applicative</h3>
            <?php codeBlock('php', <<<'CODE'
// app/Console/Commands/SeedUsersCommand.php
namespace App\Console\Commands;

use Core\Console\CommandInterface;
use Core\Console\Console;

final class SeedUsersCommand implements CommandInterface
{
    public function getName(): string        { return 'db:seed'; }
    public function getDescription(): string { return 'Insère des données de test'; }

    public function execute(array $args, Console $console): int
    {
        $console->info('Insertion des données…');
        // … logique de seed …
        $console->success('Base de données peuplée.');
        return 0;
    }
}
CODE) ?>
            <?php codeBlock('php', <<<'CODE'
// bin/console — enregistrer la commande applicative
$console->register(new App\Console\Commands\SeedUsersCommand());
CODE) ?>
        </section>

        <!-- ─────────────────── PROVIDERS ─────────────────── -->
        <section id="providers">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Service Providers</h2>
            <p class="text-gray-600 mb-4">
                Les bindings du conteneur sont organisés en <em>Service Providers</em>
                qui implémentent <code class="bg-gray-100 px-1.5 py-0.5 rounded text-sm">Core\ServiceProviderInterface</code>.
            </p>
            <?php codeBlock('php', <<<'CODE'
// config/dependencies.php  — liste ordonnée des providers
return [
    FrameworkServiceProvider::class,  // Session, Logger, Cache, Request, View, CSRF, Auth
    DatabaseServiceProvider::class,   // PDO
    AppServiceProvider::class,        // DAOs + Contrôleurs applicatifs
];
CODE) ?>
            <?php codeBlock('php', <<<'CODE'
// Créer votre propre provider
namespace App\Providers;

use Core\Container;
use Core\ServiceProviderInterface;

final class PaymentServiceProvider implements ServiceProviderInterface
{
    public function register(Container $c, array $appConfig, array $dbConfig): void
    {
        $c->singleton(PaymentGateway::class, function () use ($appConfig): PaymentGateway {
            return new PaymentGateway($appConfig['payment_key'] ?? '');
        });
    }
}
CODE) ?>
        </section>

        <!-- ─────────────────── ENV ─────────────────── -->
        <section id="env">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">.env &amp; Configuration</h2>
            <?php codeBlock('env', <<<'CODE'
# Application
APP_NAME="Mon Application"
APP_ENV=development        # development | production
APP_DEBUG=true
APP_TIMEZONE=Europe/Paris
APP_CHARSET=UTF-8
APP_BASE_URL=http://localhost:8080

# Base de données
DB_DRIVER=sqlite           # sqlite | mysql
DB_DATABASE=database/app.sqlite

# Pour MySQL :
# DB_DRIVER=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=astral_db
# DB_USERNAME=root
# DB_PASSWORD=secret

# Authentification
AUTH_REGISTRATION=direct   # direct | confirm

# Mailer
MAIL_DRIVER=mail           # mail | smtp
MAIL_FROM=noreply@localhost
MAIL_FROM_NAME="Astral MVC"
CODE) ?>
            <p class="text-gray-500 text-sm mt-3">
                Ne jamais committer le fichier <code>.env</code>. Utiliser <code>.env.example</code> comme modèle.
            </p>
        </section>

        <!-- ─────────────────── ADMIN ─────────────────── -->
        <section id="admin">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Admin — Gestion des rôles</h2>
            <p class="text-gray-500 text-sm mb-6">
                Interface d'administration dédiée à la gestion des rôles utilisateurs,
                accessible uniquement aux administrateurs. Toutes les routes <code>/admin/*</code>
                sont protégées par <code>AdminMiddleware</code>.
            </p>

            <!-- Routes -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Routes disponibles</h3>
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Méthode</th>
                            <th class="px-4 py-2 text-left">URI</th>
                            <th class="px-4 py-2 text-left">Action</th>
                            <th class="px-4 py-2 text-left">Middleware</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr><td class="px-4 py-2 font-mono text-indigo-600">GET</td><td class="px-4 py-2 font-mono">/admin/users</td><td class="px-4 py-2">Tableau de bord des rôles</td><td class="px-4 py-2 text-gray-500">AdminMiddleware</td></tr>
                        <tr><td class="px-4 py-2 font-mono text-amber-600">POST</td><td class="px-4 py-2 font-mono">/admin/users/:id/role</td><td class="px-4 py-2">Changer le rôle d'un utilisateur</td><td class="px-4 py-2 text-gray-500">AdminMiddleware</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Sécurité -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Règles de sécurité</h3>
            <ul class="space-y-2 mb-6">
                <?php foreach ([
                    ['🚫', 'Auto-modification', "Un administrateur ne peut pas modifier son propre rôle."],
                    ['🛡️', 'Dernier admin', "Le dernier administrateur ne peut pas être rétrogradé (protection anti-lockout)."],
                    ['✔️', 'Rôles valides', "Seuls les rôles définis dans <code>Role::all()</code> sont acceptés (<code>admin</code>, <code>user</code>)."],
                ] as [$icon, $title, $desc]): ?>
                <li class="flex items-start gap-3 bg-gray-50 rounded-xl px-4 py-3">
                    <span class="text-lg"><?= $icon ?></span>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm"><?= $title ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?= $desc ?></p>
                    </div>
                </li>
                <?php endforeach ?>
            </ul>

            <!-- Événement -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Événement <code>RoleChanged</code></h3>
            <p class="text-gray-500 text-sm mb-3">
                Dispatché automatiquement après chaque changement de rôle.
                Écoutez-le pour journaliser, notifier ou déclencher d'autres effets de bord.
            </p>
            <?php codeBlock('php', <<<'CODE'
// app/Events/RoleChanged.php — dispatché par Admin\UserController::updateRole()
final class RoleChanged implements EventInterface
{
    public function __construct(
        public User   $user,       // Utilisateur modifié
        public string $oldRole,    // Rôle précédent
        public string $newRole,    // Nouveau rôle
        public int    $changedBy,  // ID de l'admin ayant effectué la modification
    ) {}
}

// Écouter l'événement dans AppServiceProvider :
$dispatcher->listen(RoleChanged::class, LogRoleChange::class);

// Ou avec un callable :
$dispatcher->listen(RoleChanged::class, function (RoleChanged $event): void {
    // Envoyer une notification à l'utilisateur…
});
CODE) ?>

            <!-- Navigation -->
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Navigation</h3>
            <p class="text-gray-500 text-sm">
                Un lien <strong>Admin</strong> apparaît automatiquement dans la barre de navigation
                pour les utilisateurs ayant le rôle <code>admin</code>. Il pointe vers
                <code>/admin/users</code> et affiche une icône bouclier.
            </p>
        </section>

        <!-- ─────────────────── RELATIONS ─────────────────── -->
        <section id="relations">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Relations entre entités — ORM léger</h2>
            <p class="text-gray-500 text-sm mb-6">
                Astral propose deux helpers dans <code>AbstractDao</code> pour gérer les relations <strong>has-many</strong>
                (1→N) et <strong>belongs-to</strong> (N→1) sans aucune magie : pas de lazy-loading,
                pas de proxy, des appels toujours explicites.
            </p>

            <!-- Philosophie -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <?php foreach ([
                    ['✅', 'Explicite', 'Les relations sont des appels de méthodes visibles dans le DAO. Aucune requête cachée.'],
                    ['✅', 'Testable', 'Pas de magie → les DAOs sont facilement mockables dans les tests.'],
                    ['✅', 'Minimaliste', 'Deux helpers suffisent : hasMany() et belongsTo().'],
                    ['❌', 'Pas de lazy-loading', 'Accéder à $article->category ne déclenche jamais de requête automatique.'],
                ] as [$icon, $title, $desc]): ?>
                <div class="flex items-start gap-3 bg-gray-50 rounded-xl px-4 py-3">
                    <span class="text-base"><?= $icon ?></span>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm"><?= $title ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?= $desc ?></p>
                    </div>
                </div>
                <?php endforeach ?>
            </div>

            <!-- hasMany -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3"><code>hasMany()</code> — relation 1→N</h3>
            <p class="text-gray-500 text-sm mb-3">
                Charge toutes les entités liées par une clé étrangère. Exemple : tous les articles d'une catégorie.
            </p>
            <?php codeBlock('php', <<<'CODE'
// Dans CategoryDao — une catégorie a plusieurs articles
/** @return list<Article> */
public function articlesOf(int $categoryId): array
{
    return $this->hasMany(
        relatedClass: Article::class,  // Classe à hydrater
        table:        'articles',       // Table distante
        foreignKey:   'category_id',    // Clé étrangère dans articles
        localId:      $categoryId,      // ID de la catégorie
        orderBy:      'created_at',
        direction:    'DESC',
    );
}
CODE) ?>

            <!-- belongsTo -->
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3"><code>belongsTo()</code> — relation N→1</h3>
            <p class="text-gray-500 text-sm mb-3">
                Charge l'entité parente par sa clé primaire. Exemple : la catégorie d'un article.
                Retourne <code>null</code> si la clé étrangère est <code>0</code> ou absente.
            </p>
            <?php codeBlock('php', <<<'CODE'
// Dans ArticleDao — un article appartient à une catégorie
public function categoryOf(Article $article): ?Category
{
    return $this->belongsTo(
        relatedClass: Category::class,      // Classe à hydrater
        table:        'categories',          // Table parente
        foreignId:    $article->category_id, // Valeur de la FK portée par l'article
    );
}
CODE) ?>

            <!-- Chargement combiné -->
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Chargement combiné — <code>findWith*()</code></h3>
            <p class="text-gray-500 text-sm mb-3">
                Convention : proposer une méthode <code>findWithRelation()</code> dans le DAO pour charger l'entité
                et ses données liées en un seul appel (pratique pour les contrôleurs).
            </p>
            <?php codeBlock('php', <<<'CODE'
// Charger une catégorie avec ses articles publiés
$result = $categoryDao->findWithArticles(categoryId: 3, status: 'published');
if ($result !== null) {
    $category = $result['category']; // Category
    $articles = $result['articles']; // list<Article>
}

// Charger un article avec sa catégorie parente
$result = $articleDao->findWithCategory(articleId: 42);
if ($result !== null) {
    $article  = $result['article'];   // Article
    $category = $result['category'];  // Category|null
}
CODE) ?>

            <!-- Convention -->
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Conventions</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Convention</th>
                            <th class="px-4 py-2 text-left">Exemple</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr><td class="px-4 py-2">Méthode de relation : <code>&lt;entité&gt;Of($parent)</code></td><td class="px-4 py-2 text-gray-500"><code>articlesOf($categoryId)</code>, <code>categoryOf($article)</code></td></tr>
                        <tr><td class="px-4 py-2">Chargement combiné : <code>findWith&lt;Relation&gt;()</code></td><td class="px-4 py-2 text-gray-500"><code>findWithArticles()</code>, <code>findWithCategory()</code></td></tr>
                        <tr><td class="px-4 py-2">FK nulle → <code>null</code>, jamais d'exception</td><td class="px-4 py-2 text-gray-500"><code>category_id = 0</code> retourne <code>null</code></td></tr>
                        <tr><td class="px-4 py-2">Relations toujours via le DAO, jamais via le modèle</td><td class="px-4 py-2 text-gray-500">Pas de <code>$article->getCategory()</code></td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Migrations -->
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Migrations fournies</h3>
            <?php codeBlock('bash', <<<'CODE'
php bin/console migrate
# ✔ create_categories_table  (id, name, slug, created_at)
# ✔ create_articles_table    (id, category_id FK, title, slug, body, status, created_at)
CODE) ?>
        </section>

        <!-- ─────────────────── API REST ─────────────────── -->
        <section id="api">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">API REST JSON</h2>
            <p class="text-gray-500 text-sm mb-6">
                Astral fournit une couche API complète avec un <strong>format de réponse uniforme</strong>,
                une authentification par <strong>Bearer Token</strong> et la gestion <strong>CORS</strong>.
                Les routes <code>/api/v1/*</code> sont prêtes à l'emploi.
            </p>

            <!-- Format -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Format de réponse uniforme</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <?php foreach ([
                    ['200 — Succès',          '{ "data": { "id": 1, "title": "..." } }'],
                    ['201 — Créé',            '{ "data": { "id": 5, "title": "..." } }'],
                    ['204 — Supprimé',        '(corps vide)'],
                    ['200 — Paginé',          '{ "data": [...], "meta": { "total": 42, "page": 1, "per_page": 15, "pages": 3 } }'],
                    ['422 — Validation',      '{ "error": { "code": "VALIDATION_ERROR", "message": "...", "details": {...} } }'],
                    ['401 — Non authentifié', '{ "error": { "code": "UNAUTHORIZED", "message": "..." } }'],
                    ['404 — Introuvable',     '{ "error": { "code": "NOT_FOUND", "message": "..." } }'],
                    ['500 — Serveur',         '{ "error": { "code": "SERVER_ERROR", "message": "..." } }'],
                ] as [$label, $body]): ?>
                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                    <p class="text-xs font-semibold text-indigo-600 mb-1"><?= $label ?></p>
                    <code class="text-xs text-gray-600"><?= htmlspecialchars($body) ?></code>
                </div>
                <?php endforeach ?>
            </div>

            <!-- Routes -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Routes disponibles <code class="text-sm">/api/v1/*</code></h3>
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Méthode</th>
                            <th class="px-4 py-2 text-left">URI</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ([
                            ['GET',    '/api/v1/articles',        'Liste paginée (?page=N&status=published)'],
                            ['GET',    '/api/v1/articles/:id',    'Détail + catégorie (belongs-to)'],
                            ['POST',   '/api/v1/articles',        'Créer (JSON body : title, slug, body, category_id)'],
                            ['PUT',    '/api/v1/articles/:id',    'Modifier (JSON body partiel)'],
                            ['DELETE', '/api/v1/articles/:id',    'Supprimer → 204'],
                            ['GET',    '/api/v1/categories',      'Liste toutes les catégories'],
                            ['GET',    '/api/v1/categories/:id',  'Détail (?with_articles=1&status=published)'],
                        ] as [$m, $uri, $desc]):
                            $colors = ['GET' => 'text-indigo-600', 'POST' => 'text-green-600', 'PUT' => 'text-amber-600', 'DELETE' => 'text-red-500'];
                        ?>
                        <tr>
                            <td class="px-4 py-2 font-mono font-semibold <?= $colors[$m] ?? '' ?>"><?= $m ?></td>
                            <td class="px-4 py-2 font-mono text-gray-700 text-xs"><?= $uri ?></td>
                            <td class="px-4 py-2 text-gray-500 text-xs"><?= $desc ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <!-- Config -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Configuration</h3>
            <?php codeBlock('ini', <<<'CODE'
# .env
API_KEY=change-me-generate-a-secure-key
# Générer une clé sécurisée :
# php -r "echo bin2hex(random_bytes(32));"
CODE) ?>

            <!-- Exemple -->
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Appel depuis un client</h3>
            <?php codeBlock('bash', <<<'CODE'
# Lister les articles
curl -H "Authorization: Bearer <api_key>" \
     http://localhost/api/v1/articles

# Créer un article
curl -X POST \
     -H "Authorization: Bearer <api_key>" \
     -H "Content-Type: application/json" \
     -d '{"title":"Mon article","slug":"mon-article","body":"Contenu","category_id":1}' \
     http://localhost/api/v1/articles

# Catégorie avec ses articles publiés
curl -H "Authorization: Bearer <api_key>" \
     "http://localhost/api/v1/categories/1?with_articles=1&status=published"
CODE) ?>

            <!-- Créer un contrôleur API -->
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Créer un contrôleur API</h3>
            <?php codeBlock('php', <<<'CODE'
// app/Controllers/Api/PostApiController.php
final class PostApiController extends AbstractApiController
{
    public function __construct(
        private PostDao $postDao,
        private Request $request,
    ) {}

    // GET /api/v1/posts
    public function index(): JsonResponse
    {
        $result = $this->postDao->paginate(page: 1, perPage: 15);
        return $this->paginated(
            items:      array_map(fn($p) => $p->toArray(), $result['data']),
            pagination: $result,
        );
    }

    // GET /api/v1/posts/:id
    public function show(string $id): JsonResponse
    {
        $post = $this->postDao->findById((int) $id);
        return $post !== null
            ? $this->success($post->toArray())
            : $this->notFound("Post #{$id} introuvable.");
    }

    // POST /api/v1/posts — avec validation
    public function store(): JsonResponse
    {
        $v = $this->validate((array) $this->request->post(), [
            'title' => 'required|min:3|max:255',
            'body'  => 'required',
        ]);

        if ($v->fails()) {
            return $this->validationError($v->errors());
        }

        $id = $this->postDao->insert([...(array) $this->request->post(), 'created_at' => date('Y-m-d H:i:s')]);
        return $this->created($this->postDao->findById($id)?->toArray() ?? []);
    }

    // DELETE /api/v1/posts/:id
    public function destroy(string $id): Response
    {
        return $this->postDao->delete((int) $id) > 0
            ? $this->noContent()
            : $this->notFound("Post #{$id} introuvable.");
    }
}
CODE) ?>

            <!-- Middlewares -->
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Middlewares</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                    <p class="font-semibold text-gray-800 text-sm"><code>CorsMiddleware</code></p>
                    <p class="text-xs text-gray-500 mt-1">Ajoute les en-têtes <code>Access-Control-*</code> et gère les requêtes <code>OPTIONS</code> preflight (204).</p>
                </div>
                <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                    <p class="font-semibold text-gray-800 text-sm"><code>BearerTokenMiddleware</code></p>
                    <p class="text-xs text-gray-500 mt-1">Vérifie l'en-tête <code>Authorization: Bearer &lt;token&gt;</code> contre <code>API_KEY</code> dans <code>.env</code>. Retourne 401 si absent ou invalide.</p>
                </div>
            </div>
        </section>

        <!-- ─────────────────── NEW MODULE ─────────────────── -->
        <section id="new-module">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Créer un nouveau module</h2>
            <p class="text-gray-600 mb-6">
                Exemple complet : module <strong>Article</strong> (liste, détail, création, suppression).
            </p>

            <div class="space-y-6">

                <!-- Étape 1 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2">Migration — <code>database/migrations/</code></h3>
                        <?php codeBlock('bash', <<<'CODE'
php bin/console make:migration create_articles_table
# Génère : database/migrations/2026_03_11_100000_create_articles_table.php
# Éditez up() et down() puis :
php bin/console migrate
CODE) ?>
                    </div>
                </div>

                <!-- Étape 2 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2">Modèle — <code>app/Models/Article.php</code></h3>
                        <?php codeBlock('php', <<<'CODE'
namespace App\Models;

final class Article
{
    public int    $id         = 0;
    public string $title      = '';
    public string $content    = '';
    public int    $user_id    = 0;
    public string $created_at = '';
}
CODE) ?>
                    </div>
                </div>

                <!-- Étape 3 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2">DAO — <code>app/Dao/ArticleDao.php</code></h3>
                        <?php codeBlock('php', <<<'CODE'
namespace App\Dao;

use App\Models\Article;
use Database\AbstractDao;

final class ArticleDao extends AbstractDao
{
    protected function getTable(): string      { return 'articles'; }
    protected function getModelClass(): string { return Article::class; }

    // Méthodes héritées : findAll, findById, findBy, insert, update, delete, count, paginate

    public function findPublished(): array
    {
        return $this->query(
            "SELECT * FROM articles WHERE published = 1 ORDER BY created_at DESC"
        );
    }
}
CODE) ?>
                    </div>
                </div>

                <!-- Étape 4 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">4</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2">Contrôleur — <code>app/Controllers/ArticleController.php</code></h3>
                        <?php codeBlock('php', <<<'CODE'
namespace App\Controllers;

use App\Dao\ArticleDao;
use Controller\AbstractController;
use Core\Http\Response;
use Core\Request;
use Core\Session;
use Core\View;

final class ArticleController extends AbstractController
{
    public function __construct(
        View               $view,
        private Request    $request,
        private Session    $session,
        private ArticleDao $articleDao,
    ) {
        parent::__construct($view);
    }

    public function index(): Response
    {
        $result = $this->articleDao->paginate(
            page: (int) $this->request->get('page', 1),
            perPage: 10,
        );

        return $this->render('article/index', [
            'title'    => 'Articles',
            'articles' => $result['data'],
            'pages'    => $result['pages'],
            'current'  => $result['current'],
        ]);
    }

    public function store(): Response
    {
        $v = $this->validate($this->request->post(), [
            'title'   => 'required|min:3|max:200',
            'content' => 'required|min:10',
        ]);

        if ($v->fails()) {
            return $this->render('article/create', [
                'errors' => $v->errors(),
                'old'    => $this->request->post(),
            ]);
        }

        $this->articleDao->insert([
            'title'      => trim($this->request->post('title')),
            'content'    => trim($this->request->post('content')),
            'user_id'    => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->session->flash('success', 'Article publié !');
        return $this->redirect('/articles');
    }
}
CODE) ?>
                    </div>
                </div>

                <!-- Étape 5 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">5</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2">Enregistrer dans <code>app/Providers/AppServiceProvider.php</code></h3>
                        <?php codeBlock('php', <<<'CODE'
// Dans AppServiceProvider::register()
$container->singleton(ArticleDao::class, fn(Container $c) => new ArticleDao(
    $c->make(\PDO::class),
));

$container->bind(ArticleController::class, fn(Container $c) => new ArticleController(
    view:       $c->make(View::class),
    request:    $c->make(Request::class),
    session:    $c->make(Session::class),
    articleDao: $c->make(ArticleDao::class),
));
CODE) ?>
                    </div>
                </div>

                <!-- Étape 6 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">6</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2">Déclarer les routes dans <code>config/routes.php</code></h3>
                        <?php codeBlock('php', <<<'CODE'
$router->group('', function (Router $r): void {
    $r->get('/articles',             ArticleController::class, 'index');
    $r->get('/articles/create',      ArticleController::class, 'create');
    $r->post('/articles',            ArticleController::class, 'store');
    $r->get('/articles/:id',         ArticleController::class, 'show');
    $r->post('/articles/:id/delete', ArticleController::class, 'destroy');
}, [AuthMiddleware::class, CsrfMiddleware::class]);
CODE) ?>
                    </div>
                </div>

                <!-- Étape 7 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">7</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2">Créer les vues dans <code>views/article/</code></h3>
                        <p class="text-sm text-gray-600">
                            <code>index.php</code>, <code>create.php</code>, <code>show.php</code>, <code>edit.php</code> — même pattern que les vues <code>user/</code> existantes.
                            Toujours utiliser <code>htmlspecialchars(…, ENT_QUOTES, 'UTF-8')</code> et <code>&lt;?= $csrf-&gt;field() ?&gt;</code> dans les formulaires POST.
                        </p>
                    </div>
                </div>

            </div><!-- /steps -->
        </section>

        <!-- ─────────────────── FROM SCRATCH ─────────────────── -->
        <section id="from-scratch">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Démarrer un projet from scratch</h2>
            <p class="text-gray-500 text-sm mb-6">
                Astral MVC est livré avec un module d'exemple complet (<strong>Article / Category</strong>)
                qui démontre les relations ORM, l'API REST JSON et les migrations.
                Pour repartir d'une base vierge, suivez les étapes ci-dessous.
            </p>

            <!-- Alerte -->
            <div class="mb-6 flex items-start gap-3 bg-indigo-50 border border-indigo-100 text-indigo-800 text-sm rounded-xl px-4 py-3">
                <svg class="w-4 h-4 shrink-0 mt-0.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>
                    Les fichiers <code>ApiResponse</code>, <code>AbstractApiController</code>, <code>CorsMiddleware</code>
                    et <code>BearerTokenMiddleware</code> font partie du <strong>framework</strong> (<code>src/Core/</code>)
                    et ne doivent <strong>pas</strong> être supprimés — ils servent vos propres modules API.
                </span>
            </div>

            <!-- Étapes -->
            <div class="space-y-4">

                <?php foreach ([
                    ['1', 'Supprimer les modèles d\'exemple', [
                        'app/Models/Article.php',
                        'app/Models/Category.php',
                    ]],
                    ['2', 'Supprimer les DAOs d\'exemple', [
                        'app/Dao/ArticleDao.php',
                        'app/Dao/CategoryDao.php',
                    ]],
                    ['3', 'Supprimer les contrôleurs API d\'exemple', [
                        'app/Controllers/Api/ArticleApiController.php',
                        'app/Controllers/Api/CategoryApiController.php',
                    ]],
                    ['4', 'Supprimer les migrations d\'exemple', [
                        'database/migrations/2026_03_11_000002_create_categories_table.php',
                        'database/migrations/2026_03_11_000003_create_articles_table.php',
                    ]],
                ] as [$num, $title, $files]): ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold shrink-0"><?= $num ?></span>
                        <h3 class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($title) ?></h3>
                    </div>
                    <ul class="space-y-1">
                        <?php foreach ($files as $f): ?>
                        <li class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <code><?= htmlspecialchars($f) ?></code>
                        </li>
                        <?php endforeach ?>
                    </ul>
                </div>
                <?php endforeach ?>

                <!-- Étape 5 — AppServiceProvider -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold shrink-0">5</span>
                        <h3 class="font-semibold text-gray-800 text-sm">Nettoyer <code>app/Providers/AppServiceProvider.php</code></h3>
                    </div>
                    <p class="text-xs text-gray-500 mb-2">Retirez les imports et bindings liés à Article / Category :</p>
                    <?php codeBlock('php', <<<'CODE'
// Imports à supprimer
use App\Controllers\Api\ArticleApiController;
use App\Controllers\Api\CategoryApiController;
use App\Dao\ArticleDao;
use App\Dao\CategoryDao;

// Bindings à supprimer
$container->singleton(ArticleDao::class, ...);
$container->singleton(CategoryDao::class, ...);
$container->bind(ArticleApiController::class, ...);
$container->bind(CategoryApiController::class, ...);
CODE) ?>
                </div>

                <!-- Étape 6 — routes.php -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold shrink-0">6</span>
                        <h3 class="font-semibold text-gray-800 text-sm">Nettoyer <code>config/routes.php</code></h3>
                    </div>
                    <p class="text-xs text-gray-500 mb-2">Retirez les imports et le groupe de routes <code>/api/v1</code> :</p>
                    <?php codeBlock('php', <<<'CODE'
// Imports à supprimer
use App\Controllers\Api\ArticleApiController;
use App\Controllers\Api\CategoryApiController;

// Groupe à supprimer (gardez CorsMiddleware et BearerTokenMiddleware
// pour vos propres routes API)
$router->group('/api/v1', function (Router $r): void {
    $r->get('/articles', ArticleApiController::class, 'index');
    // ...
}, [CorsMiddleware::class, BearerTokenMiddleware::class]);
CODE) ?>
                </div>

            </div><!-- /steps -->

            <!-- Récapitulatif -->
            <div class="mt-6 bg-green-50 border border-green-100 rounded-xl p-4 text-sm text-green-800">
                <strong>Résultat :</strong> votre application ne contient plus que le système d'auth
                (<code>User</code>, <code>AuthController</code>, <code>UserController</code>),
                les providers, et toute la couche framework dans <code>src/Core/</code>.
                Vous repartez proprement en ajoutant vos propres modules via
                <code>php bin/console make:module MonModule</code>.
            </div>
        </section>

        <!-- ─────────────────── ÉCOSYSTÈME COMPOSER ─────────────────── -->
        <section id="ecosystem">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Écosystème Composer (optionnel)</h2>
            <p class="text-gray-600 text-sm mb-4">
                Le cœur d’Astral MVC reste minimal (voir <code>composer.json</code> du framework).
                Des packages officiels sous l’organisation
                <a href="https://github.com/astral-php" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">github.com/astral-php</a>
                complètent le projet <strong>uniquement si vous les installez</strong>.
            </p>
            <div class="overflow-x-auto rounded-xl border border-gray-100 shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Package</th>
                            <th class="px-4 py-3 font-semibold">Rôle</th>
                            <th class="px-4 py-3 font-semibold">Dépôt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <tr>
                            <td class="px-4 py-3 font-mono text-xs">astral-php/astral-form</td>
                            <td class="px-4 py-3 text-gray-600"><code>FormBuilder</code>, ServiceProvider, variable <code>$form</code> dans les vues, erreurs <code>Validator</code>.</td>
                            <td class="px-4 py-3"><a href="https://github.com/astral-php/astral-form" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">astral-form</a></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono text-xs">astral-php/astral-vite</td>
                            <td class="px-4 py-3 text-gray-600">Vite + Tailwind, <code>$vite-&gt;tags()</code> dans le layout, stubs d’intégration.</td>
                            <td class="px-4 py-3"><a href="https://github.com/astral-php/astral-vite" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">astral-vite</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-gray-500 text-xs mt-4">
                Installation typique : <code class="bg-gray-100 px-1 rounded">composer require astral-php/astral-form</code>
                ou <code class="bg-gray-100 px-1 rounded">composer require astral-php/astral-vite</code>,
                puis enregistrement du <code>ServiceProvider</code> du package dans <code>config/dependencies.php</code>
                (voir le README de chaque dépôt).
            </p>
        </section>

        <!-- ─────────────────── EVOLUTIONS ─────────────────── -->
        <section id="evolutions">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Évolutions &amp; Backlog</h2>
            <p class="text-gray-500 text-sm mb-6">
                Fonctionnalités implémentées et à venir, dans l'esprit minimaliste d'Astral MVC.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php
                // [icon, titre, desc, done, priorité]
                // priorité : 'done' | 'haute' | 'moyenne' | 'basse'
                $items = [
                    ['✅', 'Migrations de base de données',  'Système de versioning du schéma DB via CLI (make:migration, migrate, rollback, status). Batches numérotés, compatible SQLite et MySQL.',                                                                  true,  'done'],
                    ['✅', 'Scaffolding — Générateur de code','Génération de Model, DAO, Controller (resource/api) et Migration en une commande. Mode interactif guidé (make:module) ou mode direct.',                                                                   true,  'done'],
                    ['✅', 'Tests unitaires',                 '178 tests, 228 assertions couvrant Validator, Container, Request, Session, Cache, Response, EventDispatcher, Router, Migrator, AbstractDao.',                                                            true,  'done'],
                    ['✅', 'Events & Listeners',              'EventDispatcher synchrone : listen (classe/callable), dispatch, subscribe. UserRegistered → SendWelcomeEmail + LogUserActivity. UserLoggedIn → LogUserActivity.',                                         true,  'done'],
                    ['✅', 'Gestion des rôles admin',        'Admin\UserController, RoleChanged event, LogRoleChange listener. Interface /admin/users avec protections anti-lockout. Navigation admin dans la barre de nav.',                      true,  'done'],
                    ['✅', 'API REST JSON',                  'ApiResponse (enveloppe data/error/meta), AbstractApiController, CorsMiddleware, BearerTokenMiddleware. Routes /api/v1/*. Exemples : Article CRUD + Category.',                          true,  'done'],
                    ['✅', 'Composants astral-form / astral-vite', 'Packages optionnels Packagist : formulaires et pipeline Vite + Tailwind. Organisation [astral-php](https://github.com/astral-php).',                                                                                    true,  'done'],
                    ['📦', 'Queue de tâches',                'Traitement asynchrone via une table DB (envoi d\'emails, imports CSV…).',                                                                                                                                false, 'moyenne'],
                    ['🌍', 'Internationalisation',            'Système i18n minimaliste avec fichiers de traduction PHP/JSON, locale par session.',                                                                                                                     false, 'moyenne'],
                    ['📊', 'Dashboard admin',                'Interface back-office générique (liste des entités, statistiques, gestion des rôles).',                                                                                                                   false, 'moyenne'],
                    ['🔒', 'OAuth / Social login',           'Connexion via Google/GitHub grâce à un adapter léger (ex. league/oauth2-client).',                                                                                                                       false, 'basse'],
                    ['🔌', 'WebSockets',                     'Temps réel via Ratchet ou Swoole — sort volontairement du minimalisme du framework.',                                                                                                                    false, 'basse'],
                    ['✅', 'ORM léger — Relations',           'hasMany() + belongsTo() dans AbstractDao. Exemples : CategoryDao, ArticleDao (has-many + belongs-to). Migrations fournies. Philosophie : pas de lazy-loading, relations explicites.',      true,  'done'],
                    ['✅', 'Assets pipeline (Vite)',        'Via le package optionnel astral-vite : Vite, Tailwind, manifest, stubs.',                                                                                                                                     true,  'done'],
                ];

                $priorityBadge = [
                    'done'    => ['bg-green-100 text-green-700',   'Implémenté'],
                    'haute'   => ['bg-red-100 text-red-700',       '🔴 Priorité haute'],
                    'moyenne' => ['bg-yellow-100 text-yellow-700', '🟡 Priorité moyenne'],
                    'basse'   => ['bg-gray-100 text-gray-500',     '🟢 Priorité basse'],
                ];

                foreach ($items as [$icon, $titre, $desc, $done, $prio]):
                    [$badgeClass, $badgeLabel] = $priorityBadge[$prio];
                ?>
                <div class="bg-white rounded-xl border <?= $done ? 'border-green-200 bg-green-50' : 'border-gray-100' ?> p-5 shadow-sm relative">
                    <span class="absolute top-3 right-3 text-xs font-semibold <?= $badgeClass ?> px-2 py-0.5 rounded-full">
                        <?= $badgeLabel ?>
                    </span>
                    <div class="text-2xl mb-2"><?= $icon ?></div>
                    <h3 class="font-semibold <?= $done ? 'text-green-800' : 'text-gray-800' ?> text-sm mb-1"><?= htmlspecialchars($titre) ?></h3>
                    <p class="text-xs <?= $done ? 'text-green-700' : 'text-gray-500' ?>"><?= htmlspecialchars($desc) ?></p>
                </div>
                <?php endforeach ?>
            </div>
        </section>

    </div><!-- /content -->
</div><!-- /flex -->
