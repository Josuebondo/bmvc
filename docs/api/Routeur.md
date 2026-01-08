# Core\Routeur - API Reference

Système de routing pour définir les routes HTTP.

## Méthodes Principales

### obtenir(string $path, string|callable $action): Route

Définit une route GET.

```php
Routeur::obtenir('/', 'PageControleur@accueil');
Routeur::obtenir('/articles/{id}', 'ArticleControleur@show');
```

### publier(string $path, string|callable $action): Route

Définit une route POST.

```php
Routeur::publier('/articles', 'ArticleControleur@store');
Routeur::publier('/login', 'AuthControleur@login');
```

### mettre(string $path, string|callable $action): Route

Définit une route PUT.

```php
Routeur::mettre('/articles/{id}', 'ArticleControleur@update');
```

### supprimer(string $path, string|callable $action): Route

Définit une route DELETE.

```php
Routeur::supprimer('/articles/{id}', 'ArticleControleur@destroy');
```

### patcher(string $path, string|callable $action): Route

Définit une route PATCH.

```php
Routeur::patcher('/articles/{id}', 'ArticleControleur@patch');
```

### tous(string $path, string|callable $action): Route

Définit une route pour toutes les méthodes.

```php
Routeur::tous('/api/data', 'APIControleur@data');
```

### groupe(array $options, callable $callback): void

Groupe plusieurs routes.

```php
Routeur::groupe(['prefixe' => 'api'], function() {
    Routeur::obtenir('/articles', 'APIControleur@articles');
    Routeur::publier('/articles', 'APIControleur@store');
});
```

### nom(string $name): Route

Nomme une route.

```php
Routeur::obtenir('/articles/{id}', 'ArticleControleur@show')
    ->nom('articles.show');
```

### middleware(string|array $middlewares): Route

Ajoute des middleware à une route.

```php
Routeur::publier('/articles', 'ArticleControleur@store')
    ->middleware('auth');

Routeur::groupe(['middleware' => ['auth', 'admin']], function() {
    // ...
});
```

---

## Exemples

### Routes Simples

```php
// GET
Routeur::obtenir('/articles', 'ArticleControleur@index');
Routeur::obtenir('/articles/{id}', 'ArticleControleur@show');

// POST
Routeur::publier('/articles', 'ArticleControleur@store');

// PUT
Routeur::mettre('/articles/{id}', 'ArticleControleur@update');

// DELETE
Routeur::supprimer('/articles/{id}', 'ArticleControleur@destroy');
```

### Routes Groupées

```php
// Groupe API
Routeur::groupe(['prefixe' => 'api'], function() {
    Routeur::obtenir('/articles', 'APIControleur@articles');
    Routeur::obtenir('/articles/{id}', 'APIControleur@article');
});
// URLs: /api/articles, /api/articles/{id}

// Groupe avec Middleware
Routeur::groupe(['middleware' => 'auth'], function() {
    Routeur::obtenir('/dashboard', 'DashboardControleur@index');
    Routeur::obtenir('/profile', 'ProfileControleur@show');
});
```

### Routes Nommées

```php
Routeur::obtenir('/articles/{id}', 'ArticleControleur@show')
    ->nom('articles.show');

// Dans la vue
<a href="<?php echo url('articles.show', ['id' => $article->id]); ?>">
    Lire
</a>
```

---

[← Retour à INDEX](INDEX.md)
