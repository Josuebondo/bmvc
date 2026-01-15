# Core\Requete - API Reference

Encapsule la requête HTTP (GET/POST/FILES/headers/URL) et fournit des helpers utilisés par le routeur et les contrôleurs.

## Overview

- Inputs GET/POST unifiés via `input()` / `tous()`
- Paramètres d'URL (`param`, `definirParam`)
- Fichiers uploadés (`fichier` / `file`)
- Infos requête (`methode`, `chemin`, `url`, `entete`)
- Détections rapides (`estAjax`, `estApi`, `isJson`)

> L'instance est construite par le kernel; vous ne l'instanciez pas manuellement dans l'app.

## Données d'entrée

### input(string $cle, mixed $defaut = null): mixed

Cherche d'abord en POST puis en GET.

```php
$email = $request->input('email');
$page = $request->input('page', 1);
```

### tous(): array

Retourne la fusion GET + POST.

### query(string $cle, mixed $defaut = null): mixed

Alias de GET pur.

### obtenir(string $cle, mixed $defaut = null): mixed

Accès direct GET (équivalent à `query()`), retourne `$defaut` si absent.

### publier(string $cle, mixed $defaut = null): mixed

Alias POST pur.

### a(string $cle): bool

Présence de la clé en GET ou POST.

## Paramètres d'URL

### param(string $cle, mixed $defaut = null): mixed

Paramètre extrait du pattern de route (`{id}`, `{slug}`...).

```php
// /articles/5
$id = $request->param('id');
```

### definirParam(string $cle, mixed $valeur): void

Assigné par le routeur après matching.

## Fichiers uploadés

### fichier(string $nom): ?array

### file(string $nom): ?array (alias)

Retourne l'entrée `$_FILES[$nom]` ou `null`.

```php
$image = $request->file('image');
if ($image) {
    move_uploaded_file($image['tmp_name'], 'public/uploads/'.$image['name']);
}
```

## Informations requête

### methode(): string / method(): string

Méthode HTTP (`GET`, `POST`, ...).

### is(string $method): bool

Compare la méthode courante.

### chemin(): string

Chemin sans query string, nettoyé du préfixe d'application (`/BMVC/`), avec support `PATH_INFO`.

### url(): string

URI brute incluant la query string.

### entete(string $nom): ?string / header($nom, $defaut = null)

Accès aux headers (`Authorization`, `Accept`, ...).

## Détections

### estAjax(): bool

Vérifie `X-Requested-With = XMLHttpRequest`.

### estApi(): bool

True si un header `Authorization: Bearer ...` est présent.

### isJson(): bool

Détecte `Accept` ou `Content-Type` contenant `application/json` ou une requête AJAX.

## Exemples

### Formulaire classique

```php
public function store(Requete $request)
{
    $data = $request->tous();
    // ...
}
```

### Route avec paramètre

```php
public function show(Requete $request)
{
    $id = $request->param('id');
    return Article::trouver($id);
}
```

### Upload

```php
$file = $request->file('photo');
if (!$file) {
    return $this->json(['erreur' => 'Fichier manquant'], 400);
}
```

### Détection API/AJAX

```php
if ($request->estApi()) {
    return APIResponse::unauthorized();
}

if ($request->estAjax()) {
    // Retour JSON simplifié
}
```

---

[← Retour à INDEX](INDEX.md)
