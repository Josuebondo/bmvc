<?php

/**
 * Script de débogage des routes
 */

require_once __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement
$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

use Core\Routeur;

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 TEST: Vérification des routes\n";
echo str_repeat("=", 60) . "\n\n";

// Charger les routes
require_once __DIR__ . '/routes/web.php';

// Afficher les routes enregistrées
$routes = Routeur::obtenirRoutes();
echo "Total de routes enregistrées: " . count($routes) . "\n\n";

foreach ($routes as $index => $route) {
    echo "Route " . ($index + 1) . ":\n";
    echo "  - Méthode: " . $route->obtenirMethode() . "\n";
    echo "  - Chemin: " . $route->obtenirChemin() . "\n";
    echo "  - Action: ";
    $action = $route->obtenirAction();
    if (is_string($action)) {
        echo $action;
    } else {
        echo "[Closure]";
    }
    echo "\n";
    echo "  - Nom: " . ($route->obtenirNom() ?? "Sans nom") . "\n";

    // Tester si la route correspond à /articles
    if ($route->correspond('/articles')) {
        echo "  ✅ CORRESPOND à /articles\n";
    }

    echo "\n";
}

// Tester la correspondance
echo "\n" . str_repeat("=", 60) . "\n";
echo "Test de correspondance pour /articles:\n";
echo str_repeat("=", 60) . "\n";

$routeTrouvee = false;
foreach ($routes as $route) {
    if ($route->obtenirMethode() === 'GET' && $route->correspond('/articles')) {
        echo "✅ Route trouvée!\n";
        echo "   Action: " . $route->obtenirAction() . "\n";
        $routeTrouvee = true;
    }
}

if (!$routeTrouvee) {
    echo "❌ Aucune route GET correspondant à /articles\n";
}
