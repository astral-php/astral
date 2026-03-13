<?php

declare(strict_types=1);

/**
 * Déclaration des Service Providers.
 *
 * Ce fichier retourne un tableau de FQCN implémentant ServiceProviderInterface.
 * Chaque provider est instancié par Application et sa méthode register()
 * est appelée avec le Container et les tableaux de configuration.
 *
 * Ordre d'exécution : les providers sont chargés dans l'ordre du tableau.
 * Les services déclarés en premier sont disponibles pour les suivants.
 *
 * Pour ajouter un groupe de services, créez un provider dans app/Providers/
 * et ajoutez son FQCN ici — sans toucher à Application.php ni à index.php.
 */

use App\Providers\AppServiceProvider;
use Core\Providers\DatabaseServiceProvider;
use Core\Providers\FrameworkServiceProvider;

return [
    FrameworkServiceProvider::class,   // Session, Logger, Cache, Request, View, CSRF
    DatabaseServiceProvider::class,    // PDO (SQLite / MySQL)
    AppServiceProvider::class,         // DAOs, Contrôleurs applicatifs
];
