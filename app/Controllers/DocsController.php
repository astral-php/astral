<?php

declare(strict_types=1);

namespace App\Controllers;

use Controller\AbstractController;
use Core\Http\Response;

/**
 * Contrôleur de la documentation en ligne.
 */
final class DocsController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('docs/index', [
            'title' => 'Documentation — Astral MVC',
        ]);
    }
}
