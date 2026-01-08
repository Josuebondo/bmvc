# ⚡ Chapitre 3: Quick Start - Premières Applications

**Construisez votre première vraie application avec BMVC**

---

## 🎯 Objectifs de ce Chapitre

- Créer une application complète (Blog mini)
- Comprendre le pattern MVC en pratique
- Utiliser les contrôleurs, modèles et vues
- Gérer les routes et paramètres

**Temps estimé:** 20-30 minutes

---

## 📋 Application 1: Blog Mini

### Étape 1: Créer la Structure

Lancez les commandes CLI pour générer les fichiers:

```bash
php bmvc make:controller ArticleController
php bmvc make:model Article
php bmvc make:migration CreateArticlesTable
```

Ou les raccourcis:

```bash
php bmvc -cc ArticleController
php bmvc -cm Article
php bmvc -cmg CreateArticlesTable
```

### Étape 2: Définir les Routes

Fichier: `routes/web.php`

```php
<?php

use Core\Routeur;

// Page d'accueil
Routeur::obtenir('/', 'PageControleur@accueil');

// Articles
Routeur::obtenir('/articles', 'ArticleControleur@index');         // Liste
Routeur::obtenir('/articles/nouveau', 'ArticleControleur@create'); // Formulaire
Routeur::publier('/articles', 'ArticleControleur@store');          // Enregistrer
Routeur::obtenir('/articles/{id}', 'ArticleControleur@show');      // Détail
Routeur::obtenir('/articles/{id}/editer', 'ArticleControleur@edit'); // Édition
Routeur::mettre('/articles/{id}', 'ArticleControleur@update');     // Mettre à jour
Routeur::supprimer('/articles/{id}', 'ArticleControleur@destroy'); // Supprimer
```

### Étape 3: Créer le Modèle

Fichier: `app/Modeles/Article.php`

```php
<?php

namespace App\Modeles;

use Core\Modele;

class Article extends Modele
{
    protected string $table = 'articles';

    protected array $fillable = ['titre', 'contenu', 'auteur'];
}
```

### Étape 4: Créer le Contrôleur

Fichier: `app/Controleurs/ArticleControleur.php`

```php
<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\Article;
use Core\Requete;
use Core\Reponse;

class ArticleControleur extends BaseControleur
{
    // Afficher la liste des articles
    public function index(Requete $request, Reponse $response): string
    {
        $articles = Article::tous(); // Récupérer tous les articles

        return $this->afficher('articles/index', [
            'articles' => $articles
        ]);
    }

    // Afficher le formulaire de création
    public function create(Requete $request, Reponse $response): string
    {
        return $this->afficher('articles/create');
    }

    // Enregistrer un nouvel article
    public function store(Requete $request, Reponse $response): string
    {
        $donnees = $request->tous();

        // Validation
        $erreurs = $this->valider($donnees, [
            'titre' => 'requis|min:3|max:100',
            'contenu' => 'requis|min:10',
            'auteur' => 'requis'
        ]);

        if (!empty($erreurs)) {
            $this->flash('erreur', 'Erreurs de validation');
            return $this->rediriger('/articles/nouveau');
        }

        // Créer l'article
        Article::creer($donnees);

        $this->flash('succes', 'Article créé avec succès!');
        return $this->rediriger('/articles');
    }

    // Afficher un article détaillé
    public function show(Requete $request, Reponse $response): string
    {
        $id = $request->param('id');
        $article = Article::trouver($id);

        if (!$article) {
            return $this->erreur(404, 'Article non trouvé');
        }

        return $this->afficher('articles/show', [
            'article' => $article
        ]);
    }

    // Afficher le formulaire d'édition
    public function edit(Requete $request, Reponse $response): string
    {
        $id = $request->param('id');
        $article = Article::trouver($id);

        if (!$article) {
            return $this->erreur(404, 'Article non trouvé');
        }

        return $this->afficher('articles/edit', [
            'article' => $article
        ]);
    }

    // Mettre à jour un article
    public function update(Requete $request, Reponse $response): string
    {
        $id = $request->param('id');
        $article = Article::trouver($id);

        if (!$article) {
            return $this->erreur(404, 'Article non trouvé');
        }

        $donnees = $request->tous();

        $article->mettre_a_jour($donnees);

        $this->flash('succes', 'Article mis à jour!');
        return $this->rediriger('/articles/' . $id);
    }

    // Supprimer un article
    public function destroy(Requete $request, Reponse $response): string
    {
        $id = $request->param('id');
        $article = Article::trouver($id);

        if (!$article) {
            return $this->erreur(404, 'Article non trouvé');
        }

        $article->supprimer();

        $this->flash('succes', 'Article supprimé!');
        return $this->rediriger('/articles');
    }
}
```

### Étape 5: Créer les Vues

#### Vue 1: Liste des articles

Fichier: `app/Vues/articles/index.php`

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Articles</title>
    <style>
      body {
        font-family: Arial;
        margin: 20px;
      }
      table {
        width: 100%;
        border-collapse: collapse;
      }
      th,
      td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
      }
      th {
        background: #f0f0f0;
      }
      a {
        color: #0066cc;
        text-decoration: none;
      }
      .btn {
        padding: 8px 15px;
        background: #0066cc;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }
    </style>
  </head>
  <body>
    <h1>📝 Articles</h1>
    <a href="/articles/nouveau" class="btn">+ Nouveau Article</a>

    <table>
      <thead>
        <tr>
          <th>Titre</th>
          <th>Auteur</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($articles as $article): ?>
        <tr>
          <td><?php echo e($article->titre); ?></td>
          <td><?php echo e($article->auteur); ?></td>
          <td>
            <a href="/articles/<?php echo $article->id; ?>">Voir</a>
            <a href="/articles/<?php echo $article->id; ?>/editer">Éditer</a>
            <form
              method="POST"
              action="/articles/<?php echo $article->id; ?>"
              style="display:inline;"
            >
              <input type="hidden" name="_method" value="DELETE" />
              <button type="submit" onclick="return confirm('Confirmer?')">
                Supprimer
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </body>
</html>
```

#### Vue 2: Créer un article

Fichier: `app/Vues/articles/create.php`

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Nouvel Article</title>
    <style>
      body {
        font-family: Arial;
        margin: 20px;
        max-width: 600px;
      }
      form {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 4px;
      }
      label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
      }
      input,
      textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
      }
      button {
        margin-top: 20px;
        padding: 10px 20px;
        background: #0066cc;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }
    </style>
  </head>
  <body>
    <h1>📝 Créer un Article</h1>

    <form method="POST" action="/articles">
      <label>Titre</label>
      <input type="text" name="titre" required />

      <label>Contenu</label>
      <textarea name="contenu" rows="5" required></textarea>

      <label>Auteur</label>
      <input type="text" name="auteur" required />

      <button type="submit">Créer</button>
      <a href="/articles">← Retour</a>
    </form>
  </body>
</html>
```

#### Vue 3: Détail d'un article

Fichier: `app/Vues/articles/show.php`

```html
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo e($article->titre); ?></title>
    <style>
      body {
        font-family: Arial;
        margin: 20px;
        max-width: 800px;
      }
      .container {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 4px;
      }
      a {
        color: #0066cc;
      }
    </style>
  </head>
  <body>
    <h1><?php echo e($article->titre); ?></h1>
    <p>
      <strong>Auteur:</strong>
      <?php echo e($article->auteur); ?>
    </p>

    <div class="container">
      <p><?php echo nl2br(e($article->contenu)); ?></p>
    </div>

    <div>
      <a href="/articles/<?php echo $article->id; ?>/editer">Éditer</a>
      <a href="/articles">← Retour</a>
    </div>
  </body>
</html>
```

### Étape 6: Tester l'Application

1. Lancez le serveur: `php bmvc -d`
2. Allez à: `http://localhost:8000/articles`
3. Créez un article
4. Visionnez la liste
5. Modifiez et supprimez

✅ **Bravo!** Vous avez créé une application blog complète!

---

## 🎨 Concepts Clés Expliqués

### Pattern MVC en Pratique

```
Navigateur (User)
    ↓
Route (routes/web.php)
    ↓
Controller (ArticleControleur)
    ↓
Model (Article)
    ↓
View (articles/index.php)
    ↓
HTML (Navigateur)
```

### Cycle Requête-Réponse

```
1. Utilisateur clique sur un lien
   ↓
2. Navigateur envoie une requête HTTP
   ↓
3. Routeur trouve la bonne route
   ↓
4. Contrôleur traite la logique
   ↓
5. Modèle communique avec la BD
   ↓
6. Vue affiche le résultat
   ↓
7. Réponse HTML envoyée au navigateur
```

### Les 4 Opérations CRUD

| Opération  | HTTP   | Route          | Méthode   | Description |
| ---------- | ------ | -------------- | --------- | ----------- |
| **C**reate | POST   | /articles      | store()   | Créer       |
| **R**ead   | GET    | /articles/{id} | show()    | Lire        |
| **U**pdate | PUT    | /articles/{id} | update()  | Modifier    |
| **D**elete | DELETE | /articles/{id} | destroy() | Supprimer   |

---

## 📱 Application 2: Liste TODO Rapide

Créons une application TODO minimaliste.

### Routes

```php
Routeur::obtenir('/todos', 'TodoControleur@index');
Routeur::publier('/todos', 'TodoControleur@store');
Routeur::supprimer('/todos/{id}', 'TodoControleur@destroy');
```

### Contrôleur (Simplifié)

```php
class TodoControleur extends BaseControleur
{
    public function index(Requete $request, Reponse $response): string
    {
        $todos = Todo::tous();
        return $this->afficher('todos/index', ['todos' => $todos]);
    }

    public function store(Requete $request, Reponse $response): string
    {
        Todo::creer(['titre' => $request->input('titre')]);
        return $this->rediriger('/todos');
    }

    public function destroy(Requete $request, Reponse $response): string
    {
        Todo::trouver($request->param('id'))->supprimer();
        return $this->rediriger('/todos');
    }
}
```

---

## 🔍 Points Clés à Retenir

✅ **Les Routes** définissent comment accéder à votre application

✅ **Les Contrôleurs** contiennent la logique métier

✅ **Les Modèles** communiquent avec la base de données

✅ **Les Vues** affichent les données en HTML

✅ **La Validation** protège vos données

✅ **Le Message Flash** affiche des messages temporaires

---

## 🎯 Prochaines Étapes

Vous comprenez maintenant le pattern MVC?

**Continuez votre apprentissage:**

👉 [Chapitre 4: Guide Complet d'Utilisation →](../usage/GUIDE_UTILISATION.md)

**Ou découvrez plus d'exemples:**

👉 [Chapitre 5: Exemples Pratiques →](../../examples/)

---

**Framework BMVC v1.0.0**

_Maîtrisez le MVC avec des exemples concrets_ 🚀
