<?php

/**
 * Script de test pour vérifier que les articles sont sauvegardés en BD
 */

// Charger l'autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Charger la configuration d'environnement
$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

// Importer le modèle Article
use App\Modeles\Article;
use Core\BaseBD;

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 TEST: Sauvegarde des articles en base de données\n";
echo str_repeat("=", 60) . "\n\n";

try {
    // Afficher les articles existants
    echo "1️⃣  Récupération des articles existants...\n";
    $articles = Article::tout();
    echo "   ✅ " . count($articles) . " article(s) trouvé(s)\n\n";

    foreach ($articles as $article) {
        echo "   - ID: {$article->id}, Titre: {$article->titre}\n";
    }
    echo "\n";

    // Créer un nouvel article
    echo "2️⃣  Création d'un nouvel article...\n";
    $nouvelArticle = Article::creer([
        'titre' => 'Article de test - ' . date('Y-m-d H:i:s'),
        'contenu' => 'Ceci est un article de test créé automatiquement pour vérifier que la sauvegarde en BD fonctionne correctement.'
    ]);
    echo "   ✅ Article créé avec l'ID: {$nouvelArticle->id}\n\n";

    // Récupérer et vérifier
    echo "3️⃣  Vérification de la sauvegarde...\n";
    $articleVerifie = Article::trouver($nouvelArticle->id);
    if ($articleVerifie) {
        echo "   ✅ Article trouvé en BD!\n";
        echo "   - Titre: {$articleVerifie->titre}\n";
        echo "   - Contenu: " . substr($articleVerifie->contenu, 0, 50) . "...\n\n";
    } else {
        echo "   ❌ Erreur: Article non trouvé en BD!\n\n";
    }

    // Afficher tous les articles
    echo "4️⃣  Total des articles après création...\n";
    $tousArticles = Article::tout();
    echo "   ✅ " . count($tousArticles) . " article(s) au total\n\n";

    echo str_repeat("=", 60) . "\n";
    echo "✨ TEST RÉUSSI! La base de données fonctionne correctement.\n";
    echo str_repeat("=", 60) . "\n\n";
} catch (\Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n\n";
}
