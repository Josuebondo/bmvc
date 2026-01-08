# 📖 Chapitre 4: Guide Complet d'Utilisation

**Maîtrisez BMVC en Profondeur - Utilisation Avancée**

---

## 🎯 Objectif du Chapitre

Comprendre tous les aspects avancés de BMVC:

- Contrôleurs complets
- Modèles et ORM avancé
- Vues professionnelles
- Routage avancé
- Middleware
- Validation complète
- Sessions et authentification

**Temps:** ~4 heures | **Niveau:** Intermédiaire-Avancé

---

## 1️⃣ Contrôleurs Avancés (30 min)

### Structure d'un Contrôleur

```php
<?php

namespace App\Controleurs;

use App\BaseControleur;
use Core\Requete;
use Core\Reponse;

class ArticleControleur extends BaseControleur
{
    // Méthodes publiques accessibles via routes
    public function index(Requete $request, Reponse $response): string
    {
        // Votre logique ici
        return "Hello";
    }
}
```

### Injection de Dépendances

Les paramètres de type-hint sont injectés automatiquement:

```php
public function show(Requete $request, Reponse $response): string
{
    $id = $request->param('id');
    $article = Article::trouver($id);

    return $this->afficher('articles.show', ['article' => $article]);
}
```

### Méthodes d'Aide du BaseControleur

#### Afficher une Vue

```php
// Retourner une vue avec données
return $this->afficher('articles.index', [
    'articles' => Article::tous()
]);
```

#### Réponse JSON

```php
public function api(Requete $request, Reponse $response): string
{
    return $this->json([
        'status' => 'success',
        'data' => Article::tous()
    ]);
}
```

#### Redirection

```php
// Redirection simple (302)
return $this->rediriger('/articles');

// Redirection permanente (301)
return $this->rediriger('/old-url', 301);
```

#### Gestion des Erreurs

```php
// Erreur 404
return $this->erreur(404, 'Article non trouvé');

// Erreur 403
return $this->erreur(403, 'Accès refusé');

// Erreur 500
return $this->erreur(500, 'Erreur serveur');
```

#### Réponse Personnalisée

```php
return $this->reponse('Contenu HTML', 200, [
    'Content-Type' => 'text/html; charset=utf-8'
]);
```

### Middleware sur Contrôleur

```php
class AdminControleur extends BaseControleur
{
    public function __construct()
    {
        // Ajouter un middleware au contrôleur entier
        $this->middleware('auth');
    }

    public function index()
    {
        // Ce contrôleur nécessite l'authentification
        return $this->afficher('admin.index');
    }
}
```

---

## 2️⃣ Modèles et ORM Avancé (45 min)

### Créer un Modèle

```php
<?php

namespace App\Modeles;

use Core\Modele;

class Article extends Modele
{
    // Table associée
    protected string $table = 'articles';

    // Colonnes à masse-assigner
    protected array $fillable = ['titre', 'contenu', 'auteur_id'];

    // Colonnes à masquer (ex: password)
    protected array $hidden = [];

    // Colonnes de timestamp (created_at, updated_at)
    protected bool $timestamps = true;
}
```

### Requêtes Simples

#### Récupérer Tous les Enregistrements

```php
$articles = Article::tous();

foreach ($articles as $article) {
    echo $article->titre;
}
```

#### Récupérer par ID

```php
$article = Article::trouver(1);

if ($article) {
    echo $article->titre;
} else {
    echo "Non trouvé";
}
```

#### Créer un Enregistrement

```php
$article = Article::creer([
    'titre' => 'Mon Article',
    'contenu' => 'Contenu...',
    'auteur_id' => 1
]);

echo $article->id; // ID généré
```

#### Mettre à Jour

```php
$article = Article::trouver(1);
$article->titre = 'Nouveau Titre';
$article->sauvegarder();
```

#### Supprimer

```php
$article = Article::trouver(1);
$article->supprimer();
```

### Query Builder Avancé

#### Where (Où)

```php
// Un seul "where"
$articles = Article::où('auteur_id', '=', 1)->tous();

// Plusieurs "where"
$articles = Article::où('auteur_id', '=', 1)
    ->où('statut', '=', 'publié')
    ->tous();

// Opérateurs: =, !=, <, >, <=, >=, LIKE
$articles = Article::où('titre', 'LIKE', '%PHP%')->tous();
```

#### OrderBy (Trier)

```php
// Trier par date DESC
$articles = Article::ordonner('created_at', 'DESC')->tous();

// Trier par date ASC (défaut)
$articles = Article::ordonner('created_at')->tous();
```

#### Limite et Offset

```php
// Les 10 premiers
$articles = Article::limiter(10)->tous();

// Sauter 10 et prendre 10
$articles = Article::limiter(10)->decaler(10)->tous();

// Page 2 avec 10 par page
$page = 2;
$parPage = 10;
$articles = Article::limiter($parPage)->decaler(($page-1) * $parPage)->tous();
```

#### Paginer

```php
// Paginer: 15 articles par page
$articles = Article::paginer(15);

// Propriétés disponibles
$articles->items;      // Articles de la page
$articles->total;      // Total articles
$articles->parPage;    // Par page
$articles->pageActuelle; // Page actuelle
$articles->pages;      // Nombre de pages
```

#### Compter

```php
$total = Article::compter();

$total = Article::où('auteur_id', '=', 1)->compter();
```

### Relations ORM

#### Relation HasMany (Un vers Plusieurs)

```php
class Article extends Modele
{
    // Un article a plusieurs commentaires
    public function commentaires()
    {
        return $this->hasMany('Commentaire', 'article_id');
    }
}

// Utiliser la relation
$article = Article::trouver(1);
$commentaires = $article->commentaires()->tous();
```

#### Relation BelongsTo (Appartient à)

```php
class Commentaire extends Modele
{
    // Un commentaire appartient à un article
    public function article()
    {
        return $this->belongsTo('Article', 'article_id');
    }
}

// Utiliser la relation
$commentaire = Commentaire::trouver(1);
$article = $commentaire->article();
echo $article->titre;
```

#### Chargement Eager (Optimiser les Requêtes)

```php
// Mauvais: N+1 queries
$articles = Article::tous();
foreach ($articles as $article) {
    $commentaires = $article->commentaires()->tous(); // Requête par article!
}

// Bon: 2 requêtes seulement
$articles = Article::chargerEager('commentaires')->tous();
foreach ($articles as $article) {
    $commentaires = $article->commentaires(); // Données en cache
}
```

### Timestamps et Soft Delete

#### Timestamps Automatiques

```php
class Article extends Modele
{
    protected bool $timestamps = true; // Activer timestamps
}

// Accéder aux timestamps
$article = Article::trouver(1);
echo $article->created_at; // "2026-01-07 10:30:00"
echo $article->updated_at; // "2026-01-07 14:20:00"
```

#### Soft Delete (Supprimer Sans Vraiment Supprimer)

```php
class Article extends Modele
{
    protected bool $softDelete = true;
}

// Supprimer
$article->supprimer(); // Ajoute deleted_at

// Inclure les supprimés
$articles = Article::avecSupprimes()->tous();

// Récupérer seulement les supprimés
$articles = Article::seulementSupprimes()->tous();

// Restaurer
$article->restaurer(); // Retire deleted_at
```

---

## 3️⃣ Vues et Templating (30 min)

### Créer une Vue Basique

Fichier: `app/Vues/articles/index.php`

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Articles</title>
  </head>
  <body>
    <h1>Articles</h1>

    <?php foreach ($articles as $article): ?>
    <div>
      <h2><?php echo e($article->titre); ?></h2>
      <p><?php echo e($article->contenu); ?></p>
    </div>
    <?php endforeach; ?>
  </body>
</html>
```

### Layouts (Modèles Principaux)

Fichier: `app/Vues/layouts/app.php`

```html
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title><?php section('titre', 'BMVC'); ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>" />
  </head>
  <body>
    <header>
      <nav>
        <ul>
          <li><a href="<?php echo url('/'); ?>">Accueil</a></li>
          <li><a href="<?php echo url('/articles'); ?>">Articles</a></li>
        </ul>
      </nav>
    </header>

    <main>
      <?php section('contenu'); ?>
    </main>

    <footer>
      <p>&copy; 2026 BMVC</p>
    </footer>

    <script src="<?php echo asset('js/app.js'); ?>"></script>
  </body>
</html>
```

### Utiliser un Layout

Fichier: `app/Vues/articles/index.php`

```html
<?php etendre('layouts.app'); ?>

<?php debut_section('titre'); ?>
Articles
<?php fin_section('titre'); ?>

<?php debut_section('contenu'); ?>
<h1>Articles</h1>

<table>
  <tr>
    <th>Titre</th>
    <th>Auteur</th>
  </tr>
  <?php foreach ($articles as $article): ?>
  <tr>
    <td><?php echo e($article->titre); ?></td>
    <td><?php echo e($article->auteur); ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php fin_section('contenu'); ?>
```

### Inclusions Partielles

```html
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
<h1>Articles</h1>

<?php // Inclure une partielle ?>
<?php section('partials/article-list'); ?>
<?php fin_section('contenu'); ?>
```

Fichier: `app/Vues/partials/article-list.php`

```html
<ul>
  <?php foreach ($articles as $article): ?>
  <li>
    <a href="/articles/<?php echo $article->id; ?>">
      <?php echo e($article->titre); ?>
    </a>
  </li>
  <?php endforeach; ?>
</ul>
```

### Protection XSS

```html
<!-- ❌ MAUVAIS - Affiche du HTML/JS brut -->
<p><?php echo $utilisateur->nom; ?></p>

<!-- ✅ BON - Échappe pour XSS -->
<p><?php echo e($utilisateur->nom); ?></p>

<!-- Avec la fonction raccourcie -->
<p><?php echo e($donnees['titre']); ?></p>
```

### Helpers de Vue

```html
<!-- Générer une URL -->
<a href="<?php echo url('/articles'); ?>">Articles</a>

<!-- Lien nommé -->
<a href="<?php echo url('articles'); ?>">Articles</a>

<!-- Asset (CSS, JS, images) -->
<link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>" />
<script src="<?php echo asset('js/app.js'); ?>"></script>
<img src="<?php echo asset('images/logo.png'); ?>" alt="Logo" />

<!-- Ancien input (après validation) -->
<input type="text" name="email" value="<?php echo ancien('email'); ?>" />

<!-- Message flash -->
<?php if (flash('succes')): ?>
<div class="alert alert-success">
  <?php echo flash('succes'); ?>
</div>
<?php endif; ?>

<?php if (flash('erreur')): ?>
<div class="alert alert-danger">
  <?php echo flash('erreur'); ?>
</div>
<?php endif; ?>

<!-- Afficher les erreurs de validation -->
<?php if (!empty($erreurs)): ?>
<ul class="errors">
  <?php foreach ($erreurs as $champ =>
  $messages): ?>
  <?php foreach ($messages as $message): ?>
  <li><?php echo e($message); ?></li>
  <?php endforeach; ?>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
```

---

## 4️⃣ Routage Avancé (30 min)

### Routes Simples

```php
use Core\Routeur;

// GET
Routeur::obtenir('/', 'PageControleur@accueil');

// POST
Routeur::publier('/articles', 'ArticleControleur@store');

// PUT
Routeur::mettre('/articles/{id}', 'ArticleControleur@update');

// DELETE
Routeur::supprimer('/articles/{id}', 'ArticleControleur@destroy');

// PATCH
Routeur::patcher('/articles/{id}', 'ArticleControleur@patch');

// Toutes les méthodes
Routeur::tous('/api/data', 'APIControleur@data');
```

### Paramètres Dynamiques

```php
// Paramètre simple
Routeur::obtenir('/articles/{id}', 'ArticleControleur@show');

// Paramètre avec contrainte regex
Routeur::obtenir('/articles/{slug}', 'ArticleControleur@showSlug')
    ->ou('slug', '[a-z0-9-]+');

// Plusieurs paramètres
Routeur::obtenir('/categories/{id}/articles/{article_id}',
    'ArticleControleur@showInCategory');

// Paramètre optionnel (avec point d'interrogation)
Routeur::obtenir('/articles/{?page}', 'ArticleControleur@index');
```

### Routes Nommées

```php
// Définir une route nommée
Routeur::obtenir('/articles/{id}', 'ArticleControleur@show')
    ->nom('articles.show');

// Récupérer l'URL dans le contrôleur ou vue
echo url('articles.show', ['id' => 1]);
// Résultat: /articles/1

// Dans une vue
<a href="<?php echo url('articles.show', ['id' => $article->id]); ?>">
    <?php echo e($article->titre); ?>
</a>
```

### Groupes de Routes

```php
// Grouper les routes avec un préfixe
Routeur::groupe(['prefixe' => 'api'], function() {
    Routeur::obtenir('/articles', 'APIControleur@articles');
    Routeur::obtenir('/articles/{id}', 'APIControleur@article');
    Routeur::publier('/articles', 'APIControleur@createArticle');
});
// URLs générées:
// GET /api/articles
// GET /api/articles/{id}
// POST /api/articles

// Grouper avec middleware
Routeur::groupe(['prefixe' => 'admin', 'middleware' => 'auth'], function() {
    Routeur::obtenir('/dashboard', 'AdminControleur@dashboard');
    Routeur::obtenir('/users', 'AdminControleur@users');
});
// Toutes les routes admin nécessitent l'authentification

// Grouper avec namespace
Routeur::groupe(['namespace' => 'Admin'], function() {
    Routeur::obtenir('/dashboard', 'DashboardControleur@index');
    // Cherche: App\Controleurs\Admin\DashboardControleur
});
```

---

## 5️⃣ Middleware (20 min)

### Créer un Middleware

Fichier: `app/Intergiciels/MonMiddleware.php`

```php
<?php

namespace App\Intergiciels;

use Core\Requete;
use Core\Reponse;

class MonMiddleware
{
    public function traiter(Requete $request, Reponse $response, callable $next)
    {
        // Code AVANT le contrôleur

        // Appeler le contrôleur
        $resultat = $next($request, $response);

        // Code APRÈS le contrôleur

        return $resultat;
    }
}
```

### Exemple: Middleware d'Authentification

```php
class Auth
{
    public function traiter(Requete $request, Reponse $response, callable $next)
    {
        $utilisateur = session('utilisateur');

        if (!$utilisateur) {
            return redirect('/login');
        }

        return $next($request, $response);
    }
}
```

### Enregistrer un Middleware

Dans `config/app.php`:

```php
'middleware' => [
    // Middleware global (tous les requêtes)
    'global' => [
        App\Intergiciels\VerifyCSRFToken::class,
    ],

    // Middleware nommé (à utiliser spécifiquement)
    'named' => [
        'auth' => App\Intergiciels\Auth::class,
        'admin' => App\Intergiciels\Admin::class,
    ]
]
```

### Utiliser un Middleware

```php
// Sur une route
Routeur::obtenir('/dashboard', 'DashboardControleur@index')
    ->middleware('auth');

// Sur un groupe de routes
Routeur::groupe(['middleware' => 'auth'], function() {
    Routeur::obtenir('/dashboard', 'DashboardControleur@index');
    Routeur::obtenir('/profile', 'ProfileControleur@show');
});

// Sur un contrôleur
class AdminControleur extends BaseControleur
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
}
```

---

## 6️⃣ Validation Complète (30 min)

### Règles de Validation

```php
$erreurs = $this->valider($_POST, [
    'nom' => 'requis|min:3|max:100',
    'email' => 'requis|email',
    'age' => 'numero',
    'telephone' => 'min:10',
    'site' => 'url',
    'password' => 'min:8|confirmed',
    'role' => 'in:user,admin,moderator',
    'date' => 'date',
    'unique_email' => 'unique:utilisateurs,email',
]);

if (!empty($erreurs)) {
    return $this->json(['erreurs' => $erreurs], 422);
}
```

### Toutes les Règles

| Règle                  | Description         | Exemple                                            |
| ---------------------- | ------------------- | -------------------------------------------------- |
| `requis`               | Champ obligatoire   | `'nom' => 'requis'`                                |
| `email`                | Format email valide | `'email' => 'email'`                               |
| `numero`               | Valeur numérique    | `'age' => 'numero'`                                |
| `min:N`                | Longueur minimum    | `'pwd' => 'min:8'`                                 |
| `max:N`                | Longueur maximum    | `'nom' => 'max:100'`                               |
| `longueur:N`           | Longueur exacte     | `'code' => 'longueur:5'`                           |
| `regex:PATTERN`        | Regex personnalisé  | `'code' => 'regex:/^[A-Z0-9]+$/'`                  |
| `url`                  | URL valide          | `'site' => 'url'`                                  |
| `date`                 | Format date         | `'date' => 'date'`                                 |
| `unique:table,colonne` | Valeur unique en BD | `'email' => 'unique:users,email'`                  |
| `confirmed`            | Champ confirmation  | `'pwd' => 'confirmed'` (attend `pwd_confirmation`) |
| `in:val1,val2`         | Liste de valeurs    | `'role' => 'in:admin,user'`                        |

### Messages d'Erreur Personnalisés

```php
$erreurs = $this->valider($_POST, [
    'email' => 'requis|email'
], [
    'email.requis' => 'L\'email est obligatoire',
    'email.email' => 'Email invalide',
    'nom.min' => 'Le nom doit faire au minimum 3 caractères'
]);
```

### Afficher les Erreurs

Dans la vue:

```html
<?php if (!empty($erreurs)): ?>
<div class="alert alert-danger">
  <ul>
    <?php foreach ($erreurs as $champ =>
    $messages): ?>
    <?php foreach ($messages as $message): ?>
    <li><?php echo e($message); ?></li>
    <?php endforeach; ?>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<!-- Ou par champ -->
<?php if (isset($erreurs['email'])): ?>
<span class="error">
  <?php echo implode(', ', $erreurs['email']); ?>
</span>
<?php endif; ?>
```

### Remplir les Anciens Inputs

```php
// Dans le contrôleur après validation échouée
$this->sauvegarderAncienInputs();
$this->flash('erreur', 'Erreurs de validation');
return $this->rediriger('/formulaire');

// Dans la vue pour pré-remplir
<input type="text" name="email" value="<?php echo ancien('email'); ?>">
<input type="text" name="nom" value="<?php echo ancien('nom'); ?>">
```

---

## 7️⃣ Sessions et Authentification (30 min)

### Gestion des Sessions

```php
// Définir une session
session('utilisateur', [
    'id' => 1,
    'nom' => 'Jean Dupont',
    'email' => 'jean@example.com'
]);

// Récupérer une session
$utilisateur = session('utilisateur');

// Vérifier si existe
if (session('utilisateur')) {
    echo "Utilisateur connecté";
}

// Supprimer une session
session('utilisateur', null);

// Toutes les sessions
$toutes = session();
```

### Messages Flash

```php
// Enregistrer un message flash
$this->flash('succes', 'Article créé avec succès!');
$this->flash('erreur', 'Erreur lors de la création');
$this->flash('info', 'Information importante');

// Afficher dans la vue
<?php if (flash('succes')): ?>
    <div class="alert alert-success">
        <?php echo flash('succes'); ?>
    </div>
<?php endif; ?>

// Récupérer et afficher
<?php $message = flash('succes'); ?>
<?php if ($message): ?>
    <p><?php echo e($message); ?></p>
<?php endif; ?>
```

### Authentification

#### Enregistrer un Utilisateur

```php
// Hash bcrypt
$password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

// Créer l'utilisateur
$utilisateur = Utilisateur::creer([
    'nom' => $_POST['nom'],
    'email' => $_POST['email'],
    'password' => $password_hash
]);
```

#### Connecter un Utilisateur

```php
$utilisateur = Utilisateur::où('email', '=', $_POST['email'])->premier();

if ($utilisateur && password_verify($_POST['password'], $utilisateur->password)) {
    // Correct! Créer la session
    session('utilisateur', [
        'id' => $utilisateur->id,
        'nom' => $utilisateur->nom,
        'email' => $utilisateur->email
    ]);

    return $this->rediriger('/dashboard');
} else {
    // Erreur
    return $this->json(['erreur' => 'Identifiants incorrects'], 401);
}
```

#### Middleware d'Authentification

```php
class Auth
{
    public function traiter(Requete $request, Reponse $response, callable $next)
    {
        if (!session('utilisateur')) {
            return redirect('/login');
        }

        return $next($request, $response);
    }
}
```

---

## 8️⃣ Formulaires et Soumissions (20 min)

### Créer un Formulaire

```html
<form method="POST" action="/articles" enctype="multipart/form-data">
    <!-- Champs obligatoires -->
    <input type="hidden" name="_method" value="POST">

    <!-- Champs -->
    <input type="text" name="titre" value="<?php echo ancien('titre'); ?>" placeholder="Titre">
    <textarea name="contenu" placeholder="Contenu"><?php echo ancien('contenu'); ?></textarea>
    <input type="email" name="email" value="<?php echo ancien('email'); ?>">

    <!-- Sélect -->
    <select name="categorie">
        <option value="">-- Sélectionner --</option>
        <option value="1" <?php echo ancien('categorie') == '1' ? 'selected' : ''; ?>>Tech</option>
        <option value="2" <?php echo ancien('categorie') == '2' ? 'selected' : ''; ?>>Vie</option>
    </select>

    <!-- Checkbox -->
    <input type="checkbox" name="newsletter" value="1"
        <?php echo ancien('newsletter') ? 'checked' : ''; ?>>

    <!-- Radio -->
    <input type="radio" name="type" value="article"
        <?php echo ancien('type') == 'article' ? 'checked' : ''; ?>>
    <input type="radio" name="type" value="news"
        <?php echo ancien('type') == 'news' ? 'checked' : ''; ?>>

    <!-- Bouton -->
    <button type="submit">Soumettre</button>
</form>
```

### Protection CSRF

BMVC inclut une protection CSRF automatique:

```html
<!-- Si vous créez manuellement le token -->
<form method="POST" action="/articles">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
  <!-- ... champs ... -->
</form>
```

### Soumission PUT/DELETE

Pour PUT et DELETE (non supportés en HTML):

```html
<!-- Édition (PUT) -->
<form method="POST" action="/articles/<?php echo $id; ?>">
  <input type="hidden" name="_method" value="PUT" />
  <!-- ... champs ... -->
  <button type="submit">Mettre à Jour</button>
</form>

<!-- Suppression (DELETE) -->
<form
  method="POST"
  action="/articles/<?php echo $id; ?>"
  onsubmit="return confirm('Confirmer la suppression?')"
>
  <input type="hidden" name="_method" value="DELETE" />
  <button type="submit">Supprimer</button>
</form>
```

### Récupérer les Données

```php
public function store(Requete $request, Reponse $response): string
{
    // Tous les inputs
    $donnees = $request->tous();

    // Un input spécifique
    $titre = $request->input('titre');
    $email = $request->input('email', 'default@example.com'); // Avec défaut

    // Fichier uploadé
    $fichier = $request->file('image');

    // Query string
    $page = $request->query('page', 1);

    // Paramètre d'URL
    $id = $request->param('id');
}
```

---

## 🎯 Résumé et Bonnes Pratiques

### ✅ Bonnes Pratiques

1. **Injection de Dépendances** - Utilisez les paramètres typés
2. **Validation** - Validez TOUJOURS les données utilisateur
3. **Protection XSS** - Utilisez toujours `e()` pour l'affichage
4. **Relations ORM** - Utilisez le chargement eager pour optimiser
5. **Middleware** - Centralisez la logique commune
6. **Messages Flash** - Pour les retours utilisateur
7. **Anciens Inputs** - Remplissez-les après une validation échouée
8. **Erreurs Gracieuses** - Affichez les erreurs proprement

### ❌ À Éviter

❌ Afficher les données utilisateur sans `e()`  
❌ Requêtes N+1 (charger les relations dans des boucles)  
❌ Stocker les mots de passe en clair  
❌ Ignorer la validation des entrées  
❌ Exposer les erreurs sensibles aux utilisateurs  
❌ Créer des requêtes SQL manuellement (risque injection)

---

## 💡 Exemples Pratiques Complets

### Exemple: Créer un Article

**Route:**

```php
Routeur::obtenir('/articles/nouveau', 'ArticleControleur@create');
Routeur::publier('/articles', 'ArticleControleur@store');
```

**Contrôleur:**

```php
public function create(Requete $request, Reponse $response): string
{
    return $this->afficher('articles.create');
}

public function store(Requete $request, Reponse $response): string
{
    // Valider
    $erreurs = $this->valider($request->tous(), [
        'titre' => 'requis|min:3|max:200',
        'contenu' => 'requis|min:10',
        'categorie_id' => 'requis|numero'
    ]);

    if (!empty($erreurs)) {
        $this->sauvegarderAncienInputs();
        $this->flash('erreur', 'Erreurs de validation');
        return $this->rediriger('/articles/nouveau');
    }

    // Créer
    Article::creer([
        'titre' => $request->input('titre'),
        'contenu' => $request->input('contenu'),
        'categorie_id' => $request->input('categorie_id'),
        'auteur_id' => session('utilisateur')['id']
    ]);

    $this->flash('succes', 'Article créé!');
    return $this->rediriger('/articles');
}
```

**Vue:**

```html
<?php etendre('layouts.app'); ?>

<?php debut_section('titre'); ?>
    Créer un Article
<?php fin_section(); ?>

<?php debut_section('contenu'); ?>
    <h1>Créer un Article</h1>

    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-danger">
            <?php foreach ($erreurs as $champ => $messages): ?>
                <p><?php echo implode(', ', $messages); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/articles">
        <input type="text" name="titre"
            value="<?php echo ancien('titre'); ?>"
            placeholder="Titre" required>

        <textarea name="contenu"
            placeholder="Contenu" required><?php echo ancien('contenu'); ?></textarea>

        <select name="categorie_id" required>
            <option value="">-- Catégorie --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat->id; ?>"
                    <?php echo ancien('categorie_id') == $cat->id ? 'selected' : ''; ?>>
                    <?php echo e($cat->nom); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Créer</button>
    </form>
<?php fin_section(); ?>
```

---

## 🎓 Prochaines Étapes

Vous maîtrisez maintenant BMVC en profondeur!

**Prochaine étape:** [Chapitre 5: Exemples Pratiques](../../examples/)

---

**Framework BMVC v1.0.0**

_Maîtrisez le MVC professionnel_ 🚀
