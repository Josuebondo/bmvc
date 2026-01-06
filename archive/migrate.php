<?php

/**
 * Script de migration - Créer la table utilisateurs
 */

try {
    $db = new \PDO(
        'mysql:host=localhost;dbname=bmvc',
        'root',
        '',
        [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
    );

    // Créer la table utilisateurs
    $sql = "
        CREATE TABLE IF NOT EXISTS utilisateurs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nom VARCHAR(100) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            mot_de_passe VARCHAR(255) NOT NULL,
            role VARCHAR(50) DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $db->exec($sql);
    echo "✓ Table utilisateurs créée/vérifiée avec succès!\n";

    // Ajouter un utilisateur de test
    $email_test = 'admin@exemple.com';

    // Vérifier si l'utilisateur existe
    $check = $db->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $check->execute([$email_test]);

    if (!$check->fetch()) {
        $password_hash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);

        $insert = $db->prepare("
            INSERT INTO utilisateurs (nom, email, mot_de_passe, role) 
            VALUES (?, ?, ?, ?)
        ");

        $insert->execute(['Administrateur', $email_test, $password_hash, 'admin']);
        echo "✓ Utilisateur de test créé: admin@exemple.com (password: admin123)\n";
    } else {
        echo "✓ Utilisateur de test existe déjà\n";
    }
} catch (\Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
