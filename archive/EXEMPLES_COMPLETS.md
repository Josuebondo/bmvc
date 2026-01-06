# 🧪 EXEMPLES CONCRETS - Tester BMVC

Ce fichier contient des exemples pratiques pour tester chaque partie du framework.

## 🧪 Exemple 1: Page Simple (Accueil)

### Contrôleur: `app/Controleurs/AccueilControleur.php`

```php
<?php

namespace App\Controleurs;

use Core\Requete;
use Core\Reponse;

class AccueilControleur
{
    /**
     * Page d'accueil
     */
    public function index(Requete $request, Reponse $response): string
    {
        return vue('accueil', [
            'titre' => 'Bienvenue dans BMVC',
            'description' => 'Un framework web moderne en français'
        ]);
    }
}
```

### Vue: `app/Vues/accueil.php`

```php
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
<div class="container py-5">
    <h1><?= e($titre) ?></h1>
    <p class="lead"><?= e($description) ?></p>

    <div class="mt-4">
        <a href="/exemple-formulaire" class="btn btn-primary">Voir le formulaire</a>
        <a href="/exemple-crud" class="btn btn-secondary">Voir le CRUD</a>
    </div>
</div>
<?php fin_section('contenu'); ?>
```

### Route: `routes/web.php`

```php
Routeur::get('/', [AccueilControleur::class, 'index']);
```

---

## 📝 Exemple 2: Formulaire avec Validation

### Contrôleur: `app/Controleurs/FormulaireControleur.php`

```php
<?php

namespace App\Controleurs;

use Core\Requete;
use Core\Reponse;

class FormulaireControleur
{
    /**
     * Afficher le formulaire
     */
    public function afficher(Requete $request, Reponse $response): string
    {
        return vue('formulaire.creer');
    }

    /**
     * Traiter le formulaire
     */
    public function traiter(Requete $request, Reponse $response): void
    {
        // Valider les données
        $validateur = validateur()->verifier($_POST, [
            'nom' => 'requis|min:3|max:100',
            'email' => 'requis|email',
            'message' => 'requis|min:10'
        ]);

        if ($validateur->echoue()) {
            redirect('/exemple-formulaire')->avec('erreurs', $validateur->erreurs());
            return;
        }

        // Traiter les données valides
        $donnees = [
            'nom' => $_POST['nom'],
            'email' => $_POST['email'],
            'message' => $_POST['message']
        ];

        // Sauvegarder ou envoyer par email
        // ...

        redirect('/exemple-formulaire')->avec('succes', 'Message envoyé avec succès!');
    }
}
```

### Vue: `app/Vues/formulaire/creer.php`

```php
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
<div class="container py-5">
    <h2>Formulaire de Contact</h2>

    <!-- Messages d'erreur -->
    <?php if (isset($_SESSION['erreurs'])): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($_SESSION['erreurs'] as $champ => $erreur): ?>
                    <li><?= e($champ) ?>: <?= e($erreur) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['erreurs']); ?>
    <?php endif; ?>

    <!-- Message de succès -->
    <?php if (isset($_SESSION['succes'])): ?>
        <div class="alert alert-success">
            <?= e($_SESSION['succes']) ?>
        </div>
        <?php unset($_SESSION['succes']); ?>
    <?php endif; ?>

    <!-- Formulaire -->
    <form method="POST" action="/formulaire/traiter" class="mt-4">
        <?= csrf() ?>

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>
<?php fin_section('contenu'); ?>
```

### Routes: `routes/web.php`

```php
Routeur::get('/exemple-formulaire', [FormulaireControleur::class, 'afficher']);
Routeur::post('/formulaire/traiter', [FormulaireControleur::class, 'traiter']);
```

---

## 📦 Exemple 3: CRUD Complet (Articles)

### Modèle: `app/Modeles/Article.php`

```php
<?php

namespace App\Modeles;

use Core\Modele;

class Article extends Modele
{
    protected string $table = 'articles';
}
```

### Contrôleur: `app/Controleurs/ArticleControleur.php`

```php
<?php

namespace App\Controleurs;

use App\Modeles\Article;
use Core\Requete;
use Core\Reponse;

class ArticleControleur
{
    /**
     * Lister tous les articles
     */
    public function index(Requete $request, Reponse $response): string
    {
        $articles = Article::tout();
        return vue('articles.index', ['articles' => $articles]);
    }

    /**
     * Afficher un article
     */
    public function show(Requete $request, Reponse $response): string
    {
        $id = $request->param('id');
        $article = Article::trouver($id);

        if (!$article) {
            http_response_code(404);
            return vue('erreur.404');
        }

        return vue('articles.show', ['article' => $article]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function creer(Requete $request, Reponse $response): string
    {
        return vue('articles.creer');
    }

    /**
     * Sauvegarder un nouvel article
     */
    public function sauvegarder(Requete $request, Reponse $response): void
    {
        // Valider
        $validateur = validateur()->verifier($_POST, [
            'titre' => 'requis|max:200',
            'contenu' => 'requis|min:20'
        ]);

        if ($validateur->echoue()) {
            redirect('/articles/creer')->avec('erreurs', $validateur->erreurs());
            return;
        }

        // Créer
        Article::creer([
            'titre' => $_POST['titre'],
            'contenu' => $_POST['contenu'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        redirect('/articles')->avec('succes', 'Article créé avec succès!');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function editer(Requete $request, Reponse $response): string
    {
        $id = $request->param('id');
        $article = Article::trouver($id);

        if (!$article) {
            http_response_code(404);
            return vue('erreur.404');
        }

        return vue('articles.editer', ['article' => $article]);
    }

    /**
     * Mettre à jour un article
     */
    public function mettreAJour(Requete $request, Reponse $response): void
    {
        $id = $request->param('id');

        // Valider
        $validateur = validateur()->verifier($_POST, [
            'titre' => 'requis|max:200',
            'contenu' => 'requis|min:20'
        ]);

        if ($validateur->echoue()) {
            redirect("/articles/$id/editer")->avec('erreurs', $validateur->erreurs());
            return;
        }

        // Mettre à jour
        Article::where('id', $id)->mettreAJour([
            'titre' => $_POST['titre'],
            'contenu' => $_POST['contenu']
        ]);

        redirect("/articles/$id")->avec('succes', 'Article modifié avec succès!');
    }

    /**
     * Supprimer un article
     */
    public function supprimer(Requete $request, Reponse $response): void
    {
        $id = $request->param('id');
        Article::where('id', $id)->supprimer();

        redirect('/articles')->avec('succes', 'Article supprimé avec succès!');
    }
}
```

### Routes: `routes/web.php`

```php
// Articles
Routeur::get('/exemple-crud', [ArticleControleur::class, 'index']);
Routeur::get('/articles', [ArticleControleur::class, 'index']);
Routeur::get('/articles/{id}', [ArticleControleur::class, 'show']);
Routeur::get('/articles/creer', [ArticleControleur::class, 'creer']);
Routeur::post('/articles', [ArticleControleur::class, 'sauvegarder']);
Routeur::get('/articles/{id}/editer', [ArticleControleur::class, 'editer']);
Routeur::put('/articles/{id}', [ArticleControleur::class, 'mettreAJour']);
Routeur::delete('/articles/{id}', [ArticleControleur::class, 'supprimer']);
```

### Vue: `app/Vues/articles/index.php`

```php
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
<div class="container py-5">
    <h2>Articles</h2>

    <a href="/articles/creer" class="btn btn-primary mb-3">+ Nouvel Article</a>

    <?php if (empty($articles)): ?>
        <p>Aucun article pour le moment.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?= e($article['id']) ?></td>
                        <td><a href="/articles/<?= $article['id'] ?>"><?= e($article['titre']) ?></a></td>
                        <td><?= e($article['created_at']) ?></td>
                        <td>
                            <a href="/articles/<?= $article['id'] ?>/editer" class="btn btn-sm btn-warning">Éditer</a>
                            <form method="POST" action="/articles/<?= $article['id'] ?>" style="display:inline;">
                                <?= csrf() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php fin_section('contenu'); ?>
```

### Vue: `app/Vues/articles/show.php`

```php
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
<div class="container py-5">
    <h2><?= e($article['titre']) ?></h2>
    <small class="text-muted"><?= e($article['created_at']) ?></small>

    <div class="mt-4">
        <?= e($article['contenu']) ?>
    </div>

    <div class="mt-4">
        <a href="/articles/<?= $article['id'] ?>/editer" class="btn btn-warning">Éditer</a>
        <a href="/articles" class="btn btn-secondary">Retour</a>
    </div>
</div>
<?php fin_section('contenu'); ?>
```

---

## 🧪 Table SQL pour Tester

```sql
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    contenu LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Données de test
INSERT INTO articles (titre, contenu) VALUES
('Mon premier article', 'Ceci est le contenu du premier article'),
('Second article', 'Contenu du deuxième article avec plus de détails'),
('Troisième article', 'Un article pour bien comprendre le fonctionnement du CRUD');
```

---

## 🚀 Comment Tester

### 1. Démarrer le serveur

```bash
php -S localhost:8000 -t public
```

### 2. Tester l'accueil

```
http://localhost:8000/
```

### 3. Tester le formulaire

```
http://localhost:8000/exemple-formulaire
```

### 4. Tester le CRUD

```
http://localhost:8000/articles
http://localhost:8000/articles/creer
http://localhost:8000/articles/1
```

---

## ✅ Ce que tu testeras

- ✅ Routes GET / POST / PUT / DELETE
- ✅ Contrôleurs et injection
- ✅ Vues et layouts
- ✅ Validation avec messages
- ✅ ORM (CRUD)
- ✅ Sessions et messages flash
- ✅ CSRF protection
- ✅ Protection XSS (echapper)

Tous les éléments clés du framework en action! 🎯
