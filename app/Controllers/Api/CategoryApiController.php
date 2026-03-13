<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Dao\CategoryDao;
use Controller\AbstractApiController;
use Core\Http\JsonResponse;
use Core\Request;

/**
 * @example Ce fichier fait partie des exemples fournis avec Astral MVC.
 *          Vous pouvez le supprimer ou l'adapter à votre projet.
 *          Voir README.md — section "Démarrer un projet from scratch".
 *
 * Contrôleur API — Catégories.
 *
 * Démontre :
 *   - Listing simple (GET /api/v1/categories)
 *   - Détail avec relation has-many (GET /api/v1/categories/:id?with_articles=1)
 */
final class CategoryApiController extends AbstractApiController
{
    public function __construct(
        private CategoryDao $categoryDao,
        private Request     $request,
    ) {}

    // -------------------------------------------------------------------------
    // Actions
    // -------------------------------------------------------------------------

    /**
     * GET /api/v1/categories
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryDao->findAll(orderBy: 'name');
        return $this->success(array_map(fn($c) => $c->toArray(), $categories));
    }

    /**
     * GET /api/v1/categories/:id
     * GET /api/v1/categories/:id?with_articles=1          (avec articles)
     * GET /api/v1/categories/:id?with_articles=1&status=published
     */
    public function show(string $id): JsonResponse
    {
        $withArticles = (bool) $this->request->query('with_articles', false);
        $status       = (string) $this->request->query('status', '');

        if ($withArticles) {
            $result = $this->categoryDao->findWithArticles((int) $id, $status);

            if ($result === null) {
                return $this->notFound("Catégorie #{$id} introuvable.");
            }

            return $this->success(array_merge(
                $result['category']->toArray(),
                ['articles' => array_map(fn($a) => $a->toArray(), $result['articles'])],
            ));
        }

        $category = $this->categoryDao->findById((int) $id);

        if ($category === null) {
            return $this->notFound("Catégorie #{$id} introuvable.");
        }

        return $this->success($category->toArray());
    }
}
