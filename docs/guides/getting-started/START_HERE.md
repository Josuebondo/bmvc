# 🚀 Chapitre 2: Démarrage Rapide

**Installation et première utilisation de BMVC en 20 minutes**

---

## 1️⃣ Installation avec Composer

### Prérequis

Avant de commencer, vérifiez que vous avez:

- **PHP 8.0+** (vérifiez avec `php -v`)
- **Composer** (vérifiez avec `composer -v`)
- **MySQL/MariaDB** (optionnel, pour la base de données)

### Installation du Framework

**Étape 1:** Créer un nouveau projet BMVC

```bash
composer create-project bmvc/bmvc mon-app
```

**Étape 2:** Aller dans le dossier

```bash
cd mon-app
```



✅ **Fait!** Le framework est installé.

---

## 2️⃣ Démarrage du Serveur

### Méthode 1: Avec la Commande CLI (Recommandé)

```bash
php bmvc -d
```

ou le nom long:

```bash
php bmvc demarrer
```

Résultat:

```
🚀 Serveur BMVC lancé sur http://localhost:8000
Appuyez sur CTRL+C pour arrêter...
```

### Méthode 2: Avec PHP Natif

```bash
php -S localhost:8000 -t public/
```

### Méthode 3: Avec Port Personnalisé

```bash
php bmvc -d --port=3000
```

ou:

```bash
php bmvc -d -p 3000
```

---

## 3️⃣ Vérification des Tests

BMVC inclut 35 tests pour vérifier que tout fonctionne correctement.

### Exécuter tous les tests

```bash
composer test
```

Résultat attendu:

```
PHPUnit 9.5.28
✅ 35 tests, 0 failures, 0 errors
✅ Code Coverage: 85%+
```

### Exécuter uniquement les tests unitaires

```bash
composer test:unit
```

### Exécuter uniquement les tests fonctionnels

```bash
composer test:functional
```

### Générer un rapport de couverture

```bash
composer test:coverage
```

---

## 4️⃣ Hello World Minimal

### Étape 1: Ouvrir le fichier routes

Fichier: `routes/web.php`

Contenu actuel:

```php
<?php

use Core\Routeur;

// Affiche "Hello World"
Routeur::obtenir('/', 'PageControleur@accueil');
```

### Étape 2: Créer le Contrôleur

**Option A: Créer automatiquement avec CLI**

```bash
php bmvc creer:controller PageController
```
ou:
```bash
php bmvc -cc PageController
```

**Option B: Créer manuellement**

Créer le fichier: `app/Controleurs/PageControleur.php`

```php
<?php

namespace App\Controleurs;

use App\BaseControleur;
use Core\Requete;
use Core\Reponse;

class PageControleur extends BaseControleur
{
    public function accueil(Requete $request, Reponse $response): string
    {
        return "Hello World! 🚀 BMVC fonctionne!";
    }
}
```

### Étape 3: Tester dans le Navigateur

1. Lancez le serveur: `php bmvc -d`
2. Ouvrez: `http://localhost:8000`
3. Vous devriez voir: **"Hello World! 🚀 BMVC fonctionne!"**

✅ **Bravo!** Vous avez créé votre premier "Hello World"!

---

## 5️⃣ Hello World avec Vue

Créons maintenant un exemple avec une vue (HTML).

### Étape 1: Mettre à jour le Contrôleur

Fichier: `app/Controleurs/PageControleur.php`

```php
<?php

namespace App\Controleurs;

use App\BaseControleur;
use Core\Requete;
use Core\Reponse;

class PageControleur extends BaseControleur
{
    public function accueil(Requete $request, Reponse $response): string
    {
        $donnees = [
            'titre' => 'Bienvenue sur BMVC',
            'message' => 'Votre framework MVC préféré!'
        ];

        return $this->afficher('pages/accueil', $donnees);
    }
}
```

### Étape 2: Créer la Vue

Créer le fichier: `app/Vues/pages/accueil.php`

```html
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo e($titre); ?></title>
    <style>
      body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      }
      .container {
        text-align: center;
        background: white;
        padding: 50px;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      }
      h1 {
        color: #333;
        margin: 0;
      }
      p {
        color: #666;
        font-size: 18px;
      }
      .rocket {
        font-size: 60px;
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="rocket">🚀</div>
      <h1><?php echo e($titre); ?></h1>
      <p><?php echo e($message); ?></p>
      <hr />
      <p style="color: #999; font-size: 14px;">🎉 Framework BMVC v1.0.0</p>
    </div>
  </body>
</html>
```

### Étape 3: Tester

1. Actualiser votre navigateur: `http://localhost:8000`
2. Vous devriez voir une belle page d'accueil

✅ **Excellent!** Vous avez créé votre première page avec vue!

---

## 6️⃣ Exemple avec Paramètres

### Créer une Route Paramétrée

Fichier: `routes/web.php`

```php
<?php

use Core\Routeur;

Routeur::obtenir('/', 'PageControleur@accueil');

// Nouvelle route avec paramètre {nom}
Routeur::obtenir('/saluer/{nom}', 'PageControleur@saluer');
```

### Créer la Méthode du Contrôleur

Fichier: `app/Controleurs/PageControleur.php`

Ajouter cette méthode:

```php
public function saluer(Requete $request, Reponse $response): string
{
    $nom = $request->param('nom');

    return $this->afficher('pages/saluer', [
        'nom' => $nom
    ]);
}
```

### Créer la Vue

Fichier: `app/Vues/pages/saluer.php`

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Salutation</title>
  </head>
  <body>
    <h1>
      Bonjour,
      <?php echo e($nom); ?>! 👋
    </h1>
    <p>Bienvenue sur BMVC!</p>
    <a href="/">← Retour</a>
  </body>
</html>
```

### Tester

Ouvrir: `http://localhost:8000/saluer/Jean`

Résultat: **"Bonjour, Jean! 👋"**

---

## 7️⃣ Commandes Utiles

### Gestion du Serveur

| Commande                        | Raccourci             | Description       |
| ------------------------------- | --------------------- | ----------------- |
| `php bmvc demarrer`             | `php bmvc -d`         | Lancer le serveur |
| `php bmvc demarrer --port=3000` | `php bmvc -d -p 3000` | Port personnalisé |

### Génération de Code

| Commande                   | Raccourci       | Description         |
| -------------------------- | --------------- | ------------------- |
| `php bmvc make:controller` | `php bmvc -cc`  | Créer un contrôleur |
| `php bmvc make:model`      | `php bmvc -cm`  | Créer un modèle     |
| `php bmvc make:migration`  | `php bmvc -cmg` | Créer une migration |

### Tests

| Commande                   | Description             |
| -------------------------- | ----------------------- |
| `composer test`            | Exécuter tous les tests |
| `composer test:unit`       | Tests unitaires         |
| `composer test:functional` | Tests fonctionnels      |
| `composer test:coverage`   | Rapport de couverture   |

### Qualité du Code

| Commande            | Description             |
| ------------------- | ----------------------- |
| `composer lint`     | Vérifier la syntaxe PHP |
| `composer phpstan`  | Analyse statique        |
| `composer cs-check` | Vérifier PSR-12         |
| `composer check`    | Tous les vérifications  |

---

## 8️⃣ Structure Créée

Après avoir suivi ce chapitre, votre projet ressemble à ceci:

```
mon-app/
├── app/
│   ├── Controleurs/
│   │   └── PageControleur.php      ← Votre contrôleur
│   ├── Vues/
│   │   └── pages/
│   │       ├── accueil.php         ← Votre première vue
│   │       └── saluer.php          ← Votre deuxième vue
│   └── Modeles/
│
├── routes/
│   └── web.php                     ← Vos routes
│
├── public/
│   └── index.php                   ← Point d'entrée
│
├── config/
│   └── (configuration)
│
├── core/
│   └── (framework core)
│
├── composer.json
├── .env.example
├── phpunit.xml
└── bmvc                            ← Commande CLI
```

---

## 9️⃣ Prochaines Étapes

### Vous avez fini ce chapitre? 🎉

**Option 1: Continuer simplement**

👉 [Chapitre 3: Quick Start →](QUICKSTART.md)

**Option 2: Découvrir plus d'exemples**

👉 [Chapitre 5: Exemples Pratiques →](../../examples/)

**Option 3: Maîtriser le framework**

👉 [Chapitre 4: Guide Complet →](../usage/GUIDE_UTILISATION.md)

---

## 🎯 Résumé du Chapitre

✅ Vous avez:

- Installé BMVC en 1 minute
- Lancé le serveur local
- Vérifiéles tests
- Créé "Hello World"
- Créé une page avec vue
- Compris le système de routes avec paramètres
- Découvert les commandes CLI utiles

**Temps total:** ~20 minutes

---

**Framework BMVC v1.0.0**

_Prêt à apprendre la suite?_ 👉 [Continuez →](QUICKSTART.md)
