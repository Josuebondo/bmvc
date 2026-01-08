# Core\Modele - API Reference

ORM pour interagir avec la base de données.

## Méthodes Statiques

### creer(array $data): self

Crée un nouvel enregistrement.

```php
$article = Article::creer([
    'titre' => 'Mon Article',
    'contenu' => 'Contenu...'
]);
```

### trouver(int $id): self|null

Récupère un enregistrement par ID.

```php
$article = Article::trouver(1);
```

### tous(): array

Récupère tous les enregistrements.

```php
$articles = Article::tous();
```

### premier(): self|null

Récupère le premier enregistrement.

```php
$article = Article::où('statut', '=', 'publié')->premier();
```

### compter(): int

Compte les enregistrements.

```php
$total = Article::compter();
$published = Article::où('statut', '=', 'publié')->compter();
```

### où(string $column, string $operator, mixed $value): Builder

Filtre les enregistrements.

```php
Article::où('auteur_id', '=', 1)->tous();
Article::où('titre', 'LIKE', '%PHP%')->tous();
Article::où('created_at', '>', '2026-01-01')->tous();
```

### ordonner(string $column, string $direction = 'ASC'): Builder

Trie les résultats.

```php
Article::ordonner('created_at', 'DESC')->tous();
Article::ordonner('titre')->tous(); // ASC par défaut
```

### limiter(int $limit): Builder

Limite le nombre de résultats.

```php
Article::limiter(10)->tous();
```

### decaler(int $offset): Builder

Saute des enregistrements.

```php
Article::limiter(10)->decaler(20)->tous(); // Page 3 (10 par page)
```

### paginer(int $perPage = 15): Pagination

Pagine les résultats.

```php
$articles = Article::paginer(15);
// $articles->items, $articles->total, $articles->pages
```

### chargerEager(...$relations): Builder

Charge les relations (évite N+1).

```php
$articles = Article::chargerEager('commentaires', 'auteur')->tous();
foreach ($articles as $article) {
    $article->commentaires; // Déjà chargés
}
```

## Méthodes d'Instance

### sauvegarder(): bool

Sauvegarde les modifications.

```php
$article = Article::trouver(1);
$article->titre = 'Nouveau Titre';
$article->sauvegarder();
```

### supprimer(): bool

Supprime l'enregistrement.

```php
$article = Article::trouver(1);
$article->supprimer();
```

### update(array $data): bool

Met à jour directement.

```php
$article->update(['titre' => 'Nouveau', 'statut' => 'publié']);
```

## Relations

### hasMany(string $class, string $foreignKey): Relation

Relation 1:N.

```php
public function commentaires() {
    return $this->hasMany('Commentaire', 'article_id');
}

$article->commentaires()->tous();
```

### belongsTo(string $class, string $foreignKey): Relation

Relation N:1.

```php
public function article() {
    return $this->belongsTo('Article', 'article_id');
}

$comment->article();
```

### hasMany... avec Condition

```php
$article->commentaires()->où('statut', '=', 'approuvé')->tous();
```

---

## Exemples

### CRUD Complet

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

### Query Builder

```php
// Articles publiés, triés par date, paginés
$articles = Article::où('statut', '=', 'publié')
    ->ordonner('created_at', 'DESC')
    ->paginer(10);

foreach ($articles->items as $article) {
    echo $article->titre;
}

echo "Page " . $articles->pageActuelle . " / " . $articles->pages;
```

---

[← Retour à INDEX](INDEX.md)
