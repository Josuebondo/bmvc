#!/usr/bin/env php
<?php

/**
 * Script pour créer la table articles et tester la création
 */

require_once __DIR__ . '/public/index.php';

use Core\BaseBD;
use App\Modeles\Article;

echo "\n📊 Préparation de la base de données...\n\n";

$bd = BaseBD::obtenir();

// Créer la table articles
$sql = "CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    contenu LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$bd->connexion()->exec($sql);
echo "✅ Table articles prête\n";

// Créer la table contacts
$sql = "CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$bd->connexion()->exec($sql);
echo "✅ Table contacts prête\n\n";

// Vider les tables pour recommencer
$bd->connexion()->exec("TRUNCATE TABLE articles");
$bd->connexion()->exec("TRUNCATE TABLE contacts");

echo "🗑️  Tables vidées\n\n";

// Créer quelques articles d'exemple
echo "📝 Création des articles d'exemple...\n\n";

$articles_data = [
    [
        'titre' => 'Introduction à BMVC',
        'contenu' => 'BMVC est un framework PHP léger et puissant inspiré de Laravel. Découvrez comment démarrer rapidement avec notre documentation complète.'
    ],
    [
        'titre' => 'ORM en 5 minutes',
        'contenu' => 'Apprenez à utiliser notre mini-ORM Eloquent-like. Créez, lisez, mettez à jour et supprimez des données facilement avec une API élégante.'
    ],
    [
        'titre' => 'PHP 8.1 avec BMVC',
        'contenu' => 'Explorez les dernières features de PHP 8.1 avec BMVC. Le typed properties, named arguments, et bien plus encore sont supportés.'
    ]
];

foreach ($articles_data as $data) {
    $article = Article::creer($data);
    echo "✅ Article créé: \"{$article->titre}\" (ID: {$article->id})\n";
}

echo "\n✨ Toutes les données d'exemple sont prêtes!\n";
echo "Vous pouvez maintenant:\n";
echo "  - Visiter http://localhost:8000/articles\n";
echo "  - Créer un nouvel article via le formulaire\n";
echo "  - Les articles seront sauvegardés en base de données\n\n";
