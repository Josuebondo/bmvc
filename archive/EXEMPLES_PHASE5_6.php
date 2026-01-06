<?php

/**
 * EXEMPLES D'UTILISATION - Phase 5 & 6
 * Validation, Services, Cache, Erreurs
 */

// Charger l'autoload
require_once __DIR__ . '/vendor/autoload.php';

// ============================================================
// 1. VALIDATION AVEC VALIDATEUR
// ============================================================

// Simple validation
$validateur = new \Core\Validateur();
$validateur->ajouter('email', ['requis', 'email']);
$validateur->ajouter('mot_de_passe', ['requis', 'min:8']);

if ($validateur->valider($_POST)) {
    echo "✅ Données valides";
} else {
    $erreurs = $validateur->erreurs();
    // ['email' => ['Le champ Email est requis'], ...]
}

// ============================================================
// 2. UTILISER LES SERVICES
// ============================================================

// Service d'authentification
$authService = auth_service();
$user = $authService->connexion('admin@exemple.com', 'admin123');
if ($user) {
    echo "✅ Connecté: " . $user->nom;
}

// Service de validation métier
$validationService = validation_service();
$v = $validationService->validerArticle(['titre' => 'Mon article', 'contenu' => 'Contenu complet...']);
if ($v->valider(['titre' => 'Test', 'contenu' => 'Lorem ipsum dolor sit amet'])) {
    echo "✅ Article valide";
}

// Service d'upload
$uploadService = upload()
    ->setRepertoire(__DIR__ . '/uploads/')
    ->setExtensionsAutorisees(['jpg', 'png', 'pdf'])
    ->setTailleMax(5); // 5 Mo

if (isset($_FILES['avatar'])) {
    $nomFichier = $uploadService->uploader($_FILES['avatar']);
    if ($nomFichier) {
        echo "✅ Fichier uploadé: " . $nomFichier;
    }
}

// Service de notifications
$notif = notification();
$notif->success('Opération réussie!');
$notif->error('Une erreur est survenue');
$notif->envoyerEmail('user@exemple.com', 'Bienvenue', 'Contenu du message');

// ============================================================
// 3. UTILISER LE CACHE
// ============================================================

// Enregistrer une valeur
$user = ['id' => 1, 'nom' => 'John'];
\Core\Cache::mettre('user_1', $user, 3600); // 1 heure

// Récupérer une valeur
$userCache = \Core\Cache::obtenir('user_1');
echo "User cache: " . json_encode($userCache);

// Souvenir (récupère ou crée le cache)
$user = \Core\Cache::souvenir('user_1', function () {
    // Logique qui récupère l'utilisateur
    return ['id' => 1, 'nom' => 'John'];
}, 3600);

// Vérifier l'existence
if (\Core\Cache::existe('user_1')) {
    echo "✅ Utilisateur en cache";
}

// Supprimer du cache
\Core\Cache::oublier('user_1');

// Vider tout le cache
\Core\Cache::vider();

// ============================================================
// 4. HELPERS GLOBAUX
// ============================================================

// Créer un validateur directement
$v = validateur();
$v->ajouter('email', ['requis', 'email']);

// Accéder aux services directement
$notif = notification();
$upload = upload();
$authSvc = auth_service();
$validationSvc = validation_service();

// ============================================================
// 5. GESTION DES ERREURS
// ============================================================

// Initialiser au démarrage (dans public/index.php)
\Core\GestionnaireErreurs::initialiser(
    debug: env('DEBOGAGE', true),
    cheminLogs: __DIR__ . '/../storage/logs/'
);

// Les erreurs sont maintenant gérées automatiquement
// - Mode debug: affiche les détails
// - Mode production: pages 404/500
// - Logs: enregistrés dans storage/logs/

// Exemple d'erreur (en mode debug):
// trigger_error("Message d'erreur", E_USER_NOTICE);

// ============================================================
// 6. CACHE CONFIG
// ============================================================

// Obtenir une configuration
$appName = \Core\CacheConfig::get('app.name', 'BMVC');
$dbHost = \Core\CacheConfig::get('database.host', 'localhost');

// Définir une configuration
\Core\CacheConfig::set('app.version', '1.0.0');

// Accéder à des sous-clés
$dbConfig = \Core\CacheConfig::get('database.mysql');

// ============================================================
// 7. CACHE ROUTES
// ============================================================

// Vérifier si les routes sont en cache
if (\Core\CacheRoutes::existe()) {
    $routesCache = \Core\CacheRoutes::obtenir();
    echo "✅ Routes en cache";
} else {
    // Compiler les routes
    $routes = \Core\Routeur::compiler();
    \Core\CacheRoutes::sauvegarder($routes);
}

// Effacer le cache des routes
\Core\CacheRoutes::oublier();

// ============================================================
// 8. DANS UN CONTRÔLEUR (Exemple complet)
// ============================================================

namespace App\Controleurs;

class ArticleControleur extends \App\BaseControleur
{
    public function creer()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->afficher('articles.creer');
        }

        // Valider les données
        $v = validateur();
        $v->ajouter('titre', ['requis', 'min:3', 'max:255']);
        $v->ajouter('contenu', ['requis', 'min:10']);

        if (!$v->valider($_POST)) {
            $_SESSION['erreurs'] = $v->erreurs();
            $_SESSION['anciens_inputs'] = $_POST;
            return $this->redirection('/articles/creer');
        }

        // Créer l'article
        $article = new \App\Modeles\Article();
        $article->titre = $_POST['titre'];
        $article->contenu = $_POST['contenu'];
        $article->sauvegarder();

        // Notifier l'utilisateur
        notification()->success('Article créé avec succès!');

        // Invalider le cache des articles
        \Core\Cache::oublier('articles_all');

        return $this->redirection('/articles');
    }

    public function index()
    {
        // Utiliser le cache pour la liste
        $articles = \Core\Cache::souvenir('articles_all', function () {
            return \App\Modeles\Article::tous();
        }, 3600); // Cache 1 heure

        return $this->afficher('articles.index', [
            'articles' => $articles
        ]);
    }
}

// ============================================================
// 9. UPLOAD AVEC VALIDATION
// ============================================================

if (isset($_FILES['photo'])) {
    $uploadService = upload()
        ->setExtensionsAutorisees(['jpg', 'jpeg', 'png'])
        ->setTailleMax(5);

    $nomFichier = $uploadService->uploader($_FILES['photo']);

    if ($nomFichier) {
        notification()->success('Photo uploadée: ' . $nomFichier);
        // Sauvegarder le nom en BD
    } else {
        notification()->error('Erreur lors de l\'upload');
    }
}

// ============================================================
// 10. VALIDATION PERSONNALISÉE
// ============================================================

$v = new \Core\Validateur();

// Règles
$v->ajouter('email', ['requis', 'email']);
$v->ajouter('password', ['requis', 'min:8']);
$v->ajouter('password_confirm', ['match:password']);

// Messages personnalisés
$messages = [
    'password_confirm' => [
        'match' => 'Les deux mots de passe ne correspondent pas'
    ]
];

if ($v->valider($_POST)) {
    echo "✅ Inscription validée";
} else {
    // Afficher les erreurs
    foreach ($v->erreurs() as $champ => $messages) {
        echo "<div class='error'>$champ: " . implode(', ', $messages) . "</div>";
    }
}

// ============================================================
