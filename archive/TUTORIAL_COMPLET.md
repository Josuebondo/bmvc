# 🎓 Tutorial Complet: Créer un Framework MVC comme BMVC

## 📖 Table des matières

1. [Comprendre l'architecture](#-comprendre-larchitecture)
2. [Étape 1: Structure de base](#étape-1-structure-de-base)
3. [Étape 2: Composer et PSR-4](#étape-2-composer-et-psr-4)
4. [Étape 3: Classes Core](#étape-3-classes-core)
5. [Étape 4: Routeur](#étape-4-routeur)
6. [Étape 5: Base de données](#étape-5-base-de-données)
7. [Étape 6: Authentification](#étape-6-authentification)
8. [Étape 7: Services](#étape-7-services)
9. [Étape 8: Validation](#étape-8-validation)
10. [Étape 9: Cache et Erreurs](#étape-9-cache-et-erreurs)

---

## 🏗️ Comprendre l'architecture

### Modèle MVC

```
CLIENT (Navigateur)
   ↓ (Requête HTTP)
ROUTEUR (routes/web.php)
   ↓ Trouve la route
CONTRÔLEUR (app/Controleurs/)
   ↓ Logique métier
MODÈLE (app/Modeles/)
   ↓ Accès base de données
VUE (app/Vues/)
   ↓ HTML/Template
RÉPONSE HTTP → CLIENT
```

### Exemple complet

```
1. Utilisateur visite: http://localhost/BMVC/articles/1

2. ROUTEUR: Match la route → ArticleControleur@afficher(1)

3. CONTRÔLEUR:
   $article = Article::trouver(1);  // Modèle
   return $this->afficher('articles.afficher', ['article' => $article]);

4. MODÈLE: Récupère l'article de la base de données

5. VUE: app/Vues/articles/afficher.php
   <h1><?= $article->titre ?></h1>

6. Client reçoit le HTML rendu
```

---

## Étape 1: Structure de base

### Créer les dossiers

```bash
mkdir BMVC
cd BMVC

mkdir -p app/Controleurs
mkdir -p app/Modeles
mkdir -p app/Vues
mkdir -p app/Services
mkdir -p core
mkdir -p routes
mkdir -p public
mkdir -p config
mkdir -p storage/cache
mkdir -p storage/logs
mkdir -p tests
```

### Arborescence finale

```
BMVC/
├── app/                    # Logique application
│   ├── Controleurs/       # Contrôleurs
│   ├── Modeles/           # Modèles
│   ├── Services/          # Services réutilisables
│   ├── Vues/              # Templates HTML
│   └── BaseControleur.php # Classe de base
│
├── core/                   # Noyau du framework
│   ├── Application.php
│   ├── Routeur.php
│   ├── Validateur.php
│   ├── Cache.php
│   ├── Helpers.php
│   └── ... (20 autres classes)
│
├── routes/
│   └── web.php            # Définition des routes
│
├── public/
│   ├── index.php          # Point d'entrée
│   ├── .htaccess
│   ├── css/
│   ├── js/
│   └── uploads/
│
├── config/
│   ├── database.php
│   ├── app.php
│   └── ...
│
├── storage/
│   ├── cache/             # Cache fichiers
│   └── logs/              # Logs erreurs
│
├── composer.json          # Dépendances PHP
├── .env                   # Variables d'environnement
└── README.md
```

---

## Étape 2: Composer et PSR-4

### Qu'est-ce que Composer?

Composer = NPM pour PHP (gestionnaire de dépendances)

### PSR-4 (Autoloading standard)

**Règle:** 1 fichier = 1 classe

✅ **BON:**

```
app/Services/AuthService.php      → namespace App\Services; class AuthService { }
app/Services/PaymentService.php   → namespace App\Services; class PaymentService { }
core/Routeur.php                  → namespace Core; class Routeur { }
```

❌ **MAUVAIS:**

```
app/Services/Services.php → 4 classes (AuthService, PaymentService, UploadService, NotificationService)
// Composer ne les trouvera pas!
```

### Fichier composer.json

```json
{
  "name": "bmvc/mvc",
  "description": "Framework MVC en français",
  "require": {
    "php": ">=8.0"
  },
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Core\\": "core/"
    },
    "files": ["core/Helpers.php"]
  }
}
```

**C'est quoi?**

- `"App\\": "app/"` → Toute classe `App\*` est dans le dossier `app/`
- `"Core\\": "core/"` → Toute classe `Core\*` est dans le dossier `core/`
- `"files": ["core/Helpers.php"]` → Charger les fonctions globales

### Initialiser Composer

```bash
composer install
# Cela crée vendor/ et vendor/autoload.php
```

### Utiliser l'autoload

```php
<?php
// Au démarrage de l'application
require_once 'vendor/autoload.php';

// Maintenant les classes sont chargées automatiquement!
$auth = new \App\Services\AuthService();
$routeur = new \Core\Routeur();
```

---

## Étape 3: Classes Core

### 3.1 Classe Application (Kernel)

**Fichier:** `core/Application.php`

```php
<?php

namespace Core;

class Application
{
    private Routeur $routeur;
    private \PDO $pdo;

    public function __construct()
    {
        // Initialiser le routeur
        $this->routeur = new Routeur();

        // Initialiser la base de données
        $this->pdo = new \PDO(
            'mysql:host=localhost;dbname=bmvc',
            'root',
            '',
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    public function demarrer()
    {
        // Récupérer la requête actuelle
        $methode = $_SERVER['REQUEST_METHOD'];
        $chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Trouver et exécuter la route
        $route = $this->routeur->trouver($methode, $chemin);

        if ($route) {
            $route->executer();
        } else {
            http_response_code(404);
            echo "Page non trouvée";
        }
    }

    public function obtenirPDO(): \PDO
    {
        return $this->pdo;
    }
}
```

### 3.2 Classe Routeur

**Fichier:** `core/Routeur.php`

```php
<?php

namespace Core;

class Routeur
{
    private array $routes = [];

    /**
     * Définit une route GET
     */
    public function get(string $chemin, callable|string $action): void
    {
        $this->routes['GET'][$chemin] = $action;
    }

    /**
     * Définit une route POST
     */
    public function post(string $chemin, callable|string $action): void
    {
        $this->routes['POST'][$chemin] = $action;
    }

    /**
     * Trouve une route correspondante
     */
    public function trouver(string $methode, string $chemin): ?Route
        {
        // Nettoyer le chemin
        $chemin = '/' . trim($chemin, '/');

        // Vérifier dans les routes exactes
        if (isset($this->routes[$methode][$chemin])) {
            return new Route($chemin, $this->routes[$methode][$chemin]);
        }

        // Chercher une route avec paramètres
        foreach ($this->routes[$methode] ?? [] as $pattern => $action) {
            if ($this->correspondre($pattern, $chemin, $params)) {
                return new Route($chemin, $action, $params);
            }
        }

        return null;
    }

    /**
     * Vérifie si un pattern correspond à un chemin
     */
    private function correspondre(string $pattern, string $chemin, array &$params): bool
    {
        // Convertir /articles/{id} en /articles/(\d+)
        $regex = preg_replace('/{(\w+)}/', '(\d+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $chemin, $matches)) {
            array_shift($matches); // Enlever le match complet
            $params = $matches;
            return true;
        }

        return false;
    }
}
```

### 3.3 Classe Modèle de base

**Fichier:** `core/Modele.php`

```php
<?php

namespace Core;

abstract class Modele
{
    protected static string $table = '';
    protected static \PDO $pdo;

    public static function setPDO(\PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    /**
     * Récupère tous les enregistrements
     */
    public static function tous(): array
    {
        $requete = self::$pdo->query("SELECT * FROM " . static::$table);
        return $requete->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    /**
     * Récupère un enregistrement par ID
     */
    public static function trouver(int $id): ?self
    {
        $requete = self::$pdo->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $requete->execute([$id]);
        return $requete->fetchObject(static::class) ?: null;
    }

    /**
     * Sauvegarde dans la base de données
     */
    public function sauvegarder(): void
    {
        $proprietes = get_object_vars($this);

        // INSERT si pas d'ID, UPDATE sinon
        if (empty($this->id)) {
            // INSERT
            $colonnes = implode(', ', array_keys($proprietes));
            $placeholders = implode(', ', array_fill(0, count($proprietes), '?'));

            $requete = self::$pdo->prepare(
                "INSERT INTO " . static::$table . " ($colonnes) VALUES ($placeholders)"
            );
            $requete->execute(array_values($proprietes));
        } else {
            // UPDATE
            $updates = implode(', ', array_map(fn($col) => "$col = ?", array_keys($proprietes)));
            $requete = self::$pdo->prepare(
                "UPDATE " . static::$table . " SET $updates WHERE id = ?"
            );
            $requete->execute([...array_values($proprietes), $this->id]);
        }
    }
}
```

---

## Étape 4: Routeur

### Définir les routes

**Fichier:** `routes/web.php`

```php
<?php

use Core\Routeur;

$routeur = new Routeur();

// Routes
$routeur->get('/', 'HomeControleur@index');
$routeur->get('/articles', 'ArticleControleur@index');
$routeur->get('/articles/{id}', 'ArticleControleur@afficher');
$routeur->post('/articles', 'ArticleControleur@creer');

return $routeur;
```

### Point d'entrée

**Fichier:** `public/index.php`

```php
<?php

// Charger Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Initialiser l'application
$app = new \Core\Application();

// Démarrer
$app->demarrer();
```

### Configuration Apache

**Fichier:** `public/.htaccess`

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

**Cela signifie:** Rediriger toutes les requêtes vers `index.php`, sauf les fichiers/dossiers existants

---

## Étape 5: Base de données

### Créer le modèle

**Fichier:** `app/Modeles/Article.php`

```php
<?php

namespace App\Modeles;

class Article extends \Core\Modele
{
    protected static string $table = 'articles';

    public int $id;
    public string $titre;
    public string $contenu;
    public string $auteur;
    public \DateTime $date_creation;
}
```

### Créer la table

**Fichier:** `migrate.php`

```php
<?php

$pdo = new \PDO('mysql:host=localhost', 'root', '');

// Créer la base de données
$pdo->exec("CREATE DATABASE IF NOT EXISTS bmvc");

// Utiliser la base de données
$pdo->exec("USE bmvc");

// Créer la table articles
$pdo->exec("
    CREATE TABLE IF NOT EXISTS articles (
        id INT PRIMARY KEY AUTO_INCREMENT,
        titre VARCHAR(255) NOT NULL,
        contenu LONGTEXT NOT NULL,
        auteur VARCHAR(100),
        date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

echo "✅ Base de données créée!";
```

**Exécuter:**

```bash
php migrate.php
```

---

## Étape 6: Authentification

### Service d'authentification

**Fichier:** `app/Services/AuthService.php`

```php
<?php

namespace App\Services;

use App\Modeles\Utilisateur;
use Core\Validateur;

class AuthService
{
    /**
     * Authentifie un utilisateur
     */
    public function connexion(string $email, string $motDePasse): ?Utilisateur
    {
        $utilisateur = Utilisateur::parEmail($email);

        if (!$utilisateur) {
            return null;
        }

        // Vérifier le mot de passe (bcrypt)
        if (!password_verify($motDePasse, $utilisateur->mot_de_passe)) {
            return null;
        }

        // Stocker en session
        $_SESSION['utilisateur_id'] = $utilisateur->id;
        $_SESSION['utilisateur_nom'] = $utilisateur->nom;

        return $utilisateur;
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function deconnexion(): void
    {
        unset($_SESSION['utilisateur_id']);
        unset($_SESSION['utilisateur_nom']);
    }

    /**
     * Récupère l'utilisateur connecté
     */
    public function utilisateur(): ?Utilisateur
    {
        if (!isset($_SESSION['utilisateur_id'])) {
            return null;
        }

        return Utilisateur::trouver($_SESSION['utilisateur_id']);
    }
}
```

### Helper global

**Fichier:** `core/Helpers.php`

```php
<?php

if (!function_exists('auth')) {
    /**
     * Récupère le service d'authentification
     */
    function auth(): \App\Services\AuthService
    {
        static $service;
        if (!$service) {
            $service = new \App\Services\AuthService();
        }
        return $service;
    }
}

if (!function_exists('connecte')) {
    /**
     * Vérifie si l'utilisateur est connecté
     */
    function connecte(): bool
    {
        return auth()->utilisateur() !== null;
    }
}

if (!function_exists('utilisateur')) {
    /**
     * Récupère l'utilisateur connecté
     */
    function utilisateur(): ?\App\Modeles\Utilisateur
    {
        return auth()->utilisateur();
    }
}
```

---

## Étape 7: Services

### Concept: DRY (Don't Repeat Yourself)

```php
// ❌ MAUVAIS: Répéter le code partout

// Contrôleur 1
$email = new PHPMailer();
$email->addAddress($_POST['email']);
$email->Subject = 'Bienvenue';
$email->Body = 'Welcome!';
$email->send();

// Contrôleur 2
$email = new PHPMailer();
$email->addAddress($_POST['email']);
$email->Subject = 'Confirmation';
$email->Body = 'Please confirm...';
$email->send();

// Contrôleur 3
// RÉPÉTER ENCORE...
```

### ✅ SOLUTION: Service

```php
// Service: app/Services/EmailService.php
class EmailService
{
    public function bienvenue(string $email, string $nom): bool { }
    public function confirmation(string $email): bool { }
}

// Contrôleur 1
email_service()->bienvenue($_POST['email'], $_POST['nom']);

// Contrôleur 2
email_service()->confirmation($_POST['email']);

// Contrôleur 3
email_service()->bienvenue($_POST['email'], $_POST['nom']);
```

### Créer un service complètement

**4 étapes (comme montré dans GUIDE_AJOUTER_SERVICES.md)**

---

## Étape 8: Validation

### Classe Validateur

**Fichier:** `core/Validateur.php`

```php
<?php

namespace Core;

class Validateur
{
    private array $regles = [];
    private array $messages = [];
    private array $erreurs = [];

    /**
     * Ajoute une règle de validation
     */
    public function ajouter(string $champ, array $regles, array $messages = []): self
    {
        $this->regles[$champ] = $regles;
        if (!empty($messages)) {
            $this->messages[$champ] = $messages;
        }
        return $this;
    }

    /**
     * Valide les données
     */
    public function valider(array $donnees): bool
    {
        $this->erreurs = [];

        foreach ($this->regles as $champ => $regles) {
            $valeur = $donnees[$champ] ?? '';

            foreach ($regles as $regle) {
                if (!$this->validerRegle($champ, $valeur, $regle)) {
                    $this->erreurs[$champ][] = $this->obtenirMessage($champ, $regle);
                }
            }
        }

        return empty($this->erreurs);
    }

    /**
     * Valide une règle spécifique
     */
    private function validerRegle(string $champ, string $valeur, string $regle): bool
    {
        match ($regle) {
            'requis' => !empty($valeur),
            'email' => filter_var($valeur, FILTER_VALIDATE_EMAIL),
            'url' => filter_var($valeur, FILTER_VALIDATE_URL),
            'nombre' => is_numeric($valeur),
            default => $this->validerRegles($valeur, $regle),
        };
    }

    /**
     * Récupère les erreurs
     */
    public function erreurs(): array
    {
        return $this->erreurs;
    }
}
```

### Utilisation dans un contrôleur

```php
class ArticleControleur
{
    public function creer()
    {
        $v = validateur();
        $v->ajouter('titre', ['requis', 'min:5', 'max:200']);
        $v->ajouter('contenu', ['requis', 'min:20']);

        if (!$v->valider($_POST)) {
            $_SESSION['erreurs'] = $v->erreurs();
            return $this->redirection('/articles/creer');
        }

        // Créer l'article...
        $article = new Article();
        $article->titre = $_POST['titre'];
        $article->contenu = $_POST['contenu'];
        $article->sauvegarder();

        notification()->succes('Article créé!');
        return $this->redirection('/articles');
    }
}
```

---

## Étape 9: Cache et Erreurs

### Cache simple

**Fichier:** `core/Cache.php`

```php
<?php

namespace Core;

class Cache
{
    private static string $cheminCache = '';

    public static function initialiser(string $chemin = ''): void
    {
        self::$cheminCache = $chemin ?: __DIR__ . '/../storage/cache/';
    }

    /**
     * Mettre en cache
     */
    public static function mettre(string $cle, mixed $valeur, int $ttl = 3600): void
    {
        $fichier = self::$cheminCache . md5($cle) . '.cache';
        file_put_contents($fichier, serialize($valeur));
    }

    /**
     * Obtenir du cache
     */
    public static function obtenir(string $cle, mixed $default = null): mixed
    {
        $fichier = self::$cheminCache . md5($cle) . '.cache';

        if (!file_exists($fichier)) {
            return $default;
        }

        return unserialize(file_get_contents($fichier));
    }

    /**
     * Récupérer ou créer
     */
    public static function souvenir(string $cle, callable $callback, int $ttl = 3600): mixed
    {
        if ($valeur = self::obtenir($cle)) {
            return $valeur;
        }

        $valeur = $callback();
        self::mettre($cle, $valeur, $ttl);

        return $valeur;
    }
}
```

### Gestion des erreurs

**Fichier:** `core/GestionnaireErreurs.php`

```php
<?php

namespace Core;

class GestionnaireErreurs
{
    private static bool $modeDebug = true;

    public static function initialiser(bool $debug = true): void
    {
        self::$modeDebug = $debug;

        set_error_handler([self::class, 'gererErreur']);
        set_exception_handler([self::class, 'gererException']);
    }

    /**
     * Gère les erreurs PHP
     */
    public static function gererErreur(int $niveau, string $message, string $fichier, int $ligne)
    {
        self::enregistrer($message, $fichier, $ligne);

        if (self::$modeDebug) {
            echo "<pre style='color: red;'>Erreur: $message\nFichier: $fichier:$ligne</pre>";
        } else {
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }

        return true;
    }

    /**
     * Enregistre l'erreur
     */
    private static function enregistrer(string $message, string $fichier, int $ligne): void
    {
        $date = date('Y-m-d H:i:s');
        $contenu = "[$date] $message\nFichier: $fichier:$ligne\n\n";

        $nomFichier = __DIR__ . '/../storage/logs/erreurs-' . date('Y-m-d') . '.log';
        file_put_contents($nomFichier, $contenu, FILE_APPEND);
    }
}
```

### Initialiser au démarrage

**Fichier:** `public/index.php`

```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Initialiser les erreurs
\Core\GestionnaireErreurs::initialiser(true); // true = debug

// Initialiser le cache
\Core\Cache::initialiser();

// Démarrer l'application
$app = new \Core\Application();
$app->demarrer();
```

---

## 🎯 Résumé du parcours

```
DÉBUT
  ↓
1. Créer structure dossiers
  ↓
2. Installer Composer & PSR-4
  ↓
3. Créer classe Application (Kernel)
  ↓
4. Créer Routeur
  ↓
5. Créer point d'entrée (index.php)
  ↓
6. Créer classe Modèle de base
  ↓
7. Créer premiers modèles
  ↓
8. Créer authentification
  ↓
9. Créer validateur
  ↓
10. Créer services réutilisables
  ↓
11. Ajouter cache et gestion erreurs
  ↓
12. Ajouter helpers globaux
  ↓
FIN: Framework complet! 🎉
```

---

## 📚 Ressources utilisées

- PHP 8.0+ (Types, match, etc.)
- Composer (Autoloading PSR-4)
- MySQL/PDO (Base de données)
- Sessions PHP (Authentification)
- .htaccess (Mod_rewrite)

---

## 🚀 Prochaines étapes

Maintenant que vous comprenez la structure, vous pouvez:

1. **Ajouter vos propres services** (voir GUIDE_AJOUTER_SERVICES.md)
2. **Créer des contrôleurs** personnalisés
3. **Ajouter des middlewares** pour la validation de requête
4. **Intégrer une ORM** pour simplifier les requêtes SQL
5. **Tester avec PHPUnit**
6. **Déployer en production**

---

## 💡 Conseils finaux

✅ **Toujours**:

- Respecter PSR-4 (1 fichier = 1 classe)
- Utiliser les namespaces
- Valider les entrées utilisateur
- Gérer les erreurs proprement
- Encapsuler la logique complexe

❌ **Jamais**:

- Mettre plusieurs classes dans un fichier
- Oublier de faire `composer dump-autoload`
- Développer sans validation
- Utiliser `eval()` ou `include()` dynamiques
- Exposer les erreurs en production

---

**C'est le chemin complet pour créer un framework MVC professionnel!** 🎓🚀
