<?php

/**
 * Simulation complète de la requête pour /articles
 */

require_once __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement
$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

use Core\Routeur;
use Core\Requete;

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 SIMULATION: Requête pour /articles\n";
echo str_repeat("=", 60) . "\n\n";

// Charger les routes
require_once __DIR__ . '/routes/web.php';

// Simuler plusieurs scénarios de requête
$scenarios = [
    [
        'name' => 'Requête directe /articles',
        'server' => [
            'REQUEST_URI' => '/articles',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/public/index.php'
        ]
    ],
    [
        'name' => 'Requête avec BMVC /BMVC/articles',
        'server' => [
            'REQUEST_URI' => '/BMVC/articles',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/BMVC/public/index.php'
        ]
    ],
    [
        'name' => 'Requête avec XAMPP path',
        'server' => [
            'REQUEST_URI' => '/xampp/htdocs/BMVC/articles',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/xampp/htdocs/BMVC/public/index.php'
        ]
    ]
];

$routes = Routeur::obtenirRoutes();

foreach ($scenarios as $scenario) {
    echo "📍 " . $scenario['name'] . "\n";
    echo "   REQUEST_URI: " . $scenario['server']['REQUEST_URI'] . "\n";
    echo "   SCRIPT_NAME: " . $scenario['server']['SCRIPT_NAME'] . "\n";

    $requete = new Requete($scenario['server'], [], [], []);
    $cheminExtrait = $requete->chemin();
    echo "   Chemin extrait: " . $cheminExtrait . "\n";

    // Chercher si une route correspond
    $routeTrouvee = false;
    foreach ($routes as $route) {
        if ($route->obtenirMethode() === 'GET' && $route->correspond($cheminExtrait)) {
            echo "   ✅ Route trouvée: " . $route->obtenirAction() . "\n";
            $routeTrouvee = true;
            break;
        }
    }

    if (!$routeTrouvee) {
        echo "   ❌ Aucune route GET correspondant à " . $cheminExtrait . "\n";

        // Afficher les routes GET disponibles
        echo "   Routes GET disponibles:\n";
        foreach ($routes as $route) {
            if ($route->obtenirMethode() === 'GET') {
                echo "      - " . $route->obtenirChemin() . "\n";
            }
        }
    }

    echo "\n";
}

echo str_repeat("=", 60) . "\n";
