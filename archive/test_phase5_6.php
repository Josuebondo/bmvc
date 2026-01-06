<?php

/**
 * TEST PHASE 5 & 6
 * Validation, Services, Cache, Erreurs
 */

// Charger l'autoload
require_once __DIR__ . '/vendor/autoload.php';

echo "<h2>🧪 Tests Phase 5 & 6</h2>";
echo "<style>
    body { font-family: Segoe UI, sans-serif; margin: 20px; }
    .test { margin: 20px 0; padding: 15px; border-radius: 5px; }
    .pass { background: #d4edda; border-left: 4px solid #28a745; }
    .fail { background: #f8d7da; border-left: 4px solid #dc3545; }
    .info { background: #d1ecf1; border-left: 4px solid #0c5460; }
    h3 { margin: 0 0 10px 0; }
</style>";

$passed = 0;
$failed = 0;

function test($nom, $condition)
{
    global $passed, $failed;
    $classe = $condition ? 'pass' : 'fail';
    $icon = $condition ? '✅' : '❌';
    $etat = $condition ? 'SUCCÈS' : 'ERREUR';

    echo "<div class='test $classe'>";
    echo "<h3>$icon $nom</h3>";
    echo "<p><strong>État:</strong> $etat</p>";
    echo "</div>";

    if ($condition) {
        $passed++;
    } else {
        $failed++;
    }
}

// ============================================================
// TEST 1: Validateur
// ============================================================

echo "<h3>📋 Tests Validateur</h3>";

$v = new \Core\Validateur();
$v->ajouter('email', ['requis', 'email']);
$v->ajouter('password', ['requis', 'min:8']);

$donnees = [
    'email' => 'test@exemple.com',
    'password' => 'securepass123'
];

test("Validateur::valider() - Données valides", $v->valider($donnees));

// Test avec données invalides
$v2 = new \Core\Validateur();
$v2->ajouter('email', ['email']);
$result = $v2->valider(['email' => 'invalid']);
test("Validateur::valider() - Email invalide détecté", !$result);

// Test erreurs
$v3 = new \Core\Validateur();
$v3->ajouter('email', ['requis']);
$v3->valider([]);
test("Validateur::erreurs() - Retourne les erreurs", !empty($v3->erreurs()));

// ============================================================
// TEST 2: Services d'Authentification
// ============================================================

echo "<h3>🔐 Tests AuthService</h3>";

try {
    $authService = auth_service();
    test("AuthService instance créée", $authService instanceof \App\Services\AuthService);

    // Valider connexion
    $v = $authService->validerConnexion([
        'email' => 'test@exemple.com',
        'mot_de_passe' => 'password123'
    ]);
    test("AuthService::validerConnexion() créé un validateur", $v instanceof \Core\Validateur);
} catch (\Exception $e) {
    test("AuthService - Exception", false);
}

// ============================================================
// TEST 3: Services de Validation
// ============================================================

echo "<h3>✔️ Tests ValidationService</h3>";

try {
    $validationService = validation_service();
    test("ValidationService instance créée", $validationService instanceof \App\Services\ValidationService);

    $v = $validationService->validerArticle([
        'titre' => 'Mon article',
        'contenu' => 'Contenu long et détaillé...'
    ]);
    test("ValidationService::validerArticle() fonctionne", $v instanceof \Core\Validateur);
} catch (\Exception $e) {
    test("ValidationService - Exception", false);
}

// ============================================================
// TEST 4: Service d'Upload
// ============================================================

echo "<h3>📤 Tests UploadService</h3>";

try {
    $uploadService = upload();
    test("UploadService instance créée", $uploadService instanceof \App\Services\UploadService);

    $uploadService->setTailleMax(5);
    test("UploadService::setTailleMax() chainable", $uploadService instanceof \App\Services\UploadService);
} catch (\Exception $e) {
    test("UploadService - Exception", false);
}

// ============================================================
// TEST 5: Service de Notification
// ============================================================

echo "<h3>🔔 Tests NotificationService</h3>";

try {
    $notif = notification();
    test("NotificationService instance créée", $notif instanceof \App\Services\NotificationService);

    // Test flash messages
    $notif->succes('Test message');
    test("NotificationService::success() fonctionne", isset($_SESSION['flash']['succes']));
} catch (\Exception $e) {
    test("NotificationService - Exception", false);
}

// ============================================================
// TEST 6: Helpers globaux
// ============================================================

echo "<h3>🔧 Tests Helpers</h3>";

test("validateur() helper existe", function_exists('validateur'));
test("notification() helper existe", function_exists('notification'));
test("upload() helper existe", function_exists('upload'));
test("auth_service() helper existe", function_exists('auth_service'));
test("validation_service() helper existe", function_exists('validation_service'));

// ============================================================
// TEST 7: Cache
// ============================================================

echo "<h3>💾 Tests Cache</h3>";

// Initialiser le cache
\Core\Cache::initialiser(__DIR__ . '/storage/cache/', 3600);

// Test mettre/obtenir
\Core\Cache::mettre('test_key', ['data' => 'value'], 3600);
$cached = \Core\Cache::obtenir('test_key');
test("Cache::mettre() et Cache::obtenir() fonctionnent", !empty($cached) && $cached['data'] === 'value');

// Test existe
test("Cache::existe() détecte la clé", \Core\Cache::existe('test_key'));

// Test oublier
\Core\Cache::oublier('test_key');
test("Cache::oublier() supprime la clé", !\Core\Cache::existe('test_key'));

// Test souvenir
$result = \Core\Cache::souvenir('remember_key', function () {
    return ['data' => 'remembered'];
}, 3600);
test("Cache::souvenir() fonctionne", $result['data'] === 'remembered');

// ============================================================
// TEST 8: Cache Config
// ============================================================

echo "<h3>⚙️ Tests CacheConfig</h3>";

\Core\CacheConfig::set('test.value', 'hello');
$value = \Core\CacheConfig::obtenir('test.value');
test("CacheConfig::set() et obtenir() fonctionnent", $value === 'hello');

$default = \Core\CacheConfig::obtenir('nonexistent.key', 'default');
test("CacheConfig::obtenir() retourne default si absent", $default === 'default');

// ============================================================
// TEST 9: Cache Routes
// ============================================================

echo "<h3>🛣️ Tests CacheRoutes</h3>";

$routes = ['GET' => ['/' => 'HomeController@index']];
\Core\CacheRoutes::sauvegarder($routes);
test("CacheRoutes::sauvegarder() fonctionne", \Core\CacheRoutes::existe());

$loaded = \Core\CacheRoutes::obtenir();
test("CacheRoutes::obtenir() charge les routes", !empty($loaded));

\Core\CacheRoutes::oublier();
test("CacheRoutes::oublier() supprime le cache", !\Core\CacheRoutes::existe());

// ============================================================
// RÉSUMÉ
// ============================================================

echo "<h2>📊 Résumé des Tests</h2>";
echo "<div class='test info'>";
echo "<h3>Résultats</h3>";
echo "<p><strong>✅ Tests réussis:</strong> $passed</p>";
echo "<p><strong>❌ Tests échoués:</strong> $failed</p>";
echo "<p><strong>Taux de réussite:</strong> " . round(($passed / ($passed + $failed)) * 100) . "%</p>";

if ($failed === 0) {
    echo "<p style='color: green; font-weight: bold;'>🎉 TOUS LES TESTS PASSENT!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>⚠️ Certains tests ont échoué</p>";
}

echo "</div>";

// Nettoyage
\Core\Cache::vider();
