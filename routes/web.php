<?php

/**
 * ======================================================================
 * Routes Web - Framework BMVC Production
 * ======================================================================
 */

use Core\Routeur;

// Route accueil
Routeur::obtenir('/', 'AccueilControleur@index')->nom('accueil');

// Route page de démarrage
Routeur::obtenir('/demarrage', 'DémarrageControlleur@index')->nom('démarrage');

// Routes Photos - Tests Upload
Routeur::obtenir('/photos', 'PhotoControleur@index')->nom('photos.index');
Routeur::obtenir('/photos/creer', 'PhotoControleur@creer')->nom('photos.creer');
Routeur::publier('/photos/sauvegarder', 'PhotoControleur@sauvegarder')->nom('photos.sauvegarder');
Routeur::obtenir('/photos/sauvegarder', 'PhotoControleur@sauvegarder')->nom('photos.sauvegarder');
Routeur::obtenir('/photos/voir/{id}', 'PhotoControleur@voir')->nom('photos.voir');
Routeur::obtenir('/photos/supprimer/{id}', 'PhotoControleur@supprimer')->nom('photos.supprimer');
Routeur::obtenir('/fichiers/{nom}', 'PhotoControleur@fichier')->nom('photos.fichier');
