# Core\Traduction - API Reference

Internationalisation (i18n) multilingue.

## Configuration

```php
// config/app.php
'default_language' => 'fr',
'languages' => ['fr', 'en', 'es'],
'translations_path' => 'ressources/traductions'
```

## Fichiers de Traduction

**Fichier: ressources/traductions/fr.php**

```php
return [
    'articles' => [
        'title' => 'Articles',
        'create' => 'Créer un article',
        'edit' => 'Éditer',
        'delete' => 'Supprimer',
        'messages' => [
            'created' => 'Article créé avec succès',
            'updated' => 'Article mis à jour',
            'deleted' => 'Article supprimé'
        ]
    ],
    'validation' => [
        'email_invalid' => 'Email invalide',
        'required' => 'Champ obligatoire'
    ]
];
```

**Fichier: ressources/traductions/en.php**

```php
return [
    'articles' => [
        'title' => 'Articles',
        'create' => 'Create article',
        'messages' => [
            'created' => 'Article created successfully'
        ]
    ]
];
```

## Utilisation

### Fonction \_\_()

```php
// Vue
<h1><?php echo __('articles.title'); ?></h1>
<a href="/articles/create"><?php echo __('articles.create'); ?></a>

// Contrôleur
$message = __('articles.messages.created');

// Avec variables
<?php echo __('messages.welcome', ['name' => 'John']); ?>
// Fichier: 'welcome' => 'Bienvenue {name}'
```

### Changer la Langue

```php
Traduction::definirLangue('en');

// Maintenant toutes les traductions sont en anglais
echo __('articles.title'); // "Articles"
```

### Langues Supportées

```php
$languages = Traduction::languesSupportees();
// ['fr', 'en', 'es']

$current = Traduction::langueActuelle();
// 'fr'
```

## Exemples Réels

### Vue Multilingue

```html
<div class="language-selector">
  <a href="?lang=fr">Français</a>
  <a href="?lang=en">English</a>
</div>

<h1><?php echo __('articles.title'); ?></h1>
<button><?php echo __('articles.create'); ?></button>
```

### Contrôleur avec i18n

```php
public function store(Requete $request): string
{
    // La langue peut être détectée depuis le navigateur
    $lang = $request->header('Accept-Language');
    if (strpos($lang, 'en') !== false) {
        Traduction::definirLangue('en');
    }

    $erreurs = $this->valider($_POST, [
        'email' => 'requis|email'
    ]);

    if (!empty($erreurs)) {
        $this->flash('error', __('validation.failed'));
        return $this->rediriger('/create');
    }

    $article = Article::creer($_POST);
    $this->flash('success', __('articles.messages.created'));
    return $this->rediriger('/articles/' . $article->id);
}
```

---

[← Retour à INDEX](INDEX.md)
