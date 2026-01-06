# 📋 Guide Complet des Layouts et Sections

## 🎯 Introduction

Les layouts (gabarits) dans BMVC permettent de créer une structure HTML commune à plusieurs pages, évitant la duplication de code (header, navigation, footer, etc.).

Le système utilise les **sections** pour définir différentes zones de contenu qui peuvent être personnalisées par chaque vue.

---

## 🏗️ Architecture du Système

```
┌─────────────────────────────────┐
│      Layout (app.php)           │
│  ┌──────────────────────────┐   │
│  │     HEADER/NAV           │   │
│  ├──────────────────────────┤   │
│  │   section('contenu')  ◄──┼───┼─── Injecté par la vue
│  ├──────────────────────────┤   │
│  │     FOOTER               │   │
│  └──────────────────────────┘   │
└─────────────────────────────────┘
         ▲
         │
      étendre()
         │
    ┌────┴─────────┐
    │   Vue.php    │
    │ (accueil)    │
    └──────────────┘
```

---

## 📝 Exemple Basique

### 1️⃣ Étendre un Layout

Dans votre vue (`app/Vues/accueil.php`):

```php
<?php
// Utiliser le layout app
etendre('layouts.app');
?>

<?php debut_section('contenu'); ?>
    <h2>Bienvenue sur BMVC</h2>
    <p>Ceci est le contenu de la page d'accueil.</p>
<?php fin_section('contenu'); ?>
```

### 2️⃣ Le Layout (`app/Vues/layouts/app.php`)

```php
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BMVC</title>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Mon Application</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/">Accueil</a>
        <a href="/articles">Articles</a>
    </nav>

    <!-- Contenu principal (injecté par la vue) -->
    <main>
        <?php section('contenu'); ?>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Mon Application</p>
    </footer>
</body>
</html>
```

---

## 🔧 Fonctions Principales

### `etendre(string $layout)`

Défini le layout à utiliser pour la vue actuelle.

```php
<?php etendre('layouts.app'); ?>
<?php etendre('layouts.admin'); ?>
<?php etendre('layouts.blog'); ?>
```

**Format du chemin:**

- `layouts.app` → `app/Vues/layouts/app.php`
- `layouts.admin.sidebar` → `app/Vues/layouts/admin/sidebar.php`
- Notation pointée = séparateurs de dossiers

---

### `debut_section(string $nom)` & `fin_section(string $nom)`

Délimite une section nommée.

```php
<?php debut_section('contenu'); ?>
    <!-- Votre contenu -->
    <h2>Titre</h2>
    <p>Paragraphe</p>
<?php fin_section('contenu'); ?>
```

**Règles importantes:**

- Le nom est arbitraire (`'contenu'`, `'sidebar'`, `'scripts'`, etc.)
- Chaque section doit avoir un matching `debut_section()` et `fin_section()`
- Le nom doit être identique dans les deux

---

### `section(string $nom, string $contenuParDefaut = '')`

Affiche le contenu d'une section définie dans la vue.

```php
<!-- Dans le layout -->
<main>
    <?php section('contenu'); ?>
</main>

<aside>
    <?php section('sidebar', '<p>Pas de sidebar</p>'); ?>
</aside>
```

Le deuxième paramètre est optionnel et s'affiche si la section n'est pas définie.

---

## 📚 Exemples Pratiques

### Exemple 1: Page Simple

**Vue: `app/Vues/apropos.php`**

```php
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
    <div class="container">
        <h1>À Propos</h1>
        <p>Lorem ipsum dolor sit amet...</p>
    </div>
<?php fin_section('contenu'); ?>
```

### Exemple 2: Page avec Sidebar

**Vue: `app/Vues/blog/article.php`**

```php
<?php etendre('layouts.blog'); ?>

<?php debut_section('contenu'); ?>
    <article>
        <h1><?= e($article['titre']) ?></h1>
        <p><?= e($article['contenu']) ?></p>
    </article>
<?php fin_section('contenu'); ?>

<?php debut_section('sidebar'); ?>
    <div class="widget">
        <h3>Catégories</h3>
        <ul>
            <?php foreach ($categories as $cat): ?>
                <li><a href="<?= url('/blog?cat=' . $cat['id']) ?>"><?= e($cat['nom']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php fin_section('sidebar'); ?>
```

**Layout: `app/Vues/layouts/blog.php`**

```php
<!DOCTYPE html>
<html>
<head>
    <title>Blog</title>
</head>
<body>
    <header>
        <h1>Mon Blog</h1>
    </header>

    <div class="wrapper">
        <main class="col-8">
            <?php section('contenu'); ?>
        </main>
        <aside class="col-4">
            <?php section('sidebar'); ?>
        </aside>
    </div>

    <footer>
        <p>&copy; 2024</p>
    </footer>
</body>
</html>
```

### Exemple 3: Plusieurs Sections pour Styles & Scripts

**Vue: `app/Vues/dashboard.php`**

```php
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
    <h1>Dashboard</h1>
    <div id="chart"></div>
<?php fin_section('contenu'); ?>

<?php debut_section('styles'); ?>
    <link rel="stylesheet" href="/css/dashboard.css">
<?php fin_section('styles'); ?>

<?php debut_section('scripts'); ?>
    <script src="/js/chart.js"></script>
    <script>
        // Initialiser le graphique
        createChart('#chart');
    </script>
<?php fin_section('scripts'); ?>
```

**Layout: `app/Vues/layouts/app.php`**

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application</title>
    <!-- Styles du layout -->
    <link rel="stylesheet" href="/css/app.css">
    <!-- Styles des vues -->
    <?php section('styles'); ?>
</head>
<body>
    <header>...</header>

    <main>
        <?php section('contenu'); ?>
    </main>

    <footer>...</footer>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <!-- Scripts des vues -->
    <?php section('scripts'); ?>
</body>
</html>
```

---

## 🎨 Bonnes Pratiques

### ✅ À FAIRE

```php
<!-- Toujours appeler etendre en premier -->
<?php etendre('layouts.app'); ?>

<!-- Puis définir les sections -->
<?php debut_section('contenu'); ?>
    <!-- Contenu -->
<?php fin_section('contenu'); ?>
```

```php
<!-- Utiliser des noms de section clairs -->
<?php section('contenu'); ?>      <!-- ✅ Clair -->
<?php section('main_content'); ?>  <!-- ✅ Lisible -->
<?php section('x'); ?>             <!-- ❌ Confus -->
```

### ❌ À ÉVITER

```php
<!-- Ne pas oublier debut_section -->
<?php fin_section('contenu'); ?>  <!-- ❌ Erreur sans debut_section -->

<!-- Ne pas imbriquer les sections -->
<?php debut_section('a'); ?>
    <?php debut_section('b'); ?>
    <?php fin_section('b'); ?>
<?php fin_section('a'); ?>  <!-- ❌ Pas supporté -->

<!-- Ne pas utiliser des noms incohérents -->
<?php debut_section('contenu'); ?>
    <!-- ... -->
<?php fin_section('content'); ?>  <!-- ❌ 'contenu' ≠ 'content' -->
```

---

## 📦 Créer Vos Propres Layouts

### Layout Administrateur

**`app/Vues/layouts/admin.php`**

```php
<!DOCTYPE html>
<html>
<head>
    <title>Admin - BMVC</title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body class="admin">
    <aside class="sidebar">
        <nav>
            <a href="/admin/dashboard">Dashboard</a>
            <a href="/admin/utilisateurs">Utilisateurs</a>
            <a href="/admin/articles">Articles</a>
        </nav>
    </aside>

    <main class="content">
        <header class="top-bar">
            <h1><?php section('titre', 'Admin'); ?></h1>
        </header>

        <?php section('contenu'); ?>
    </main>
</body>
</html>
```

**Utilisation:**

```php
<?php etendre('layouts.admin'); ?>

<?php debut_section('titre'); ?>
    Gestion des Utilisateurs
<?php fin_section('titre'); ?>

<?php debut_section('contenu'); ?>
    <!-- Contenu de la page admin -->
<?php fin_section('contenu'); ?>
```

### Layout Login (Minimal)

**`app/Vues/layouts/auth.php`**

```php
<!DOCTYPE html>
<html>
<head>
    <title>Authentification</title>
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <?php section('contenu'); ?>
    </div>
</body>
</html>
```

---

## 🔄 Flux de Rendu

1. **Contrôleur appelle `vue()`**

   ```php
   return vue('accueil', ['titre' => 'Accueil']);
   ```

2. **Vue est chargée et exécutée**

   - Appelle `etendre('layouts.app')`
   - Capture les sections avec `debut_section()` / `fin_section()`

3. **Layout est rendu**

   - Affiche le HTML du layout
   - Appelle `section()` pour injecter le contenu des vues

4. **Résultat final envoyé au navigateur**

---

## 🐛 Dépannage

### Contenu n'apparaît pas?

```php
<!-- ❌ WRONG - Pas d'etendre -->
<?php debut_section('contenu'); ?>
    <p>Contenu</p>
<?php fin_section('contenu'); ?>

<!-- ✅ CORRECT - Etendre en premier -->
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
    <p>Contenu</p>
<?php fin_section('contenu'); ?>
```

### Section vide?

```php
<!-- Vérifier le nom est identique -->
<?php debut_section('main'); ?>
    <!-- ... -->
<?php fin_section('main'); ?>

<!-- Layout -->
<?php section('main'); ?>  <!-- ✅ Même nom -->
<?php section('content'); ?>  <!-- ❌ Nom différent -->
```

### Layout non trouvé?

```php
<!-- Format correct -->
etendre('layouts.app');           <!-- ✅ layouts/app.php -->
etendre('layouts.admin.sidebar');  <!-- ✅ layouts/admin/sidebar.php -->

<!-- ❌ Mauvais -->
etendre('app');                    <!-- Cherche app.php, pas layouts/app.php -->
etendre('layouts/app');            <!-- Utiliser la notation pointée -->
```

---

## 📖 Résumé des Fonctions

| Fonction          | Usage                 | Exemple                     |
| ----------------- | --------------------- | --------------------------- |
| `etendre()`       | Définir le layout     | `etendre('layouts.app');`   |
| `debut_section()` | Commencer une section | `debut_section('contenu');` |
| `fin_section()`   | Terminer une section  | `fin_section('contenu');`   |
| `section()`       | Afficher une section  | `section('contenu');`       |

---

## 🚀 Cas d'Usage Avancés

### Sections Optionnelles avec Défaut

```php
<!-- Layout avec contenu par défaut -->
<?php section('sidebar', '<p>Aucune sidebar</p>'); ?>

<!-- Vue l'override -->
<?php debut_section('sidebar'); ?>
    <div class="sidebar">Custom sidebar</div>
<?php fin_section('sidebar'); ?>
```

### Plusieurs Layouts dans l'App

```
app/Vues/layouts/
├── app.php          (layout principal)
├── blog.php         (layout blog avec sidebar)
├── admin.php        (layout administrateur)
└── auth.php         (layout authentication)
```

Chaque vue choisit son layout:

```php
// Page publique
<?php etendre('layouts.app'); ?>

// Page blog
<?php etendre('layouts.blog'); ?>

// Page admin
<?php etendre('layouts.admin'); ?>

// Page login
<?php etendre('layouts.auth'); ?>
```

---

**Dernière mise à jour:** 2024  
**Version:** BMVC 1.0
