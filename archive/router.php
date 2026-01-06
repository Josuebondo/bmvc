<?php

/**
 * Routeur pour le serveur PHP intégré
 * À utiliser avec: php -S localhost:8000 router.php
 */

// Si c'est une vraie requête de fichier, la servir normalement
$requested_file = __DIR__ . '/public' . $_SERVER['REQUEST_URI'];

if (file_exists($requested_file)) {
    return false; // Le serveur PHP servira le fichier statique
}

// Sinon, rediriger vers index.php
$_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '/';
require_once __DIR__ . '/public/index.php';
