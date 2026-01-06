# 🔐 Configuration Environnement - Guide Complet

## 📋 Vue d'ensemble

BMVC utilise un système de variables d'environnement pour gérer les configurations sensibles et éditables. Cela permet de:

- ✅ Séparer la configuration du code
- ✅ Faciliter le déploiement multi-environnement (dev, staging, prod)
- ✅ Protéger les informations sensibles
- ✅ Configurer les services sans modifier le code

---

## 📂 Fichiers de Configuration

### `.env` (À créer)

Fichier contenant vos configurations **personnelles**. **NE PAS committer** ce fichier.

```bash
# Créer depuis l'exemple
cp .env.example .env
```

### `.env.example`

Fichier d'exemple avec toutes les variables disponibles. Peut être commité.

---

## 🚀 Utilisation

### 1. Charger le fichier .env

Automatiquement au démarrage dans `public/index.php`:

```php
\Core\Env::charger(APP_PATH . '/.env');
```

### 2. Accéder à une variable

**Via la fonction helper `env()`:**

```php
// Récupérer une variable
$emailFrom = env('ADRESSE_EMAIL_EXPEDITEUR');

// Avec valeur par défaut
$maxSize = env('TAILLE_MAX_UPLOAD', 5);

// Type conversions
$debug = env('DEBOGAGE') === 'true';
$ttl = (int) env('TTL_CACHE', 3600);
```

**Via la classe `Env`:**

```php
\Core\Env::obtenir('ADRESSE_EMAIL_EXPEDITEUR');
\Core\Env::obtenir('ADRESSE_EMAIL_EXPEDITEUR', 'default@example.com');
\Core\Env::existe('MAIL_FROM_ADDRESS');
\Core\Env::tous();  // Retourne toutes les variables
```

---

## 📝 Variables Disponibles

### APPLICATION

```env
NOM_APPLICATION=BMVC                    # Nom de l'application
ENVIRONNEMENT=local                     # Environnement (local, staging, production)
DEBOGAGE=true                           # Activer le debug
CLE_SECRETE=your-secret-key             # Clé secrète de l'app
FUSEAU_HORAIRE=Europe/Paris             # Fuseau horaire
LOCALE=fr                               # Locale (fr, en, etc.)
URL_APPLICATION=http://localhost:8000   # URL de base
```

### BASE DE DONNÉES

```env
TYPE_CONNEXION=mysql                    # Type de connexion
HOTE_BD=localhost                       # Serveur
PORT_BD=3306                            # Port
NOM_BD=bmvc                             # Nom de la BD
UTILISATEUR_BD=root                     # Utilisateur
MOT_DE_PASSE_BD=                        # Mot de passe
```

### CACHE & SESSION

```env
PILOTE_CACHE=file                       # Pilote de cache (file, redis, etc.)
PILOTE_SESSION=file                     # Pilote de session
TTL_CACHE=3600                          # TTL cache en secondes
```

### EMAIL / NOTIFICATIONS

```env
PILOTE_MAIL=mail                        # Pilote mail (mail, smtp, etc.)
ADRESSE_EMAIL_EXPEDITEUR=noreply@example.com    # Email d'envoi
NOM_EMAIL_EXPEDITEUR=BMVC Application           # Nom expéditeur
SERVEUR_MAIL=localhost                  # Serveur SMTP
PORT_MAIL=587                           # Port SMTP
UTILISATEUR_MAIL=                       # Utilisateur SMTP
MOT_DE_PASSE_MAIL=                      # Mot de passe SMTP
UTILISER_TLS_MAIL=true                  # Utiliser TLS
```

### URLS & ROUTES

```env
URL_REINITIALISATION_MDP=/reinitialiser       # Route réinitialisation
EXPIRATION_REINITIALISATION_MDP=86400         # Expiration en secondes (24h)
```

### UPLOAD FICHIERS

```env
REPERTOIRE_UPLOAD=public/uploads/              # Répertoire d'upload
TAILLE_MAX_UPLOAD=2                            # Taille max en Mo
EXTENSIONS_AUTORISEES=jpg,png,gif              # Extensions autorisées
REPERTOIRE_TEMP_UPLOAD=storage/uploads/        # Répertoire temporaire
```

### SMS / MESSAGING (Optionnel)

```env
FOURNISSEUR_SMS=twilio                  # Fournisseur SMS
COMPTE_SID_SMS=                         # Account SID Twilio
JET_AUTH_SMS=                           # Token d'authentification
NUMERO_ENVOI_SMS=                       # Numéro d'envoi
```

### SERVICES

```env
TTL_SERVICE_CACHE=3600                 # TTL par défaut
TAILLE_CHUNK_UPLOAD=1048576            # Taille des chunks upload
PILOTE_QUEUE=database                   # Pilote de queue
```

### SÉCURITÉ

```env
ROUNDS_BCRYPT=12                        # Rounds bcrypt
ALGO_HASH=bcrypt                        # Algo de hash
```

---

## 💡 Exemples d'Utilisation dans les Services

### NotificationService

```php
class NotificationService
{
    public function envoyerEmail($destinataire, $sujet, $corps)
    {
        $from = env('ADRESSE_EMAIL_EXPEDITEUR', 'noreply@example.com');
        $headers = "From: $from\r\n";

        return mail($destinataire, $sujet, $corps, $headers);
    }

    public function reinitialiserMotDePasse($email, $token)
    {
        $url = env('URL_APPLICATION') . env('URL_REINITIALISATION_MDP');
        $lien = "$url?token=$token";
        // ...
    }
}
```

### UploadService

```php
class UploadService
{
    public function __construct()
    {
        $this->repertoire = env('REPERTOIRE_UPLOAD', 'public/uploads/');
        $this->tailleMax = (int) env('TAILLE_MAX_UPLOAD', 5);

        $extensions = env('EXTENSIONS_AUTORISEES', 'jpg,png');
{
    public function __construct()
    {
        $this->apiKey = env('MON_API_KEY');
        $this->endpoint = env('MON_API_ENDPOINT', 'https://api.example.com');
    }
}
```

---

## 🔒 Bonnes Pratiques

### ✅ À FAIRE

```php
// Utiliser les variables d'environnement
$dbPassword = env('MOT_DE_PASSE_BD');

// Avec valeur par défaut
$timeout = (int) env('DELAI_REQUETE', 30);

// Vérifier l'existence
if (env('CLE_API_SMS')) {
    // Initialiser le service SMS
}
```

### ❌ À ÉVITER

```php
// Ne PAS coder en dur les secrets
$password = 'my-secret-password';

// Ne PAS committer le .env
git add .env  // ❌ NON!

// Ne PAS faire confiance aveuglément
// Toujours valider et castrer les variables
```

---

## 🚀 Déploiement

### Production

```env
ENVIRONNEMENT=production
DEBOGAGE=false
MOT_DE_PASSE_BD=strong-password-here
MOT_DE_PASSE_MAIL=smtp-password
```

### Staging

```env
ENVIRONNEMENT=staging
DEBOGAGE=true
MOT_DE_PASSE_BD=staging-password
SERVEUR_MAIL=smtp-staging.example.com
```

---

## 🔧 Ajout de Nouvelles Variables

### 1. Ajouter dans `.env.example`

```env
MA_NOUVELLE_VARIABLE=valeur-par-defaut
```

### 2. Ajouter dans `.env`

```env
MA_NOUVELLE_VARIABLE=ma-valeur-reelle
```

### 3. Utiliser dans le code

```php
$valeur = env('MA_NOUVELLE_VARIABLE', 'defaut');
```

---

## 📊 Configuration Avancée

### Variables Avec Guillemets

```env
# Valeur avec espaces
NOM_APPLICATION="BMVC Framework"

# URLs complexes
URL_API="https://api.example.com/v1"

# Mots de passe spéciaux
MOT_DE_PASSE_BD="p@ssw0rd!with#special"
```

### Comments

```env
# Ceci est un commentaire
NOM_APPLICATION=BMVC  # Commentaire en fin de ligne

# Sections logiques
# ================================================
# CONFIGURATION EMAIL
# ================================================
SERVEUR_MAIL=localhost
```

---

## ⚠️ Sécurité

1. **Ne jamais committer `.env`**

   ```bash
   echo ".env" >> .gitignore
   ```

2. **Protéger les fichiers `.env`**

   ```bash
   chmod 600 .env
   ```

3. **Utiliser des valeurs fortes en production**

   - Mots de passe forts
   - Clés API secrètes
   - Tokens uniques

4. **Rotationner régulièrement les secrets**
   - Changer les mots de passe
   - Régénérer les tokens

---

## 🐛 Troubleshooting

### "Fichier .env non trouvé"

```php
// S'assurer que le fichier existe
php -r "echo file_exists('.env') ? 'OK' : 'ERREUR';"
```

### Variable non trouvée

```php
// Vérifier la variable
\Core\Env::existe('MA_VARIABLE');

// Vérifier le fichier .env
grep MA_VARIABLE .env

// Déboguer
print_r(\Core\Env::tous());
```

### Guillemets mal interprétés

```env
# ❌ Guillemets inclus dans la valeur
MOT_DE_PASSE="password"  → password="password"

# ✅ Guillemets retirés
MOT_DE_PASSE=password    → password
```

---

## 📚 Ressources

- [PHP dotenv (inspiration)](https://github.com/vlucas/phpdotenv)
- [Variables d'environnement (standard)](https://en.wikipedia.org/wiki/Environment_variable)
- [Twelve Factor App](https://12factor.net/)

---

**BMVC v1.0 - Configuration Environnement Complète** ✅
