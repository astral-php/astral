<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Dao\UserDao;
use Controller\AbstractController;
use Core\Http\Response;
use Core\View;

/**
 * Contrôleur de la page d'accueil.
 *
 * Injecte UserDao pour détecter l'état d'installation initiale
 * (aucun utilisateur en base → affiche le bouton "Créer l'administrateur").
 */
final class HomeController extends AbstractController
{
    public function __construct(
        View              $view,
        private UserDao   $userDao,
        private string    $version = '1.0.0',
    ) {
        parent::__construct($view);
    }

    public function index(): Response
    {
        return $this->render('home/index', [
            'title'    => 'Astral MVC',
            'message'  => 'Framework PHP 8.x minimaliste avec DI, migrations, scaffolding, events et auth prête à l’emploi.',
            'version'  => $this->version,
            'hasUsers' => $this->userDao->count() > 0,
        ]);
    }
}
