<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @example Ce fichier fait partie des exemples fournis avec Astral MVC.
 *          Vous pouvez le supprimer ou l'adapter à votre projet.
 *          Voir README.md — section "Démarrer un projet from scratch".
 *
 * Modèle Category — catégorie d'articles.
 *
 * Exemple de relation has-many :
 *   Une catégorie possède plusieurs articles (hasMany).
 *   Usage : CategoryDao::findWithArticles($id, $articleDao)
 */
final class Category
{
    public int    $id         = 0;
    public string $name       = '';
    public string $slug       = '';
    public string $created_at = '';

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
            'id'         => $this->id,
            'name'       => $this->name,
            'slug'       => $this->slug,
            'created_at' => $this->created_at,
        ];
    }
}
