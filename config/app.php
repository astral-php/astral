<?php

declare(strict_types=1);

/**
 * Configuration générale de l'application.
 *
 * Les valeurs sont lues depuis le fichier .env (chargé par Application).
 * Les valeurs ci-dessous servent de fallback si la variable est absente.
 *
 * @return array<string, mixed>
 */
return [
    'name'     => $_ENV['APP_NAME']     ?? 'ASTRAL-MVC',
    'version'  => $_ENV['APP_VERSION']  ?? '1.1.0',
    'env'      => $_ENV['APP_ENV']      ?? 'production',
    'debug'    => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
    'charset'  => $_ENV['APP_CHARSET']  ?? 'UTF-8',
    'base_url' => $_ENV['APP_BASE_URL'] ?? '',

    // 'direct' : accès immédiat après inscription
    // 'confirm' : l'utilisateur doit confirmer son e-mail
    'auth_registration' => $_ENV['AUTH_REGISTRATION'] ?? 'direct',

    // ── API REST JSON ──────────────────────────────────────────────────────────
    // Clé secrète Bearer token pour les routes /api/v1/*
    // Générer avec : php -r "echo bin2hex(random_bytes(32));"
    'api_key' => $_ENV['API_KEY'] ?? '',

    'mail' => [
        'driver'      => $_ENV['MAIL_DRIVER']      ?? 'mail',
        'host'        => $_ENV['MAIL_HOST']         ?? 'localhost',
        'port'        => (int) ($_ENV['MAIL_PORT']  ?? 25),
        'username'    => $_ENV['MAIL_USERNAME']     ?? '',
        'password'    => $_ENV['MAIL_PASSWORD']     ?? '',
        'encryption'  => $_ENV['MAIL_ENCRYPTION']   ?? '',
        'from'        => $_ENV['MAIL_FROM']         ?? 'noreply@localhost',
        'from_name'   => $_ENV['MAIL_FROM_NAME']    ?? 'Mini-MVC',
    ],
];
