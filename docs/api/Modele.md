# 🗄️ Classe Modele

**ORM (Object-Relational Mapping) pour accès aux bases de données**

---

## 📖 Description

La classe `Modele` est la base de tous les modèles de données. Elle fournit une interface intuitive pour interagir avec la base de données sans écrire de SQL brut.

**Localisation:** `core/Modele.php`

---

## 🔧 Méthodes Principales

### Requêtes de Base

#### `tous($colonnes = ['*'])`

Récupère tous les enregistrements.

```php
$articles = Article::tous();
$articles = Article::tous(['id', 'titre', 'auteur']);
```

#### `trouver($id, $colonnes = ['*'])`

Trouve un enregistrement par ID.

```php
$article = Article::trouver(1);
$article = Article::trouver(1, ['titre', 'contenu']);

if (!$article) {
    // Not found
}
```

#### `oui($conditions)`

Récupère un enregistrement correspondant aux conditions.

```php
$article = Article::oui(['titre' => 'Mon Article']);
$article = Article::oui(['auteur' => 'Jean', 'statut' => 'publie']);

if ($article) {
    // Found
}
```

#### `ou($conditions)`

Récupère des enregistrements avec conditions OR.

```php
$articles = Article::ou(['statut' => 'brouillon', 'publier' => false]);
```

---

### Filtrage

#### `ou($colonnes, $operateur = '=', $valeur = null)`

Ajoute une condition OR à la requête.

```php
$articles = Article::ou('statut', '=', 'publie')
    ->ou('statut', '=', 'programme')
    ->tous();
```

#### `limite($nombre)`

Limite le nombre de résultats.

```php
$articles = Article::limite(10)->tous();
```

#### `decaler($nombre)`

Décale les résultats (pour pagination).

```php
$articles = Article::decaler(20)->limite(10)->tous();  // Résultats 20-30
```

#### `ordonner($colonne, $direction = 'ASC')`

Trie les résultats.

```php
$articles = Article::ordonner('date_creation', 'DESC')->tous();
$articles = Article::ordonner('titre', 'ASC')->tous();
```

#### `grouper($colonnes)`

Groupe les résultats.

```php
$articles = Article::grouper('auteur')->tous();
```

#### `distinct()`

Récupère des valeurs distinctes.

```php
$auteurs = Article::distinct()->tous();
```

---

### Agrégation

#### `compter()`

Compte les enregistrements.

```php
$total = Article::compter();
$publies = Article::oui(['statut' => 'publie'])->compter();
```

#### `somme($colonne)`

Calcule la somme.

```php
$total = Vente::somme('montant');
```

#### `moyenne($colonne)`

Calcule la moyenne.

```php
$avg = Note::moyenne('valeur');
```

#### `min($colonne)` / `max($colonne)`

Calcule min/max.

```php
$plus_ancien = Article::min('date_creation');
$plus_recent = Article::max('date_creation');
```

---

### Créer / Modifier / Supprimer

#### `creer($donnees)`

Crée un nouvel enregistrement.

```php
$article = Article::creer([
    'titre' => 'Mon Article',
    'contenu' => 'Contenu...',
    'auteur' => 'Jean'
]);

// $article->id contient l'ID inséré
echo $article->id;
```

#### `sauvegarder()`

Sauvegarde les modifications d'un modèle.

```php
$article = Article::trouver(1);
$article->titre = 'Nouveau titre';
$article->contenu = 'Nouveau contenu';
$article->sauvegarder();
```

#### `mettrAJour($donnees)`

Met à jour les enregistrements.

```php
// Mise à jour d'un enregistrement
$article = Article::trouver(1);
$article->mettrAJour(['titre' => 'Nouveau titre', 'statut' => 'publie']);

// Mise à jour en masse
Article::oui(['auteur' => 'Jean'])->mettrAJour(['statut' => 'archive']);
```

#### `supprimer()`

Supprime un enregistrement.

```php
$article = Article::trouver(1);
$article->supprimer();
```

#### `supprimerOu($conditions)`

Supprime les enregistrements correspondant aux conditions.

```php
Article::supprimerOu(['statut' => 'brouillon']);
Article::supprimerOu(['date_creation', '<', '2020-01-01']);
```

---

### Conversions

#### `enTable()`

Convertit le modèle en array associatif.

```php
$article = Article::trouver(1);
$data = $article->enTable();

// [
//   'id' => 1,
//   'titre' => 'Mon Article',
//   'contenu' => '...',
//   'auteur' => 'Jean'
// ]
```

#### `enJson()`

Convertit le modèle en JSON.

```php
$article = Article::trouver(1);
echo $article->enJson();

// {"id":1,"titre":"Mon Article",...}
```

#### `toArray()`

Alias de `enTable()` (compatible Laravel).

```php
$data = $article->toArray();
```

---

### Relations

#### `appartientA($modele, $cle_etrangere = null)`

Définie une relation "belongs to".

```php
class Commentaire extends Modele
{
    public function article()
    {
        return $this->appartientA('Article', 'article_id');
    }
}

$commentaire = Commentaire::trouver(1);
$article = $commentaire->article();
```

#### `aPlusieurs($modele, $cle_etrangere = null)`

Définie une relation "has many".

```php
class Article extends Modele
{
    public function commentaires()
    {
        return $this->aPlusieurs('Commentaire', 'article_id');
    }
}

$article = Article::trouver(1);
$commentaires = $article->commentaires();
```

---

## 📚 Exemples d'Utilisation

### CRUD Basique

```php
// CREATE
$article = Article::creer([
    'titre' => 'Mon Article',
    'contenu' => 'Contenu de l\'article',
    'auteur' => 'Jean'
]);

// READ
$article = Article::trouver($article->id);
echo $article->titre;

// UPDATE
$article->titre = 'Titre modifié';
$article->sauvegarder();

// DELETE
$article->supprimer();
```

### Requêtes Complexes

```php
// Articles publiés, triés par date, limité à 10
$articles = Article::oui(['statut' => 'publie'])
    ->ordonner('date_creation', 'DESC')
    ->limite(10)
    ->tous();

// Pagination
$page = 1;
$parPage = 10;
$articles = Article::decaler(($page - 1) * $parPage)
    ->limite($parPage)
    ->tous();

// Agrégation
$total = Article::compter();
$parAuteur = Article::grouper('auteur')->tous();
```

### Bulk Operations

```php
// Mettre à jour plusieurs enregistrements
Article::oui(['auteur' => 'Jean'])
    ->mettrAJour(['statut' => 'archive', 'date_archive' => date('Y-m-d')]);

// Supprimer plusieurs enregistrements
Article::supprimerOu(['date_creation', '<', '2020-01-01']);
```

### Relations

```php
// Récupérer les commentaires d'un article
$article = Article::trouver(1);
$commentaires = $article->commentaires();

foreach ($commentaires as $commentaire) {
    echo $commentaire->contenu;
}

// Créer un commentaire lié
$commentaire = Commentaire::creer([
    'article_id' => $article->id,
    'auteur' => 'Lecteur',
    'contenu' => 'Excellent article!'
]);

// Récupérer l'article d'un commentaire
$article = $commentaire->article();
```

---

## 📋 Propriétés

```php
// Propriété de table
protected static $table = 'articles';

// Clé primaire
protected static $cle_primaire = 'id';

// Colonnes remplissables
protected $fillable = ['titre', 'contenu', 'auteur'];

// Colonnes cachées
protected $hidden = ['password', 'token'];
```

---

## 🔗 Configuration du Modèle

```php
<?php

namespace App\Modeles;

use BMVC\Core\Modele;

class Article extends Modele
{
    // Nom de la table
    protected static $table = 'articles';

    // Colonne de clé primaire
    protected static $cle_primaire = 'id';

    // Colonnes remplissables
    protected $fillable = [
        'titre',
        'contenu',
        'auteur',
        'date_creation',
        'statut'
    ];

    // Colonnes à cacher (ex: lors de enTable())
    protected $hidden = [];

    // Relations
    public function commentaires()
    {
        return $this->aPlusieurs('Commentaire', 'article_id');
    }
}
```

---

## 📋 Cheat Sheet

```php
// Récupérer des données
Article::tous();                    // Tous
Article::trouver($id);              // Par ID
Article::oui(['col' => 'val']);     // Avec conditions

// Filtrer
->limite(10);                       // Limiter
->decaler(20);                      // Pagination
->ordonner('col', 'DESC');          // Tri

// Compter/Agréger
->compter();                        // Nombre total
->somme('col');                     // Somme
->moyenne('col');                   // Moyenne

// Modifier
Article::creer($data);              // Créer
$model->sauvegarder();              // Sauvegarder
$model->supprimer();                // Supprimer

// Convertir
$model->enTable();                  // Array
$model->enJson();                   // JSON
```

---

## 🧪 Tests

Voir `tests/ModeleTest.php` pour les tests complets.

```bash
php vendor/bin/phpunit tests/ModeleTest.php
```

---

## 📖 Voir aussi

- [Validation](Validation.md) - Valider les données avant de les sauvegarder
- [Guide Utilisation](../guides/usage/GUIDE_UTILISATION.md) - Exemples complets

---

**BMVC Framework v1.0.0** | [Retour à l'index](../INDEX.md)
