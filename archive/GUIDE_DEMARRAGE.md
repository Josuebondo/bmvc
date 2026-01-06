# 📖 BMVC - Guide de démarrage rapide

## 🚀 Installation (5 minutes)

### 1. Prérequis

```
- PHP 8.0+
- MySQL/MariaDB
- Apache mod_rewrite
- XAMPP (recommandé)
```

### 2. Configuration

```bash
# Créer la base de données
CREATE DATABASE bmvc;

# Exécuter les migrations
cd C:\xampp\htdocs\BMVC
php migrate.php
```

### 3. Accès

```
URL:       http://localhost/BMVC/
Email:     admin@exemple.com
Password:  admin123
```

---

## 📚 Documentation (15 minutes)

### Documents à lire

**1. README.md** - Vue d'ensemble générale

```
- Architecture
- Installation
- 11 Features principales
- Guides pratiques
```

**2. PHASE5_6_STATUS.md** - Détails Phase 5 & 6

```
- Validateur
- Services
- Cache
- Erreurs
- Logs
```

**3. EXEMPLES_PHASE5_6.php** - Code d'utilisation

```
10 exemples concrets de code
Comment utiliser chaque feature
```

**4. test_phase5_6.php** - Tests complets

```
Visiter: http://localhost/BMVC/test_phase5_6.php
Tests interactifs
Validation automatique
```

---

## 🎯 Utilisation rapide

### Créer une page

**1. Contrôleur**

```php
// app/Controleurs/MonControleur.php

namespace App\Controleurs;

class MonControleur extends \App\BaseControleur
{
    public function index()
    {
        return $this->afficher('mon.index', [
            'titre' => 'Ma page'
        ]);
    }
}
```

**2. Vue**

```php
<!-- app/Vues/mon/index.php -->

<div class="container">
    <h1><?= e($titre) ?></h1>
    <p>Bienvenue sur ma page!</p>
</div>
```

**3. Route**

```php
// routes/web.php

Routeur::obtenir('/ma-page', 'MonControleur@index')->nom('ma.page');
```

**4. Accès**

```
http://localhost/BMVC/ma-page
```

---

### Valider un formulaire

```php
// Dans le contrôleur
$v = validateur();
$v->ajouter('email', ['requis', 'email']);
$v->ajouter('password', ['requis', 'min:8']);

if ($v->valider($_POST)) {
    // Valide!
    notification()->succes('Enregistré!');
} else {
    // Erreurs
    $_SESSION['erreurs'] = $v->erreurs();
}
```

**Dans la vue:**

```php
<form method="POST">
    <?= csrf_input() ?>

    <input name="email" value="<?= ancien('email') ?>">
    <?php if (!empty($erreurs['email'])): ?>
        <span class="error"><?= $erreurs['email'][0] ?></span>
    <?php endif; ?>

    <button type="submit">Envoyer</button>
</form>
```

---

### Utiliser les services

**Authentification:**

```php
$user = auth_service()->connexion('email@exemple.com', 'password');
notification()->bienvenue($email, $nom);
```

**Validation métier:**

```php
$v = validation_service()->validerArticle($_POST);
$v = validation_service()->validerMotDePasseFort($password);
```

**Upload fichiers:**

```php
$fichier = upload()
    ->setTailleMax(5)
    ->uploader($_FILES['avatar']);
```

**Notifications:**

```php
notification()->succes('Succès!');
notification()->erreur('Erreur!');
notification()->envoyerEmail($email, $sujet, $contenu);
```

---

### Mettre en cache

```php
// Enregistrer
Cache::mettre('user_1', $user, 3600);

// Récupérer
$user = Cache::obtenir('user_1');

// Souvenir (obtenir ou mettre en cache)
$user = Cache::souvenir('user_1', function() {
    return \App\Modeles\Utilisateur::trouver(1);
}, 3600);

// Vérifier
if (Cache::existe('user_1')) { }

// Supprimer
Cache::oublier('user_1');

// Vider tout
Cache::vider();
```

---

## 🧪 Tests

### Tester une feature

```
Visiter: http://localhost/BMVC/test_phase5_6.php

Résultats:
- ✅ Validateur
- ✅ Services
- ✅ Cache
- ✅ Helpers
- ✅ Erreurs
```

### Tests manuels

```
1. Inscription: http://localhost/BMVC/register
2. Login: http://localhost/BMVC/login
3. Articles: http://localhost/BMVC/articles
4. Upload: Créer article avec image
5. Validation: Essayer d'envoyer un formulaire vide
```

---

## 🔧 Configuration

### Mode Debug/Production

**Dans public/index.php:**

```php
GestionnaireErreurs::initialiser(
    debug: env('DEBOGAGE', true),  // true = debug, false = production
    cheminLogs: __DIR__ . '/../storage/logs/'
);
```

### Logs

```
storage/logs/erreurs-2026-01-05.log

Format: [DATE] [TYPE] Message | Fichier:ligne
```

---

## 📁 Structure importante

```
app/
├── Controleurs/     ← Vos contrôleurs
├── Modeles/        ← Vos modèles
├── Services/       ← Services métier
└── Vues/           ← Vos vues

core/
├── Validateur.php      ← Validation
├── Cache.php           ← Cache
├── GestionnaireErreurs.php  ← Erreurs
└── Helpers.php        ← Fonctions globales

routes/
└── web.php         ← Définir vos routes

storage/
├── cache/          ← Cache automatique
└── logs/           ← Logs erreurs
```

---

## 🔑 Fonctions les plus utiles

### Helpers

```php
validateur()                // Validateur
notification()              // Notifications
upload()                    // Upload fichiers
auth_service()             // Authentification
validation_service()       // Validation métier
Cache::mettre/obtenir/souvenir()  // Cache
e($text)                   // Échappe (XSS)
ancien('field')            // Ancienne valeur
auth()                     // Utilisateur connecté
est_connecte()            // Vérifié connexion
csrf_input()              // Token CSRF
```

### Contrôleur

```php
$this->afficher($vue, $donnees)    // Afficher vue
$this->redirection($url)            // Rediriger
$_SESSION['erreurs'] = $erreurs     // Erreurs
$_SESSION['flash'] = $message       // Message flash
$_SESSION['anciens_inputs'] = $data // Remplissage form
```

### Vue

```php
<?= e($text) ?>              // Afficher échappé
<?= ancien('field') ?>       // Ancienne valeur
<?= csrf_input() ?>          // Token CSRF
<?php if (est_connecte()): ?> // Vérifier connexion
<?= auth()->nom ?>           // Utilisateur actuel
```

---

## 🐛 Débogage

### Voir les erreurs

```php
// En mode debug (true dans GestionnaireErreurs)
// Les erreurs s'affichent directement

// En mode production (false)
// Les erreurs vont dans les logs
```

### Consulter les logs

```bash
# Logs d'erreurs
cat storage/logs/erreurs-2026-01-05.log

# Format: [DATE HEURE] [TYPE] Message | Fichier:ligne
```

### Utiliser dump()

```php
dump($variable);  // Affiche sans die
dd($variable);    // Affiche et die
```

---

## 📞 Support rapide

### Problème: Les routes donnent 404

**Solution:**

1. Vérifier mod_rewrite activé
2. Vérifier `.htaccess` dans public/
3. Vérifier la route dans `routes/web.php`

### Problème: Erreur "Class not found"

**Solution:**

1. Vérifier le namespace
2. Vérifier le nom du fichier
3. Vérifier que la classe existe

### Problème: Base de données vide

**Solution:**

```bash
php migrate.php  # Exécuter les migrations
```

### Problème: Upload échoue

**Solution:**

```php
$upload->setTailleMax(10);  // Augmenter taille
$upload->setRepertoire('/chemin/');  // Bon chemin
chmod(public/uploads, 0755);  // Permissions
```

---

## 🎯 Workflow typique

### 1. Créer une nouvelle page

```
1. Créer le contrôleur
2. Créer la vue
3. Ajouter la route
4. Tester
```

### 2. Ajouter un formulaire

```
1. Créer le formulaire dans la vue
2. Ajouter la validation dans le contrôleur
3. Traiter les données
4. Afficher les erreurs
```

### 3. Stocker en cache

```
1. Utiliser Cache::souvenir()
2. Invalider avec Cache::oublier()
3. Tester les performances
```

### 4. Envoyer email

```
$notif = notification();
$notif->envoyerEmail($email, $sujet, $corps);
```

---

## ✅ Checklist de démarrage

- [ ] Installation complète
- [ ] Migration exécutée
- [ ] Login fonctionne
- [ ] Articles affichés
- [ ] Lecture README.md
- [ ] Tests validés
- [ ] Exemples copiés
- [ ] Créer 1ère page
- [ ] Ajouter 1er formulaire
- [ ] Tester 1er upload

---

## 🚀 Prochaines étapes

**Courte durée:**

1. Lire la documentation
2. Tester les exemples
3. Créer une page
4. Ajouter un formulaire

**Moyen terme:**

1. Créer un nouveau modèle
2. Implémenter un service
3. Ajouter un contrôleur
4. Concevoir une vue

**Long terme:**

1. Admin panel
2. REST API
3. Tests unitaires
4. Déploiement

---

## 📚 Ressources

**Incluses:**

- README.md (Documentation)
- PHASE5_6_STATUS.md (Features)
- EXEMPLES_PHASE5_6.php (Exemples)
- test_phase5_6.php (Tests)
- CONCLUSION.md (Résumé)

**À consulter:**

- Articles créés
- Tests existants
- Contrôleurs exemples
- Modèles existants

---

## 💡 Tips & Tricks

### Performance

```php
// Utiliser le cache!
$data = Cache::souvenir('key', fn() => loadData(), 3600);
```

### Sécurité

```php
// Toujours échappe les données
<?= e($variable) ?>

// Toujours utiliser CSRF
<?= csrf_input() ?>
```

### Maintenabilité

```php
// Créer des services pour la logique réutilisable
// Mettre les contrôleurs simples et propres
// Commenter le code complexe
```

### Debugging

```php
// Mode debug = plus d'infos
// Logs = trace erreurs production
// Tests = validation automatique
```

---

## 📞 FAQ

**Q: Comment créer un modèle?**

```php
class MonModele extends Modele {
    protected $table = 'ma_table';
}
```

**Q: Comment créer un contrôleur?**

```php
class MonControleur extends BaseControleur {
    public function index() { }
}
```

**Q: Comment valider?**

```php
$v = validateur();
$v->ajouter('field', ['requis']);
$v->valider($donnees);
```

**Q: Comment mettre en cache?**

```php
Cache::souvenir('key', function() { }, 3600);
```

**Q: Comment envoyer un email?**

```php
notification()->envoyerEmail($email, $sujet, $contenu);
```

---

**BMVC Framework - Démarrage rapide**  
✅ Prêt à développer!
