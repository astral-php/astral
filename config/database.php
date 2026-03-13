<?php

declare(strict_types=1);

/**
 * Configuration de la base de données.
 *
 * Les valeurs sont lues depuis le fichier .env (chargé par Application).
 * Pour SQLite, DB_DATABASE est un chemin relatif à la racine du projet.
 * Pour MySQL, DB_DATABASE est le nom de la base.
 *
 * @return array<string, mixed>
 */

$driver = $_ENV['DB_DRIVER'] ?? 'sqlite';

return [
    'driver'   => $driver,

    // SQLite : chemin absolu construit depuis BASE_PATH
    // MySQL  : nom de la base de données
    'database' => $driver === 'sqlite'
        ? (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__))
            . DIRECTORY_SEPARATOR . ltrim($_ENV['DB_DATABASE'] ?? 'database/app.sqlite', '/\\')
        : ($_ENV['DB_DATABASE'] ?? 'mvc_db'),

    'host'     => $_ENV['DB_HOST']     ?? '127.0.0.1',
    'port'     => (int) ($_ENV['DB_PORT'] ?? 3306),
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset'  => $_ENV['DB_CHARSET']  ?? 'utf8mb4',
];
