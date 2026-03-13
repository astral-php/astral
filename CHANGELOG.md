# Journal des modifications

Toutes les modifications notables de **Astral MVC** sont documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet respecte le [Versioning Sémantique](https://semver.org/lang/fr/).

---

## [1.0.0] — 2026-03-11

Première publication publique d'Astral MVC — micro-framework PHP 8.x minimaliste.

### Ajouté

#### Cœur du framework (`src/`)
- **Conteneur DI** — auto-wiring via Reflection, support singleton, liaison de services
- **Pattern Service Provider** — `ServiceProviderInterface` pour l'enregistrement modulaire
- **Router** — routage par regex, routes nommées, groupes de middleware, paramètres de route
- **Request** — objet inspiré PSR encapsulant les superglobales, parsing du corps JSON
- **Objets Response** — `Response`, `JsonResponse`, `RedirectResponse`, tous testables
- **Session** — wrapper typé avec messages flash
- **CSRF Guard** — génération de token, validation, protection par formulaire ou globale
- **Validator** — validation par règles (`required`, `email`, `min`, `max`, `unique`…)
- **Logger** — logger fichier inspiré PSR-3 avec support du contexte
- **Cache** — cache fichier avec TTL
- **Mailer** — wrapper PHPMailer avec configuration SMTP
- **View** — moteur de templates PHP natif avec layouts et helper CSRF

#### Authentification (`src/Core/Auth/`)
- Inscription, connexion, déconnexion
- Garde d'authentification basée sur la session
- Système de rôles (`Role::ADMIN`, `Role::USER`)
- `AdminMiddleware` pour la protection des routes
- Panneau admin pour la gestion des rôles utilisateurs avec garde anti-blocage

#### Base de données (`src/Database/`)
- `Connection` — singleton PDO (MySQL et SQLite compatibles)
- `AbstractDao` — CRUD générique (`find`, `findAll`, `insert`, `update`, `delete`, `paginate`)
- `hasMany()` / `belongsTo()` — helpers de relations explicites 1-N et N-1 (ORM léger)
- **Système de migrations** — migrations horodatées, classe `Migrator`, cycle `up()`/`down()`
- Commandes CLI : `migrate`, `migrate:rollback`, `migrate:status`, `make:migration`

#### Événements et écouteurs (`src/Core/Events/`)
- `EventInterface`, `ListenerInterface`, `SubscriberInterface`
- `EventDispatcher` synchrone avec écouteurs résolus par le conteneur
- Événements applicatifs : `UserRegistered`, `UserLoggedIn`, `RoleChanged`

#### Console CLI (`bin/console`, `src/Core/Console/`)
- Dispatcher `Console` + `CommandInterface`
- Commandes de scaffolding : `make:model`, `make:dao`, `make:controller`, `make:module`
- Mode interactif avec helpers `ask()`, `confirm()`, `choice()`

#### API REST (`src/Core/Http/`, `src/Controller/`, `src/Core/Middleware/`)
- `ApiResponse` — réponses JSON structurées (`success`, `error`, `pagination`, `validationError`)
- `AbstractApiController` — classe de base avec méthodes helper typées
- `CorsMiddleware` — en-têtes CORS + prévol OPTIONS (HTTP 204)
- `BearerTokenMiddleware` — authentification par clé API (HTTP 401 en cas d'échec)
- Réponses d'erreur JSON pour toutes les routes `/api/*` (exceptions framework incluses)

#### Application (`app/`)
- Module d'authentification complet (inscription, connexion, profil, déconnexion)
- Module exemple : Catégories + Articles avec relations `hasMany`/`belongsTo` (`@example`)
- Contrôleurs API exemple pour Articles (CRUD complet) et Catégories (`@example`)
- `AppServiceProvider` avec câblage complet des dépendances

#### Tests (`tests/`)
- Suites de tests PHPUnit 9.6 : `Core`, `Database`, `Events`
- Tests pour `Validator`, `Container`, `Request`, `Session`, `Cache`, `Response`, `Migrator`, `EventDispatcher`

#### Documentation
- `README.md` — guide complet d'installation, utilisation, API, migrations, scaffolding
- `views/docs/index.php` — documentation web complète (servie sur `/docs`)


## Versioning

Ce projet utilise le [Versioning Sémantique](https://semver.org/lang/fr/) :
- **MAJOR** — changements incompatibles de l'API publique ou de l'architecture
- **MINOR** — nouvelles fonctionnalités, rétrocompatibles
- **PATCH** — corrections de bugs, rétrocompatibles
