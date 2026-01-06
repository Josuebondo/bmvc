# 🌐 Classe Traduction (i18n)

**Internationalization (i18n) et localization (l10n)**

---

## 📖 Description

La classe `Traduction` fournit un système d'internationalisation complet pour supporter plusieurs langues dans votre application. Stockez les chaînes de caractères dans des fichiers de langue et accédez-les facilement.

**Localisation:** `core/Traduction.php`

---

## 🔧 Méthodes Principales

### Obtenir une Traduction

#### `obtenir($cle, $parametres = [], $langue = null)`

Récupère une chaîne traduite.

```php
// Simple
echo Traduction::obtenir('accueil.titre');
// "Bienvenue sur BMVC"

// Avec paramètres
echo Traduction::obtenir('email.body', ['nom' => 'Jean']);
// "Bonjour Jean, bienvenue!"

// Avec langue spécifique
echo Traduction::obtenir('accueil.titre', [], 'en');
// "Welcome to BMVC"
```

#### `obtenirAvecFallback($cle, $fallback = null, $parametres = [])`

Récupère une traduction avec valeur par défaut.

```php
echo Traduction::obtenirAvecFallback('page.titre', 'Sans titre', ['id' => 1]);
```

#### `existe($cle, $langue = null)`

Vérifie si une clé de traduction existe.

```php
if (Traduction::existe('accueil.titre')) {
    echo Traduction::obtenir('accueil.titre');
}
```

---

### Langue Actuelle

#### `langue()`

Récupère la langue actuelle.

```php
$langue = Traduction::langue();
// "fr"
```

#### `definirLangue($langue)`

Définit la langue actuelle.

```php
Traduction::definirLangue('en');
Traduction::definirLangue('es');
Traduction::definirLangue('de');
```

#### `langueParDefaut()`

Récupère la langue par défaut.

```php
$default = Traduction::langueParDefaut();
// "fr"
```

---

### Charger les Fichiers de Langue

#### `charger($groupe, $langue = null)`

Charge un fichier de langue.

```php
// Charge resources/lang/fr/accueil.php
Traduction::charger('accueil', 'fr');

// Charge la langue actuelle
Traduction::charger('messages');
```

#### `toutLesGroups($langue = null)`

Charge tous les fichiers de langue d'un répertoire.

```php
Traduction::toutLesGroups('fr');
```

---

### Gestion des Pluriels

#### `pluriel($cle, $nombre, $parametres = [])`

Gère les pluriels.

```php
// Dans le fichier de langue:
// 'articles' => [
//     'one' => 'Il y a :count article',
//     'other' => 'Il y a :count articles'
// ]

echo Traduction::pluriel('messages.articles', 1);
// "Il y a 1 article"

echo Traduction::pluriel('messages.articles', 5);
// "Il y a 5 articles"
```

---

### Messages Traduites Courantes

#### Messages de Validation

```php
'validation' => [
    'requis' => ':attribute est obligatoire',
    'email' => ':attribute doit être une adresse email valide',
    'min' => ':attribute doit contenir au moins :min caractères',
    'max' => ':attribute ne doit pas dépasser :max caractères',
    'numero' => ':attribute doit être un nombre',
]
```

#### Messages d'Erreur

```php
'erreurs' => [
    '404' => 'Page non trouvée',
    '500' => 'Erreur serveur interne',
    'non_autorise' => 'Vous n\'êtes pas autorisé à accéder à cette ressource',
]
```

---

## 📁 Structure des Fichiers de Langue

```
resources/
└── lang/
    ├── fr/
    │   ├── accueil.php        // Traductions pour la page d'accueil
    │   ├── messages.php       // Messages généraux
    │   ├── validation.php     // Messages de validation
    │   ├── emails.php         // Textes d'emails
    │   └── erreurs.php        // Messages d'erreur
    │
    └── en/
        ├── accueil.php
        ├── messages.php
        ├── validation.php
        ├── emails.php
        └── erreurs.php
```

---

## 📚 Exemples d'Utilisation

### Fichier de Langue (resources/lang/fr/accueil.php)

```php
<?php

return [
    'titre' => 'Bienvenue sur BMVC',
    'sous_titre' => 'Framework PHP moderne et léger',
    'description' => 'Un framework PHP moderne conçu pour la simplicité et la performance',

    'navigation' => [
        'accueil' => 'Accueil',
        'blog' => 'Blog',
        'contact' => 'Contact',
        'admin' => 'Administration',
    ],

    'boutons' => [
        'creer' => 'Créer',
        'modifier' => 'Modifier',
        'supprimer' => 'Supprimer',
        'sauvegarder' => 'Sauvegarder',
        'annuler' => 'Annuler',
    ],

    'messages' => [
        'bienvenue' => 'Bienvenue :nom!',
        'succes_creation' => 'L\'élément a été créé avec succès',
        'succes_modification' => 'L\'élément a été modifié avec succès',
        'succes_suppression' => 'L\'élément a été supprimé avec succès',
    ],
];
```

### Fichier de Langue (resources/lang/en/accueil.php)

```php
<?php

return [
    'titre' => 'Welcome to BMVC',
    'sous_titre' => 'Modern and lightweight PHP framework',
    'description' => 'A modern PHP framework designed for simplicity and performance',

    'navigation' => [
        'accueil' => 'Home',
        'blog' => 'Blog',
        'contact' => 'Contact',
        'admin' => 'Admin',
    ],

    'boutons' => [
        'creer' => 'Create',
        'modifier' => 'Edit',
        'supprimer' => 'Delete',
        'sauvegarder' => 'Save',
        'annuler' => 'Cancel',
    ],

    'messages' => [
        'bienvenue' => 'Welcome :nom!',
        'succes_creation' => 'Item created successfully',
        'succes_modification' => 'Item updated successfully',
        'succes_suppression' => 'Item deleted successfully',
    ],
];
```

### Utilisation dans les Contrôleurs

```php
class PageControleur
{
    public function accueil()
    {
        return [
            'titre' => Traduction::obtenir('accueil.titre'),
            'description' => Traduction::obtenir('accueil.description'),
        ];
    }

    public function contact()
    {
        return [
            'titre' => Traduction::obtenir('contact.titre'),
            'formulaire' => [
                'nom' => Traduction::obtenir('contact.formulaire.nom'),
                'email' => Traduction::obtenir('contact.formulaire.email'),
                'message' => Traduction::obtenir('contact.formulaire.message'),
                'envoyer' => Traduction::obtenir('contact.formulaire.envoyer'),
            ],
        ];
    }
}
```

### Utilisation dans les Vues

```php
<!-- resources/views/accueil.php -->
<h1><?= traduction('accueil.titre') ?></h1>
<p><?= traduction('accueil.description') ?></p>

<nav>
    <a href="/"><?= traduction('accueil.navigation.accueil') ?></a>
    <a href="/blog"><?= traduction('accueil.navigation.blog') ?></a>
    <a href="/contact"><?= traduction('accueil.navigation.contact') ?></a>
</nav>

<!-- Avec paramètres -->
<h2><?= traduction('accueil.messages.bienvenue', ['nom' => $user->nom]) ?></h2>
```

### Sélecteur de Langue

```php
class LangueControleur
{
    public function changer(Requete $requete)
    {
        $langue = $requete->post('langue');

        if (in_array($langue, ['fr', 'en', 'es', 'de'])) {
            Traduction::definirLangue($langue);
            $requete->session()->definir('langue', $langue);
        }

        return response()->redirection($requete->entete('referer'));
    }
}
```

### Validation avec Traduction

```php
$validation = new Validation($donnees, [
    'email' => 'requis|email',
    'nom' => 'requis|min:2'
]);

if ($validation->echoue()) {
    $erreurs = [];
    foreach ($validation->erreurs() as $champ => $messages) {
        foreach ($messages as $message) {
            // Le message est déjà traduit
            $erreurs[$champ][] = $message;
        }
    }

    return response()->json(['erreurs' => $erreurs], 422);
}
```

### Emails Multilingues

```php
class EmailControleur
{
    public function inscription($user)
    {
        Traduction::definirLangue($user->langue);

        $contenu = Traduction::obtenir('emails.inscription.body', [
            'nom' => $user->nom,
            'lien' => url('/verify?token=' . $user->token)
        ]);

        // Envoyer l'email
        mail(
            $user->email,
            Traduction::obtenir('emails.inscription.sujet'),
            $contenu
        );
    }
}
```

---

## 🔧 Configuration

### Fichier de Configuration (config/traduction.php)

```php
<?php

return [
    // Langue par défaut
    'par_defaut' => 'fr',

    // Langues supportées
    'supportees' => ['fr', 'en', 'es', 'de'],

    // Répertoire des fichiers de langue
    'chemin' => base_path('resources/lang'),

    // Locale PHP (pour strtotime, etc.)
    'locale_php' => 'fr_FR.UTF-8',

    // Déterminer la langue à partir du:
    'determination' => [
        'session' => true,    // Session utilisateur
        'cookie' => true,     // Cookie navigateur
        'header' => true,     // Accept-Language header
        'defaut' => 'fr'      // Par défaut
    ],
];
```

---

## 📋 Cheat Sheet

```php
// Obtenir une traduction
Traduction::obtenir('groupe.cle');
Traduction::obtenir('groupe.cle', ['param' => 'valeur']);

// Avec fallback
Traduction::obtenirAvecFallback('groupe.cle', 'valeur par défaut');

// Vérifier existence
Traduction::existe('groupe.cle');

// Gérer la langue
Traduction::langue();               // Langue actuelle
Traduction::definirLangue('en');    // Changer de langue
Traduction::langueParDefaut();      // Langue par défaut

// Charger les fichiers
Traduction::charger('groupe');      // Charger un groupe
Traduction::toutLesGroups();        // Charger tous

// Pluriels
Traduction::pluriel('groupe.cle', $nombre);

// Fonction courte dans les vues
traduction('groupe.cle');
traduction('groupe.cle', ['param' => 'val']);
```

---

## 🧪 Tests

Voir `tests/TraductionTest.php` pour les tests complets.

```bash
php vendor/bin/phpunit tests/TraductionTest.php
```

---

## 📖 Voir aussi

- [Guide Utilisation](../guides/usage/GUIDE_UTILISATION.md) - Exemples complets
- [Middleware](Middleware.md) - Middleware pour sélection de langue

---

**BMVC Framework v1.0.0** | [Retour à l'index](../INDEX.md)
