#!/usr/bin/env php
<?php

/**
 * ======================================================================
 * Setup Photos - Installation rapide de la table photos
 * ======================================================================
 * 
 * Utilisation:
 * php setup-photos.php
 * 
 * Ceci va:
 * 1. Créer la table photos
 * 2. Vérifier les permissions des dossiers
 * 3. Tester l'upload
 */

// Couleurs pour le terminal
class Colors
{
    const RESET = "\033[0m";
    const RED = "\033[91m";
    const GREEN = "\033[92m";
    const YELLOW = "\033[93m";
    const BLUE = "\033[94m";
    const CYAN = "\033[36m";
}

function log_info($msg)
{
    echo Colors::BLUE . "[INFO]" . Colors::RESET . " $msg\n";
}

function log_success($msg)
{
    echo Colors::GREEN . "[✓]" . Colors::RESET . " $msg\n";
}

function log_error($msg)
{
    echo Colors::RED . "[✗]" . Colors::RESET . " $msg\n";
}

function log_warning($msg)
{
    echo Colors::YELLOW . "[!]" . Colors::RESET . " $msg\n";
}

// En-tête
echo "\n";
echo Colors::CYAN;
echo "╔════════════════════════════════════════╗\n";
echo "║  📸 Setup Photos - BMVC Framework      ║\n";
echo "║  Installation rapide pour les tests    ║\n";
echo "╚════════════════════════════════════════╝\n";
echo Colors::RESET;
echo "\n";

// Inclure l'autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement
require_once __DIR__ . '/core/Env.php';

log_info("Initialisation...");

try {
    // Étape 1: Vérifier la connexion à la base de données
    log_info("Vérification de la connexion à la base de données...");

    $dbConfig = require __DIR__ . '/config/base_de_donnees.php';

    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};port={$dbConfig['port']}",
        $dbConfig['user'],
        $dbConfig['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    log_success("Connexion à la base de données réussie");

    // Sélectionner la base de données
    $pdo->exec("USE " . $dbConfig['database']);
    log_success("Base de données '{$dbConfig['database']}' sélectionnée");

    // Étape 2: Créer la table
    log_info("Création de la table 'photos'...");

    $sql = "
        CREATE TABLE IF NOT EXISTS photos (
            id INT PRIMARY KEY AUTO_INCREMENT,
            titre VARCHAR(255) NOT NULL,
            description TEXT,
            fichier VARCHAR(255) NOT NULL UNIQUE,
            chemin_relatif VARCHAR(500),
            chemin_complet VARCHAR(500),
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_titre (titre),
            INDEX idx_date (date_creation)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($sql);
    log_success("Table 'photos' créée/vérifiée");

    // Étape 3: Vérifier le dossier uploads
    log_info("Vérification du dossier d'uploads...");

    $uploadDir = __DIR__ . '/public/uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
        log_success("Dossier créé: $uploadDir");
    } else {
        log_success("Dossier existe: $uploadDir");
    }

    // Vérifier les permissions
    if (is_writable($uploadDir)) {
        log_success("Dossier accessible en écriture");
    } else {
        log_warning("Dossier non accessible en écriture");
        echo "Essayez: chmod 755 public/uploads/\n";
    }

    // Étape 4: Vérifier les fichiers créés
    log_info("Vérification des fichiers...");

    $files = [
        'app/Controleurs/PhotoControleur.php' => 'Contrôleur',
        'app/Modeles/Photo.php' => 'Modèle',
        'app/Vues/photos/creer.php' => 'Vue (créer)',
        'app/Vues/photos/index.php' => 'Vue (index)',
        'app/Vues/photos/voir.php' => 'Vue (voir)',
        'tests/Unit/Services/UploadServiceTest.php' => 'Tests',
    ];

    foreach ($files as $file => $desc) {
        $path = __DIR__ . '/' . $file;
        if (file_exists($path)) {
            log_success("$desc: $file");
        } else {
            log_error("$desc: $file (MANQUANT)");
        }
    }

    // Résumé
    echo "\n";
    echo Colors::GREEN;
    echo "╔════════════════════════════════════════╗\n";
    echo "║  ✓ Installation complètée!             ║\n";
    echo "╚════════════════════════════════════════╝\n";
    echo Colors::RESET;
    echo "\n";

    echo Colors::CYAN . "📋 Prochaines étapes:" . Colors::RESET . "\n";
    echo "  1. Accéder au formulaire: http://localhost/BMVC/photos/creer\n";
    echo "  2. Tester l'upload: http://localhost/BMVC/test-upload.php\n";
    echo "  3. Exécuter les tests: vendor/bin/phpunit tests/Unit/Services/UploadServiceTest.php\n";
    echo "\n";

    echo Colors::CYAN . "📸 Routes disponibles:" . Colors::RESET . "\n";
    echo "  GET  /photos                    - Galerie\n";
    echo "  GET  /photos/creer              - Formulaire d'upload\n";
    echo "  POST /photos/sauvegarder        - Traiter l'upload\n";
    echo "  GET  /photos/voir/{id}          - Voir une photo\n";
    echo "  GET  /photos/supprimer/{id}     - Supprimer une photo\n";
    echo "\n";
} catch (\Exception $e) {
    log_error("Erreur: " . $e->getMessage());
    exit(1);
}

echo "\n";
