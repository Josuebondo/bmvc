<?php

/**
 * Afficher le contenu complet rendu
 */

require_once __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement
$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

use Core\Vue;

$vue = new Vue(__DIR__ . '/app/Vues');
$contenu = $vue->rendre('articles.creer', [
    'titre' => 'Créer un article'
]);

echo $contenu;
