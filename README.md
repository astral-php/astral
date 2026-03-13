# Astral MVC — Framework PHP 8.x minimaliste

[![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Version](https://img.shields.io/badge/version-1.0.0-blue)](CHANGELOG.md)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Tests](https://img.shields.io/badge/tests-PHPUnit%209.6-9933CC)](phpunit.xml)

Micro-framework MVC orienté objet, compatible PHP 8.0 → 8.4.
Dépendances de production : `vlucas/phpdotenv`, `phpmailer/phpmailer`.
Minimaliste par design, puissant par convention.

---

## Structure

```
astral-mvc/
├── app/
│   ├── Controllers/        # Contrôleurs applicatifs (AuthController, UserController…)
│   ├── Dao/                # Data Access Objects métier
│   ├── Events/             # Événements applicatifs (UserRegistered, UserLoggedIn…)
│   ├── Listeners/          # Listeners d'événements (SendWelcomeEmail, LogUserActivity…)
│   ├── Models/             # Entités / modèles de données (User…)
│   └── Providers/
│       └── AppServiceProvider.php   # ← DAOs + contrôleurs + listeners applicatifs
├── config/
│   ├── app.php             # Config générale (env, debug, timezone…)
│   ├── database.php        # Basculer SQLite ↔ MySQL ici
│   ├── dependencies.php    # ← Liste des Service Providers
│   └── routes.php          # ← Déclaration de toutes les routes
├── database/
│   └── migrations/         # Fichiers de migration (YYYY_MM_DD_HHMMSS_nom.php)
├── public/
│   ├── .htaccess           # Réécriture Apache
│   └── index.php           # Bootstrap minimal (ne pas modifier)
├── src/
│   ├── Core/
│   │   ├── Application.php        # Chef d'orchestre du démarrage
│   │   ├── Cache.php              # Cache fichier (TTL, remember, flush)
│   │   ├── ServiceProviderInterface.php  # Contrat des providers
│   │   ├── Providers/
│   │   │   ├── FrameworkServiceProvider.php  # Session, Logger, Cache, View…
│   │   │   └── DatabaseServiceProvider.php   # PDO
│   │   ├── Container.php          # Conteneur DI (autowiring + singletons)
│   │   ├── CsrfGuard.php          # Protection CSRF (token de session)
│   │   ├── Logger.php             # Logger fichier journalier
│   │   ├── Mailer/
│   │   │   └── Mailer.php         # Envoi e-mail (SMTP via PHPMailer)
│   │   ├── Request.php            # Requête HTTP (JSON, verb spoofing)
│   │   ├── Router.php             # Routeur (routes + middleware + groupes)
│   │   ├── Session.php            # Session + messages flash
│   │   ├── Validator.php          # Validation des données
│   │   ├── View.php               # Moteur de rendu → retourne string
│   │   ├── Console/
│   │   │   ├── CommandInterface.php
│   │   │   ├── Console.php        # Dispatcher + helpers ANSI
│   │   │   └── Commands/
│   │   │       ├── ClearCacheCommand.php
│   │   │       ├── MigrateCommand.php          # migrate
│   │   │       ├── MigrateRollbackCommand.php  # migrate:rollback
│   │   │       ├── MigrateStatusCommand.php    # migrate:status
│   │   │       └── MakeMigrationCommand.php    # make:migration
│   │   ├── Auth/
│   │   │   ├── Auth.php           # Service auth (login, logout, check, is, can)
│   │   │   ├── Role.php           # Constantes ADMIN | USER | GUEST
│   │   │   └── Middleware/
│   │   │       ├── AuthMiddleware.php   # Connecté ou → /login
│   │   │       ├── AdminMiddleware.php  # Admin ou → 403
│   │   │       └── GuestMiddleware.php  # Invité ou → /
│   │   ├── Exception/
│   │   │   ├── AuthorizationException.php  # 403
│   │   │   ├── CsrfException.php
│   │   │   ├── NotFoundException.php
│   │   │   └── ValidationException.php
│   │   ├── Http/
│   │   │   ├── Response.php       # Réponse HTML (testable)
│   │   │   ├── JsonResponse.php   # Réponse JSON
│   │   │   └── RedirectResponse.php
│   │   └── Middleware/
│   │       ├── CsrfMiddleware.php
│   │       └── MiddlewareInterface.php
│   ├── Controller/
│   │   └── AbstractController.php # render/redirect/json → Response
│   └── Database/
│       ├── AbstractDao.php        # CRUD générique PDO + pagination
│       ├── Connection.php         # Singleton PDO (SQLite / MySQL)
│       └── Migration/
│           ├── Migration.php      # Classe abstraite de base (up/down)
│           └── Migrator.php       # Moteur : run, rollback, status
├── bin/
│   └── console                    # Point d'entrée CLI
├── storage/
│   ├── cache/              # Cache fichier (auto-créé)
│   └── logs/               # Logs journaliers (auto-créé)
├── .env                    # Variables d'environnement (non versionné)
├── .env.example            # Template à copier
├── tests/                  # Tests PHPUnit
├── views/
│   ├── auth/               # login.php, register.php, forgot-password…
│   ├── docs/               # Documentation en ligne
│   ├── errors/             # Pages d'erreur (403, 404, 500)
│   ├── home/
│   ├── layout/             # Layout principal (Tailwind CDN)
│   ├── profile/
│   └── user/
├── composer.json
└── phpunit.xml
```

---

## Installation

```bash
composer install
cp .env.example .env   # puis adaptez les valeurs
```

## Démarrage rapide (Laragon / Apache)

1. Copier le projet dans `laragon/www/mvc/`
2. Accéder à `http://mvc.test` ou `http://localhost/mvc`
3. La base SQLite et le dossier `storage/logs/` sont créés automatiquement

---

## Démarrer un projet from scratch

Astral MVC est livré avec un module d'exemple complet (**Article / Category**)
qui démontre les ORM léger (hasMany, belongsTo), l'API REST JSON et les migrations.

Si vous souhaitez repartir d'une **base vierge** pour construire votre propre application,
supprimez les fichiers suivants :

### 1. Modèles et DAOs d'exemple

```bash
rm app/Models/Article.php
rm app/Models/Category.php
rm app/Dao/ArticleDao.php
rm app/Dao/CategoryDao.php
```

### 2. Contrôleurs API d'exemple

```bash
rm app/Controllers/Api/ArticleApiController.php
rm app/Controllers/Api/CategoryApiController.php
rmdir app/Controllers/Api   # si le dossier est vide
```

### 3. Migrations d'exemple

```bash
rm database/migrations/2026_03_11_000002_create_categories_table.php
rm database/migrations/2026_03_11_000003_create_articles_table.php
```

### 4. Nettoyer `AppServiceProvider`

Dans `app/Providers/AppServiceProvider.php`, retirez :

```php
// Imports à supprimer
use App\Controllers\Api\ArticleApiController;
use App\Controllers\Api\CategoryApiController;
use App\Dao\ArticleDao;
use App\Dao\CategoryDao;

// Bindings à supprimer (section DAOs)
$container->singleton(ArticleDao::class, ...);
$container->singleton(CategoryDao::class, ...);

// Bindings à supprimer (section Contrôleurs API)
$container->bind(ArticleApiController::class, ...);
$container->bind(CategoryApiController::class, ...);
```

### 5. Nettoyer `config/routes.php`

Retirez le groupe `/api/v1` et ses imports :

```php
// Imports à supprimer
use App\Controllers\Api\ArticleApiController;
use App\Controllers\Api\CategoryApiController;

// Groupe de routes à supprimer
$router->group('/api/v1', function (Router $r): void {
    $r->get('/articles', ...);
    // ...
}, [CorsMiddleware::class, BearerTokenMiddleware::class]);
```

> **Note :** Les middlewares `CorsMiddleware` et `BearerTokenMiddleware`, ainsi que la classe
> `ApiResponse` et `AbstractApiController`, font partie du **framework** (`src/Core/`) — vous
> pouvez les réutiliser pour vos propres modules API sans les supprimer.

---

## Variables d'environnement (.env)

Copiez `.env.example` en `.env` et adaptez les valeurs. Le fichier `.env`
**ne doit jamais être versionné** (déjà dans `.gitignore`).

```ini
APP_NAME="ASTRAL-MVC"
APP_ENV=development       # development | production
APP_DEBUG=true
APP_TIMEZONE=Europe/Paris

DB_DRIVER=sqlite
DB_DATABASE=database/app.sqlite

# MySQL
# DB_DRIVER=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=mvc_db
# DB_USERNAME=root
# DB_PASSWORD=
```

Les fichiers `config/app.php` et `config/database.php` lisent `$_ENV` avec
des valeurs de fallback. En production, les variables peuvent aussi être
injectées directement par le serveur (`.env` non requis).

---

## Architecture du démarrage

```
public/index.php
    └── Application::run()
            ├── loadDotEnv()        — charge .env via vlucas/phpdotenv
            ├── Logger              — disponible dès le début
            ├── bootEnvironment()   — timezone, affichage d'erreurs
            ├── ensureDatabase()    — crée le dossier SQLite si absent
            ├── loadDependencies()  ← config/dependencies.php
            ├── Session::start()    — avant tout rendu
            ├── View::share()       — $session et $csrf dans toutes les vues
            ├── loadRoutes()        ← config/routes.php
            └── dispatch()          — pipeline middleware → contrôleur
                                       → Response::send()
```


| Fichier à modifier                     | Quand                                          |
| -------------------------------------- | ---------------------------------------------- |
| `app/Providers/AppServiceProvider.php` | Ajouter un DAO ou un contrôleur                |
| `config/dependencies.php`              | Ajouter / retirer un Service Provider          |
| `config/routes.php`                    | Ajouter / modifier / supprimer une route       |
| `config/app.php`                       | Changer l'environnement, le debug, la timezone |
| `config/database.php`                  | Changer de driver ou de base de données        |


> `public/index.php`, `src/Core/Application.php` et les providers `src/Core/Providers/` ne sont **jamais** modifiés.

---

## Fonctionnalités

### Session & Messages flash

```php
// Injecter Session dans un contrôleur
public function __construct(View $view, Session $session) { … }

// Enregistrer un message flash (avant redirect)
$this->session->flash('success', 'Utilisateur créé avec succès.');
$this->redirect('/users');

// Dans la vue suivante (lu une seule fois)
<?php if ($session->hasFlash('success')): ?>
    <p><?= htmlspecialchars($session->getFlash('success')) ?></p>
<?php endif; ?>
```

---

### Validation

```php
// Dans un contrôleur (méthode héritée de AbstractController)
$v = $this->validate($request->body, [
    'name'  => 'required|min:2|max:100',
    'email' => 'required|email',
    'age'   => 'integer|min:0|max:120',
]);

if ($v->fails()) {
    // Réafficher le formulaire avec les erreurs
    $this->render('users/create', ['errors' => $v->errors()]);
    return;
}
```

**Règles disponibles :**


| Règle       | Description                                       |
| ----------- | ------------------------------------------------- |
| `required`  | Champ non vide                                    |
| `min:N`     | Longueur ≥ N (chaîne) ou valeur ≥ N (nombre)      |
| `max:N`     | Longueur ≤ N (chaîne) ou valeur ≤ N (nombre)      |
| `email`     | Adresse e-mail valide                             |
| `integer`   | Entier                                            |
| `numeric`   | Valeur numérique                                  |
| `alpha`     | Lettres uniquement                                |
| `url`       | URL valide                                        |
| `confirmed` | Doit correspondre au champ `{field}_confirmation` |
| `in:a,b,c`  | Valeur parmi la liste                             |


---

### Protection CSRF

Le token est partagé automatiquement dans toutes les vues via `$csrf`.

```php
// Dans chaque formulaire POST/PUT/DELETE
<form method="POST" action="/users">
    <?= $csrf->field() ?>   <!-- génère <input type="hidden" name="_token" value="…"> -->
    …
</form>
```

La vérification se fait via `CsrfMiddleware` à appliquer aux routes concernées :

```php
// config/routes.php
$router->post('/users', UserController::class, 'store')
       ->middleware(CsrfMiddleware::class);

// ou sur un groupe entier
use Core\Middleware\CsrfMiddleware;

$router->group('', function (Router $r) {
    $r->post('/users',            UserController::class, 'store');
    $r->post('/users/:id/delete', UserController::class, 'destroy');
}, [CsrfMiddleware::class]);
```

---

### Middleware

Créer un middleware dans `app/Middleware/` :

```php
namespace App\Middleware;

use Core\Middleware\MiddlewareInterface;
use Core\Request;
use Core\Session;

final class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private Session $session) {}

    public function handle(Request $request, callable $next): void
    {
        if (!$this->session->has('user_id')) {
            header('Location: /login');
            exit;
        }
        $next();
    }
}
```

Enregistrer dans `config/dependencies.php` :

```php
$container->bind(AuthMiddleware::class, fn(Container $c) => new AuthMiddleware(
    $c->make(Session::class),
));
```

Appliquer dans `config/routes.php` :

```php
$router->group('/admin', function (Router $r) {
    $r->get('/dashboard', AdminController::class, 'index');
    $r->get('/users',     AdminController::class, 'users');
}, [AuthMiddleware::class, CsrfMiddleware::class]);
```

---

### Routes — verbes HTTP complets & groupes

```php
// config/routes.php — tous les verbes HTTP
$router->get('/users',          UserController::class, 'index');
$router->post('/users',         UserController::class, 'store');
$router->put('/users/:id',      UserController::class, 'update');
$router->patch('/users/:id',    UserController::class, 'patch');
$router->delete('/users/:id',   UserController::class, 'destroy');

// Spoofing HTML (forms ne supportent que GET/POST)
<form method="POST" action="/users/42">
    <input type="hidden" name="_method" value="DELETE">
    <?= $csrf->field() ?>
</form>

// Groupes avec préfixe
$router->group('/api/v1', function (Router $r) {
    $r->get('/users',     ApiUserController::class, 'index');
    $r->post('/users',    ApiUserController::class, 'store');
});
```

---

### Logger

Les erreurs 500 sont loguées automatiquement dans `storage/logs/YYYY-MM-DD.log`.

```php
// Injecter Logger dans un service ou contrôleur
public function __construct(private Logger $logger) {}

$this->logger->info('Utilisateur créé', ['id' => $userId]);
$this->logger->warning('Tentative suspecte', ['ip' => $_SERVER['REMOTE_ADDR']]);
$this->logger->error('Connexion BDD échouée', ['driver' => 'mysql']);
```

Format d'une ligne de log :

```
[2026-03-09 14:32:01] ERROR: Connexion BDD échouée {"driver":"mysql"}
```

---

### Pagination

```php
// Dans un contrôleur
$page   = (int) $request->query('page', 1);
$result = $this->userDao->paginate(page: $page, perPage: 15);

// $result contient :
// [
//   'data'     => [...],  // enregistrements de la page
//   'total'    => 150,    // nombre total d'enregistrements
//   'pages'    => 10,     // nombre total de pages
//   'current'  => 2,      // page courante
//   'per_page' => 15,
// ]
$this->render('users/index', $result);
```

```php
// Dans la vue — navigation simple
<?php for ($i = 1; $i <= $pages; $i++): ?>
    <a href="/users?page=<?= $i ?>"
       <?= $i === $current ? 'class="active"' : '' ?>>
        <?= $i ?>
    </a>
<?php endfor; ?>
```

---

### Objet Response (actions testables)

Les méthodes `render()`, `redirect()` et `json()` retournent désormais
des objets `Response`. L'envoi HTTP n'est déclenché qu'à la fin par le Router,
ce qui permet de tester les contrôleurs sans sortie réelle.

```php
// Tester une action sans HTTP
$response = $controller->index();

assert($response->getStatus() === 200);
assert(str_contains($response->getContent(), 'Liste des utilisateurs'));
```

```php
// Dans un contrôleur — retourner la réponse (plus d'exit)
public function store(): Response
{
    // …
    return $this->render('user/create', ['errors' => $v->errors()]);
    // ou
    return $this->redirect('/users');
    // ou
    return $this->json(['id' => $id], 201);
}
```

---

### Cache fichier

```php
// Injecter Cache dans un contrôleur ou service
public function __construct(private Cache $cache) {}

// Mettre en cache 1 heure
$users = $this->cache->remember('users.all', 3600, fn() => $this->userDao->findAll());

// Invalider après une modification
$this->cache->forget('users.all');

// Tout vider
$this->cache->flush();
```

---

### Console CLI

```bash
# Lister les commandes disponibles
php bin/console list

# ── Scaffolding ─────────────────────────────────────────
# Module complet en mode interactif (guidé)
php bin/console make:module

# Module complet en mode direct (Model + DAO + Controller + Migration)
php bin/console make:module Article
php bin/console make:module Article --api        # contrôleur JSON
php bin/console make:module Article --no-migrate # sans migration

# Fichiers individuels
php bin/console make:model Article
php bin/console make:dao Article
php bin/console make:controller Article             # contrôleur vide
php bin/console make:controller Article --resource  # CRUD complet
php bin/console make:controller Article --api       # JSON REST
php bin/console make:migration create_articles_table

# ── Migrations ──────────────────────────────────────────
php bin/console migrate
php bin/console migrate:status
php bin/console migrate:rollback

# ── Cache ───────────────────────────────────────────────
php bin/console cache:clear
```

**Ajouter une commande applicative :**

```php
// app/Console/SeedUsersCommand.php
namespace App\Console;

use Core\Console\CommandInterface;
use Core\Console\Console;

final class SeedUsersCommand implements CommandInterface
{
    public function getName(): string { return 'db:seed'; }
    public function getDescription(): string { return 'Insère des données de test'; }

    public function execute(array $args, Console $console): int
    {
        // … insérer des données …
        $console->success('Base de données peuplée.');
        return 0;
    }
}
```

```php
// bin/console — enregistrer la commande
$console->register(new App\Console\SeedUsersCommand());
```

---

### Service Providers

`config/dependencies.php` déclare un **tableau de providers** — plus de Closure plate.
Application instancie chaque provider et appelle `register()` dans l'ordre du tableau.

```php
// config/dependencies.php
return [
    FrameworkServiceProvider::class,   // Session, Logger, Cache, Request, View, CSRF
    DatabaseServiceProvider::class,    // PDO
    AppServiceProvider::class,         // DAOs, Contrôleurs
];
```

**Ajouter un groupe de services** (ex: envoi d'e-mails) :

```php
// app/Providers/MailServiceProvider.php
namespace App\Providers;

use Core\Container;
use Core\ServiceProviderInterface;

final class MailServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container, array $appConfig, array $dbConfig): void
    {
        $container->singleton(Mailer::class, fn() => new Mailer(
            host: $_ENV['MAIL_HOST'] ?? 'localhost',
            port: (int) ($_ENV['MAIL_PORT'] ?? 587),
        ));
    }
}
```

```php
// config/dependencies.php — ajouter à la liste
return [
    FrameworkServiceProvider::class,
    DatabaseServiceProvider::class,
    AppServiceProvider::class,
    App\Providers\MailServiceProvider::class,  // ← nouveau
];
```

> Les providers du framework (`src/Core/Providers/`) ne sont **jamais** modifiés.
> Vos ajouts se font exclusivement dans `app/Providers/`.

---

### JSON / API REST

```php
// Corps JSON automatiquement décodé (Content-Type: application/json)
$data = $request->body;         // tableau associatif

// Répondre en JSON
$this->json(['id' => 1, 'name' => 'Alice'], 201);

// Vérifier le type de requête
$request->isJson();   // true si Content-Type: application/json
$request->isXhr();    // true si X-Requested-With: XMLHttpRequest
```

---

## Basculer vers MySQL

Éditer `.env` (recommandé) :

```ini
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mvc_db
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

---

## Relations entre entités — ORM léger

**Philosophie Astral :** pas de lazy-loading, pas de magie. Les relations sont des appels explicites dans les DAOs.

`AbstractDao` expose deux helpers `protected` que les DAOs concrets peuvent appeler :

### `hasMany()` — relation 1→N

```php
// Dans un DAO concret
/** @return list<Article> */
protected function articlesOf(int $categoryId): array
{
    return $this->hasMany(
        relatedClass: Article::class,
        table:        'articles',
        foreignKey:   'category_id',
        localId:      $categoryId,
        orderBy:      'created_at',
        direction:    'DESC',
    );
}
```

### `belongsTo()` — relation N→1

```php
// Dans un DAO concret
public function categoryOf(Article $article): ?Category
{
    return $this->belongsTo(
        relatedClass: Category::class,
        table:        'categories',
        foreignId:    $article->category_id,
    );
}
```

### Chargement combiné

Les DAOs exposent des méthodes de "chargement avec relation" pour éviter de multiples appels :

```php
// Charger une catégorie ET ses articles
$result = $categoryDao->findWithArticles(categoryId: 3);
// $result['category']  → Category
// $result['articles']  → list<Article>

// Charger un article ET sa catégorie
$result = $articleDao->findWithCategory(articleId: 42);
// $result['article']   → Article
// $result['category']  → Category|null
```

### Exemple complet : module Article

```php
// app/Dao/ArticleDao.php — relation belongs-to
final class ArticleDao extends AbstractDao
{
    protected function getTable(): string      { return 'articles'; }
    protected function getModelClass(): string { return Article::class; }

    /** Retourne les articles d'une catégorie */
    public function findByCategory(int $categoryId): array
    {
        return $this->hasMany(
            relatedClass: Article::class,
            table:        'articles',
            foreignKey:   'category_id',
            localId:      $categoryId,
            orderBy:      'created_at',
            direction:    'DESC',
        );
    }

    /** Charge la catégorie parente d'un article */
    public function categoryOf(Article $article): ?Category
    {
        return $this->belongsTo(
            relatedClass: Category::class,
            table:        'categories',
            foreignId:    $article->category_id,
        );
    }
}
```

### Règles et conventions


| Règle                                          | Raison                                                |
| ---------------------------------------------- | ----------------------------------------------------- |
| Pas de lazy-loading                            | Rend les requêtes visibles et prévisibles             |
| Méthodes nommées `<entité>Of($parent)`         | Ex: `articlesOf($categoryId)`, `categoryOf($article)` |
| `belongsTo` retourne `null` si `foreignId = 0` | Sécurité si la clé étrangère est nulle                |
| `findWith*()` pour chargement combiné          | Pratique pour les contrôleurs                         |


### Migrations fournies

```bash
php bin/console migrate
# Applique : create_categories_table, create_articles_table
```

---

## Authentification & Rôles

### Rôles disponibles

```php
use Core\Auth\Role;

Role::ADMIN  // 'admin'
Role::USER   // 'user'
Role::GUEST  // 'guest' (non connecté)
```

### Service Auth

Disponible dans tous les contrôleurs via le conteneur, et dans toutes les vues
via la variable `$auth` partagée automatiquement.

```php
// Contrôleur
$this->auth->login($user);      // connecte et régénère la session
$this->auth->logout();          // déconnecte

$this->auth->check();           // bool : connecté ?
$this->auth->guest();           // bool : non connecté ?
$this->auth->is(Role::ADMIN);   // bool : rôle exact
$this->auth->can(Role::ADMIN, Role::USER); // bool : l'un de ces rôles

$this->auth->id();    // int|null
$this->auth->name();  // string
$this->auth->email(); // string
$this->auth->role();  // string (Role::GUEST si non connecté)
```

```php
// Vue (variable $auth partagée globalement)
<?php if ($auth->check()): ?>
    Bienvenue, <?= htmlspecialchars($auth->name()) ?> !
<?php endif ?>

<?php if ($auth->is(\Core\Auth\Role::ADMIN)): ?>
    <a href="/admin">Administration</a>
<?php endif ?>
```

### Middleware Auth

Trois middleware intégrés, utilisables dans `config/routes.php` :


| Middleware        | Comportement                                                                              |
| ----------------- | ----------------------------------------------------------------------------------------- |
| `AuthMiddleware`  | Redirige vers `/login` si non connecté                                                    |
| `AdminMiddleware` | Redirige vers `/login` si non connecté ; lève `AuthorizationException` (403) si non admin |
| `GuestMiddleware` | Redirige vers `/` si déjà connecté (pour `/login`, `/register`)                           |


```php
use Core\Auth\Middleware\AdminMiddleware;
use Core\Auth\Middleware\AuthMiddleware;
use Core\Auth\Middleware\GuestMiddleware;

// Route individuelle
$router->get('/profile', UserController::class, 'profile')
       ->middleware(AuthMiddleware::class);

// Groupe protégé par authentification
$router->group('', function (Router $r): void {
    $r->get('/dashboard', DashboardController::class, 'index');
    $r->get('/settings',  SettingsController::class, 'index');
}, [AuthMiddleware::class]);

// Zone admin
$router->group('/admin', function (Router $r): void {
    $r->get('/dashboard', AdminController::class, 'index');
    $r->get('/users',     AdminController::class, 'users');
}, [AdminMiddleware::class]);

// Routes invités seulement
$router->get('/login',    AuthController::class, 'loginForm')->middleware(GuestMiddleware::class);
$router->post('/login',   AuthController::class, 'login')->middleware(GuestMiddleware::class);
$router->get('/register', AuthController::class, 'registerForm')->middleware(GuestMiddleware::class);
$router->post('/register', AuthController::class, 'register')->middleware(GuestMiddleware::class);
```

### Gestion des rôles dans UserDao

```php
// Créer un admin
$userDao->createUser('Alice', 'alice@example.com', 'secret', Role::ADMIN);

// Promouvoir un utilisateur existant
$userDao->promote(userId: 5, role: Role::ADMIN);

// Lister tous les admins
$admins = $userDao->findByRole(Role::ADMIN);
```

### Interface d'administration des rôles

L'application embarque une interface dédiée accessible aux administrateurs :


| Méthode | URI                     | Action                           | Middleware      |
| ------- | ----------------------- | -------------------------------- | --------------- |
| GET     | `/admin/users`          | Tableau de bord des rôles        | AdminMiddleware |
| POST    | `/admin/users/:id/role` | Changer le rôle d'un utilisateur | AdminMiddleware |


**Contrôleur :** `App\Controllers\Admin\UserController`

**Règles de sécurité intégrées :**

- Un admin ne peut pas modifier son propre rôle.
- Le dernier administrateur ne peut pas être rétrogradé (protection anti-lockout).
- Seuls les rôles définis dans `Role::all()` sont acceptés.

**Événement dispatché après chaque changement :**

```php
// app/Events/RoleChanged.php
final class RoleChanged implements EventInterface
{
    public function __construct(
        public User   $user,
        public string $oldRole,
        public string $newRole,
        public int    $changedBy,
    ) {}
}
```

**Listener fourni :** `App\Listeners\LogRoleChange` — journalise l'action via `Logger`.

### Modèle User — helpers de rôle

```php
$user->isAdmin();                       // bool
$user->isUser();                        // bool
$user->hasRole(Role::ADMIN, Role::USER); // bool : l'un de ces rôles
```

### Routes d'authentification (incluses par défaut)


| Méthode | URI         | Action                   | Middleware      |
| ------- | ----------- | ------------------------ | --------------- |
| GET     | `/login`    | Formulaire de connexion  | GuestMiddleware |
| POST    | `/login`    | Traitement de connexion  | GuestMiddleware |
| POST    | `/logout`   | Déconnexion              | —               |
| GET     | `/register` | Formulaire d'inscription | GuestMiddleware |
| POST    | `/register` | Traitement d'inscription | GuestMiddleware |


---

## Routes disponibles (application de démonstration)


| Méthode | URI                 | Action                   | Middleware      |
| ------- | ------------------- | ------------------------ | --------------- |
| GET     | `/`                 | Page d'accueil           | —               |
| GET     | `/login`            | Formulaire de connexion  | GuestMiddleware |
| POST    | `/login`            | Traitement connexion     | GuestMiddleware |
| POST    | `/logout`           | Déconnexion              | —               |
| GET     | `/register`         | Formulaire d'inscription | GuestMiddleware |
| POST    | `/register`         | Inscription              | GuestMiddleware |
| GET     | `/users`            | Liste des utilisateurs   | AuthMiddleware  |
| GET     | `/users/create`     | Formulaire de création   | AuthMiddleware  |
| GET     | `/users/:id`        | Fiche d'un utilisateur   | AuthMiddleware  |
| POST    | `/users`            | Créer un utilisateur     | AuthMiddleware  |
| POST    | `/users/:id/delete` | Supprimer un utilisateur | AuthMiddleware  |


---

## Tests

```bash
# Suite complète (178 tests)
vendor/bin/phpunit

# Par groupe
vendor/bin/phpunit --testsuite Core
vendor/bin/phpunit --testsuite Database

# Fichier individuel
vendor/bin/phpunit tests/Core/ValidatorTest.php
```

**Couverture actuelle** :


| Fichier                              | Ce qui est testé                                                     |
| ------------------------------------ | -------------------------------------------------------------------- |
| `tests/Core/ValidatorTest.php`       | 10 règles de validation, combinaisons, `errors()`/`first()`          |
| `tests/Core/ContainerTest.php`       | `bind`, `singleton`, `instance`, `has`, `make`, autowiring           |
| `tests/Core/RequestTest.php`         | Méthode HTTP, verb spoofing, URI, `input()`/`query()`/`header()`     |
| `tests/Core/SessionTest.php`         | `get/set/has/forget`, flash messages, `pullAllFlashes`               |
| `tests/Core/CacheTest.php`           | `get/set/has/forget/flush`, `remember()`, expiration TTL             |
| `tests/Core/Http/ResponseTest.php`   | `Response`, `JsonResponse`, `RedirectResponse`                       |
| `tests/Core/EventDispatcherTest.php` | `listen`, `dispatch`, `subscribe`, `hasListeners`, ordre, exceptions |
| `tests/Core/RouterTest.php`          | Route statique, route non trouvée                                    |
| `tests/Database/AbstractDaoTest.php` | Insert, findAll, update, delete, authenticate                        |
| `tests/Database/MigratorTest.php`    | `run`, `rollback`, `status`, batches, orphan detection               |


---

## Events & Listeners

Le système d'événements découple les effets de bord des contrôleurs.
`EventDispatcher` est synchrone et zéro dépendance.

### Créer un événement

```php
// app/Events/OrderPlaced.php
final class OrderPlaced implements EventInterface
{
    public function __construct(
        public Order  $order,
        public User   $customer,
    ) {}
}
```

### Créer un listener

```php
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
```

### Enregistrer dans AppServiceProvider

```php
$dispatcher->listen(OrderPlaced::class, SendOrderConfirmation::class);
```

### Dispatcher depuis un contrôleur

```php
$this->dispatcher->dispatch(new OrderPlaced($order, $user));
```

### Subscriber (regroupement de listeners)

```php
final class ShopSubscriber implements SubscriberInterface
{
    public function subscribe(EventDispatcher $dispatcher): void
    {
        $dispatcher->listen(OrderPlaced::class,    SendOrderConfirmation::class);
        $dispatcher->listen(OrderShipped::class,   SendShippingNotification::class);
    }
}

// Dans AppServiceProvider :
$dispatcher->subscribe(ShopSubscriber::class);
```

### Événements fournis


| Événement        | Déclenché par                | Listeners actifs                      |
| ---------------- | ---------------------------- | ------------------------------------- |
| `UserRegistered` | `AuthController::register()` | `SendWelcomeEmail`, `LogUserActivity` |
| `UserLoggedIn`   | `AuthController::login()`    | `LogUserActivity`                     |


---

## Migrations de base de données

Le système de migrations versionne le schéma de base de données via des fichiers PHP
dans `database/migrations/`. Chaque migration est un batch identifié en base dans la
table `migrations` (créée automatiquement, SQLite et MySQL compatibles).

### Convention de nommage

```
YYYY_MM_DD_HHMMSS_nom_en_snake_case.php  →  classe StudlyCase du suffixe
2026_03_11_100000_create_articles_table.php  →  CreateArticlesTable
```

### Commandes

```bash
# 1. Générer un fichier de migration
php bin/console make:migration create_articles_table
# → database/migrations/2026_03_11_100000_create_articles_table.php

# 2. Voir l'état de toutes les migrations
php bin/console migrate:status

# 3. Appliquer les migrations en attente (nouveau batch)
php bin/console migrate

# 4. Annuler le dernier batch
php bin/console migrate:rollback
```

### Anatomie d'un fichier de migration

```php
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
```

### Résultat de `migrate:status`

```
+---------------------------------------------------+---------+-------+---------------------+
| Migration                                         | Statut  | Batch | Exécutée le         |
+---------------------------------------------------+---------+-------+---------------------+
| 2026_03_11_000001_create_users_table.php          | applied | 1     | 2026-03-11 10:00:00 |
| 2026_03_11_100000_create_articles_table.php       | pending | -     | -                   |
+---------------------------------------------------+---------+-------+---------------------+
```


| Statut    | Signification                                |
| --------- | -------------------------------------------- |
| `applied` | Migration exécutée en base                   |
| `pending` | Fichier présent, pas encore appliqué         |
| `orphan`  | Enregistrement en base mais fichier supprimé |


---

## API REST JSON

Astral embarque une couche API complète, activée via des routes `/api/v1/*` protégées par CORS et Bearer Token.

### Format de réponse uniforme

```json
// Succès (200)
{ "data": { "id": 1, "title": "Mon article" } }

// Liste paginée (200)
{ "data": [...], "meta": { "total": 42, "page": 1, "per_page": 15, "pages": 3 } }

// Créé (201)
{ "data": { "id": 5, "title": "Nouveau" } }

// Supprimé (204) — corps vide

// Erreur de validation (422)
{ "error": { "code": "VALIDATION_ERROR", "message": "...", "details": { "title": ["..."] } } }

// Introuvable (404)
{ "error": { "code": "NOT_FOUND", "message": "Article #99 introuvable." } }

// Non authentifié (401)
{ "error": { "code": "UNAUTHORIZED", "message": "Token manquant ou invalide." } }
```

### Configuration

Dans `.env` :
```ini
API_KEY=change-me-generate-a-secure-key
# Générer : php -r "echo bin2hex(random_bytes(32));"
```

### Appels depuis un client

```bash
# Lister les articles (paginé)
curl -H "Authorization: Bearer <api_key>" \
     http://localhost/api/v1/articles?page=1

# Détail d'un article avec sa catégorie
curl -H "Authorization: Bearer <api_key>" \
     http://localhost/api/v1/articles/1

# Créer un article
curl -X POST \
     -H "Authorization: Bearer <api_key>" \
     -H "Content-Type: application/json" \
     -d '{"title":"Mon article","slug":"mon-article","body":"...","category_id":1}' \
     http://localhost/api/v1/articles

# Catégories avec articles publiés
curl -H "Authorization: Bearer <api_key>" \
     "http://localhost/api/v1/categories/1?with_articles=1&status=published"
```

### Routes disponibles

| Méthode | URI | Action |
|---------|-----|--------|
| GET | `/api/v1/articles` | Liste paginée (`?page=N&status=published`) |
| GET | `/api/v1/articles/:id` | Détail + catégorie liée |
| POST | `/api/v1/articles` | Créer (JSON body) |
| PUT | `/api/v1/articles/:id` | Modifier (JSON body partiel) |
| DELETE | `/api/v1/articles/:id` | Supprimer → 204 |
| GET | `/api/v1/categories` | Liste toutes les catégories |
| GET | `/api/v1/categories/:id` | Détail (`?with_articles=1`) |

### Créer un contrôleur API

```php
// app/Controllers/Api/PostApiController.php
final class PostApiController extends AbstractApiController
{
    public function __construct(
        private PostDao $postDao,
        private Request $request,
    ) {}

    public function index(): JsonResponse
    {
        $result = $this->postDao->paginate(page: 1, perPage: 15);
        return $this->paginated(
            items:      array_map(fn($p) => $p->toArray(), $result['data']),
            pagination: $result,
        );
    }

    public function show(string $id): JsonResponse
    {
        $post = $this->postDao->findById((int) $id);
        return $post !== null
            ? $this->success($post->toArray())
            : $this->notFound("Post #{$id} introuvable.");
    }

    public function store(): JsonResponse
    {
        $v = $this->validate((array) $this->request->post(), [
            'title' => 'required|min:3',
            'body'  => 'required',
        ]);

        if ($v->fails()) {
            return $this->validationError($v->errors());
        }

        $id = $this->postDao->insert([...(array) $this->request->post(), 'created_at' => date('Y-m-d H:i:s')]);
        return $this->created($this->postDao->findById($id)?->toArray() ?? []);
    }
}
```

### Middlewares

| Middleware | Rôle |
|-----------|------|
| `CorsMiddleware` | En-têtes `Access-Control-*`, gestion des requêtes OPTIONS preflight |
| `BearerTokenMiddleware` | Vérifie `Authorization: Bearer <token>` vs `API_KEY` |

---

## Scaffolding — Générateur de code

Le générateur crée automatiquement le squelette d'un module complet (Model, DAO, Controller, Migration).

### Mode interactif (recommandé)

```bash
php bin/console make:module
```

Le prompt guide étape par étape :

```
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
```

### Mode direct

```bash
# Tout générer d'un coup (web CRUD)
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
```

### Ce que génère chaque commande


| Commande                               | Fichier généré                          | Description                                                        |
| -------------------------------------- | --------------------------------------- | ------------------------------------------------------------------ |
| `make:model Article`                   | `app/Models/Article.php`                | Modèle anémique avec propriétés typées                             |
| `make:dao Article`                     | `app/Dao/ArticleDao.php`                | DAO héritant d'`AbstractDao`, table auto-détectée                  |
| `make:controller Article --resource`   | `app/Controllers/ArticleController.php` | 7 actions CRUD (index, show, create, store, edit, update, destroy) |
| `make:controller Article --api`        | `app/Controllers/ArticleController.php` | 5 actions JSON avec validation et codes HTTP                       |
| `make:migration create_articles_table` | `database/migrations/…php`              | Migration SQLite + MySQL                                           |


### Après la génération — étapes manuelles

```bash
# 1. Appliquer la migration
php bin/console migrate

# 2. Enregistrer dans app/Providers/AppServiceProvider.php
$container->singleton(ArticleDao::class, fn($c) => new ArticleDao($c->make(PDO::class)));
$container->bind(ArticleController::class, fn($c) => new ArticleController(
    view:        $c->make(View::class),
    request:     $c->make(Request::class),
    session:     $c->make(Session::class),
    articleDao:  $c->make(ArticleDao::class),
));

# 3. Ajouter les routes dans config/routes.php
$router->group('', function (Router $r): void {
    $r->get('/articles',             ArticleController::class, 'index');
    $r->get('/articles/create',      ArticleController::class, 'create');
    $r->post('/articles',            ArticleController::class, 'store');
    $r->get('/articles/:id',         ArticleController::class, 'show');
    $r->get('/articles/:id/edit',    ArticleController::class, 'edit');
    $r->put('/articles/:id',         ArticleController::class, 'update');
    $r->post('/articles/:id/delete', ArticleController::class, 'destroy');
}, [AuthMiddleware::class, CsrfMiddleware::class]);
```

> `public/index.php` et `src/Core/Application.php` ne sont **jamais** modifiés.

---

## Fonctionnalités PHP 8.x utilisées

- `declare(strict_types=1)` partout
- Constructor Property Promotion (`public function __construct(PDO $pdo)`)
- Named Arguments (`findAll(orderBy: 'name')`)
- `match` expression dans `Connection.php` et `Validator.php`
- Union types (`string|array`)
- `str_contains()`, `str_starts_with()`
- `fn()` arrow functions
- `mixed` type hint
- `never` return type (PHP 8.1, utilisé optionnellement)

