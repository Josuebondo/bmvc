<?php

/**
 * Test du chemin avec différentes URLs
 */

require_once __DIR__ . '/vendor/autoload.php';

use Core\Requete;

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 TEST: Extraction du chemin de la requête\n";
echo str_repeat("=", 60) . "\n\n";

// Tester différents cas
$testCases = [
    [
        'REQUEST_URI' => '/BMVC/articles',
        'SCRIPT_NAME' => '/BMVC/public/index.php',
        'REQUEST_METHOD' => 'GET'
    ],
    [
        'REQUEST_URI' => '/BMVC/articles/creer',
        'SCRIPT_NAME' => '/BMVC/public/index.php',
        'REQUEST_METHOD' => 'GET'
    ],
    [
        'REQUEST_URI' => '/BMVC/articles?id=1',
        'SCRIPT_NAME' => '/BMVC/public/index.php',
        'REQUEST_METHOD' => 'GET'
    ],
    [
        'REQUEST_URI' => '/articles',
        'SCRIPT_NAME' => '/public/index.php',
        'REQUEST_METHOD' => 'GET'
    ],
];

foreach ($testCases as $index => $server) {
    $requete = new Requete($server, [], [], []);
    echo "Test " . ($index + 1) . ":\n";
    echo "  REQUEST_URI: " . $server['REQUEST_URI'] . "\n";
    echo "  SCRIPT_NAME: " . $server['SCRIPT_NAME'] . "\n";
    echo "  Chemin extrait: " . $requete->chemin() . "\n\n";
}

echo str_repeat("=", 60) . "\n";
