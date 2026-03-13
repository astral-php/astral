<?php

declare(strict_types=1);

namespace Core\Providers;

use Core\Auth\Auth;
use Core\Auth\Middleware\AdminMiddleware;
use Core\Auth\Middleware\AuthMiddleware;
use Core\Auth\Middleware\GuestMiddleware;
use Core\Cache;
use Core\Container;
use Core\CsrfGuard;
use Core\Events\EventDispatcher;
use Core\Logger;
use Core\Middleware\CsrfMiddleware;
use Core\Request;
use Core\ServiceProviderInterface;
use Core\Session;
use Core\View;

/**
 * Enregistre tous les services fondamentaux du framework :
 * Session, Logger, Cache, Request, View, CsrfGuard, CsrfMiddleware.
 */
final class FrameworkServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container, array $appConfig, array $dbConfig): void
    {
        $container->singleton(Session::class, fn() => new Session());

        $container->singleton(Logger::class, fn() => new Logger(BASE_PATH . '/storage/logs'));

        $container->singleton(Cache::class, fn() => new Cache(BASE_PATH . '/storage/cache'));

        $container->singleton(Request::class, fn() => new Request());

        $container->singleton(View::class, fn() => new View(basePath: BASE_PATH));

        $container->singleton(
            CsrfGuard::class,
            fn(Container $c) => new CsrfGuard($c->make(Session::class)),
        );

        $container->bind(
            CsrfMiddleware::class,
            fn(Container $c) => new CsrfMiddleware($c->make(CsrfGuard::class)),
        );

        $container->singleton(Auth::class, fn(Container $c) => new Auth($c->make(Session::class)));

        $container->singleton(
            EventDispatcher::class,
            fn(Container $c) => new EventDispatcher($c),
        );

        $container->bind(
            AuthMiddleware::class,
            fn(Container $c) => new AuthMiddleware($c->make(Auth::class)),
        );

        $container->bind(
            AdminMiddleware::class,
            fn(Container $c) => new AdminMiddleware($c->make(Auth::class)),
        );

        $container->bind(
            GuestMiddleware::class,
            fn(Container $c) => new GuestMiddleware($c->make(Auth::class)),
        );
    }
}
