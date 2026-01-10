<?php

/**
 * ======================================================================
 * Routes Web - Framework BMVC Production
 * ======================================================================
 */

use Core\Routeur;

// Route accueil
Routeur::obtenir('/', 'AccueilControlleur@index')->nom('accueil');

// Route page de démarrage
Routeur::obtenir('/demarrage', 'DémarrageControlleur@index')->nom('démarrage');
