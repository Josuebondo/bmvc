<?php

/**
 * VÉRIFICATION COMPLÈTE DU FRAMEWORK BMVC
 * Checklist de toutes les fonctionnalités
 */

require_once __DIR__ . '/vendor/autoload.php';

$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "✅ VÉRIFICATION FRAMEWORK BMVC\n";
echo str_repeat("=", 70) . "\n\n";

$features = [];

// PHASE 1: BASE DU FRAMEWORK
echo "🟢 PHASE 1: BASE DU FRAMEWORK\n";
echo str_repeat("-", 70) . "\n";

$features['1.1'] = [
    'Structure du projet',
    file_exists(__DIR__ . '/app') &&
        file_exists(__DIR__ . '/core') &&
        file_exists(__DIR__ . '/public') &&
        file_exists(__DIR__ . '/config') &&
        file_exists(__DIR__ . '/routes')
];

$features['1.2'] = [
    '.htaccess & URLs propres',
    file_exists(__DIR__ . '/public/.htaccess')
];

$features['1.3'] = [
    'Point d\'entrée public/index.php',
    file_exists(__DIR__ . '/public/index.php')
];

$features['1.4'] = [
    'Kernel Application.php',
    file_exists(__DIR__ . '/core/Application.php') &&
        class_exists('\Core\Application')
];

// PHASE 2: ROUTAGE & MVC
echo "\n🟡 PHASE 2: ROUTAGE & MVC\n";
echo str_repeat("-", 70) . "\n";

$features['2.1'] = [
    'Routeur (GET/POST/PUT/DELETE)',
    class_exists('\Core\Routeur') &&
        method_exists('\Core\Routeur', 'obtenir') &&
        method_exists('\Core\Routeur', 'publier') &&
        method_exists('\Core\Routeur', 'mettre') &&
        method_exists('\Core\Routeur', 'supprimer')
];

$features['2.2'] = [
    'Routeur - Paramètres dynamiques {id}',
    class_exists('\Core\Route') &&
        method_exists('\Core\Route', 'correspond')
];

$features['2.3'] = [
    'Routeur - Middlewares par route',
    class_exists('\Core\Route') &&
        method_exists('\Core\Route', 'middleware')
];

$features['2.4'] = [
    'Routeur - Groupes de routes',
    class_exists('\Core\Routeur') &&
        method_exists('\Core\Routeur', 'groupe')
];

$features['2.5'] = [
    'Contrôleurs - BaseControleur',
    class_exists('\App\BaseControleur') &&
        method_exists('\App\BaseControleur', 'afficher')
];

$features['2.6'] = [
    'Contrôleurs - Héritage correct',
    class_exists('\App\Controleurs\ArticleControleur') &&
        is_subclass_of('\App\Controleurs\ArticleControleur', '\App\BaseControleur')
];

$features['2.7'] = [
    'Vue - Layouts',
    class_exists('\Core\Vue') &&
        method_exists('\Core\Vue', 'extends')
];

$features['2.8'] = [
    'Vue - Sections',
    class_exists('\Core\Vue') &&
        method_exists('\Core\Vue', 'debut_section') &&
        method_exists('\Core\Vue', 'fin_section')
];

$features['2.9'] = [
    'Vue - Protection XSS',
    class_exists('\Core\Vue') &&
        method_exists('\Core\Vue', 'e')
];

// PHASE 3: BASE DE DONNÉES & ORM
echo "\n🟠 PHASE 3: BASE DE DONNÉES & ORM\n";
echo str_repeat("-", 70) . "\n";

$features['3.1'] = [
    'Connexion PDO - Singleton',
    class_exists('\Core\BaseBD') &&
        method_exists('\Core\BaseBD', 'obtenir')
];

$features['3.2'] = [
    'Connexion PDO - Config .env',
    file_exists(__DIR__ . '/.env') &&
        env('TYPE_CONNEXION') !== null
];

$features['3.3'] = [
    'ORM - Modele.php',
    class_exists('\Core\Modele')
];

$features['3.4'] = [
    'ORM - tout()',
    class_exists('\Core\Modele') &&
        method_exists('\Core\Modele', 'tout')
];

$features['3.5'] = [
    'ORM - trouver()',
    class_exists('\Core\Modele') &&
        method_exists('\Core\Modele', 'trouver')
];

$features['3.6'] = [
    'ORM - ou() / where()',
    class_exists('\Core\Modele') &&
        method_exists('\Core\Modele', 'ou')
];

$features['3.7'] = [
    'ORM - creer()',
    class_exists('\Core\Modele') &&
        method_exists('\Core\Modele', 'creer')
];

$features['3.8'] = [
    'ORM - sauvegarder()',
    class_exists('\Core\Modele') &&
        method_exists('\Core\Modele', 'sauvegarder')
];

$features['3.9'] = [
    'ORM - supprimer()',
    class_exists('\Core\Modele') &&
        method_exists('\Core\Modele', 'supprimer')
];

$features['3.10'] = [
    'ORM - Prepared Statements',
    file_exists(__DIR__ . '/core/Modele.php')
];

$features['3.11'] = [
    'Migrations',
    file_exists(__DIR__ . '/core/Migration.php') ||
        file_exists(__DIR__ . '/app/Migrations')
];

// AFFICHER LES RÉSULTATS
echo "\n";
foreach ($features as $id => $feature) {
    $name = $feature[0];
    $done = $feature[1];
    $icon = $done ? '✅' : '❌';
    $status = $done ? 'FAIT' : 'À FAIRE';
    printf("%-4s %-40s %s\n", $icon, $name, $status);
}

// RÉSUMÉ
echo "\n" . str_repeat("=", 70) . "\n";
$total = count($features);
$done_count = count(array_filter($features, fn($f) => $f[1]));
$percentage = round(($done_count / $total) * 100);

echo "📊 RÉSUMÉ: $done_count/$total fonctionnalités implémentées ($percentage%)\n";
echo str_repeat("=", 70) . "\n\n";

// DÉTAILS MANQUANTS
$missing = array_filter($features, fn($f) => !$f[1]);
if (!empty($missing)) {
    echo "❌ FONCTIONNALITÉS MANQUANTES:\n";
    foreach ($missing as $id => $feature) {
        echo "   - " . $feature[0] . "\n";
    }
    echo "\n";
}
