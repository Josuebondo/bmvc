<?php

/**
 * ======================================================================
 * Routes Web - Framework BMVC Production
 * ======================================================================
 */

use Core\Routeur;

// Route Welcome
Routeur::obtenir('/', 'WelcomeControleur@index')->nom('welcome');
Routeur::obtenir('/welcome', 'WelcomeControleur@index')->nom('welcome');
