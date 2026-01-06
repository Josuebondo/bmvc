# 🛣️ Classe Routeur

**Gestion du routage HTTP**

---

## 📖 Description

La classe `Routeur` gère le routage des requêtes HTTP vers les contrôleurs appropriés. Elle supporte GET, POST, PUT, DELETE et d'autres méthodes HTTP.

**Localisation:** `core/Routeur.php`

---

## 🔧 Méthodes Principales

### Enregistrer des Routes

#### `get($chemin, $destination)`

Enregistre une route GET.

```php
Routeur::get('/blog', 'ArticleControleur@index');
Routeur::get('/blog/{id}', 'ArticleControleur@afficher');
Routeur::get('/blog/{slug}/modifier', 'ArticleControleur@formulaireModifier');
```

#### `post($chemin, $destination)`

Enregistre une route POST.

```php
Routeur::post('/blog', 'ArticleControleur@creer');
Routeur::post('/blog/{id}', 'ArticleControleur@sauvegarder');
```

#### `put($chemin, $destination)`

Enregistre une route PUT.

```php
Routeur::put('/blog/{id}', 'ArticleControleur@remplacer');
```

#### `delete($chemin, $destination)`

Enregistre une route DELETE.

```php
Routeur::delete('/blog/{id}', 'ArticleControleur@supprimer');
```

#### `patch($chemin, $destination)`

Enregistre une route PATCH.

```php
Routeur::patch('/blog/{id}', 'ArticleControleur@mettreAJour');
```

#### `tous($chemin, $destination)`

Enregistre une route pour toutes les méthodes.

```php
Routeur::tous('/health', 'HealthControleur@check');
```

---

### Groupes de Routes

#### `groupe($options, $callback)`

Groupe plusieurs routes avec des préfixes ou middleware communs.

```php
// Avec préfixe
Routeur::groupe(['prefix' => 'admin'], function() {
    Routeur::get('/dashboard', 'DashboardControleur@index');
    Routeur::get('/users', 'UserControleur@index');
    Routeur::post('/users', 'UserControleur@creer');
});

// Avec middleware
Routeur::groupe(['middleware' => 'auth'], function() {
    Routeur::post('/profile/update', 'ProfileControleur@mettrAJour');
    Routeur::delete('/account', 'AccountControleur@supprimer');
});

// Combiné
Routeur::groupe(['prefix' => 'api', 'middleware' => 'auth'], function() {
    Routeur::post('/articles', 'ArticleControleur@creer');
    Routeur::get('/articles/{id}', 'ArticleControleur@afficher');
});
```

---

### Paramètres de Route

Les paramètres de route sont définis avec des accolades.

```php
// Un paramètre
Routeur::get('/blog/{id}', 'ArticleControleur@afficher');

// Plusieurs paramètres
Routeur::get('/blog/{annee}/{mois}', 'ArticleControleur@parDate');

// Paramètres optionnels
Routeur::get('/blog/{page?}', 'ArticleControleur@index');

// Avec expressions régulières
Routeur::get('/blog/{id:numero}', 'ArticleControleur@afficher');
Routeur::get('/blog/{slug:alpha}', 'ArticleControleur@parSlug');
```

#### Expressions Régulières Prédéfinies

```php
{id:numero}     // \d+           - Nombres uniquement
{slug:alpha}    // [a-zA-Z]+     - Lettres uniquement
{code:alnum}    // [a-zA-Z0-9]+  - Alphanumérique
{uuid:uuid}     // UUID format   - Format UUID
{date:date}     // YYYY-MM-DD    - Format date
```

---

### Récupérer les Paramètres

Dans les contrôleurs, les paramètres de route sont passés en arguments.

```php
// Route: /blog/{id}
public function afficher($id, Requete $requete)
{
    // $id contient la valeur du paramètre
    $article = Article::trouver($id);
}

// Route: /blog/{annee}/{mois}
public function parDate($annee, $mois, Requete $requete)
{
    // $annee et $mois contiennent les valeurs
    $articles = Article::entreDate($annee, $mois);
}
```

---

### Routes Nommées

#### `nom($nom)`

Donne un nom à une route pour la référence.

```php
Routeur::get('/blog/{id}', 'ArticleControleur@afficher')->nom('article.afficher');
Routeur::post('/blog', 'ArticleControleur@creer')->nom('article.creer');
```

#### `genererUrl($nom, $parametres = [])`

Génère une URL à partir du nom de la route.

```php
// Génère: /blog/123
$url = Routeur::genererUrl('article.afficher', ['id' => 123]);

// Génère: /blog/2025/01
$url = Routeur::genererUrl('article.parDate', ['annee' => 2025, 'mois' => 1]);
```

---

### Middleware

#### `middleware($nom)`

Attache un middleware à une route ou un groupe.

```php
// Sur une route
Routeur::post('/admin/users', 'UserControleur@creer')->middleware('auth', 'admin');

// Sur un groupe
Routeur::groupe(['middleware' => 'auth'], function() {
    Routeur::get('/profile', 'ProfileControleur@afficher');
    Routeur::post('/profile', 'ProfileControleur@sauvegarder');
});
```

---

### Exécution du Routeur

#### `executer(Requete $requete)`

Exécute le routeur pour traiter la requête.

```php
$requete = new Requete();
$reponse = Routeur::executer($requete);
```

**Note:** C'est généralement géré par le point d'entrée principal de l'application.

---

### Routes Spéciales

#### `nonTrouve($destination)`

Définit la route 404.

```php
Routeur::nonTrouve('ErrorControleur@nonTrouve');
```

#### `erreur($code, $destination)`

Définit une route d'erreur personnalisée.

```php
Routeur::erreur(500, 'ErrorControleur@serveur');
Routeur::erreur(403, 'ErrorControleur@interdit');
```

---

## 📚 Exemples d'Utilisation

### Routes Simples

```php
// config/routes.php
use BMVC\Core\Routeur;

// Pages publiques
Routeur::get('/', 'PageControleur@accueil')->nom('accueil');
Routeur::get('/about', 'PageControleur@apropos')->nom('apropos');
Routeur::get('/contact', 'PageControleur@contact')->nom('contact');

// Blog
Routeur::get('/blog', 'ArticleControleur@index')->nom('blog');
Routeur::get('/blog/{id}', 'ArticleControleur@afficher')->nom('article');
Routeur::get('/blog/tag/{tag}', 'ArticleControleur@parTag')->nom('tag');
```

### API REST

```php
Routeur::groupe(['prefix' => 'api/v1'], function() {
    // Articles
    Routeur::get('/articles', 'ArticleControleur@lister');
    Routeur::post('/articles', 'ArticleControleur@creer');
    Routeur::get('/articles/{id}', 'ArticleControleur@afficher');
    Routeur::put('/articles/{id}', 'ArticleControleur@remplacer');
    Routeur::delete('/articles/{id}', 'ArticleControleur@supprimer');

    // Commentaires
    Routeur::get('/articles/{id}/comments', 'CommentaireControleur@lister');
    Routeur::post('/articles/{id}/comments', 'CommentaireControleur@creer');
});
```

### Routes Protégées

```php
// Routes d'administration
Routeur::groupe(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
    Routeur::get('/dashboard', 'AdminControleur@dashboard')->nom('admin.dashboard');
    Routeur::get('/users', 'UserControleur@index')->nom('admin.users');
    Routeur::post('/users', 'UserControleur@creer')->nom('admin.users.create');
    Routeur::get('/users/{id}/edit', 'UserControleur@edit')->nom('admin.users.edit');
    Routeur::put('/users/{id}', 'UserControleur@update')->nom('admin.users.update');
    Routeur::delete('/users/{id}', 'UserControleur@delete')->nom('admin.users.delete');
});

// Routes utilisateur protégées
Routeur::groupe(['middleware' => 'auth'], function() {
    Routeur::get('/profile', 'ProfileControleur@afficher')->nom('profile');
    Routeur::post('/profile', 'ProfileControleur@sauvegarder')->nom('profile.save');
    Routeur::post('/logout', 'AuthControleur@deconnexion')->nom('logout');
});
```

### Génération d'URL dans les Vues

```php
<!-- Dans une vue -->
<a href="<?= Routeur::genererUrl('article', ['id' => $article->id]) ?>">
    <?= $article->titre ?>
</a>

<form action="<?= Routeur::genererUrl('article.creer') ?>" method="POST">
    <input type="text" name="titre">
    <button type="submit">Créer</button>
</form>
```

---

## 🔗 Configuration

Le fichier `config/routes.php` définit toutes les routes:

```php
<?php

use BMVC\Core\Routeur;

// Routes de l'application
require __DIR__ . '/../routes/web.php';
require __DIR__ . '/../routes/api.php';

// Gestion des erreurs
Routeur::nonTrouve('ErrorControleur@nonTrouve');
Routeur::erreur(500, 'ErrorControleur@serveur');
```

---

## 📋 Cheat Sheet

```php
// Enregistrer des routes
Routeur::get('/path', 'Controleur@methode');
Routeur::post('/path', 'Controleur@methode');
Routeur::put('/path', 'Controleur@methode');
Routeur::delete('/path', 'Controleur@methode');
Routeur::tous('/path', 'Controleur@methode');

// Paramètres
/blog/{id}           // Paramètre requis
/blog/{page?}        // Paramètre optionnel
/blog/{id:numero}    // Avec validation

// Groupes
Routeur::groupe(['prefix' => 'admin'], $callback);
Routeur::groupe(['middleware' => 'auth'], $callback);

// Noms et URLs
->nom('article.show');
Routeur::genererUrl('article.show', ['id' => 1]);

// Middleware
->middleware('auth', 'admin');

// Routes spéciales
Routeur::nonTrouve('ErrorControleur@notFound');
Routeur::erreur(500, 'ErrorControleur@error');
```

---

## 🧪 Tests

Voir `tests/RouteurTest.php` pour les tests complets.

```bash
php vendor/bin/phpunit tests/RouteurTest.php
```

---

## 📖 Voir aussi

- [Requete](Requete.md) - Gestion des requêtes
- [Reponse](Reponse.md) - Gestion des réponses
- [Middleware](Middleware.md) - Filtrage des requêtes

---

**BMVC Framework v1.0.0** | [Retour à l'index](../INDEX.md)
