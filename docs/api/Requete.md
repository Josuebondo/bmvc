# 📬 Classe Requete

**Gestion des requêtes HTTP**

---

## 📖 Description

La classe `Requete` encapsule les données de la requête HTTP (GET, POST, headers, etc.) et fournit une interface simple pour y accéder.

**Localisation:** `core/Requete.php`

---

## 🔧 Méthodes Principales

### Construction

```php
public function __construct()
```

Crée une nouvelle instance de Requete en analysant automatiquement la requête HTTP actuelle.

---

### Récupération des Données

#### `get($cle = null, $defaut = null)`

Récupère les paramètres GET.

```php
// Tous les paramètres GET
$get = $requete->get();

// Un paramètre spécifique
$id = $requete->get('id');

// Avec valeur par défaut
$page = $requete->get('page', 1);
```

#### `post($cle = null, $defaut = null)`

Récupère les paramètres POST.

```php
// Tous les paramètres POST
$post = $requete->post();

// Un paramètre spécifique
$nom = $requete->post('nom');

// Avec valeur par défaut
$email = $requete->post('email', '');
```

#### `input($cle = null, $defaut = null)`

Récupère les données GET + POST combinées.

```php
// Paramètre qui peut venir de GET ou POST
$recherche = $requete->input('q');
```

#### `fichier($cle)`

Récupère un fichier uploadé.

```php
$avatar = $requete->fichier('avatar');

// Vérifie si le fichier existe
if ($requete->fichier('avatar')) {
    $chemin = $requete->fichier('avatar')['tmp_name'];
    $nom = $requete->fichier('avatar')['name'];
}
```

---

### Informations de la Requête

#### `methode()`

Retourne la méthode HTTP (GET, POST, PUT, DELETE, etc.).

```php
if ($requete->methode() === 'POST') {
    // Traiter un POST
}
```

#### `url()`

Retourne l'URL complète de la requête.

```php
$url = $requete->url();
// https://exemple.com/blog/articles?page=2
```

#### `cheminUri()`

Retourne le chemin URI (sans domaine et paramètres).

```php
$chemin = $requete->cheminUri();
// /blog/articles
```

#### `nomDomaine()`

Retourne le nom de domaine.

```php
$domaine = $requete->nomDomaine();
// exemple.com
```

---

### Headers HTTP

#### `entete($cle = null)`

Récupère les headers HTTP.

```php
// Tous les headers
$headers = $requete->entete();

// Un header spécifique
$type = $requete->entete('Content-Type');

// Défaut: 'application/json'
$type = $requete->entete('Accept', 'application/json');
```

---

### Validation

#### `valider($regles)`

Valide les données de la requête.

```php
$erreurs = $requete->valider([
    'email' => 'requis|email',
    'nom' => 'requis|min:3',
    'age' => 'nombre|min:18'
]);

if (!empty($erreurs)) {
    // Afficher les erreurs
    var_dump($erreurs);
}
```

---

### Session

#### `session()`

Retourne l'instance de Session.

```php
$session = $requete->session();
$user = $session->obtenir('user');
```

#### `estConnecte()`

Vérifie si l'utilisateur est connecté.

```php
if ($requete->estConnecte()) {
    // L'utilisateur est connecté
}
```

---

## 📚 Exemples d'Utilisation

### Récupérer des Données de Formulaire

```php
// Dans un contrôleur
public function creerArticle(Requete $requete)
{
    $titre = $requete->post('titre');
    $contenu = $requete->post('contenu');
    $auteur = $requete->post('auteur', 'Anonyme');

    // Validation
    $erreurs = $requete->valider([
        'titre' => 'requis|min:5|max:200',
        'contenu' => 'requis|min:10',
    ]);

    if (!empty($erreurs)) {
        return ['erreurs' => $erreurs];
    }

    // Créer l'article
    $article = new Article();
    $article->titre = $titre;
    $article->contenu = $contenu;
    $article->auteur = $auteur;
    $article->sauvegarder();

    return ['succes' => 'Article créé'];
}
```

### Gérer un Upload de Fichier

```php
public function telecharger(Requete $requete)
{
    if ($requete->methode() === 'POST') {
        $fichier = $requete->fichier('document');

        if ($fichier) {
            $nom = time() . '_' . $fichier['name'];
            $destination = 'storage/uploads/' . $nom;

            if (move_uploaded_file($fichier['tmp_name'], $destination)) {
                return ['succes' => 'Fichier téléchargé'];
            }
        }
    }

    return [];
}
```

### API REST

```php
public function obtenir(Requete $requete)
{
    $id = $requete->get('id');

    $article = Article::trouver($id);

    if (!$article) {
        return response()->json(['erreur' => 'Non trouvé'], 404);
    }

    return response()->json($article->enTable());
}

public function creer(Requete $requete)
{
    $donnees = json_decode(file_get_contents('php://input'), true);

    $erreurs = Validation::valider($donnees, [
        'titre' => 'requis',
        'contenu' => 'requis',
    ]);

    if (!empty($erreurs)) {
        return response()->json(['erreurs' => $erreurs], 422);
    }

    $article = Article::creer($donnees);
    return response()->json($article->enTable(), 201);
}
```

---

## 🔗 Propriétés Publiques

```php
// Paramètres GET
$requete->_get['id'] // = 123

// Paramètres POST
$requete->_post['nom'] // = "Jean"

// Fichiers uploadés
$requete->_fichier['avatar'] // = [...]

// Headers HTTP
$requete->_entete['Content-Type'] // = "application/json"

// Méthode HTTP
$requete->_methode // = "POST"

// URI de la requête
$requete->_uri // = "/blog/articles"
```

---

## 📋 Cheat Sheet

```php
// Accéder aux données
$requete->get('id');           // Paramètre GET
$requete->post('nom');         // Paramètre POST
$requete->input('recherche');  // GET ou POST
$requete->fichier('avatar');   // Fichier uploadé

// Informations
$requete->methode();           // GET, POST, PUT, DELETE...
$requete->url();               // URL complète
$requete->cheminUri();         // /chemin/vers/page
$requete->nomDomaine();        // exemple.com

// Headers
$requete->entete('Accept');    // Récupérer un header
$requete->entete();            // Tous les headers

// Validation
$requete->valider($regles);    // Valider les données

// Session
$requete->session();           // Accéder à la session
$requete->estConnecte();       // Utilisateur connecté?
```

---

## 🧪 Tests

Voir `tests/RequeteTest.php` pour les tests complets.

```bash
php vendor/bin/phpunit tests/RequeteTest.php
```

---

## 📖 Voir aussi

- [Reponse](Reponse.md) - Gestion des réponses HTTP
- [Validation](Validation.md) - Validation des données
- [Session](Session.md) - Gestion de la session utilisateur
- [Middleware](Middleware.md) - Filtrage des requêtes

---

**BMVC Framework v1.0.0** | [Retour à l'index](../INDEX.md)
