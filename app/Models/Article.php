<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @example Ce fichier fait partie des exemples fournis avec Astral MVC.
 *          Vous pouvez le supprimer ou l'adapter à votre projet.
 *          Voir README.md — section "Démarrer un projet from scratch".
 *
 * Modèle Article.
 *
 * Exemples de relations :
 *   - Un article appartient à une catégorie (belongsTo).
 *     Usage : ArticleDao::findWithCategory($id, $categoryDao)
 *   - Une catégorie possède plusieurs articles (hasMany).
 *     Usage : CategoryDao::findWithArticles($categoryId, $articleDao)
 */
final class Article
{
    public int    $id          = 0;
    public int    $category_id = 0;
    public string $title       = '';
    public string $slug        = '';
    public string $body        = '';

    /** Statut : 'draft' | 'published' */
    public string $status      = 'draft';
    public string $created_at  = '';

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPersisted(): bool
    {
        return $this->id > 0;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'category_id' => $this->category_id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'body'        => $this->body,
            'status'      => $this->status,
            'created_at'  => $this->created_at,
        ];
    }
}
