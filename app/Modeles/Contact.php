<?php

namespace App\Modeles;

use Core\Modele;

/**
 * Modèle Contact
 * 
 * Représente la table contacts
 * Utilisation:
 *   Contact::tout() - tous les contacts
 *   Contact::trouver(1) - un contact par ID
 *   Contact::creer(['nom' => '...', 'email' => '...', 'message' => '...'])
 */
class Contact extends Modele
{
    protected string $table = 'contacts';
}
