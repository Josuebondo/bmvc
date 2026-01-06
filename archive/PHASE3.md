# 📊 PHASE 3: Base de Données & ORM

## 🎯 Aperçu

La Phase 3 ajoute une couche de base de données robuste au framework BMVC avec:

- **BaseBD**: Connexion PDO singleton
- **Modele**: Mini-ORM inspiré d'Eloquent
- **Migration**: Système de versionnement (bonus)

---

## 1️⃣ Connexion Base de Données (BaseBD)

### Configuration

Modifiez le fichier `.env`:

```env
# Base de données
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=bmvc
DB_USERNAME=root
DB_PASSWORD=
```

### Utilisation basique

```php
use Core\BaseBD;

// Obtenir l'instance unique (singleton)
$bd = BaseBD::obtenir();

// Récupérer tous les résultats
$articles = $bd->tous("SELECT * FROM articles");

// Récupérer une ligne
$article = $bd->une("SELECT * FROM articles WHERE id = :id", [':id' => 1]);

// Exécuter une requête
$bd->executer("INSERT INTO articles (titre) VALUES (?)", ['Mon Article']);

// Obtenir le dernier ID inséré
$id = $bd->dernierInsertId();
```

### Transactions

```php
$bd = BaseBD::obtenir();

try {
    $bd->commencer();

    $bd->executer("INSERT INTO articles (titre) VALUES (?)", ['Article 1']);
    $bd->executer("INSERT INTO articles (titre) VALUES (?)", ['Article 2']);

    $bd->valider(); // Commit
} catch (Exception $e) {
    $bd->annuler(); // Rollback
}
```

### Drivers supportés

- **MySQL**: `DB_CONNECTION=mysql`
- **SQLite**: `DB_CONNECTION=sqlite`
- **PostgreSQL**: `DB_CONNECTION=pgsql`

---

## 2️⃣ ORM (Modele)

### Créer un modèle

```php
// app/Modeles/Article.php
namespace App\Modeles;

use Core\Modele;

class Article extends Modele
{
    protected string $table = 'articles';
}
```

Le nom de la table se déduit automatiquement du nom de classe si non défini.

### Opérations CRUD

#### Créer (Create)

```php
use App\Modeles\Article;

// Méthode 1: statique
$article = Article::creer([
    'titre' => 'PHP 8.1',
    'contenu' => 'Découvrez les nouvelles features'
]);

// Méthode 2: instance
$article = new Article();
$article->titre = 'PHP 8.1';
$article->contenu = 'Découvrez les nouvelles features';
$article->sauvegarder();
```

#### Lire (Read)

```php
// Tous les enregistrements
$articles = Article::tout();

// Par ID
$article = Article::trouver(1);

// Avec conditions
$article = Article::ou('titre', 'PHP')
    ->et('contenu', 'features')
    ->premier();

// Tous les résultats filtrés
$articles = Article::ou('titre', 'like', '%PHP%')->obtenir();
```

#### Mettre à jour (Update)

```php
$article = Article::trouver(1);
$article->titre = 'Nouveau titre';
$article->sauvegarder();
```

#### Supprimer (Delete)

```php
$article = Article::trouver(1);
$article->supprimer();
```

### Opérateurs WHERE

```php
// Égal (=)
Article::ou('id', 1)->premier()

// Comparaison
Article::ou('id', '>', 5)->obtenir()
Article::ou('id', '>=', 10)->obtenir()
Article::ou('titre', 'like', '%PHP%')->obtenir()

// Chaîner les conditions (ET)
Article::ou('id', '>', 1)
    ->et('titre', 'like', '%Framework%')
    ->obtenir()
```

### Accès aux attributs

```php
$article = Article::trouver(1);

// Accès direct (magic methods)
echo $article->titre;      // Affiche le titre
$article->contenu = '...'; // Modifie le contenu
$article->sauvegarder();   // Sauvegarde

// Convertir en tableau
$donnees = $article->toArray();

// Convertir en JSON
$json = $article->toJson();
```

---

## 3️⃣ Utilisation dans les contrôleurs

### ArticleControleur avec BD

```php
namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\Article;

class ArticleControleur extends BaseControleur
{
    public function index()
    {
        $articles = Article::tout();

        return $this->afficher('articles.index', [
            'articles' => $articles,
            'titre' => 'Tous les Articles'
        ]);
    }

    public function voir($id)
    {
        $article = Article::trouver($id);

        if (!$article) {
            throw new \Core\Exceptions\HttpNotFoundException("Article non trouvé");
        }

        return $this->afficher('articles.voir', [
            'article' => $article->toArray()
        ]);
    }

    public function creer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $erreurs = $this->valider($_POST, [
                'titre' => 'requis|min:5|max:200',
                'contenu' => 'requis|min:20'
            ]);

            if (empty($erreurs)) {
                Article::creer([
                    'titre' => $_POST['titre'],
                    'contenu' => $_POST['contenu']
                ]);

                $this->flash('succes', 'Article créé avec succès!');
                $this->rediriger('/articles');
            } else {
                $this->sauvegarderAncienInputs();
                $this->sauvegarderErreurs($erreurs);
                $this->rediriger('/articles/creer');
            }
        }

        return $this->afficher('articles.creer');
    }
}
```

### ContactControleur avec BD

```php
namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\Contact;

class ContactControleur extends BaseControleur
{
    public function formulaire()
    {
        return $this->afficher('contact.formulaire');
    }

    public function envoyer()
    {
        $erreurs = $this->valider($_POST, [
            'nom' => 'requis|min:3',
            'email' => 'requis|email',
            'message' => 'requis|min:10'
        ]);

        if (empty($erreurs)) {
            Contact::creer([
                'nom' => $_POST['nom'],
                'email' => $_POST['email'],
                'message' => $_POST['message']
            ]);

            return $this->json([
                'succes' => true,
                'message' => 'Votre message a été envoyé!'
            ]);
        }

        return $this->json([
            'succes' => false,
            'erreurs' => $erreurs
        ], 422);
    }
}
```

---

## 4️⃣ Gestion des erreurs BD

### Mode Debug vs Production

En `.env`:

```env
APP_DEBUG=true   # Affiche les détails d'erreur
APP_DEBUG=false  # Cache les détails en production
```

### Exemple d'erreur

```php
try {
    $article = Article::trouver(999);
    if (!$article) {
        throw new Exception("Article non trouvé");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
```

---

## 5️⃣ Migrations (Bonus)

### Créer une migration

```php
// database/migrations/2024_01_05_create_articles_table.php

namespace Database\Migrations;

use Core\Migration;

class CreerTableArticles extends Migration
{
    public function vers(): void
    {
        // Créer la table
        $sql = "CREATE TABLE articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titre VARCHAR(200) NOT NULL,
            contenu LONGTEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $this->connexion->exec($sql);
    }

    public function retour(): void
    {
        // Annuler la migration
        $this->supprimerTable('articles');
    }
}
```

---

## 📋 Checklist d'implémentation

- ✅ BaseBD: Connexion PDO singleton
- ✅ Modele: CRUD complet (tout, trouver, creer, mettreAJour, supprimer)
- ✅ WHERE conditions: ou(), et(), obtenir()
- ✅ Article & Contact modèles
- ✅ Intégration avec contrôleurs
- ⏳ Migration: Système complet
- ⏳ Relations: belongsTo, hasMany

---

## 📚 Ressources

- [PDO PHP Documentation](https://www.php.net/manual/en/book.pdo.php)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Prepared Statements](https://www.php.net/manual/en/pdo.prepared-statements.php)
