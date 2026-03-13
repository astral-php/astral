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
 * DAO pour l'entité Category.
 *
 * Démontre la relation has-many (1 catégorie → N articles) en utilisant
 * le helper `hasMany()` hérité d'AbstractDao.
 *
 * @extends AbstractDao<Category>
 */
final class CategoryDao extends AbstractDao
{
    protected function getTable(): string
    {
        return 'categories';
    }

    protected function getModelClass(): string
    {
        return Category::class;
    }

    // -------------------------------------------------------------------------
    // Recherche
    // -------------------------------------------------------------------------

    public function findBySlug(string $slug): ?Category
    {
        $rows = $this->query(
            sql:    "SELECT * FROM {$this->getTable()} WHERE slug = :slug LIMIT 1",
            params: [':slug' => $slug],
        );

        /** @var Category|null */
        return $rows[0] ?? null;
    }

    // -------------------------------------------------------------------------
    // Relation has-many : une catégorie possède plusieurs articles
    // -------------------------------------------------------------------------

    /**
     * Retourne tous les articles d'une catégorie (relation has-many).
     *
     * Exemple d'utilisation :
     *   $articles = $categoryDao->articlesOf($category->id);
     *
     * @return list<Article>
     */
    public function articlesOf(int $categoryId, string $status = ''): array
    {
        if ($status !== '') {
            /** @var list<Article> */
            return $this->query(
                sql:    "SELECT * FROM articles WHERE category_id = :id AND status = :status ORDER BY created_at DESC",
                params: [':id' => $categoryId, ':status' => $status],
            );
        }

        /** @var list<Article> */
        return $this->hasMany(
            relatedClass: Article::class,
            table:        'articles',
            foreignKey:   'category_id',
            localId:      $categoryId,
            orderBy:      'created_at',
            direction:    'DESC',
        );
    }

    /**
     * Charge une catégorie avec ses articles en un seul appel.
     *
     * Retourne null si la catégorie n'existe pas.
     *
     * @return array{category: Category, articles: list<Article>}|null
     */
    public function findWithArticles(int $categoryId, string $status = ''): ?array
    {
        /** @var Category|null $category */
        $category = $this->findById($categoryId);

        if ($category === null) {
            return null;
        }

        return [
            'category' => $category,
            'articles' => $this->articlesOf($categoryId, $status),
        ];
    }
}
