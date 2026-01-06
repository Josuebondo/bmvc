# ⚡ DÉMARRAGE RAPIDE - Framework BMVC v1.0.0

**Lancez-vous avec BMVC en 5 minutes!** 🚀

---

## 🎯 Installation (2 min)

### Option 1: Composer (Recommandé)

```bash
composer require bmvc/framework
```

### Option 2: Cloner depuis GitHub

```bash
git clone https://github.com/bmvc/framework.git
cd framework
composer install
```

---

## 👋 Bonjour le Monde (1 min)

### 1. Créer un Contrôleur

**Fichier:** `app/Controllers/HelloController.php`

```php
<?php
namespace App\Controllers;

class HelloController {
    public function index() {
        return "Bonjour, BMVC!";
    }
}
```

### 2. Ajouter une Route

**Fichier:** `routes/web.php`

```php
$router->get('/', 'HelloController@index');
```

### 3. Lancer le Serveur

```bash
php -S localhost:8000
```

### 4. Visiter dans le Navigateur

```
http://localhost:8000
```

✅ **Fait!** Vous verrez: `Bonjour, BMVC!`

---

## 🔥 Tâches Courantes (2 min)

### Créer un Modèle d'Article

**Fichier:** `app/Models/Post.php`

```php
<?php
namespace App\Models;
use Core\Modele;

class Post extends Modele {
    protected $table = 'posts';
}
```

### Créer un Contrôleur d'Articles

**Fichier:** `app/Controllers/PostController.php`

```php
<?php
namespace App\Controllers;
use App\Models\Post;
use Core\APIResponse;

class PostController {
    // Lister tous les articles
    public function index() {
        $posts = Post::all();
        return APIResponse::succes($posts);
    }

    // Obtenir un article
    public function show($id) {
        $post = Post::find($id);
        return APIResponse::succes($post);
    }

    // Créer un article
    public function store() {
        $post = Post::create([
            'title' => $_POST['title'],
            'content' => $_POST['content']
        ]);
        return APIResponse::succes($post, 'Article créé', 201);
    }
}
```

### Ajouter les Routes

**Fichier:** `routes/web.php`

```php
// GET /posts → afficher tous
$router->get('/posts', 'PostController@index');

// GET /posts/{id} → afficher un
$router->get('/posts/{id}', 'PostController@show')
    ->where('id', '[0-9]+');

// POST /posts → créer
$router->post('/posts', 'PostController@store');
```

### Tester

```bash
# Obtenir tous les articles
curl http://localhost:8000/posts

# Obtenir article 1
curl http://localhost:8000/posts/1

# Créer un article
curl -X POST http://localhost:8000/posts \
  -d "title=Mon Article" \
  -d "content=Contenu de l'article"
```

---

## 🧪 Tests (1 min)

### Exécuter Tous les Tests

```bash
composer test
```

### Résultat Attendu

```
PHPUnit 9.5.x

35 tests, 0 failures, 0 errors ✅
Couverture de Code: 85%+
```

### Exécuter des Tests Spécifiques

```bash
# Tests unitaires uniquement
composer test:unit

# Tests fonctionnels uniquement
composer test:functional

# Avec rapport de couverture
composer test:coverage
```

---

## 🛠️ Configuration

### Configuration de l'Environnement

**Fichier:** `.env`

```env
APP_NAME=BMVC
APP_ENV=production
APP_DEBUG=false

DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=bmvc

LANGUAGE=fr
```

### Configuration de la Base de Données

```sql
-- Créer la base de données
CREATE DATABASE bmvc DEFAULT CHARSET utf8mb4;

-- Créer la table posts (exemple)
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 📚 Concepts Clés

### Routage

```php
// Route GET
$router->get('/accueil', 'HomeController@index');

// Route POST
$router->post('/users', 'UserController@store');

// Avec paramètres
$router->get('/posts/{id}', 'PostController@show');

// Avec contraintes
$router->get('/posts/{id}', 'PostController@show')
    ->where('id', '[0-9]+');

// Route nommée
$router->get('/apropos', 'PageController@about')
    ->name('about');
```

### Modèles (ORM)

```php
use App\Models\Post;

// Obtenir tous
$posts = Post::all();

// Obtenir un
$post = Post::find(1);

// Clause WHERE
$posts = Post::where('author', 'Jean')->get();
$post = Post::where('id', 1)->first();

// Créer
$post = Post::create([
    'title' => 'Mon Article',
    'content' => 'Contenu ici'
]);

// Mettre à jour
$post->update(['title' => 'Nouveau Titre']);

// Supprimer
$post->delete();
```

### Validation

```php
use Core\Validation;

$data = [
    'email' => 'user@example.com',
    'password' => 'secret123'
];

$rules = [
    'email' => 'required|email',
    'password' => 'required|min:6'
];

Validation::validate($data, $rules);
// Retourne true si valide, lève une exception sinon
```

### Traductions (i18n)

```php
use Core\Traduction;

// Charger la langue
Traduction::chargerLangue('fr');

// Obtenir une traduction
$message = Traduction::obtenir('messages.welcome');

// Avec variables
$greeting = Traduction::obtenir('messages.hello',
    ['name' => 'Jean']
);

// Changer de langue
Traduction::chargerLangue('en');
```

### REST API

```php
use Core\APIResponse;

// Réponse succès
return APIResponse::succes(
    ['user' => $user],
    'Utilisateur créé',
    201
);

// Réponse erreur
return APIResponse::erreur('Email invalide', 400);

// 401 Non authentifié
return APIResponse::nonAuthentifie('Connexion requise');

// 403 Non autorisé
return APIResponse::nonAutorise('Non autorisé');

// 404 Non trouvé
return APIResponse::nonTrouve('Utilisateur non trouvé');
```

---

## 🎯 Prochaines Étapes

### Apprendre Plus

1. 📖 Lire [GUIDE_UTILISATION.md](GUIDE_UTILISATION.md) - Guide complet (30 min)
2. 📋 Voir [EXEMPLE_BLOG_COMPLET.md](EXEMPLE_BLOG_COMPLET.md) - Exemple complet app (30 min)
3. 🧪 Apprendre [GUIDE_TESTS_EXECUTION.md](GUIDE_TESTS_EXECUTION.md) - Guide de tests (30 min)

### Construire Quelque Chose

1. Créer un modèle
2. Ajouter des routes
3. Écrire des tests
4. Déployer!

### Obtenir de l'Aide

1. 📚 [INDEX_DOCUMENTATION_COMPLETE.md](INDEX_DOCUMENTATION_COMPLETE.md) - Index principal
2. 🔍 Chercher dans la documentation
3. 📖 Vérifier les exemples

---

## 💡 Conseils Pro

### Utiliser le Générateur CLI

```bash
# Générer le module Gallery avec routes
php bmvc -cmd Gallery

# Crée:
# - app/Controllers/GalleryController.php
# - app/Models/Gallery.php
# - Routes pour opérations CRUD
```

### Activer le Mode Débogage

```env
# .env
APP_DEBUG=true
```

### Voir Toutes les Routes

```bash
# Afficher les routes enregistrées
php bmvc -cmd routes

# Sortie: Liste de toutes les routes
```

### Générer une Réponse API

```php
use Core\APIResponse;

// Retourne automatiquement JSON
return APIResponse::succes(['data' => $data]);
```

### Gérer les Erreurs

```php
try {
    $user = User::find($id);
} catch (Exception $e) {
    return APIResponse::erreur($e->getMessage(), 500);
}
```

---

## 🚀 Déployer en Production

### Déploiement Rapide

```bash
# 1. Installer sans dépendances dev
composer install --no-dev --optimize-autoloader

# 2. Configurer l'environnement
cp .env.example .env
# Éditer .env avec les paramètres de production

# 3. Définir les permissions
chmod 755 storage/
chmod 755 storage/cache/
chmod 755 storage/logs/

# 4. Fait! Votre app est prête
```

### Vérifier le Déploiement

```bash
# Exécuter les tests
composer test

# Doit afficher: 35 tests, 0 failures ✅
```

---

## 📋 Aide-Mémoire

### Routes

```php
$router->get($path, $controller@$method);
$router->post($path, $controller@$method);
$router->put($path, $controller@$method);
$router->delete($path, $controller@$method);
```

### Base de Données

```php
Model::all();
Model::find($id);
Model::where($column, $value)->get();
Model::create($data);
Model::update($data);
Model::delete();
```

### Validation

```php
'email' => 'required|email'
'name' => 'required|min:3|max:100'
'age' => 'required|numeric|min:18'
'password' => 'required|min:6|confirmed'
```

### Traductions

```php
Traduction::chargerLangue('fr');
Traduction::obtenir('messages.key');
Traduction::chargerLangue('en');
```

### Réponse API

```php
APIResponse::succes($data, $message, 200);
APIResponse::erreur($message, 400);
APIResponse::nonAuthentifie($message);
APIResponse::nonAutorise($message);
APIResponse::nonTrouve($message);
```

---

## 🆘 Dépannage

### Erreur "Class not found"

```
Solution: Exécuter composer dump-autoload
```

### Routes ne fonctionnent pas

```
Solution: Vérifier .htaccess ou nginx.conf
Vérifier la syntaxe de routes/web.php
```

### Échec de connexion à la base de données

```
Solution: Vérifier les paramètres DB_* dans .env
Vérifier que la base de données existe
Vérifier que MySQL fonctionne
```

### Tests échouent

```
Solution: Exécuter: composer test
Vérifier la sortie des tests pour les détails
Revoir tests/bootstrap.php
```

### Permission refusée

```
Solution: chmod 755 storage/
Vérifier la propriété des fichiers
```

---

## 📞 Support

### Liens Rapides

- 📖 [README Principal](README.md)
- 📚 [Index Complet Documentation](INDEX_DOCUMENTATION_COMPLETE.md)
- 💻 [Guide d'Utilisation](GUIDE_UTILISATION.md)
- 📋 [Exemple Blog](EXEMPLE_BLOG_COMPLET.md)
- 🧪 [Guide de Tests](GUIDE_TESTS_EXECUTION.md)
- 📦 [Guide de Déploiement](DEPLOYMENT_CHECKLIST.md)

### Structure Documentation

- **Démarrage:** QUICKSTART.md (ce fichier)
- **Apprentissage:** GUIDE_UTILISATION.md
- **Exemples:** EXEMPLE_BLOG_COMPLET.md
- **Tests:** GUIDE_TESTS_EXECUTION.md
- **Déploiement:** DEPLOYMENT_CHECKLIST.md

---

## 🎉 Vous Êtes Prêt!

```
✅ Framework installé
✅ Hello World fonctionne
✅ Routes en place
✅ Base de données connectée
✅ Tests passent
✅ Prêt à construire!
```

### Construisez Quelque Chose d'Extraordinaire! 🚀

```
Maintenant que vous connaissez les bases:

1. Créer vos modèles
2. Écrire vos routes
3. Développer vos contrôleurs
4. Écrire des tests
5. Déployer en production
6. Célébrer! 🎊
```

---

**Démarrage Rapide BMVC**

**Temps jusqu'à la première route:** < 5 minutes  
**Temps jusqu'à la production:** < 1 heure  
**Statut:** ✅ Prêt!

**Commencez à construire maintenant!** 🚀
