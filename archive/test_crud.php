<?php

/**
 * Test simple du CRUD complet
 */

require_once __DIR__ . '/vendor/autoload.php';

$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

use App\Modeles\Article;

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 TEST CRUD ARTICLES\n";
echo str_repeat("=", 60) . "\n\n";

// 1. LIRE (READ)
echo "1️⃣ LIRE - Afficher tous les articles:\n";
$articles = Article::tout();
echo "   ✓ " . count($articles) . " article(s) trouvé(s)\n\n";

// 2. CRÉER (CREATE)
echo "2️⃣ CRÉER - Créer un nouvel article:\n";
$nouvel = Article::creer([
    'titre' => 'Article test #' . time(),
    'contenu' => 'Ceci est un article de test créé pour vérifier le CRUD'
]);
echo "   ✓ Article créé avec l'ID: " . $nouvel->id . "\n";
echo "   Titre: " . $nouvel->titre . "\n\n";

// 3. ÉDITER (UPDATE)
echo "3️⃣ ÉDITER - Mettre à jour l'article:\n";
$article_a_editer = Article::trouver($nouvel->id);
echo "   Titre avant: " . $article_a_editer->titre . "\n";
$article_a_editer->titre = "Article modifié - " . time();
$article_a_editer->sauvegarder();
echo "   Titre après: " . $article_a_editer->titre . "\n\n";

// 4. VÉRIFIER L'ÉDITION
echo "4️⃣ VÉRIFIER - Récupérer l'article modifié:\n";
$article_verif = Article::trouver($nouvel->id);
echo "   ✓ Titre: " . $article_verif->titre . "\n\n";

// 5. SUPPRIMER (DELETE)
echo "5️⃣ SUPPRIMER - Supprimer l'article:\n";
$article_verif->supprimer();
echo "   ✓ Article supprimé\n\n";

// 6. VÉRIFIER LA SUPPRESSION
echo "6️⃣ VÉRIFIER - Rechercher l'article supprimé:\n";
$article_supprime = Article::trouver($nouvel->id);
if ($article_supprime === null) {
    echo "   ✓ Article introuvable (suppression confirmée)\n\n";
} else {
    echo "   ✗ Article toujours présent (ERREUR)\n\n";
}

echo str_repeat("=", 60) . "\n";
echo "✨ TEST CRUD TERMINÉ\n";
echo str_repeat("=", 60) . "\n\n";
