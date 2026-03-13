<?php

declare(strict_types=1);

namespace App\Dao;

use App\Models\Article;
use App\Models\Category;
use Database\AbstractDao;

/**
 * @example Ce fichier fait partie des exemples fournis avec Astral MVC.
 *          Vous pouvez le supprimer ou l'adapter à votre projet.
 *          Voir README.md — section "Démarrer un projet from scratch".
 *
 * DAO pour l'entité Article.
 *
 * Démontre la relation belongs-to (1 article → 1 catégorie) en utilisant
 * le helper `belongsTo()` hérité d'AbstractDao.
 *
 * @extends AbstractDao<Article>
 */
final class ArticleDao extends AbstractDao
{
    protected function getTable(): string
    {
        return 'articles';
    }

    protected function getModelClass(): string
    {
        return Article::class;
    }

    // -------------------------------------------------------------------------
    // Recherche
    // -------------------------------------------------------------------------

    public function findBySlug(string $slug): ?Article
    {
        $rows = $this->query(
            sql:    "SELECT * FROM {$this->getTable()} WHERE slug = :slug LIMIT 1",
            params: [':slug' => $slug],
        );

        /** @var Article|null */
        return $rows[0] ?? null;
    }

    /**
     * Retourne tous les articles publiés, du plus récent au plus ancien.
     *
     * @return list<Article>
     */
    public function findPublished(): array
    {
        /** @var list<Article> */
        return $this->query(
            sql: "SELECT * FROM {$this->getTable()} WHERE status = 'published' ORDER BY created_at DESC",
        );
    }

    /**
     * Retourne les articles d'une catégorie, avec filtre de statut optionnel.
     *
     * @return list<Article>
     */
    public function findByCategory(int $categoryId, string $status = ''): array
    {
        if ($status !== '') {
            /** @var list<Article> */
            return $this->query(
                sql:    "SELECT * FROM {$this->getTable()} WHERE category_id = :id AND status = :status ORDER BY created_at DESC",
                params: [':id' => $categoryId, ':status' => $status],
            );
        }

        /** @var list<Article> */
        return $this->hasMany(
            relatedClass: Article::class,
            table:        $this->getTable(),
            foreignKey:   'category_id',
            localId:      $categoryId,
            orderBy:      'created_at',
            direction:    'DESC',
        );
    }

    // -------------------------------------------------------------------------
    // Relation belongs-to : un article appartient à une catégorie
    // -------------------------------------------------------------------------

    /**
     * Charge la catégorie parente d'un article (relation belongs-to).
     *
     * Exemple d'utilisation :
     *   $category = $articleDao->categoryOf($article);
     *
     * @return Category|null  null si aucune catégorie associée
     */
    public function categoryOf(Article $article): ?Category
    {
        /** @var Category|null */
        return $this->belongsTo(
            relatedClass: Category::class,
            table:        'categories',
            foreignId:    $article->category_id,
        );
    }

    /**
     * Charge un article avec sa catégorie en un seul appel.
     *
     * Retourne null si l'article n'existe pas.
     *
     * @return array{article: Article, category: Category|null}|null
     */
    public function findWithCategory(int $articleId): ?array
    {
        /** @var Article|null $article */
        $article = $this->findById($articleId);

        if ($article === null) {
            return null;
        }

        return [
            'article'  => $article,
            'category' => $this->categoryOf($article),
        ];
    }
}
