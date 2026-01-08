# Core\Requete - API Reference

Classe pour accéder aux données de la requête HTTP.

## Méthodes

### input(string $key, mixed $default = null): mixed

Récupère la valeur d'un champ de formulaire ou query parameter.

```php
$email = $request->input('email');
$page = $request->input('page', 1); // Avec défaut
```

**Paramètres:**

- `$key` - Nom du champ
- `$default` - Valeur par défaut

### tous(): array

Retourne tous les inputs (POST + GET).

```php
$data = $request->tous();
// ['email' => 'john@example.com', 'name' => 'John']
```

### query(string $key, mixed $default = null): mixed

Récupère une valeur de query string (?key=value).

```php
$page = $request->query('page', 1);
$search = $request->query('q');
```

### param(string $key): mixed

Récupère un paramètre d'URL ({id}, {slug}).

```php
// URL: /articles/5
$id = $request->param('id'); // 5
```

### method(): string

Récupère la méthode HTTP.

```php
$method = $request->method(); // GET, POST, PUT, DELETE
```

### header(string $key, mixed $default = null): mixed

Récupère un header HTTP.

```php
$accept = $request->header('Accept');
$auth = $request->header('Authorization');
```

### file(string $key): array|null

Récupère un fichier uploadé.

```php
$file = $request->file('image');
// ['name' => 'photo.jpg', 'tmp_name' => '/tmp/...', 'size' => 1024]
```

### is(string $method): bool

Vérifie la méthode HTTP.

```php
if ($request->is('POST')) {
    // Formulaire soumis
}
```

### isJson(): bool

Vérifie si la requête demande du JSON.

```php
if ($request->isJson()) {
    return $this->json(['status' => 'ok']);
}
```

---

## Exemples Complets

### Récupérer les Données d'un Formulaire

```php
public function store(Requete $request): string
{
    $email = $request->input('email');
    $name = $request->input('name');
    $newsletter = $request->input('newsletter', false);

    $user = User::create([
        'email' => $email,
        'name' => $name,
        'newsletter' => (bool) $newsletter
    ]);

    return $this->json(['id' => $user->id], 201);
}
```

### Gérer les Fichiers

```php
public function upload(Requete $request): string
{
    $file = $request->file('image');

    if (!$file) {
        return $this->json(['error' => 'No file'], 400);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = time() . '.' . $ext;

    move_uploaded_file($file['tmp_name'], 'public/uploads/' . $newName);

    return $this->json(['url' => '/uploads/' . $newName]);
}
```

### Pagination

```php
public function index(Requete $request): string
{
    $page = $request->query('page', 1);
    $perPage = $request->query('per_page', 10);

    $items = Item::paginer($perPage);

    return $this->afficher('items.index', [
        'items' => $items->items,
        'total' => $items->total,
        'page' => $page
    ]);
}
```

---

[← Retour à INDEX](INDEX.md)
