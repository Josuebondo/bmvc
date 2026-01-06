<?php

/**
 * Test pour vérifier si mod_rewrite fonctionne
 */

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 TEST: Vérification de mod_rewrite\n";
echo str_repeat("=", 60) . "\n\n";

// Vérifier si mod_rewrite est chargé
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "✅ mod_rewrite est ACTIVÉ\n\n";
    } else {
        echo "❌ mod_rewrite est DÉSACTIVÉ\n";
        echo "   Modules Apache disponibles:\n";
        foreach ($modules as $module) {
            if (strpos($module, 'rewrite') !== false) {
                echo "      - $module\n";
            }
        }
        echo "\n";
    }
} else {
    echo "⚠️  Impossible de vérifier les modules Apache\n";
    echo "   (Vous n'êtes pas sur Apache, ou la fonction est désactivée)\n\n";
}

// Vérifier AllowOverride
echo "Vérification du .htaccess:\n";
if (file_exists(__DIR__ . '/.htaccess')) {
    echo "✅ Fichier .htaccess trouvé\n";
    echo "   Contenu:\n";
    $lines = file(__DIR__ . '/.htaccess');
    foreach (array_slice($lines, 0, 10) as $line) {
        echo "   " . trim($line) . "\n";
    }
} else {
    echo "❌ Fichier .htaccess NON TROUVÉ\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
