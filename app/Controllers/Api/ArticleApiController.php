<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Dao\ArticleDao;
use App\Dao\CategoryDao;
use Controller\AbstractApiController;
use Core\Http\JsonResponse;
use Core\Http\Response;
use Core\Request;
use Database\Exception\DatabaseException;

/**
 * @example Ce fichier fait partie des exemples fournis avec Astral MVC.
 *          Vous pouvez le supprimer ou l'adapter à votre projet.
 *          Voir README.md — section "Démarrer un projet from scratch".
 *
 * Contrôleur API — Articles (CRUD complet).
 *
 * Démontre :
 *   - Listing paginé (GET /api/v1/articles)
 *   - Détail avec relation belongs-to (GET /api/v1/articles/:id)
 *   - Création avec validation (POST /api/v1/articles)
 *   - Mise à jour partielle (PUT /api/v1/articles/:id)
 *   - Suppression (DELETE /api/v1/articles/:id → 204)
 *
 * Toutes les routes sont protégées par BearerTokenMiddleware.
 */
final class ArticleApiController extends AbstractApiController
{
    public function __construct(
        private ArticleDao  $articleDao,
        private CategoryDao $categoryDao,
        private Request     $request,
    ) {}

    // -------------------------------------------------------------------------
    // Actions
    // -------------------------------------------------------------------------

    /**
     * GET /api/v1/articles
     * GET /api/v1/articles?page=2&status=published
     */
    public function index(): JsonResponse
    {
        $page   = max(1, (int) $this->request->query('page', 1));
        $status = (string) $this->request->query('status', '');

        if ($status !== '') {
            $articles = $this->articleDao->findPublished();
            return $this->success(array_map(fn($a) => $a->toArray(), $articles));
        }

        $result = $this->articleDao->paginate(
            page:      $page,
            perPage:   15,
            orderBy:   'created_at',
            direction: 'DESC',
        );

        return $this->paginated(
            items:      array_map(fn($a) => $a->toArray(), $result['data']),
            pagination: $result,
        );
    }

    /**
     * GET /api/v1/articles/:id
     * Charge l'article et sa catégorie (belongs-to).
     */
    public function show(string $id): JsonResponse
    {
        $result = $this->articleDao->findWithCategory((int) $id);

        if ($result === null) {
            return $this->notFound("Article #{$id} introuvable.");
        }

        return $this->success(array_merge(
            $result['article']->toArray(),
            ['category' => $result['category']?->toArray()],
        ));
    }

    /**
     * POST /api/v1/articles
     * Corps JSON attendu : { title, slug, body, category_id, status? }
     */
    public function store(): JsonResponse
    {
        $data = (array) $this->request->post();

        $v = $this->validate($data, [
            'title'       => 'required|min:3|max:255',
            'slug'        => 'required|min:3|max:255',
            'body'        => 'required',
            'category_id' => 'required|integer',
        ]);

        if ($v->fails()) {
            return $this->validationError($v->errors());
        }

        try {
            $id = $this->articleDao->insert([
                'category_id' => (int) $data['category_id'],
                'title'       => (string) $data['title'],
                'slug'        => (string) $data['slug'],
                'body'        => (string) $data['body'],
                'status'      => in_array($data['status'] ?? '', ['draft', 'published'], true)
                    ? (string) $data['status']
                    : 'draft',
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (DatabaseException) {
            return $this->conflict("Un article avec ce slug existe déjà.");
        }

        $article = $this->articleDao->findById($id);
        return $this->created($article?->toArray() ?? []);
    }

    /**
     * PUT /api/v1/articles/:id
     * Corps JSON : les champs à modifier (title, slug, body, category_id, status).
     */
    public function update(string $id): JsonResponse
    {
        $article = $this->articleDao->findById((int) $id);

        if ($article === null) {
            return $this->notFound("Article #{$id} introuvable.");
        }

        $data = (array) $this->request->post();

        $v = $this->validate($data, [
            'title'  => 'min:3|max:255',
            'slug'   => 'min:3|max:255',
            'status' => 'in:draft,published',
        ]);

        if ($v->fails()) {
            return $this->validationError($v->errors());
        }

        $allowed = ['category_id', 'title', 'slug', 'body', 'status'];
        $update  = array_filter(
            $data,
            fn(string $k) => in_array($k, $allowed, true),
            ARRAY_FILTER_USE_KEY,
        );

        if ($update !== []) {
            try {
                $this->articleDao->update((int) $id, $update);
            } catch (DatabaseException) {
                return $this->conflict("Un article avec ce slug existe déjà.");
            }
        }

        return $this->success($this->articleDao->findById((int) $id)?->toArray() ?? []);
    }

    /**
     * DELETE /api/v1/articles/:id → HTTP 204
     */
    public function destroy(string $id): Response
    {
        $deleted = $this->articleDao->delete((int) $id);

        if ($deleted === 0) {
            return $this->notFound("Article #{$id} introuvable.");
        }

        return $this->noContent();
    }
}
