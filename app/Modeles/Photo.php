<?php

namespace App\Modeles;

use Core\Modele;

/**
 * Modèle Photo
 * Gère les données des photos uploadées
 */
class Photo extends Modele
{
    protected string $table = 'photos';

    /**
     * Les propriétés assignables en masse
     */
    protected $proprietes = [
        'titre',
        'description',
        'fichier',
        'chemin_relatif',
        'chemin_complet',
        'date_creation'
    ];
}
