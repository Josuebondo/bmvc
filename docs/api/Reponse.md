# 📤 Classe Reponse

**Gestion des réponses HTTP**

---

## 📖 Description

La classe `Reponse` encapsule les données de réponse HTTP (status, headers, corps) et fournit une interface pour formater les réponses (JSON, HTML, redirection, etc.).

**Localisation:** `core/Reponse.php`

---

## 🔧 Méthodes Principales

### Construction

```php
public function __construct($contenu = '', $statut = 200, array $entetes = [])
```

Crée une nouvelle réponse HTTP.

```php
$reponse = new Reponse('Bienvenue!', 200);
$reponse = new Reponse('Non trouvé', 404);
$reponse = new Reponse(json_encode(['id' => 1]), 200, ['Content-Type' => 'application/json']);
```

---

### Définir le Contenu

#### `contenu($texte = null)`

Définit ou récupère le contenu de la réponse.

```php
$reponse->contenu('Bonjour le monde');

$contenu = $reponse->contenu();
```

#### `json($donnees, $statut = 200)`

Envoie une réponse JSON.

```php
return $reponse->json(['id' => 1, 'nom' => 'Article'], 200);

// Raccourci
return response()->json($article->enTable());
```

#### `html($contenu, $statut = 200)`

Envoie du HTML.

```php
return $reponse->html('<h1>Bonjour</h1>', 200);
```

#### `texte($texte, $statut = 200)`

Envoie du texte brut.

```php
return $reponse->texte('Fichier téléchargé', 200);
```

#### `fichier($cheminFichier, $nomTelechargement = null)`

Télécharge un fichier.

```php
return $reponse->fichier('storage/documents/rapport.pdf', 'rapport.pdf');

// Sans renommer
return $reponse->fichier('storage/documents/rapport.pdf');
```

---

### Status HTTP

#### `statut($code = null)`

Définit ou récupère le code de statut HTTP.

```php
$reponse->statut(404);

$reponse->statut();  // 404
```

#### Code de Statut Raccourcis

```php
$reponse->ok();              // 200 OK
$reponse->cree();            // 201 Created
$reponse->redirection();     // 302 Found
$reponse->nonModifie();      // 304 Not Modified
$reponse->mauvaisRequete();  // 400 Bad Request
$reponse->nonAutorise();     // 401 Unauthorized
$reponse->interdit();        // 403 Forbidden
$reponse->nonTrouve();       // 404 Not Found
$reponse->erreur();          // 500 Internal Server Error
```

---

### Headers HTTP

#### `entete($cle, $valeur = null)`

Définit ou récupère les headers.

```php
// Définir
$reponse->entete('Content-Type', 'application/json');
$reponse->entete('X-Custom-Header', 'valeur');

// Récupérer
$type = $reponse->entete('Content-Type');
```

#### `entetes($headers = null)`

Définit ou récupère tous les headers.

```php
// Définir plusieurs headers
$reponse->entetes([
    'Content-Type' => 'application/json',
    'Cache-Control' => 'no-cache'
]);

// Récupérer tous
$headers = $reponse->entetes();
```

#### `cacheControl($directive = 'no-cache')`

Définit la directive Cache-Control.

```php
$reponse->cacheControl('public, max-age=3600');
$reponse->cacheControl('no-cache');
$reponse->cacheControl('private, max-age=86400');
```

---

### Redirection

#### `redirection($url, $statut = 302)`

Redirige vers une URL.

```php
return $reponse->redirection('/blog');
return $reponse->redirection('https://exemple.com', 301);
```

#### `retour()`

Redirige vers la page précédente.

```php
return $reponse->retour();
```

---

### Cookies

#### `cookie($nom, $valeur, $duree = 86400)`

Définit un cookie.

```php
$reponse->cookie('remember_me', 'user123', 86400 * 30);  // 30 jours
$reponse->cookie('theme', 'dark', 86400 * 365);          // 1 an
```

#### `supprimerCookie($nom)`

Supprime un cookie.

```php
$reponse->supprimerCookie('remember_me');
```

---

### Envoi

#### `envoyer()`

Envoie la réponse au client.

```php
$reponse->envoyer();
```

**Note:** Les contrôleurs envoient automatiquement les réponses. Utilisez rarement cette méthode directement.

---

## 📚 Exemples d'Utilisation

### Réponse JSON

```php
public function obtenirArticles(Requete $requete)
{
    $articles = Article::tous();

    return response()
        ->json(['articles' => $articles])
        ->entete('X-Total-Count', count($articles));
}
```

### Réponse avec Statut Personnalisé

```php
public function creerArticle(Requete $requete)
{
    $article = Article::creer($requete->post());

    return response()
        ->json(['id' => $article->id, 'message' => 'Créé'])
        ->statut(201);
}
```

### Redirection

```php
public function sauvegarder(Requete $requete)
{
    $article = Article::creer($requete->post());

    $requete->session()->definir('succes', 'Article créé');

    return response()->redirection("/article/{$article->id}");
}
```

### Téléchargement de Fichier

```php
public function telecharger(Requete $requete)
{
    $id = $requete->get('id');
    $article = Article::trouver($id);

    if (!$article) {
        return response()
            ->json(['erreur' => 'Non trouvé'])
            ->statut(404);
    }

    return response()->fichier(
        "storage/exports/article_{$id}.pdf",
        "article_{$article->slug}.pdf"
    );
}
```

### Réponse HTML avec Cache

```php
public function afficher($id)
{
    $article = Article::trouver($id);

    return response()
        ->html(view('article/affiche', ['article' => $article]))
        ->cacheControl('public, max-age=3600');
}
```

### API avec Erreur Personnalisée

```php
public function traiterRequete(Requete $requete)
{
    if ($requete->methode() !== 'POST') {
        return response()
            ->json(['erreur' => 'Méthode non autorisée'])
            ->statut(405)
            ->entete('Allow', 'POST, OPTIONS');
    }

    // Traiter...
}
```

---

## 🔗 Propriétés Publiques

```php
// Contenu
$reponse->_contenu // = "Bonjour"

// Statut
$reponse->_statut // = 200

// Headers
$reponse->_entetes['Content-Type'] // = "application/json"

// Cookies
$reponse->_cookies['remember_me'] // = "user123"
```

---

## 📋 Cheat Sheet

```php
// Créer une réponse
response()->json($data);           // JSON
response()->html('<h1>Titre</h1>'); // HTML
response()->texte('Texte');         // Texte
response()->fichier('path/file');   // Fichier

// Statut HTTP
response()->ok();                   // 200
response()->cree();                 // 201
response()->nonTrouve();            // 404
response()->erreur();               // 500

// Headers
response()->entete('X-Header', 'valeur');
response()->cacheControl('public, max-age=3600');

// Redirection
response()->redirection('/chemin');
response()->retour();

// Cookies
response()->cookie('nom', 'valeur');
response()->supprimerCookie('nom');

// Envoi
response()->envoyer();
```

---

## 🧪 Tests

Voir `tests/ReponseTest.php` pour les tests complets.

```bash
php vendor/bin/phpunit tests/ReponseTest.php
```

---

## 📖 Voir aussi

- [Requete](Requete.md) - Gestion des requêtes HTTP
- [Middleware](Middleware.md) - Filtrage des réponses

---

**BMVC Framework v1.0.0** | [Retour à l'index](../INDEX.md)
