<?php

/**
 * Test simple du formulaire
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

$env = parse_ini_file(dirname(__DIR__) . '/.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

use Core\Vue;

echo "<h1>Test du formulaire de création</h1>";
echo "<hr>";

// Simuler une requête GET (pas de soumission)
$_SERVER['REQUEST_METHOD'] = 'GET';

echo "<h2>État de la session:</h2>";
echo "<pre>";
session_start();
var_dump($_SESSION);
echo "</pre>";

echo "<h2>Rendu du formulaire:</h2>";

$vue = new Vue(dirname(__DIR__) . '/app/Vues');
$contenu = $vue->rendre('articles.creer', [
    'titre' => 'Créer un article',
    'erreurs' => isset($_SESSION['erreurs']) ? $_SESSION['erreurs'] : []
]);

echo $contenu;
