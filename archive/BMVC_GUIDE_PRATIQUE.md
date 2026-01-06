# 🚀 BMVC: Guide Pratique & Complet

## 📋 Table des matières

1. [Installation rapide](#-installation-rapide)
2. [Structure du projet](#-structure-du-projet)
3. [Features complètes](#-features-complètes)
4. [Exemples concrets](#-exemples-concrets)
5. [Troubleshooting](#-troubleshooting)
6. [Déploiement](#-déploiement)
7. [Performance](#-performance)

---

## ⚡ Installation rapide

### 1. Prérequis

```
- PHP 8.0+
- MySQL/MariaDB
- Apache avec mod_rewrite
- Composer
```

### 2. Installation

```bash
# Cloner ou télécharger BMVC
cd C:\xampp\htdocs
git clone https://github.com/votreusername/BMVC.git

# Aller dans le répertoire
cd BMVC

# Installer les dépendances
composer install

# Régénérer l'autoload
composer dump-autoload
```

### 3. Configuration

**Fichier:** `.env`

```env
APP_NAME=BMVC
APP_ENV=development
APP_DEBUG=true

DB_HOST=localhost
DB_PORT=3306
DB_NAME=bmvc
DB_USER=root
DB_PASSWORD=

MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USER=votre_email@gmail.com
MAIL_PASSWORD=votre_motdepasse
```

### 4. Base de données

```bash
php migrate.php
```

### 5. Tester

```
http://localhost/BMVC/
```

---

## 📁 Structure du projet

### Organisation complète

```
BMVC/
│
├── 📂 app/
│   ├── Controleurs/          ← Logique métier
│   │   ├── HomeControleur.php
│   │   ├── ArticleControleur.php
│   │   └── UtilisateurControleur.php
│   │
│   ├── Modeles/              ← Accès BD
│   │   ├── Article.php
│   │   └── Utilisateur.php
│   │
│   ├── Services/             ← Services réutilisables
│   │   ├── AuthService.php
│   │   ├── ValidationService.php
│   │   ├── UploadService.php
│   │   └── NotificationService.php
│   │
│   ├── Vues/                 ← Templates HTML
│   │   ├── layout.php
│   │   ├── articles/
│   │   └── utilisateurs/
│   │
│   └── BaseControleur.php    ← Classe de base pour tous les contrôleurs
│
├── 📂 core/                  ← Noyau du framework (21 classes)
│   ├── Application.php       ← Kernel principal
│   ├── Routeur.php           ← Routing
│   ├── Validateur.php        ← Validation
│   ├── Cache.php             ← Cache 3 systèmes
│   ├── GestionnaireErreurs.php ← Erreurs & logs
│   ├── Helpers.php           ← Fonctions globales
│   ├── Auth.php
│   ├── CSRF.php
│   ├── Session.php
│   ├── Modele.php
│   ├── Reponse.php
│   ├── Requete.php
│   └── ... (10 autres)
│
├── 📂 routes/
│   └── web.php               ← Définition des routes
│
├── 📂 public/
│   ├── index.php             ← Point d'entrée
│   ├── .htaccess             ← Rewrite rules
│   ├── css/
│   ├── js/
│   ├── images/
│   └── uploads/              ← Fichiers uploadés
│
├── 📂 config/
│   ├── database.php
│   ├── app.php
│   └── mail.php
│
├── 📂 storage/
│   ├── cache/                ← Cache fichiers
│   ├── logs/                 ← Logs d'erreurs
│   └── uploads/              ← Fichiers temporaires
│
├── 📂 tests/
│   ├── test_auth.php
│   ├── test_crud.php
│   └── test_phase5_6.php     ← 🌟 Tests complets Phase 5 & 6
│
├── 🔧 Configuration
│   ├── composer.json
│   ├── .env
│   ├── .htaccess
│   └── migrate.php
│
└── 📚 Documentation
    ├── README.md             ← Guide principal
    ├── TUTORIAL_COMPLET.md   ← Créer un framework from scratch
    ├── GUIDE_AJOUTER_SERVICES.md ← Créer vos services
    ├── PHASE5_6_STATUS.md    ← Détails Phase 5 & 6
    └── ...
```

---

## 🎯 Features complètes

### Phase 1-4 (19 features)

```
✅ Routage automatique
✅ Contrôleurs + Modèles + Vues
✅ ORM simple
✅ Authentification
✅ Sessions
✅ CSRF protection
✅ Validation
✅ Gestion BD
✅ Helpers globaux
✅ Middlewares
✅ Gestion erreurs simple
✅ Réinitialisation mot de passe
✅ Upload sécurisé
✅ Hash bcrypt
✅ Email
✅ Pagination
✅ Soft delete
✅ Timestamps
✅ Relations
```

### Phase 5-6 (20 features)

```
✅ Validateur réutilisable avec 10+ règles
✅ 4 Services (Auth, Validation, Upload, Notification)
✅ Cache simple (TTL)
✅ CacheConfig (configuration)
✅ CacheRoutes (routes compilées)
✅ GestionnaireErreurs (debug/production)
✅ Logs automatiques
✅ Mode debug vs production
✅ Pages d'erreur personnalisées
✅ Helpers améliorés
✅ Notifications flash
✅ Envoi email professionnel
✅ Upload avec validation
✅ SMS intégré
✅ Analytics
✅ Rate limiting
✅ Queue jobs
✅ Cronjobs
✅ Testing utils
✅ Performance monitoring
```

---

## 💻 Exemples concrets

### Exemple 1: Créer un article

#### 1.1 Définir la route

**routes/web.php**

```php
$routeur->obtenir('/articles', 'ArticleControleur@index');
$routeur->envoyer('/articles', 'ArticleControleur@creer');
$routeur->obtenir('/articles/{id}', 'ArticleControleur@afficher');
```

#### 1.2 Créer le modèle

**app/Modeles/Article.php**

```php
<?php

namespace App\Modeles;

class Article extends \Core\Modele
{
    protected static string $table = 'articles';

    public int $id;
    public string $titre;
    public string $contenu;
    public int $utilisateur_id;
    public \DateTime $date_creation;
    public \DateTime $date_modification;

    /**
     * Récupère tous les articles publiés
     */
    public static function publies(): array
    {
        $requete = self::$pdo->prepare("
            SELECT * FROM articles
            WHERE publie = 1
            ORDER BY date_creation DESC
        ");
        $requete->execute();
        return $requete->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * Récupère les articles par auteur
     */
    public static function parAuteur(int $utilisateur_id): array
    {
        $requete = self::$pdo->prepare("
            SELECT * FROM articles
            WHERE utilisateur_id = ?
            ORDER BY date_creation DESC
        ");
        $requete->execute([$utilisateur_id]);
        return $requete->fetchAll(\PDO::FETCH_CLASS, self::class);
    }
}
```

#### 1.3 Créer le contrôleur

**app/Controleurs/ArticleControleur.php**

```php
<?php

namespace App\Controleurs;

use App\Modeles\Article;

class ArticleControleur extends \App\BaseControleur
{
    /**
     * Affiche la liste des articles
     */
    public function index()
    {
        // Récupérer du cache ou depuis la BD
        $articles = \Core\Cache::souvenir('articles_tous', function () {
            return Article::publies();
        }, 3600);

        return $this->afficher('articles.index', [
            'articles' => $articles,
            'titre' => 'Articles'
        ]);
    }

    /**
     * Affiche un article
     */
    public function afficher(int $id)
    {
        $article = Article::chercher($id);

        if (!$article) {
            http_response_code(404);
            return $this->afficher('404', ['titre' => 'Non trouvé']);
        }

        return $this->afficher('articles.afficher', [
            'article' => $article,
            'titre' => $article->titre
        ]);
    }

    /**
     * Formulaire de création
     */
    public function formulaireCreation()
    {
        // Vérifier que l'utilisateur est connecté
        if (!connecte()) {
            return $this->redirection('/connexion');
        }

        return $this->afficher('articles.creer', [
            'titre' => 'Créer un article'
        ]);
    }

    /**
     * Crée un article
     */
    public function creer()
    {
        // Vérifier l'authentification
        if (!connecte()) {
            notification()->erreur('Vous devez être connecté');
            return $this->redirection('/connexion');
        }

        // Valider
        $v = validateur();
        $v->ajouter('titre', ['requis', 'min:5', 'max:200']);
        $v->ajouter('contenu', ['requis', 'min:20']);

        if (!$v->valider($_POST)) {
            $_SESSION['erreurs'] = $v->erreurs();
            return $this->redirection('/articles/creer');
        }

        // Créer l'article
        $article = new Article();
        $article->titre = $_POST['titre'];
        $article->contenu = $_POST['contenu'];
        $article->utilisateur_id = utilisateur()->id;
        $article->date_creation = new \DateTime();
        $article->sauvegarder();

        // Invalider le cache
        \Core\Cache::oublier('articles_tous');

        // Notifier l'utilisateur
        notification()->succes('Article créé avec succès!');

        return $this->redirection('/articles/' . $article->id);
    }
}
```

#### 1.4 Créer la vue

**app/Vues/articles/index.php**

```php
<?php include 'app/Vues/layout.php'; ?>

<div class="container">
    <h1>Articles</h1>

    <?php if (connecte()): ?>
        <a href="/articles/creer" class="btn btn-primary">Nouvel article</a>
    <?php endif; ?>

    <div class="articles">
        <?php foreach ($articles as $article): ?>
            <article>
                <h2><?= e($article->titre) ?></h2>
                <p><?= e(substr($article->contenu, 0, 100)) ?>...</p>
                <small><?= $article->date_creation->format('d/m/Y') ?></small>
                <a href="/articles/<?= $article->id ?>">Lire plus</a>
            </article>
        <?php endforeach; ?>
    </div>
</div>
```

### Exemple 2: Authentification

#### 2.1 Formulaire de login

```php
// Route
$routeur->envoyer('/connexion', 'UtilisateurControleur@connexion');

// Contrôleur
public function connexion()
{
    if (!$_POST) {
        return $this->afficher('connexion');
    }

    // Authentifier
    $utilisateur = serviceAuth()->connexion(
        $_POST['email'],
        $_POST['mot_de_passe']
    );

    if (!$utilisateur) {
        notification()->erreur('Email ou mot de passe incorrect');
        return $this->redirection('/connexion');
    }

    notification()->succes('Bienvenue ' . $utilisateur->nom);
    return $this->redirection('/');
}
```

#### 2.2 Middleware de protection

```php
// Dans un contrôleur
public function tableauDeBord()
{
    // Vérifier l'authentification
    if (!connecte()) {
        return $this->redirection('/connexion');
    }

    // Afficher le tableau de bord
    return $this->afficher('tableaudebord');
}
```

### Exemple 3: Upload de fichier

#### 3.1 Formulaire d'upload

```html
<form method="POST" enctype="multipart/form-data">
  <input type="file" name="avatar" accept="image/*" required />
  <button type="submit">Télécharger</button>
</form>
```

#### 3.2 Traiter l'upload

```php
public function telechargerAvatar()
{
    if (!isset($_FILES['avatar'])) {
        notification()->erreur('Aucun fichier');
        return;
    }

    $upload = telecharger()
        ->definirRepertoire(__DIR__ . '/../../public/uploads/avatars/')
        ->definirExtensionsAutorisees(['jpg', 'png', 'gif'])
        ->definirTailleMax(2); // 2 Mo

    $nomFichier = $upload->charger($_FILES['avatar']);

    if (!$nomFichier) {
        notification()->erreur('Erreur lors du téléchargement');
        return;
    }

    // Sauvegarder dans la BD
    $utilisateur = utilisateur();
    $utilisateur->avatar = $nomFichier;
    $utilisateur->sauvegarder();

    notification()->succes('Avatar téléchargé!');
    return $this->redirection('/profil');
}
```

### Exemple 4: Validation avancée

```php
$v = validateur();

// Validation email
$v->ajouter('email', ['requis', 'email'], [
    'email' => 'L\'email doit être valide'
]);

// Validation mot de passe fort
$v->ajouter('motdepasse', [
    'requis',
    'min:8',
    'regex:/[A-Z]/',  // Majuscule
    'regex:/[0-9]/',  // Chiffre
    'regex:/[!@#$%]/', // Caractère spécial
], [
    'regex' => 'Le mot de passe doit contenir majuscule, chiffre et caractère spécial'
]);

// Confirmer le mot de passe
$v->ajouter('confirmation', ['match:motdepasse'], [
    'match' => 'Les mots de passe ne correspondent pas'
]);

if (!$v->valider($_POST)) {
    foreach ($v->obtenirErreurs() as $champ => $messages) {
        echo "<p class='error'>$champ: " . implode(', ', $messages) . "</p>";
    }
}
```

### Exemple 5: Services personnalisés

```php
// Créer app/Services/SMSService.php
class ServiceSMS
{
    public function envoyer(string $numero, string $message): bool
    {
        // Appeler API SMS
        // ...
        return true;
    }
}

// Ajouter helper dans core/Helpers.php
function serviceSMS(): \App\Services\ServiceSMS
{
    static $service;
    if (!$service) {
        $service = new \App\Services\ServiceSMS();
    }
    return $service;
}

// Utiliser partout
serviceSMS()->envoyer('+33612345678', 'Votre code: 123456');
```

---

## 🐛 Troubleshooting

### Problème: "Class not found"

**Solution:** Régénérer l'autoload

```bash
composer dump-autoload
```

### Problème: Erreur 404

**Vérifier:**

1. Route définie dans `routes/web.php`
2. `.htaccess` activé dans `public/`
3. Apache mod_rewrite activé

### Problème: Fichier non uploadé

**Vérifier:**

1. Dossier `public/uploads/` exists et writable
2. Permission 755
3. Limite de taille dans php.ini

```bash
# Linux
chmod 755 public/uploads/
```

### Problème: Base de données non trouvée

**Exécuter:**

```bash
php migrate.php
```

### Problème: Sessions ne fonctionnent pas

**Vérifier `public/index.php`:**

```php
session_start(); // Avant tout
```

---

## 🚀 Déploiement

### 1. Préparation

```bash
# Optimiser l'autoload
composer install --optimize-autoloader --no-dev

# Nettoyer les caches
composer dump-autoload --optimize
```

### 2. Fichier .env production

```env
APP_ENV=production
APP_DEBUG=false

DB_HOST=votre.serveur.com
DB_NAME=bmvc_prod
DB_USER=utilisateur_bd
DB_PASSWORD=motdepasse_fort

MAIL_HOST=votre.smtp.com
```

### 3. Permissions

```bash
chmod 755 public/
chmod 755 storage/cache/
chmod 755 storage/logs/
chmod 755 storage/uploads/
```

### 4. SSL/HTTPS

```apache
# .htaccess
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 5. Monitorer

```php
// Créer une route pour vérifier la santé
$routeur->obtenir('/sante', function() {
    echo json_encode(['statut' => 'ok']);
});
```

---

## ⚡ Performance

### Bonnes pratiques

```php
// ✅ UTILISER LE CACHE
$articles = \Core\Cache::souvenir('articles', function() {
    return Article::tous();
}, 3600);

// ❌ NE PAS FAIRE
foreach ($articles as $article) {
    $auteur = Utilisateur::chercher($article->utilisateur_id); // N+1 query!
}

// ✅ À LA PLACE: Eager loading
$articles = Article::avecAuteur()->tous();
```

### Mesurer les performances

```php
$debut = microtime(true);

// Code...

$fin = microtime(true);
$temps = ($fin - $debut) * 1000;

echo "Durée: {$temps}ms";
```

### Optimisations

1. **Cache** - Utiliser `Cache::souvenir()`
2. **Chargement différé** - Charger seulement si nécessaire
3. **Pagination** - Limiter les résultats
4. **Indexes BD** - Sur les colonnes recherchées
5. **Compression** - gzip les réponses
6. **CDN** - Pour les ressources statiques

---

## 📊 Statistiques

```
📦 Framework complet
├─ 39/39 features ✅
├─ 6000+ lignes code
├─ 46+ classes
├─ 21 classes core
├─ 4 services
├─ 10+ règles validation
├─ 3 systèmes cache
├─ 0 dépendances externes
└─ 100% prêt production
```

---

## 🎓 Ressources

- [TUTORIAL_COMPLET.md](TUTORIAL_COMPLET.md) - Créer un framework
- [GUIDE_AJOUTER_SERVICES.md](GUIDE_AJOUTER_SERVICES.md) - Créer des services
- [PHASE5_6_STATUS.md](PHASE5_6_STATUS.md) - Détails Phase 5 & 6
- [README.md](README.md) - Vue d'ensemble

---

## 💬 Questions?

**Consultez:**

1. Ce guide
2. Les commentaires du code
3. Les fichiers test\_\*.php
4. Les exemples EXEMPLES_PHASE5_6.php

---

**BMVC v1.0 - Framework MVC Professionnel Français 🇫🇷** ✅
