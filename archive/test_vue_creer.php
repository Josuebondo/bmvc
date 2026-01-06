<?php

/**
 * Test de rendu de la vue articles.creer
 */

require_once __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement
$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

use Core\Vue;
use App\BaseControleur;

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 TEST: Rendu de la vue articles.creer\n";
echo str_repeat("=", 60) . "\n\n";

try {
    // Créer une instance de vue
    $vue = new Vue(__DIR__ . '/app/Vues');

    echo "1️⃣  Tentative de rendu de 'articles.creer'...\n";
    $contenu = $vue->rendre('articles.creer', [
        'titre' => 'Créer un article'
    ]);

    echo "2️⃣  Contenu rendu:\n";
    echo "   Longueur: " . strlen($contenu) . " caractères\n";

    if (strlen($contenu) === 0) {
        echo "   ❌ ERREUR: Le contenu rendu est vide!\n";
    } else {
        echo "   ✅ Contenu généré\n";
        echo "   Premier 200 caractères:\n";
        echo "   " . substr($contenu, 0, 200) . "...\n";
    }

    echo "\n";
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n\n";
}

echo str_repeat("=", 60) . "\n";
