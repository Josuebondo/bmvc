# 📚 BMVC Framework - Documentation Complète

## Vue d'ensemble

**BMVC** est un framework PHP moderne inspiré de Laravel, entièrement écrit en français. Il offre une architecture MVC complète avec routeur, gestion des sessions, rendu de vues et bien plus.

---

## 🚀 Démarrage Rapide

### Installation

```bash
cd c:\xampp\htdocs\BMVC
composer install
php -S localhost:8000
```

Accédez à: **http://localhost:8000**

---

## 📁 Structure du Projet

```
BMVC/
├── public/                 # Point d'entrée web
│   └── index.php          # Bootstrap principal
├── core/                   # Cœur du framework
│   ├── Application.php     # Kernel/Noyau
│   ├── Routeur.php         # Router HTTP
│   ├── Requete.php         # Abstraction Request
│   ├── Reponse.php         # Abstraction Response
│   ├── Vue.php             # Moteur de vues
│   ├── Session.php         # Gestion sessions
│   └── Helpers.php         # Fonctions globales
├── app/
│   ├── Controleurs/        # Controllers (MVC)
│   ├── Vues/               # Templates/Views
│   └── Modeles/            # Models/Data
├── config/
│   └── app.php            # Configuration app
├── routes/
│   └── web.php            # Routes HTTP
├── .env                   # Variables d'environnement
├── composer.json          # Dépendances
└── vendor/                # PSR-4 Autoload
```

---

## 🛣️ Routage

### Définir une route

Fichier: `routes/web.php`

```php
// Route simple
$app->route('GET', '/', 'PageControleur@accueil');

// Route avec paramètre
$app->route('GET', '/article/{id}', 'ArticleControleur@afficher');

// Multiple paramètres
$app->route('GET', '/user/{id}/article/{slug}', 'ArticleControleur@show');

// POST
$app->route('POST', '/article', 'ArticleControleur@creer');
```

---

## 🎮 Contrôleurs

### Créer un contrôleur

Fichier: `app/Controleurs/PageControleur.php`

```php
<?php

namespace App\Controleurs;

class PageControleur {
    public function accueil() {
        return view('accueil', [
            'titre' => 'Bienvenue'
        ]);
    }
}
```

### Accéder à la requête

```php
<?php

namespace App\Controleurs;

class ArticleControleur {
    public function creer() {
        $requete = requete();
        $titre = $requete->input('titre');
        $contenu = $requete->input('contenu');

        // Traiter les données
        return response(['success' => true]);
    }
}
```

---

## 👁️ Vues

### Créer une vue

Fichier: `app/Vues/accueil.php`

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= $titre ?></title>
</head>
<body>
    <h1>Bienvenue sur BMVC</h1>
    <p><?= $contenu ?? 'Contenu par défaut' ?></p>
</body>
</html>
```

### Afficher une vue

```php
// Dans un contrôleur
return view('accueil', ['titre' => 'Page']);
```

---

## 📤 Réponses

### Réponse HTML

```php
return response('Hello World');
```

### Réponse JSON

```php
return response([
    'success' => true,
    'data' => ['id' => 1]
], 200, ['Content-Type' => 'application/json']);
```

### Redirection

```php
return redirect('/autre-page');
```

---

## 📝 Session

### Stocker une valeur

```php
session('user', ['id' => 1, 'name' => 'John']);
```

### Récupérer une valeur

```php
$user = session('user');
```

### Supprimer une valeur

```php
session_destroy('user');
```

---

## 🔧 Helpers Globaux

### Fonctions disponibles

| Fonction                              | Description          |
| ------------------------------------- | -------------------- |
| `view($nom, $data)`                   | Afficher une vue     |
| `response($contenu, $code, $headers)` | Créer une réponse    |
| `redirect($url)`                      | Rediriger            |
| `session($cle, $valeur)`              | Gérer sessions       |
| `requete()`                           | Accéder à la requête |
| `config($cle)`                        | Accéder config       |
| `url($chemin)`                        | Générer URL          |
| `env($variable)`                      | Accéder .env         |

---

## ⚙️ Configuration

Fichier: `config/app.php`

```php
return [
    'nom' => 'BMVC',
    'version' => '1.0.0',
    'environnement' => env('ENVIRONNEMENT', 'production'),
];
```

Fichier: `.env`

```env
APP_NAME=BMVC
APP_ENV=development
APP_URL=http://localhost:8000
```

---

## 📂 Variables d'Environnement

Utilisez le fichier `.env` pour les configurations sensibles:

```env
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
```

Accédez avec: `env('ENVIRONNEMENT')`

---

## 🔄 Cycle de Requête

```
1. Utilisateur accède à URL
2. index.php reçoit la requête
3. Autoload Composer charge les classes
4. Variables .env chargées
5. Application initialisée
6. Routeur analyse l'URL
7. Contrôleur trouvé et appelé
8. Action du contrôleur exécutée
9. Réponse générée
10. Réponse envoyée au client
```

---

## 📋 Requête HTTP

### Récupérer la requête

```php
$requete = requete();
```

### Méthodes disponibles

```php
// Récupérer une donnée
$requete->input('nom');

// Tous les paramètres
$requete->all();

// Vérifier si existe
$requete->existe('email');

// Méthode HTTP
$requete->methode(); // GET, POST, etc

// URL complète
$requete->url();

// Chemin
$requete->chemin();
```

---

## 🌐 Apache / XAMPP

### Déployer sur Apache

1. **Vérifier mod_rewrite**:

   - Ouvrir `C:\xampp\apache\conf\httpd.conf`
   - Vérifier: `LoadModule rewrite_module modules/mod_rewrite.so`

2. **Vérifier AllowOverride**:

   - Section `<Directory "/xampp/htdocs">` doit avoir: `AllowOverride All`

3. **Accès**:
   ```
   http://localhost/bmvc/
   ```

---

## 🛠️ Commandes Utiles

```bash
# Démarrer serveur développement
php -S localhost:8000

# Installer dépendances
composer install

# Mettre à jour dépendances
composer update

# Lister fichiers du projet
dir /B
```

---

## 📦 Dépendances

Le framework utilise:

- **PHP 8.0+** - Langage principal
- **Composer** - Gestion dépendances
- **PSR-4 Autoload** - Chargement automatique classes

---

## 🚨 Dépannage

### Erreur: Classe non trouvée

- Vérifier le namespace
- Vérifier le chemin du fichier
- Lancer `composer dump-autoload`

### Erreur 404: Route non trouvée

- Vérifier la route dans `routes/web.php`
- Vérifier l'URL accédée
- Vérifier la méthode HTTP (GET/POST)

### Erreur: Vue non trouvée

- Vérifier le fichier existe dans `app/Vues/`
- Vérifier le nom exact (sensible à la casse)
- Vérifier l'extension `.php`

---

## 💡 Bonnes Pratiques

✅ **À faire**:

- Organiser contrôleurs par domaine
- Nommer les vues en minuscules
- Utiliser des variables d'environnement
- Séparer logique métier du contrôleur

❌ **À éviter**:

- Code dans index.php
- Données en dur dans le code
- Pas de validation entrées
- Routes trop complexes

---

## 📞 Support

Pour plus d'informations, consultez:

- **QUICK_START.md** - Guide rapide
- **README.md** - Documentation détaillée
- **CONFIGURATION_XAMPP.md** - Configuration Apache
- **ROADMAP_COMPLETE.md** - Phases de développement

---

## 📄 Licence

Framework BMVC - Libre d'utilisation

---

**Bon développement avec BMVC! 🚀**
