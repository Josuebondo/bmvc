<?php

/**
 * Script de débogage pour vérifier la requête HTTP réelle
 * À placer dans le dossier public
 */

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 DÉBOGAGE: Requête HTTP réelle\n";
echo str_repeat("=", 60) . "\n\n";

echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "\n";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET') . "\n";
echo "\n";

// Simulation du chemin
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$chemin = explode('?', $uri)[0];
echo "Chemin brut extrait: " . $chemin . "\n";

// Simulation du traitement
if (isset($_SERVER['SCRIPT_NAME'])) {
    $scriptPath = $_SERVER['SCRIPT_NAME'];
    $appDir = dirname(dirname($scriptPath));
    echo "App dir calculé: " . $appDir . "\n";

    if ($appDir !== '/' && strpos($chemin, $appDir) === 0) {
        $cheminTraite = substr($chemin, strlen($appDir));
        echo "Chemin après suppression du préfixe: " . $cheminTraite . "\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
