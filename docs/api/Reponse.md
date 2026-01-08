# Core\Reponse - API Reference

Classe pour construire les réponses HTTP.

## Méthodes

### json(array $data, int $code = 200, array $headers = []): string

Retourne une réponse JSON avec le code HTTP.

```php
return $this->json(['status' => 'success'], 200);
return $this->json(['error' => 'Not found'], 404);
```

### afficher(string $view, array $data = []): string

Retourne une vue HTML avec les données.

```php
return $this->afficher('articles.index', [
    'articles' => $articles,
    'total' => $total
]);
```

### rediriger(string $url, int $code = 302): string

Redirige vers une URL.

```php
return $this->rediriger('/articles');
return $this->rediriger('/old-url', 301); // Permanent
```

### erreur(int $code, string $message = ''): string

Retourne une erreur HTTP.

```php
return $this->erreur(404, 'Article non trouvé');
return $this->erreur(403, 'Accès refusé');
return $this->erreur(500, 'Erreur serveur');
```

### fichier(string $path, string $filename = null): void

Télécharge un fichier.

```php
return $this->fichier('storage/export.csv', 'data.csv');
```

### flash(string $key, string $message): void

Ajoute un message flash.

```php
$this->flash('succes', 'Article créé!');
return $this->rediriger('/articles');
```

---

## Exemples

### Response JSON

```php
public function show(Requete $request): string
{
    $article = Article::trouver($request->param('id'));

    if (!$article) {
        return $this->json(['error' => 'Not found'], 404);
    }

    return $this->json([
        'id' => $article->id,
        'titre' => $article->titre,
        'created_at' => $article->created_at
    ]);
}
```

### Réponse Vue

```php
public function index(Requete $request): string
{
    $articles = Article::tous();

    return $this->afficher('articles.index', [
        'articles' => $articles,
        'count' => count($articles)
    ]);
}
```

---

[← Retour à INDEX](INDEX.md)
