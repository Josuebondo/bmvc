<?php

namespace App\Controleurs;

use App\BaseControleur;
use Core\Requete;
use Core\Reponse;

class WelcomeControleur extends BaseControleur
{
    public function index()
    {
        return $this->rendre('welcome', [
            'titre' => 'Bienvenue sur BMVC',
            'message' => 'Framework PHP MVC prêt pour la production'
        ]);
    }
}
