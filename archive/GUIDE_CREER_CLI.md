# 📖 GUIDE - Comment Ajouter une Commande CLI

Ce guide explique comment **créer et ajouter une nouvelle commande CLI** au framework BMVC.

---

## 🎯 Objectif

Vous allez apprendre à:

1. Créer une nouvelle méthode CLI
2. L'enregistrer dans le système
3. Tester la nouvelle commande
4. Ajouter un raccourci (optionnel)

---

## 📋 Structure du Fichier CLI

Le fichier `bmvc` est l'exécutable principal. Voici sa structure:

```
bmvc (exécutable PHP)
├── parseArguments()          // Parse les arguments
├── expandOption()             // Convertit -p en port
├── expandCommande()           // Convertit -cc en creer:controleur
├── executer()                 // Match et exécute la commande
├── creerControleur()          // Commande 1
├── creerModele()              // Commande 2
├── creerMigration()           // Commande 3
├── executerMigrations()       // Commande 4
├── demarrerServeur()          // Commande 5
├── tinker()                   // Commande 6
├── afficherAide()             // Commande 7
├── succes()                   // Helper pour messages verts
└── erreur()                   // Helper pour messages rouges
```

---

## 📝 Exemple 1: Ajouter une Commande Simple

### Étape 1: Créer la Méthode

Ouvrez `bmvc` et ajoutez une nouvelle méthode:

```php
/**
 * Nettoyer les fichiers temporaires
 */
protected function nettoyerCache(): void
{
    $dossierCache = __DIR__ . '/storage/cache';

    if (!is_dir($dossierCache)) {
        $this->erreur("Le dossier cache n'existe pas");
    }

    $fichiers = glob($dossierCache . '/*');
    $compte = 0;

    foreach ($fichiers as $fichier) {
        if (is_file($fichier)) {
            unlink($fichier);
            $compte++;
        }
    }

    $this->succes("Cache nettoyé! ($compte fichiers supprimés)");
}
```

### Étape 2: Enregistrer la Commande

Dans la méthode `executer()`, ajoutez le match:

```php
public function executer(): void
{
    match ($this->commande) {
        'creer:controleur' => $this->creerControleur(),
        'creer:modele' => $this->creerModele(),
        'creer:migration' => $this->creerMigration(),
        'migrer' => $this->executerMigrations(),
        'demarrer' => $this->demarrerServeur(),
        'tinker' => $this->tinker(),
        'nettoyer:cache' => $this->nettoyerCache(),  // ✅ NOUVELLE COMMANDE
        'aide', '--help', '-h' => $this->afficherAide(),
        default => $this->erreur("Commande inconnue: {$this->commande}"),
    };
}
```

### Étape 3: Ajouter le Raccourci (Optionnel)

Dans `expandCommande()`:

```php
protected function expandCommande(string $commande): string
{
    $aliases = [
        'cc' => 'creer:controleur',
        'cm' => 'creer:modele',
        'cmg' => 'creer:migration',
        'mg' => 'migrer',
        'd' => 'demarrer',
        't' => 'tinker',
        'a' => 'aide',
        'nc' => 'nettoyer:cache',  // ✅ NOUVEAU RACCOURCI
    ];

    return $aliases[$commande] ?? $commande;
}
```

### Étape 4: Mettre à Jour l'Aide

Dans `afficherAide()`, ajoutez votre commande:

```php
protected function afficherAide(): void
{
    echo <<<'TEXT'
╔═══════════════════════════════════════════════════════════════╗
║                     🚀 BMVC CLI v1.0                         ║
║          Mini-Laravel en Français - Interface Ligne          ║
╚═══════════════════════════════════════════════════════════════╝

📋 COMMANDES DISPONIBLES:

  🏗️  CRÉATION
    creer:controleur <Nom>    Créer un contrôleur (-cc)
    creer:modele <Nom>        Créer un modèle (-cm)
    creer:migration <Nom>     Créer une migration (-cmg)

  🗄️  BASE DE DONNÉES
    migrer                    Exécuter les migrations (-mg)

  🖥️  SERVEUR
    demarrer [--port=8000]    Démarrer le serveur dev (-d)
    tinker                    REPL interactif (-t)

  🧹 MAINTENANCE
    nettoyer:cache            Nettoyer les fichiers temporaires (-nc)

  ℹ️  AUTRES
    aide                      Afficher cette aide (-a)

📝 EXEMPLES:

  $ php bmvc creer:controleur UserControleur
  $ php bmvc -cc UserControleur
  $ php bmvc nettoyer:cache
  $ php bmvc -nc
  $ php bmvc demarrer -p 3000

🎯 DOCUMENTATION: https://github.com/bmvc/framework

TEXT;
}
```

### Étape 5: Tester la Commande

```bash
# Forme longue
php bmvc nettoyer:cache

# Raccourci
php bmvc -nc

# Vérifier l'aide
php bmvc aide
```

---

## 📝 Exemple 2: Commande avec Arguments

Créez une commande qui accepte des arguments:

### Étape 1: Créer la Méthode

```php
/**
 * Générer une clé secrète aléatoire
 */
protected function genererCle(): void
{
    $longueur = $this->arguments[0] ?? 32;

    if (!is_numeric($longueur) || $longueur < 16) {
        $this->erreur("La longueur doit être un nombre >= 16");
    }

    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $cle = '';

    for ($i = 0; $i < $longueur; $i++) {
        $cle .= $caracteres[random_int(0, strlen($caracteres) - 1)];
    }

    echo "\n📝 Clé générée:\n";
    echo "┌─────────────────────────────────────┐\n";
    echo "│ " . $cle . " │\n";
    echo "└─────────────────────────────────────┘\n\n";

    $this->succes("Copiez cette clé dans votre fichier .env");
}
```

### Étape 2: Enregistrer et Tester

```bash
# Longueur par défaut (32 caractères)
php bmvc generer:cle

# Longueur personnalisée
php bmvc generer:cle 64
```

---

## 📝 Exemple 3: Commande avec Options

Commande avec options (--format, --verbose, etc):

### Étape 1: Créer la Méthode

```php
/**
 * Afficher les informations du projet
 *
 * Options:
 *   --format=json     Format de sortie (json ou texte)
 *   --verbose         Afficher les détails
 */
protected function infos(): void
{
    $format = $this->options['format'] ?? 'texte';
    $verbose = isset($this->options['verbose']);

    $donnees = [
        'nom' => env('NOM_APPLICATION', 'BMVC'),
        'environnement' => env('ENVIRONNEMENT', 'development'),
        'version' => '1.0.0',
        'php_version' => phpversion(),
    ];

    if ($verbose) {
        $donnees['dossiers'] = [
            'app' => __DIR__ . '/app',
            'storage' => __DIR__ . '/storage',
            'public' => __DIR__ . '/public',
        ];
    }

    if ($format === 'json') {
        echo json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo "\n🎯 Informations BMVC:\n";
        echo "─────────────────────────────\n";
        foreach ($donnees as $cle => $valeur) {
            if (is_array($valeur)) {
                echo "  $cle:\n";
                foreach ($valeur as $k => $v) {
                    echo "    - $k: $v\n";
                }
            } else {
                echo "  $cle: $valeur\n";
            }
        }
        echo "─────────────────────────────\n\n";
    }
}
```

### Étape 2: Tester

```bash
# Format texte (par défaut)
php bmvc infos

# Format texte avec détails
php bmvc infos --verbose

# Format JSON
php bmvc infos --format=json

# Format JSON avec détails
php bmvc infos --format=json --verbose
```

---

## 📝 Exemple 4: Commande Complexe (Générateur de Code)

Créez un générateur personnalisé:

```php
/**
 * Générer un contrôleur API complet
 */
protected function creerControleurAPI(): void
{
    $nom = $this->arguments[0] ?? null;

    if (!$nom) {
        $this->erreur("Veuillez spécifier le nom: php bmvc creer:controleur-api UserAPI");
    }

    $chemin = __DIR__ . "/app/Controleurs/{$nom}.php";

    if (file_exists($chemin)) {
        $this->erreur("Le contrôleur $nom existe déjà!");
    }

    $contenu = <<<PHP
<?php
namespace App\Controleurs;

use Core\APIResponse;
use Core\APIToken;

class $nom
{
    /**
     * GET /api/exemple
     */
    public function lister()
    {
        return APIResponse::succes(
            ['donnees' => []],
            'Données récupérées'
        )->envoyer();
    }

    /**
     * POST /api/exemple
     */
    public function creer()
    {
        // Validation
        \$donnees = [
            'nom' => \$_POST['nom'] ?? null,
        ];

        if (!validate(\$donnees)) {
            return APIResponse::erreur('Données invalides', [], 400)->envoyer();
        }

        return APIResponse::succes(
            \$donnees,
            'Créé avec succès',
            201
        )->envoyer();
    }
}
PHP;

    if (!is_dir(dirname($chemin))) {
        mkdir(dirname($chemin), 0755, true);
    }

    file_put_contents($chemin, $contenu);
    $this->succes("Contrôleur API créé: $chemin");
}
```

---

## 🎨 Bonnes Pratiques

### ✅ À Faire

```php
// ✅ Utiliser les messages de succès/erreur
$this->succes("Opération réussie");
$this->erreur("Quelque chose s'est mal passé");

// ✅ Vérifier les arguments
$nom = $this->arguments[0] ?? null;
if (!$nom) {
    $this->erreur("L'argument 'nom' est requis");
}

// ✅ Vérifier les options
$verbose = isset($this->options['verbose']);
$port = $this->options['port'] ?? 8000;

// ✅ Ajouter des espaces pour la lisibilité
echo "\n";
echo "═════════════════════════════════\n";
echo "Résultat\n";
echo "═════════════════════════════════\n\n";
```

### ❌ À Éviter

```php
// ❌ Pas de echo() brut
echo "Ceci n'est pas formaté";

// ❌ Ne pas supposer que les arguments existent
$nom = $this->arguments[0];  // Peut lever une erreur!

// ❌ Ne pas utiliser exit() directement
exit(1);  // Utiliser $this->erreur() à la place

// ❌ Ne pas ignorer les erreurs de fichier
file_put_contents($chemin, $contenu);  // Pas de vérification!
```

---

## 📚 Structure Recommandée pour une Commande

````php
/**
 * Description courte de ce que fait la commande
 *
 * Utilisation:
 *   php bmvc nom:commande [arguments] [--options]
 *
 * Arguments:
 *   argument1         Description
 *
 * Options:
 *   --option=valeur   Description
 *   --flag            Description (booléen)
 */
protected function nomCommande(): void
{
    // 1. Récupérer et valider les arguments
    $argument = $this->arguments[0] ?? null;
    if (!$argument) {
        $this->erreur("L'argument 'argument' est requis");
    }

    // 2. Récupérer les options
    $option = $this->options['option'] ?? 'valeur_defaut';
    $verbose = isset($this->options['verbose']);

    // 3. Vérifier les conditions
    if (!is_valid($argument)) {
        $this->erreur("L'argument n'est pas valide");
    }

    // 4. Exécuter la logique
---

## 📝 Exemple 5: Commande Générant Plusieurs Fichiers (Module Complet)

Créez une commande qui génère **contrôleur + modèle + vue** en une seule commande:

```bash
# Créer un module complet
php bmvc creer:module Produit
php bmvc -cmd Produit
````

**Output:**

```
✓ Module Produit créé avec succès!

📁 Fichiers créés:
  ✓ Contrôleur: ./app/Controleurs/Produit.php
  ✓ Modèle:     ./app/Modeles/Produit.php
  ✓ Vue (index): ./app/Vues/produit/index.php

📋 Prochaines étapes:
  1. Créer la migration: php bmvc -cmg CreateProduitTable
  2. Ajouter les routes dans routes/web.php
  3. Créer les autres vues (creer.php, editer.php)
```

### Voici à quoi ressemble la méthode:

```php
/**
 * Créer un module complet (Contrôleur + Modèle + Vue)
 */
protected function creerModule(): void
{
    $nom = $this->arguments[0] ?? null;

    if (!$nom) {
        $this->erreur('Usage: php bmvc creer:module NomModule');
    }

    // Créer les dossiers
    $cheminVues = __DIR__ . "/app/Vues/" . strtolower($nom);
    if (!is_dir($cheminVues)) {
        mkdir($cheminVues, 0755, true);
    }

    // 1. Créer le Contrôleur avec actions CRUD
    // 2. Créer le Modèle avec table
    // 3. Créer la Vue index avec tableau

    $this->succes("Module {$nom} créé avec succès!");
    echo "\n📁 Fichiers créés:\n";
    echo "  ✓ Contrôleur: ./app/Controleurs/{$nom}.php\n";
    echo "  ✓ Modèle:     ./app/Modeles/{$nom}.php\n";
    echo "  ✓ Vue (index): ./app/Vues/" . strtolower($nom) . "/index.php\n";
}
```

**Avantages:**

- Crée un module complet d'un coup
- Inclut les actions CRUD (index, creer, enregistrer, editer, mettreAJour, supprimer)
- Génère une vue d'index avec tableau Bootstrap
- Affiche les prochaines étapes

---

## 📝 Exemple 6: Générateur de Code API

Créez une commande qui génère un contrôleur API complet:

```php
/**
 * Générer un contrôleur API complet
 */
protected function creerControleurAPI(): void
{
    $nom = $this->arguments[0] ?? null;

    if (!$nom) {
        $this->erreur("Usage: php bmvc creer:controleur-api UserAPI");
    }

    $chemin = __DIR__ . "/app/Controleurs/{$nom}.php";

    if (file_exists($chemin)) {
        $this->erreur("Le contrôleur {$nom} existe déjà!");
    }

    $contenu = <<<'PHP'
<?php
namespace App\Controleurs;

use Core\APIResponse;

class {NOM}
{
    public function index()
    {
        return APIResponse::succes(
            ['donnees' => []],
            'Données récupérées'
        )->envoyer();
    }

    public function creer()
    {
        // Validation des données
        return APIResponse::succes(
            ['id' => 1],
            'Créé avec succès',
            201
        )->envoyer();
    }
}
PHP;

    file_put_contents($chemin, $contenu);
    $this->succes("Contrôleur API {$nom} créé!");
}
```

---

## 🧪 Checklist d'Ajout d'une Commande

- [ ] Créer la méthode protégée
- [ ] Ajouter le cas dans `executer()`
- [ ] Ajouter le raccourci dans `expandCommande()` (optionnel)
- [ ] Mettre à jour l'aide dans `afficherAide()`
- [ ] Tester avec `php bmvc nom:commande`
- [ ] Tester avec le raccourci `php bmvc -xx`
- [ ] Tester avec différents arguments et options
- [ ] Ajouter la documentation au guide

---

## 🚀 Exemples de Commandes Utiles à Ajouter

```bash
# Modules complets
php bmvc creer:module Produit
php bmvc creer:module Categorie

# APIs
php bmvc creer:controleur-api ProduitAPI
php bmvc creer:controleur-api UserAPI

# Seeders
php bmvc creer:seeder UserSeeder
php bmvc seeder:run

# Validation personnalisée
php bmvc creer:validateur EmailUniqueValidateur

# Jobs/Queue
php bmvc creer:job SendEmailJob

# Événements
php bmvc creer:evenement UserCreated

# Listeners
php bmvc creer:listener UserCreatedListener

# Middleware personnalisé
php bmvc creer:middleware AuthMiddleware

# Commands avec paramètres
php bmvc db:reset
php bmvc cache:clear --all
php bmvc logs:clean --days=7
```

---

## 📞 Aide Supplémentaire

Pour voir la structure actuelle du fichier `bmvc`, utilisez:

```bash
php bmvc aide
```

Pour modifier une commande existante, trouvez la méthode correspondante et mettez-à-jour son contenu.

---

**Version:** 1.0  
**Dernière mise à jour:** 2024  
🚀 **Créez vos propres commandes CLI!**
