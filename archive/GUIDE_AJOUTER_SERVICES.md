# 📖 Guide Complet: Ajouter un Service Personnalisé

## 🎯 Objectif

Vous apprendrez à créer votre propre service réutilisable (comme AuthService, UploadService, etc.)

---

## 📋 Structure PSR-4 (Important!)

**Règle d'or:** 1 fichier = 1 classe

```
app/Services/
├── AuthService.php           ← Classe AuthService
├── ValidationService.php     ← Classe ValidationService
├── UploadService.php         ← Classe UploadService
├── NotificationService.php   ← Classe NotificationService
└── MonService.php            ← Votre nouveau service ✨
```

---

## 🚀 Étape 1: Créer le fichier service

### Exemple: Service d'envoi SMS

**Fichier:** `app/Services/SMSService.php`

```php
<?php

namespace App\Services;

/**
 * Service SMS
 * Encapsule la logique d'envoi de SMS
 */
class SMSService
{
    private string $apiKey = 'votre_cle_api';
    private string $urlAPI = 'https://api.sms.com/send';

    /**
     * Envoie un SMS
     */
    public function envoyer(string $numero, string $message): bool
    {
        // Validation du numéro
        if (!$this->validerNumero($numero)) {
            return false;
        }

        // Appel API
        $reponse = $this->appelAPI($numero, $message);

        return $reponse['succes'] ?? false;
    }

    /**
     * Envoie un code de vérification
     */
    public function envoyerCode(string $numero, int $code): bool
    {
        $message = "Votre code de vérification: $code";
        return $this->envoyer($numero, $message);
    }

    /**
     * Valide un numéro de téléphone
     */
    private function validerNumero(string $numero): bool
    {
        // Format: +33612345678
        return preg_match('/^\+[0-9]{10,15}$/', $numero) === 1;
    }

    /**
     * Appelle l'API SMS
     */
    private function appelAPI(string $numero, string $message): array
    {
        $donnees = [
            'api_key' => $this->apiKey,
            'numero' => $numero,
            'message' => $message,
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($donnees),
            ],
        ];

        $contexte = stream_context_create($options);
        $reponse = file_get_contents($this->urlAPI, false, $contexte);

        return json_decode($reponse, true) ?? ['succes' => false];
    }
}
```

---

## 🔗 Étape 2: Ajouter le helper global

Modifiez `core/Helpers.php` et ajoutez votre fonction helper:

```php
if (!function_exists('sms_service')) {
    /**
     * Obtient le service SMS
     */
    function sms_service(): \App\Services\SMSService
    {
        static $service;
        if (!$service) {
            $service = new \App\Services\SMSService();
        }
        return $service;
    }
}
```

**À ajouter à la fin du fichier, avant la fermeture `?>`**

---

## 🔄 Étape 3: Régénérer l'autoload

**Important!** Composer doit connaître votre nouvelle classe:

```bash
cd C:\xampp\htdocs\BMVC
composer dump-autoload
```

Output:

```
Generating optimized autoload files
Generated optimized autoload files containing 36 classes
```

---

## ✅ Étape 4: Utiliser votre service

### Dans un contrôleur:

```php
<?php

namespace App\Controleurs;

class UtilisateurControleur extends \App\BaseControleur
{
    public function verifier2FA()
    {
        // Générer un code aléatoire
        $code = random_int(100000, 999999);

        // Envoyer via SMS
        if (sms_service()->envoyerCode('+33612345678', $code)) {
            notification()->succes('Code SMS envoyé!');
        } else {
            notification()->erreur('Erreur lors de l\'envoi du SMS');
        }

        // Sauvegarder le code en session
        $_SESSION['code_2fa'] = $code;
        $_SESSION['code_expiration'] = time() + 300; // 5 minutes
    }
}
```

### Dans un formulaire:

```html
<!-- Vérifier le code 2FA -->
<form method="POST" action="/verifier-2fa">
  <input
    type="text"
    name="code"
    placeholder="Entrez le code reçu par SMS"
    required
  />
  <button type="submit">Vérifier</button>
</form>
```

### Dans une vue:

```php
<?php
// Envoyer une notification SMS au client
sms_service()->envoyer('+33612345678', 'Votre commande #123 a été confirmée!');
?>
```

---

## 📊 Comparaison: Avant vs Après

### ❌ AVANT (sans service)

```php
// Code répété partout
$apiKey = 'cle';
$url = 'https://api.sms.com/send';
$donnees = json_encode(['api_key' => $apiKey, ...]);

$options = ['http' => ['method' => 'POST', ...]];
$contexte = stream_context_create($options);
$reponse = file_get_contents($url, false, $contexte);

// Répéter ça dans 5 contrôleurs différents...
```

### ✅ APRÈS (avec service)

```php
// Simple et réutilisable partout!
sms_service()->envoyer('+33612345678', 'Message');
```

---

## 🎨 Types de services courants

### 1. Service Payment (Paiement)

```php
class PaymentService
{
    public function effectuerPaiement(float $montant, string $methode): bool { }
    public function rembouser(string $idTransaction, float $montant): bool { }
    public function verifierStatut(string $idTransaction): string { }
}
```

### 2. Service Email avancé

```php
class EmailService
{
    public function envoyerAvecTemplate(string $email, string $template, array $donnees): bool { }
    public function envoyerEnMasse(array $emails, string $sujet, string $contenu): int { }
    public function planifierEnvoi(string $email, string $contenu, \DateTime $quand): bool { }
}
```

### 3. Service Stockage de fichiers

```php
class StorageService
{
    public function sauvegarder(string $chemin, $contenu): bool { }
    public function telecharger(string $chemin): string { }
    public function supprimer(string $chemin): bool { }
    public function existe(string $chemin): bool { }
}
```

### 4. Service API externe

```php
class WeatherService
{
    public function obtenirMeteo(string $ville): array { }
    public function obtenirPremeteo(string $latitude, string $longitude): array { }
}
```

### 5. Service Analytics

```php
class AnalyticsService
{
    public function enregistrerVisite(string $page): void { }
    public function enregistrerClique(string $bouton): void { }
    public function obtenirStatsPage(string $page): array { }
}
```

---

## 🧪 Tester votre service

### Créer un fichier de test:

**Fichier:** `test_sms_service.php`

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

echo "<h2>🧪 Test SMS Service</h2>";

// Test 1: Envoyer un SMS
$result = sms_service()->envoyer('+33612345678', 'Test message');
echo $result ? "✅ SMS envoyé" : "❌ Erreur d'envoi";

// Test 2: Envoyer un code
$result = sms_service()->envoyerCode('+33612345678', 123456);
echo $result ? "✅ Code envoyé" : "❌ Erreur";

// Test 3: Validation numéro invalide
$result = sms_service()->envoyer('invalid', 'Test');
echo !$result ? "✅ Numéro invalide détecté" : "❌ Validation échouée";

?>
```

**Accédez:** `http://localhost/BMVC/test_sms_service.php`

---

## 🔒 Bonnes pratiques

### ✅ À FAIRE:

```php
// 1. Valider les entrées
public function envoyer(string $numero, string $message): bool
{
    if (!$this->validerNumero($numero)) {
        return false;
    }
    // ...
}

// 2. Gérer les erreurs
try {
    $reponse = $this->appelAPI(...);
} catch (Exception $e) {
    return false;
}

// 3. Encapsuler la logique complexe
private function validerNumero(string $numero): bool { }
private function appelAPI(string $numero, string $message): array { }

// 4. Utiliser les types
public function envoyer(string $numero, string $message): bool { }
```

### ❌ À ÉVITER:

```php
// ❌ Pas de validation
public function envoyer($numero, $message) { }

// ❌ Pas de typage
function envoyer($n, $m) { return true; }

// ❌ Code mélangé
public function envoyer() {
    // Validation + API + Logging tout ensemble
}

// ❌ Pas de gestion d'erreurs
$reponse = file_get_contents($url); // Peut crasher!
```

---

## 📚 Checklist: Ajouter un service

- [ ] Créer le fichier `app/Services/MonService.php`
- [ ] Respecter PSR-4 (1 fichier = 1 classe)
- [ ] Ajouter le namespace `namespace App\Services;`
- [ ] Ajouter les méthodes publiques principales
- [ ] Encapsuler la logique complexe en privé
- [ ] Ajouter le helper dans `core/Helpers.php`
- [ ] Exécuter `composer dump-autoload`
- [ ] Tester avec un fichier test
- [ ] Documenter les méthodes publiques

---

## 🎓 Exemples complets supplémentaires

### Service Logger personnalisé

```php
<?php

namespace App\Services;

class LoggerService
{
    private string $cheminLogs = __DIR__ . '/../../storage/logs/';

    public function info(string $message): void
    {
        $this->enregistrer($message, 'INFO');
    }

    public function erreur(string $message): void
    {
        $this->enregistrer($message, 'ERREUR');
    }

    private function enregistrer(string $message, string $niveau): void
    {
        $date = date('Y-m-d H:i:s');
        $contenu = "[$date] $niveau: $message\n";

        $nomFichier = $this->cheminLogs . 'app-' . date('Y-m-d') . '.log';
        file_put_contents($nomFichier, $contenu, FILE_APPEND);
    }
}
```

**Utilisation:**

```php
logger_service()->info('Utilisateur connecté');
logger_service()->erreur('Erreur de base de données');
```

---

## 💡 Astuces

### Singleton pattern (défaut dans les helpers)

```php
// La fonction helper crée UNE SEULE instance
// Même si vous l'appelez 100 fois, c'est toujours le même objet!
sms_service()->envoyer(...);
sms_service()->envoyer(...);  // Même instance!
```

### Injection de dépendances

```php
class PaymentService
{
    public function __construct(private LoggerService $logger) { }

    public function payer(float $montant): bool
    {
        $this->logger->info("Paiement de $montant€");
        // ...
    }
}
```

### Tester facilement

```php
class TestPaymentService extends PaymentService
{
    public function effectuerPaiement(float $montant, string $methode): bool
    {
        // Retourner vrai pour les tests
        return true;
    }
}
```

---

**Besoin d'aide pour créer un service spécifique? Décrivez-le et je vous aiderai!** 🚀
