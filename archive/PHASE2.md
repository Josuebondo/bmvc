# 📖 BMVC Phase 2 - Documentation Complète

## 🎯 Aperçu

Phase 2 implémente un système de **routage avancé**, un **moteur de vues avec layouts**, et des **contrôleurs puissants**.

---

## 🔧 Routage

### Routes Simples

```php
// routes/web.php
Routeur::obtenir('/', 'PageControleur@accueil')->nom('accueil');
Routeur::publier('/contact', 'ContactControleur@envoyer');
Routeur::obtenir('/utilisateur/{id}', 'UtilisateurControleur@voir')->ou('id', '[0-9]+');
```

### Méthodes HTTP

```php
Routeur::obtenir($chemin, $action);      // GET
Routeur::publier($chemin, $action);      // POST
Routeur::mettre($chemin, $action);       // PUT
Routeur::supprimer($chemin, $action);    // DELETE
Routeur::patcher($chemin, $action);      // PATCH
Routeur::tous($chemin, $action);         // Toutes les méthodes
```

### Paramètres Dynamiques

```php
// Paramètre simple
Routeur::obtenir('/post/{id}', 'PostControleur@voir');

// Avec contrainte regex
Routeur::obtenir('/page/{slug}', 'PageControleur@voir')
    ->ou('slug', '[a-z0-9-]+');

// Plusieurs paramètres
Routeur::obtenir('/categorie/{cat}/post/{id}', 'PostControleur@paCategorieVoir')
    ->ou('id', '[0-9]+');
```

### Groupes de Routes

```php
Routeur::groupe(['prefixe' => 'api'], function() {
    Routeur::obtenir('/utilisateurs', 'APIControleur@utilisateurs');
    Routeur::obtenir('/utilisateurs/{id}', 'APIControleur@utilisateur');
});

// URLs générées:
// GET /api/utilisateurs
// GET /api/utilisateurs/{id}
```

### Routes Nommées

```php
Routeur::obtenir('/contact', 'ContactControleur@index')->nom('contact');

// Dans les vues:
<a href="<?php echo url('contact'); ?>">Contact</a>
```

---

## 🎨 Système de Vues

### Création d'un Layout

```php
<!-- app/Vues/layouts/app.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?php section('titre', 'BMVC'); ?></title>
</head>
<body>
    <main>
        <?php section('contenu'); ?>
    </main>
</body>
</html>
```

### Utilisation du Layout

```php
<!-- app/Vues/ma-page.php -->
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
    <h1>Ma Page</h1>
    <p>Contenu ici</p>
<?php fin_section('contenu'); ?>
```

### Inclusion de Vues Partielles

```php
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
    <h1>Accueil</h1>
    <?php // Inclure une partielle avec des données ?>
    <?php section('navbar', ''); ?>
<?php fin_section('contenu'); ?>
```

### Protection XSS

```php
<!-- Mauvais - Affiche le HTML/JS brut -->
<p><?php echo $utilisateur->nom; ?></p>

<!-- Bon - Échappe la valeur -->
<p><?php echo e($utilisateur->nom); ?></p>

<!-- Ou avec la fonction raccourcie -->
<p><?php echo e($donnees['titre']); ?></p>
```

---

## 🎮 Contrôleurs

### Classe de Base

Tous les contrôleurs héritent de `BaseControleur`:

```php
namespace App\Controleurs;

use App\BaseControleur;

class PostControleur extends BaseControleur
{
    public function index()
    {
        return $this->afficher('posts.index', [
            'posts' => Post::all()
        ]);
    }
}
```

### Méthodes Utiles

#### Afficher une Vue

```php
// Afficher directement
$this->afficher('posts.index', ['posts' => $posts]);

// Ou retourner le contenu
return $this->rendre('posts.index', ['posts' => $posts]);
```

#### Réponse JSON

```php
public function api()
{
    return $this->json([
        'status' => 'success',
        'data' => []
    ], 200);
}
```

#### Redirection

```php
$this->rediriger('/');              // 302
$this->rediriger('/dashboard', 301);  // Redirection permanente
```

#### Validation

```php
$erreurs = $this->valider($_POST, [
    'nom' => 'requis|min:3|max:100',
    'email' => 'requis|email',
    'age' => 'numero'
]);

if (!empty($erreurs)) {
    return $this->json($this->erreurs($erreurs), 422);
}
```

#### Gestion des Sessions

```php
// Récupérer
$user = $this->session('user');

// Définir
$this->definirSession('user', $user);

// Tous les sessions
$all = $this->session();
```

#### Données Anciennes (Formulaires)

```php
// Sauvegarder après soumission
$this->sauvegarderAncienInputs();
$this->flash('erreur', 'Erreur de validation');
$this->rediriger('/formulaire');

// Dans la vue
<input type="text" value="<?php echo ancien('email'); ?>">
```

#### Messages Flash

```php
// Enregistrer
$this->flash('succes', 'Post créé avec succès!');
$this->rediriger('/posts');

// Afficher dans la vue
<?php if (flash('succes')): ?>
    <div class="alerte succes"><?php echo flash('succes'); ?></div>
<?php endif; ?>
```

---

## 📝 Formulaires

### Classe Formulaire Helper

```php
use Core\Formulaire;

$form = new Formulaire($erreurs, $anciens);

// Dans la vue:
<?php
$form = new Formulaire($_SESSION['erreurs'] ?? [], $_SESSION['ancien'] ?? []);
?>

<form method="POST">
    <?php echo $form->texte('nom', 'Votre nom'); ?>
    <?php echo $form->email('email', 'Email'); ?>
    <?php echo $form->textarea('message', 'Message'); ?>
    <?php echo $form->soumettre('Envoyer'); ?>
</form>
```

---

## 🔐 Validation

### Règles Disponibles

```php
$regles = [
    'nom' => 'requis|min:3|max:100',
    'email' => 'requis|email',
    'age' => 'numero',
    'telephone' => 'requis|min:10'
];

$erreurs = $this->valider($_POST, $regles);
```

---

## 💡 Helpers Globales

### Functions Disponibles

```php
// URLs
url('/contact')              // Génère une URL absolue

// Formulaires
input('nom')                // Récupère $_REQUEST['nom']
ancien('email')             // Ancien input sauvegardé
flash('succes')             // Message flash

// Sections Vue
etendre('layout')           // Définit le layout parent
debut_section('nom')        // Débute une section
fin_section('nom')          // Termine une section
section('contenu')          // Affiche une section

// Protection
e($valeur)                 // Échappe pour XSS
```

---

## 📚 Exemples Complets

### Page Simple

**routes/web.php:**

```php
Routeur::obtenir('/about', 'PageControleur@about')->nom('about');
```

**app/Controleurs/PageControleur.php:**

```php
public function about()
{
    return $this->afficher('pages.about', [
        'titre' => 'À propos'
    ]);
}
```

**app/Vues/pages/about.php:**

```php
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
    <h1><?php echo e($titre); ?></h1>
    <p>Contenu à propos...</p>
<?php fin_section('contenu'); ?>
```

### API JSON

```php
public function api()
{
    return $this->json([
        'methode' => 'GET',
        'path' => '/api/utilisateurs',
        'utilisateurs' => []
    ]);
}
```

---

## ✨ Points Forts Phase 2

✅ Routage flexible et puissant
✅ Système de vues avec héritage
✅ Contrôleurs avec helpers
✅ Validation intégrée
✅ Gestion des sessions et flash
✅ Protection XSS automatique
✅ Formulaires avec validation
✅ 100% en français
✅ Facile à apprendre et utiliser

---

**Happy coding! 🚀**
