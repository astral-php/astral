<?php

declare(strict_types=1);

/**
 * Déclaration des routes de l'application.
 *
 * Ce fichier retourne une Closure qui reçoit le Router et y enregistre
 * toutes les routes. Ajoutez, modifiez ou supprimez des routes ici
 * sans jamais toucher à Application.php ni à index.php.
 *
 * Syntaxe :
 *   ->get('/chemin',  NomController::class, 'action')
 *   ->post('/chemin', NomController::class, 'action')
 *
 * Middleware par route :
 *   $router->get('/profile', ProfileController::class, 'editForm')
 *          ->middleware(AuthMiddleware::class);
 *
 * Groupe de routes :
 *   $router->group('/prefix', function (Router $r) { ... }, [AdminMiddleware::class]);
 */

use App\Controllers\Admin\UserController as AdminUserController;
use App\Controllers\Api\ArticleApiController;
use App\Controllers\Api\CategoryApiController;
use App\Controllers\AuthController;
use App\Controllers\DocsController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\UserController;
use Core\Auth\Middleware\AdminMiddleware;
use Core\Auth\Middleware\AuthMiddleware;
use Core\Auth\Middleware\GuestMiddleware;
use Core\Middleware\BearerTokenMiddleware;
use Core\Middleware\CorsMiddleware;
use Core\Router;

return function (Router $router): void {

    // -------------------------------------------------------------------------
    // Pages publiques
    // -------------------------------------------------------------------------
    $router->get('/',     HomeController::class, 'index');
    $router->get('/docs', DocsController::class, 'index');

    // -------------------------------------------------------------------------
    // Authentification (invités uniquement pour login/register)
    // -------------------------------------------------------------------------
    $router->get('/login',     AuthController::class, 'loginForm')->middleware(GuestMiddleware::class);
    $router->post('/login',    AuthController::class, 'login')->middleware(GuestMiddleware::class);
    $router->post('/logout',   AuthController::class, 'logout');

    $router->get('/register',  AuthController::class, 'registerForm')->middleware(GuestMiddleware::class);
    $router->post('/register', AuthController::class, 'register')->middleware(GuestMiddleware::class);

    // Confirmation d'e-mail
    $router->get('/verify-pending', AuthController::class, 'verifyPending');
    $router->get('/verify-email',   AuthController::class, 'verifyEmail');

    // Mot de passe oublié
    $router->get('/forgot-password',  AuthController::class, 'forgotPasswordForm')->middleware(GuestMiddleware::class);
    $router->post('/forgot-password', AuthController::class, 'forgotPassword')->middleware(GuestMiddleware::class);
    $router->get('/reset-password',   AuthController::class, 'resetPasswordForm')->middleware(GuestMiddleware::class);
    $router->post('/reset-password',  AuthController::class, 'resetPassword')->middleware(GuestMiddleware::class);

    // -------------------------------------------------------------------------
    // Profil utilisateur — tout utilisateur connecté
    // -------------------------------------------------------------------------
    $router->group('', function (Router $r): void {
        $r->get('/profile',           ProfileController::class, 'editForm');
        $r->post('/profile',          ProfileController::class, 'update');
        $r->post('/profile/password', ProfileController::class, 'updatePassword');
    }, [AuthMiddleware::class]);

    // -------------------------------------------------------------------------
    // Consultation utilisateurs — tout utilisateur connecté
    // -------------------------------------------------------------------------
    $router->group('', function (Router $r): void {
        $r->get('/users',     UserController::class, 'index');
        $r->get('/users/:id', UserController::class, 'show');
    }, [AuthMiddleware::class]);

    // -------------------------------------------------------------------------
    // Gestion utilisateurs — administrateurs uniquement
    // -------------------------------------------------------------------------
    $router->group('', function (Router $r): void {
        $r->get('/users/create',      UserController::class, 'create');
        $r->post('/users',            UserController::class, 'store');
        $r->post('/users/:id/delete', UserController::class, 'destroy');
    }, [AdminMiddleware::class]);

    // -------------------------------------------------------------------------
    // Zone administration — gestion des rôles
    // -------------------------------------------------------------------------
    $router->group('/admin', function (Router $r): void {
        $r->get('/users',             AdminUserController::class, 'index');
        $r->post('/users/:id/role',   AdminUserController::class, 'updateRole');
    }, [AdminMiddleware::class]);

    // -------------------------------------------------------------------------
    // API REST v1 — protégée par CORS + Bearer Token
    // -------------------------------------------------------------------------
    $router->group('/api/v1', function (Router $r): void {

        // Articles — CRUD complet
        $r->get('/articles',        ArticleApiController::class, 'index');
        $r->get('/articles/:id',    ArticleApiController::class, 'show');
        $r->post('/articles',       ArticleApiController::class, 'store');
        $r->put('/articles/:id',    ArticleApiController::class, 'update');
        $r->delete('/articles/:id', ArticleApiController::class, 'destroy');

        // Catégories — lecture seule
        $r->get('/categories',      CategoryApiController::class, 'index');
        $r->get('/categories/:id',  CategoryApiController::class, 'show');

    }, [CorsMiddleware::class, BearerTokenMiddleware::class]);
};
