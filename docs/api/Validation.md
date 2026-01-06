# ✅ Classe Validation

**Validation des données et formulaires**

---

## 📖 Description

La classe `Validation` fournit une interface simple pour valider les données (formulaires, API, etc.) avec des règles flexibles et des messages d'erreur personnalisés.

**Localisation:** `core/Validation.php`

---

## 🔧 Méthodes Principales

### Validation de Base

#### `valider($donnees, $regles)`

Valide les données avec les règles spécifiées.

```php
$donnees = [
    'email' => 'jean@exemple.com',
    'password' => 'secure123',
    'nom' => 'Jean'
];

$regles = [
    'email' => 'requis|email',
    'password' => 'requis|min:8',
    'nom' => 'requis|min:2'
];

$validation = new Validation($donnees, $regles);

if ($validation->echoue()) {
    // La validation a échoué
    $erreurs = $validation->erreurs();
} else {
    // La validation a réussi
    // Continuer...
}
```

#### `reussie()`

Vérifie si la validation a réussi.

```php
if ($validation->reussie()) {
    // Données valides
}
```

#### `echoue()`

Vérifie si la validation a échoué.

```php
if ($validation->echoue()) {
    // Données invalides
}
```

---

### Récupérer les Erreurs

#### `erreurs()`

Récupère tous les erreurs.

```php
$erreurs = $validation->erreurs();

// [
//   'email' => ['Email est invalide'],
//   'password' => ['Mot de passe doit contenir min 8 caractères']
// ]
```

#### `premiereErreur($champ)`

Récupère la première erreur d'un champ.

```php
$erreur = $validation->premiereErreur('email');
// "Email est invalide"
```

#### `toutesLesErreurs($champ)`

Récupère toutes les erreurs d'un champ.

```php
$erreurs = $validation->toutesLesErreurs('email');
// ["Email est invalide", "Email est déjà utilisé"]
```

#### `possede($champ)`

Vérifie si un champ a des erreurs.

```php
if ($validation->possede('email')) {
    echo $validation->premiereErreur('email');
}
```

---

### Règles de Validation

#### Règles Disponibles

```php
// Existence
'requis'              // Champ obligatoire
'nullable'            // Champ peut être vide

// String
'min:5'               // Minimum 5 caractères
'max:100'             // Maximum 100 caractères
'longueur:20'         // Exactement 20 caractères
'alpha'               // Lettres uniquement
'alphanum'            // Lettres et chiffres uniquement
'regex:/^[0-9]+$/'    // Expression régulière

// Number
'nombre'              // Doit être un nombre
'entier'              // Doit être un entier
'float'               // Doit être un float
'positif'             // Doit être positif
'negatif'             // Doit être négatif

// Email / URL
'email'               // Doit être un email valide
'url'                 // Doit être une URL valide

// Date
'date'                // Doit être une date valide
'avant:2025-01-01'    // Date avant cette date
'apres:2020-01-01'    // Date après cette date

// Comparaison
'confirme'            // Doit correspondre au champ _confirmation
'pareil:password'     // Doit correspondre à ce champ
'different:email'     // Doit être différent de ce champ

// Array
'tableau'             // Doit être un tableau
'tauxComplet'         // Toutes les clés doivent correspondre à la règle

// Personnalisées
'unique:table'        // Valeur unique dans la table
'existe:table'        // Valeur existe dans la table
'mime:jpg,png,pdf'    // Type MIME valide
```

---

### Messages d'Erreur Personnalisés

#### Définir les Messages

```php
$donnees = ['email' => 'invalid'];
$regles = ['email' => 'email'];
$messages = ['email.email' => 'Veuillez entrer une adresse email valide'];

$validation = new Validation($donnees, $regles, $messages);
```

#### Messages Prédéfinis

```php
[
    'requis' => ':attribute est obligatoire',
    'email' => ':attribute doit être une adresse email valide',
    'min' => ':attribute doit contenir au moins :min caractères',
    'max' => ':attribute ne doit pas dépasser :max caractères',
    'nombre' => ':attribute doit être un nombre',
    'confirme' => ':attribute doit être confirmé',
    'pareil' => ':attribute doit correspondre à :pareil',
    'unique' => ':attribute est déjà utilisé',
    'existe' => ':attribute n\'existe pas'
]
```

#### Noms d'Attributs

```php
$donnees = ['email' => 'invalid'];
$regles = ['email' => 'email'];
$noms = ['email' => 'Adresse email'];

$validation = new Validation($donnees, $regles);
$validation->noms($noms);

// Message: "Adresse email doit être une adresse email valide"
```

---

## 📚 Exemples d'Utilisation

### Validation de Formulaire

```php
public function sauvegarder(Requete $requete)
{
    $donnees = [
        'titre' => $requete->post('titre'),
        'contenu' => $requete->post('contenu'),
        'auteur' => $requete->post('auteur'),
        'email' => $requete->post('email')
    ];

    $validation = new Validation($donnees, [
        'titre' => 'requis|min:5|max:200',
        'contenu' => 'requis|min:10',
        'auteur' => 'requis|min:2',
        'email' => 'requis|email'
    ]);

    if ($validation->echoue()) {
        return response()->json(['erreurs' => $validation->erreurs()], 422);
    }

    // Créer l'article
    Article::creer($donnees);

    return response()->json(['succes' => 'Article créé'], 201);
}
```

### Inscription Utilisateur

```php
public function inscrire(Requete $requete)
{
    $donnees = [
        'nom' => $requete->post('nom'),
        'email' => $requete->post('email'),
        'password' => $requete->post('password'),
        'password_confirmation' => $requete->post('password_confirmation')
    ];

    $validation = new Validation($donnees, [
        'nom' => 'requis|min:2|max:100',
        'email' => 'requis|email|unique:users',
        'password' => 'requis|min:8|confirme'
    ], [
        'email.unique' => 'Cet email est déjà utilisé',
        'password.confirme' => 'Les mots de passe ne correspondent pas'
    ]);

    if ($validation->echoue()) {
        return response()->json(['erreurs' => $validation->erreurs()], 422);
    }

    User::creer([
        'nom' => $donnees['nom'],
        'email' => $donnees['email'],
        'password' => password_hash($donnees['password'], PASSWORD_BCRYPT)
    ]);

    return response()->json(['succes' => 'Inscription réussie'], 201);
}
```

### Validation avec Récupération d'Erreur

```php
$validation = new Validation($donnees, $regles);

if ($validation->possede('email')) {
    echo "Email invalide: " . $validation->premiereErreur('email');
}

// Afficher toutes les erreurs d'un champ
foreach ($validation->toutesLesErreurs('email') as $erreur) {
    echo "- " . $erreur;
}
```

### Validation de Fichier

```php
$donnees = [
    'avatar' => $requete->fichier('avatar')
];

$validation = new Validation($donnees, [
    'avatar' => 'requis|mime:jpg,png,gif|max:2000000'  // 2MB max
]);

if ($validation->echoue()) {
    return response()->json(['erreurs' => $validation->erreurs()], 422);
}
```

### Validation de Date

```php
$donnees = [
    'date_naissance' => $requete->post('date_naissance'),
    'date_mariage' => $requete->post('date_mariage')
];

$validation = new Validation($donnees, [
    'date_naissance' => 'requis|date|avant:2010-01-01',
    'date_mariage' => 'date|apres:date_naissance'
]);
```

---

## 📋 Cheat Sheet

```php
// Créer et valider
$v = new Validation($donnees, $regles);

// Vérifier le résultat
$v->reussie();                      // Validation réussie?
$v->echoue();                       // Validation échouée?

// Récupérer les erreurs
$v->erreurs();                      // Tous les erreurs
$v->premiereErreur('email');        // Première erreur d'un champ
$v->toutesLesErreurs('email');      // Toutes les erreurs
$v->possede('email');               // Le champ a-t-il des erreurs?

// Règles courantes
'requis'                            // Obligatoire
'email'                             // Email valide
'min:5'                             // Minimum 5 caractères
'max:100'                           // Maximum 100 caractères
'nombre'                            // Nombre
'date'                              // Date
'confirme'                          // Confirmation
'unique:table'                      // Unique en DB
'pareil:password'                   // Doit correspondre
```

---

## 🧪 Tests

Voir `tests/ValidationTest.php` pour les tests complets.

```bash
php vendor/bin/phpunit tests/ValidationTest.php
```

---

## 📖 Voir aussi

- [Requete](Requete.md) - Récupérer les données à valider
- [Modele](Modele.md) - Sauvegarder les données valides

---

**BMVC Framework v1.0.0** | [Retour à l'index](../INDEX.md)
