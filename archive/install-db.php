#!/usr/bin/env php
<?php

/**
 * Script d'installation BMVC avec MySQL XAMPP
 * Crée la base de données et les tables
 */

echo "\n" . str_repeat("=", 60) . "\n";
echo "🚀 INSTALLATION BMVC - Base de Données MySQL\n";
echo str_repeat("=", 60) . "\n\n";

// Configuration MySQL (utilisez les paramètres de votre .env)
$db_host = 'localhost';
$db_port = 3306;
$db_username = 'root';
$db_password = ''; // XAMPP par défaut: pas de password
$db_name = 'bmvc';

try {
    // Étape 1: Connexion à MySQL (sans base de données)
    echo "1️⃣  Connexion à MySQL...\n";
    $pdo = new PDO(
        "mysql:host=$db_host;port=$db_port;charset=utf8mb4",
        $db_username,
        $db_password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    echo "✅ Connecté à MySQL\n\n";

    // Étape 2: Créer la base de données
    echo "2️⃣  Création de la base de données '$db_name'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Base de données prête\n\n";

    // Étape 3: Utiliser la base de données
    echo "3️⃣  Connexion à la base de données '$db_name'...\n";
    $pdo->exec("USE $db_name");
    echo "✅ Base de données sélectionnée\n\n";

    // Étape 4: Créer la table articles
    echo "4️⃣  Création de la table 'articles'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(200) NOT NULL,
        contenu LONGTEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ Table 'articles' créée\n\n";

    // Étape 5: Créer la table contacts
    echo "5️⃣  Création de la table 'contacts'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        message LONGTEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ Table 'contacts' créée\n\n";

    // Étape 6: Vider les tables
    echo "6️⃣  Préparation des tables...\n";
    $pdo->exec("TRUNCATE TABLE articles");
    $pdo->exec("TRUNCATE TABLE contacts");
    echo "✅ Tables vidées\n\n";

    // Étape 7: Insérer des articles d'exemple
    echo "7️⃣  Insertion des articles d'exemple...\n";

    $articles = [
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

    $stmt = $pdo->prepare("INSERT INTO articles (titre, contenu) VALUES (?, ?)");
    foreach ($articles as $article) {
        $stmt->execute([$article['titre'], $article['contenu']]);
        echo "   ✅ Inséré: \"{$article['titre']}\"\n";
    }
    echo "\n";

    // Résumé
    echo str_repeat("=", 60) . "\n";
    echo "✨ INSTALLATION RÉUSSIE!\n";
    echo str_repeat("=", 60) . "\n\n";

    echo "📊 Résumé:\n";
    echo "   • Base de données: $db_name\n";
    echo "   • Tables: articles, contacts\n";
    echo "   • Articles d'exemple: 3\n\n";

    echo "📝 Prochaines étapes:\n";
    echo "   1. Ouvrez le navigateur à: http://localhost:8000\n";
    echo "   2. Allez à: http://localhost:8000/articles\n";
    echo "   3. Créez un nouvel article!\n\n";
} catch (PDOException $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n\n";

    echo "💡 Troubleshooting:\n";
    echo "   1. MySQL est-il démarré dans XAMPP?\n";
    echo "   2. Vérifiez les paramètres dans .env:\n";
    echo "      - DB_HOST = localhost\n";
    echo "      - DB_PORT = 3306\n";
    echo "      - DB_USERNAME = root\n";
    echo "      - DB_PASSWORD = (vide pour XAMPP)\n";
    echo "   3. Sinon, lancez: mysql -u root\n\n";

    exit(1);
}
