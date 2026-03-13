<?php

declare(strict_types=1);

use Database\Migration\Migration;

/**
 * Migration : création de la table `articles`.
 *
 * Entité enfant dans l'exemple de relation has-many / belongs-to
 * fourni avec Astral MVC (Category → Articles).
 *
 * La clé étrangère `category_id` référence `categories.id`.
 * SET NULL si la catégorie est supprimée (pas de cascade) pour éviter
 * la perte silencieuse d'articles.
 */
class CreateArticlesTable extends Migration
{
    public function up(PDO $pdo): void
    {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS articles (
                    id          INTEGER  PRIMARY KEY,
                    category_id INTEGER  NULL REFERENCES categories(id) ON DELETE SET NULL,
                    title       TEXT     NOT NULL,
                    slug        TEXT     NOT NULL UNIQUE,
                    body        TEXT     NOT NULL DEFAULT '',
                    status      TEXT     NOT NULL DEFAULT 'draft',
                    created_at  TEXT     NOT NULL DEFAULT (datetime('now'))
                )
            SQL);
        } else {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS `articles` (
                    `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
                    `category_id` INT UNSIGNED  NULL,
                    `title`       VARCHAR(255)  NOT NULL,
                    `slug`        VARCHAR(255)  NOT NULL,
                    `body`        LONGTEXT      NOT NULL,
                    `status`      VARCHAR(20)   NOT NULL DEFAULT 'draft',
                    `created_at`  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `articles_slug_unique` (`slug`),
                    CONSTRAINT `articles_category_fk`
                        FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
                        ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL);
        }
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS articles');
    }
}
