# Core\Modele - API Reference

Mini-ORM inspiré d'Eloquent pour CRUD basique avec requêtes préparées.

## Overview

- Résolution automatique du nom de table (`Article` → `articles`)
- Requêtes préparées via `BaseBD`
- Chaînage simple `ou()` / `et()`
- CRUD: `creer`, `sauvegarder`, `supprimer`
- Sérialisation: `enTableau`, `toArray`, `enJSON`

> Pas de relations, pagination, ni jointures avancées dans cette version.

## Propriétés principales

- `protected string $table` : nom de la table (dérivé du nom de classe si absent)
- `protected string $clesPrimaire = 'id'`
- `protected array $donnees` : attributs courants
- `protected array $conditions`, `$parametres` : clauses WHERE et bindings

## Méthodes statiques

### tout(): array

Retourne tous les enregistrements sous forme d'instances du modèle.

```php
$articles = Article::tout();
```

### trouver(int|string $id): ?self

Récupère par clé primaire (LIMIT 1).

```php
$article = Article::trouver(1);
```

### ou(string $colonne, string|mixed $operateur = '=', mixed $valeur = null): self

Crée une instance avec une clause WHERE initiale. Si `$valeur` est omis, l'opérateur vaut `=`.

```php
Article::ou('auteur_id', 1)->obtenir();
Article::ou('created_at', '>', '2026-01-01')->premier();
```

### creer(array $donnees): self

Insère un nouvel enregistrement, hydrate l'instance (avec l'ID généré) et retourne le modèle.

```php
$article = Article::creer([
    'titre' => 'Mon Article',
    'contenu' => 'Contenu...'
]);
```

## Méthodes d'instance (query)

### et(string $colonne, string|mixed $operateur = '=', mixed $valeur = null): self

Ajoute une clause AND à l'instance courante.

```php
Article::ou('auteur_id', 1)->et('actif', 1)->obtenir();
```

### obtenir(): array

Exécute le SELECT et retourne un tableau d'instances hydratées.

### premier(): ?self

Retourne le premier résultat (ou `null`). La même instance est hydratée.

## Persistance

### sauvegarder(): void

Insert ou update selon `existe`. Utilise `inserer()` ou `mettreAJour()` en interne.

```php
$article = Article::trouver(1);
$article->titre = 'Nouveau titre';
$article->sauvegarder();
```

### supprimer(): void

Supprime l'enregistrement courant. Lève une exception si l'instance n'existe pas en base.

```php
$article = Article::trouver(1);
$article->supprimer();
```

## Accès aux attributs

- `__get($cle)` / `__set($cle, $valeur)` pour lire/écrire les colonnes
- `__isset($cle)` pour tester la présence
- `enTableau()` / `toArray()` : export en tableau associatif
- `enJSON()` : export JSON (UTF-8 non échappé)

```php
$data = $article->toArray();
echo $article->enJSON();
```

## Exemple CRUD complet

```php
// CREATE
$article = Article::creer([
    'titre' => 'Test',
    'contenu' => 'Contenu'
]);

// READ
$article = Article::trouver($article->id);

// UPDATE
$article->titre = 'Modifié';
$article->sauvegarder();

// DELETE
$article->supprimer();
```

## Notes & limitations

- Sécurité: toutes les requêtes passent par des prepared statements.
- Dérivation du nom de table: `NomModele` → `nom_modele`s (snake_case + pluriel simple).
- Pas de `orderBy`, `limit`, `join`, `relations` dans cette version.

---

[← Retour à INDEX](INDEX.md)
