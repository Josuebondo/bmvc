# ⚡ BMVC - Guide de Démarrage Rapide

## 🚀 Lancez-vous en 5 minutes !

### Option 1 : Serveur PHP de développement (Plus rapide)

```bash
# Dans le terminal, à la racine du projet
cd c:\xampp\htdocs\BMVC

# Lancez le serveur
php -S localhost:8000

# Accédez à l'application
# Ouvrez http://localhost:8000 dans votre navigateur
```

**✅ Avantages:**

- Aucune configuration
- Installation instantanée
- Parfait pour le développement

**❌ Inconvénients:**

- Un seul développeur à la fois
- Pas idéal pour la production

---

### Option 2 : XAMPP + Apache (Professionnel)

#### Étape 1 : Activer mod_rewrite

1. Ouvrez **XAMPP Control Panel**
2. Cliquez **Config** → **Apache (httpd.conf)**
3. Cherchez : `#LoadModule rewrite_module modules/mod_rewrite.so`
4. **Supprimez le `#`** au début
5. Sauvegardez (Ctrl+S)

#### Étape 2 : Démarrer Apache

1. Cliquez **Start** sur Apache dans XAMPP
2. Status devrait passer à vert

#### Étape 3 : Accédez à l'application

```
http://localhost/bmvc/
```

**✅ Avantages:**

- Configuration professionnelle
- Plusieurs développeurs
- Idéal pour la production
- Identical aux serveurs réels

**⚠️ Nécessite:**

- Configuration Apache (voir CONFIGURATION_XAMPP.md)
- mod_rewrite activé

---

## 🧪 Tests

### Via Développement (PHP -S)

```bash
# Terminal 1 : Lance le serveur
php -S localhost:8000

# Terminal 2 : Tests rapides
curl http://localhost:8000/
curl http://localhost:8000/auth/login
```

### Via XAMPP (Apache)

```bash
# Ouvrez dans le navigateur
http://localhost/bmvc/

# Vérifiez la configuration
http://localhost/bmvc/verifier-apache.php

# Explorez l'API
http://localhost/bmvc/api-docs.php
```

---

## 📖 Documentation Complète

| Document                   | Contenu                            |
| -------------------------- | ---------------------------------- |
| **README.md**              | Structure du projet & architecture |
| **ROADMAP_COMPLETE.md**    | Phases d'implémentation (8 phases) |
| **CONFIGURATION_XAMPP.md** | Configuration Apache complète      |
| **verifier-apache.php**    | Diagnostic de configuration        |
| **api-docs.php**           | Documentation interactive          |

---

## 🛠️ Structure du Projet

```
BMVC/
├── public/              # Point d'entrée web
│   ├── index.php       # Bootstrap
│   ├── .htaccess       # Config Apache
│   ├── verifier-apache.php
│   └── api-docs.php
├── app/                # Application
│   ├── Controleurs/   # Controllers
│   ├── Modeles/        # Models
│   └── Vues/           # Views (templates)
├── core/               # Framework
│   ├── Application.php
│   ├── Routeur.php
│   ├── Requete.php
│   ├── Reponse.php
│   ├── Session.php
│   ├── Vue.php
│   └── Helpers.php
├── config/             # Configuration
├── routes/             # Routes
├── stockage/           # Logs & cache
├── .env               # Variables d'environnement
├── .htaccess          # Config racine
├── composer.json      # Dépendances
└── CONFIGURATION_XAMPP.md
```

---

## 💻 Commandes Utiles

```bash
# Installation des dépendances
composer install

# Mise à jour Composer
composer update

# Régénérer l'autoload
composer dump-autoload

# Serveur PHP (dev)
php -S localhost:8000

# Tests
curl http://localhost:8000/
curl http://localhost:8000/auth/login
```

---

## 🔧 Configuration

### .env (Fichier de configuration)

```env
APP_NAME=BMVC
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=bmvc
DB_USERNAME=root
DB_PASSWORD=
```

### Changer l'environnement

```env
APP_ENV=production  # Pour la production
APP_DEBUG=false     # Désactiver debug en prod
```

---

## 📝 Routes (routes/web.php)

```php
use Core\Routeur;

// GET /
Routeur::obtenir('/', 'AccueilControleur@index');

// GET /auth/login
Routeur::obtenir('/auth/login', 'AuthControleur@afficherLogin');

// POST /auth/login
Routeur::publier('/auth/login', 'AuthControleur@traiterLogin');

// Ajouter une route
Routeur::obtenir('/users/{id}', 'UserControleur@show');
```

---

## 🎮 Créer un Contrôleur

**Fichier:** `app/Controleurs/MonControleur.php`

```php
<?php
namespace App\Controleurs;

use Core\Requete;
use Core\Reponse;

class MonControleur {
    public function index(Requete $request, Reponse $response) {
        return vue('ma-vue', ['titre' => 'Hello']);
    }
}
```

---

## 👁️ Créer une Vue

**Fichier:** `app/Vues/ma-vue.php`

```php
<h1><?= htmlspecialchars($titre) ?></h1>
<p>Bienvenue sur mon application!</p>
```

---

## 📊 Tester les Routes

### Page d'accueil

```
http://localhost:8000/
http://localhost/bmvc/  (XAMPP)
```

### Login

```
http://localhost:8000/auth/login
http://localhost/bmvc/auth/login  (XAMPP)
```

### Documentation API

```
http://localhost:8000/api-docs.php
http://localhost/bmvc/api-docs.php  (XAMPP)
```

---

## 🐛 Dépannage

### "Erreur 404"

→ Vérifiez que mod_rewrite est activé (voir CONFIGURATION_XAMPP.md)

### "Erreur 500"

→ Regardez dans `stockage/logs/app.log`

### "Fichiers .htaccess ignorés"

→ Assurez-vous que AllowOverride All est configuré

### "mod_rewrite not available"

→ Activez-le dans Apache (voir guide)

---

## 🎓 Prochaines Étapes

1. **Explorez le code** dans `app/Controleurs/`
2. **Créez vos routes** dans `routes/web.php`
3. **Créez vos contrôleurs** dans `app/Controleurs/`
4. **Créez vos vues** dans `app/Vues/`
5. **Allez à la Phase 2** : Modèles et Base de Données

---

## 📞 Besoin d'aide ?

1. Consultez **CONFIGURATION_XAMPP.md** pour Apache
2. Consultez **README.md** pour l'architecture
3. Consultez **ROADMAP_COMPLETE.md** pour les phases
4. Testez via **verifier-apache.php** pour diagnostiquer

---

## ✨ Bon développement !

🚀 Vous êtes maintenant prêt à développer avec **BMVC** !

Choisissez votre approche :

- **Développement rapide** : `php -S localhost:8000`
- **Professionnel** : Apache + XAMPP

Happy coding! 💻
