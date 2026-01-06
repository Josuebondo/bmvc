<?php

/**
 * Script de débogage pour vérifier la requête actuelle
 */

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 TEST: Débogage de la requête HTTP\n";
echo str_repeat("=", 60) . "\n\n";

echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "\n";
echo "\n";

// Simulation du chemin
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$chemin = explode('?', $uri)[0];
echo "Chemin extrait: " . $chemin . "\n";
echo "\n";
