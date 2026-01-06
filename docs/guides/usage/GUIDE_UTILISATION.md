# 📚 GUIDE D'UTILISATION - Comment Créer et Utiliser des Modules

Guide pratique étape par étape pour développer avec BMVC.

---

## 🎯 Créer un Module en 3 Étapes

### Étape 1: Générer le Module

```bash
php bmvc -cmd Produit
```

**Cela génère automatiquement:**

```
✓ Contrôleur: app/Controleurs/ProduitControleur.php
✓ Modèle:     app/Modeles/Produit.php
✓ Vue:        app/Vues/produit/index.php
✓ Routes:     Ajoutées automatiquement dans routes/web.php
```

---

### Étape 2: Créer la Migration (Schéma BD)

```bash
php bmvc -cmg CreateProduitsTable
```

**Éditer: `app/Migrations/[timestamp]_CreateProduitsTable.php`**

```php
<?php

namespace App\Migrations;

use Core\Migration;

class CreateProduitsTable extends Migration
{
    public function up(): void
    {
        // Décommenter et adapter:
        // DB::query("
        //     CREATE TABLE produits (
        //         id INT PRIMARY KEY AUTO_INCREMENT,
        //         nom VARCHAR(255) NOT NULL,
        //         description TEXT,
        //         prix DECIMAL(10,2),
        //         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        //     )
        // ");
    }

    public function down(): void
    {
        // DB::query("DROP TABLE IF EXISTS produits");
    }
}
```

---

### Étape 3: Adapter le Contrôleur

**Fichier: `app/Controleurs/ProduitControleur.php`**

#### Avant (Généré):

```php
<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\Produit;

class ProduitControleur extends BaseControleur
{
    public function index()
    {
        return vue('produit.index');
    }

    public function creer()
    {
        return vue('produit.creer');
    }

    public function enregistrer()
    {
        return vue('produit.enregistrer');
    }

    // ... autres méthodes
}
```

#### Après (Complété):

```php
<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\Produit;

class ProduitControleur extends BaseControleur
{
    /**
     * Afficher la liste des produits
     */
    public function index()
    {
        $produits = Produit::tout();

        return vue('produit.index', [
            'items' => $produits,
            'titre' => 'Liste des produits'
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function creer()
    {
        return vue('produit.creer');
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function enregistrer()
    {
        // 1. Récupérer les données
        $donnees = $this->request()->all();

        // 2. Valider
        $erreurs = $this->valider($donnees, [
            'nom' => 'requis|min:3',
            'description' => 'requis',
            'prix' => 'requis|numeric',
        ]);

        if (!empty($erreurs)) {
            return redirection('/produits/creer')
                ->avecErreurs($erreurs)
                ->avecEntrees($donnees);
        }

        // 3. Créer l'enregistrement
        Produit::creer([
            'nom' => $donnees['nom'],
            'description' => $donnees['description'],
            'prix' => $donnees['prix'],
        ]);

        // 4. Rediriger avec message
        return redirection('/produits')
            ->avec('succes', 'Produit créé avec succès!');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function editer()
    {
        $id = $this->request()->param('id');
        $produit = Produit::trouver($id);

        if (!$produit) {
            return redirection('/produits')
                ->avec('erreur', 'Produit non trouvé');
        }

        return vue('produit.editer', ['item' => $produit]);
    }

    /**
     * Mettre à jour un produit
     */
    public function mettreAJour()
    {
        $id = $this->request()->param('id');
        $donnees = $this->request()->all();

        // Validation
        $erreurs = $this->valider($donnees, [
            'nom' => 'requis|min:3',
            'prix' => 'requis|numeric',
        ]);

        if (!empty($erreurs)) {
            return redirection("/produits/{$id}/editer")
                ->avecErreurs($erreurs)
                ->avecEntrees($donnees);
        }

        // Mise à jour
        Produit::mettreAJour($id, [
            'nom' => $donnees['nom'],
            'description' => $donnees['description'],
            'prix' => $donnees['prix'],
        ]);

        return redirection('/produits')
            ->avec('succes', 'Produit mis à jour!');
    }

    /**
     * Supprimer un produit
     */
    public function supprimer()
    {
        $id = $this->request()->param('id');

        $produit = Produit::trouver($id);
        if (!$produit) {
            return redirection('/produits')
                ->avec('erreur', 'Produit non trouvé');
        }

        Produit::supprimer($id);

        return redirection('/produits')
            ->avec('succes', 'Produit supprimé!');
    }
}
```

---

## 📄 Créer les Vues

Les routes sont générées automatiquement. Il faut créer les vues correspondantes.

### Vue Index (Liste)

**Fichier: `app/Vues/produit/index.php`**

```php
<?php
section('titre', 'Produits');
?>

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Gestion des Produits</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="/produits/creer" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    <?php if (session()->has('succes')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->pull('succes') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tableau -->
    <?php if (!empty($items)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $produit): ?>
                        <tr>
                            <td><?= e($produit['id']) ?></td>
                            <td><?= e($produit['nom']) ?></td>
                            <td><?= substr(e($produit['description']), 0, 50) ?>...</td>
                            <td><?= number_format($produit['prix'], 2) ?> €</td>
                            <td>
                                <a href="/produits/<?= $produit['id'] ?>/editer"
                                   class="btn btn-sm btn-warning">
                                    ✏️ Éditer
                                </a>
                                <a href="/produits/<?= $produit['id'] ?>/supprimer"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Êtes-vous sûr?')">
                                    🗑️ Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            ℹ️ Aucun produit. <a href="/produits/creer">Créer le premier</a>
        </div>
    <?php endif; ?>
</div>
```

---

### Vue Créer (Formulaire)

**Fichier: `app/Vues/produit/creer.php`**

```php
<?php
section('titre', 'Créer un Produit');
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <h2>Ajouter un Produit</h2>

            <!-- Afficher les erreurs -->
            <?php if (!empty($erreurs)): ?>
                <div class="alert alert-danger">
                    <strong>Erreurs:</strong>
                    <ul class="mb-0">
                        <?php foreach ($erreurs as $champ => $messages): ?>
                            <li><?= implode(', ', $messages) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/produits/creer" class="needs-validation">
                <!-- Nom -->
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text"
                           class="form-control <?= isset($erreurs['nom']) ? 'is-invalid' : '' ?>"
                           id="nom"
                           name="nom"
                           value="<?= ancienne('nom') ?>"
                           required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control <?= isset($erreurs['description']) ? 'is-invalid' : '' ?>"
                              id="description"
                              name="description"
                              rows="4"
                              required><?= ancienne('description') ?></textarea>
                </div>

                <!-- Prix -->
                <div class="mb-3">
                    <label for="prix" class="form-label">Prix (€) *</label>
                    <input type="number"
                           step="0.01"
                           class="form-control <?= isset($erreurs['prix']) ? 'is-invalid' : '' ?>"
                           id="prix"
                           name="prix"
                           value="<?= ancienne('prix') ?>"
                           required>
                </div>

                <!-- Boutons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/produits" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
```

---

### Vue Éditer

**Fichier: `app/Vues/produit/editer.php`**

```php
<?php
section('titre', 'Éditer un Produit');
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <h2>Éditer: <?= e($item['nom']) ?></h2>

            <form method="POST" action="/produits/<?= $item['id'] ?>/editer">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom"
                           value="<?= e($item['nom']) ?>">
                </div>

                <div class="mb-3">
                    <label for="prix" class="form-label">Prix (€)</label>
                    <input type="number" step="0.01" class="form-control" id="prix"
                           name="prix" value="<?= $item['prix'] ?>">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"
                              rows="4"><?= e($item['description']) ?></textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/produits" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
```

---

## 🔧 Modèle (Modeles/Produit.php)

```php
<?php

namespace App\Modeles;

use Core\Modele;

class Produit extends Modele
{
    protected string $table = 'produits';

    /**
     * Récupérer tous les produits
     */
    public static function tout()
    {
        return (new self())->recuperer();
    }

    /**
     * Trouver un produit par ID
     */
    public static function trouver($id)
    {
        return (new self())->ou('id', $id)->premier();
    }

    /**
     * Créer un nouveau produit
     */
    public static function creer(array $donnees)
    {
        return (new self())->inserer($donnees);
    }

    /**
     * Mettre à jour un produit
     */
    public static function mettreAJour($id, array $donnees)
    {
        return (new self())
            ->ou('id', $id)
            ->modifier($donnees);
    }

    /**
     * Supprimer un produit
     */
    public static function supprimer($id)
    {
        return (new self())
            ->ou('id', $id)
            ->effacer();
    }
}
```

---

## 🌐 Routes Générées Automatiquement

Quand vous créez un module avec `php bmvc -cmd Produit`, ces routes sont ajoutées automatiquement:

```php
// GET    /produits              → index()       (Afficher la liste)
// GET    /produits/creer        → creer()       (Formulaire création)
// POST   /produits/creer        → enregistrer() (Traiter création)
// GET    /produits/{id}/editer  → editer()      (Formulaire édition)
// POST   /produits/{id}/editer  → mettreAJour() (Traiter édition)
// GET    /produits/{id}/supprimer → supprimer() (Traiter suppression)
```

---

## 🧪 Tester Votre Module

### 1. Démarrer le Serveur

```bash
php bmvc -d --port 8000
```

### 2. Accéder aux Routes

| Route                                     | Action               |
| ----------------------------------------- | -------------------- |
| `http://localhost:8000/produits`          | Liste des produits   |
| `http://localhost:8000/produits/creer`    | Ajouter un produit   |
| `http://localhost:8000/produits/1/editer` | Éditer le produit #1 |

### 3. Utiliser les Raccourcis CLI

```bash
# Créer rapidement
php bmvc -cmd Categorie
php bmvc -cmd Commande
php bmvc -cmd Panier

# Afficher l'aide
php bmvc -a
```

---

## 💡 Bonnes Pratiques

### ✅ À Faire

```php
// ✅ Valider les données
$erreurs = $this->valider($donnees, [
    'email' => 'requis|email',
    'nom' => 'requis|min:3',
]);

// ✅ Utiliser des helpers
echo e($data); // Échapper HTML
echo session()->has('clé');
echo ancienne('champ');

// ✅ Utiliser les redirections
return redirection('/chemin')
    ->avec('succes', 'Message')
    ->avecErreurs($erreurs);
```

### ❌ À Éviter

```php
// ❌ Injection SQL
"SELECT * FROM produits WHERE id = " . $id;

// ❌ XSS
<?= $donnees['nom'] ?>

// ❌ Pas de validation
Produit::creer($this->request()->all());
```

---

## 📊 Exemple Complet: Blog

```bash
# 1. Créer les modules
php bmvc -cmd Article
php bmvc -cmd Categorie
php bmvc -cmd Commentaire

# 2. Créer les migrations
php bmvc -cmg CreateArticlesTable
php bmvc -cmg CreateCategoriesTable
php bmvc -cmg CreateCommentsTable

# 3. Adapter les contrôleurs (ajouter la logique métier)
# 4. Créer les vues (formulaires, listes, détails)
# 5. Tester les routes
```

---

## 🎓 Résumé

**Pour créer un module complet:**

1. **Générer** → `php bmvc -cmd NomModule`
2. **Migrer** → `php bmvc -cmg CreateTableName`
3. **Adapter** → Éditer le contrôleur et le modèle
4. **Vues** → Créer creer.php et editer.php
5. **Tester** → `php bmvc -d` et accéder aux routes

**Le CLI génère automatiquement:**

- ✅ Contrôleur avec 6 méthodes CRUD
- ✅ Modèle avec table
- ✅ Vue index (liste)
- ✅ Routes dans routes/web.php

**Framework BMVC: Développement 10x plus rapide!** 🚀

---

**Version:** 1.0  
**Date:** 2024  
**État:** Production-Ready
