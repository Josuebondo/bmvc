# 🧭 ROADMAP PHASE 2-8 - BMVC Framework

## 📌 PHASE 1 : BASE DU FRAMEWORK (✅ COMPLÉTÉE)

**Réalisé :**

- ✅ Structure MVC
- ✅ Point d'entrée (index.php)
- ✅ Kernel (Application.php)
- ✅ Routeur (HTTP)
- ✅ Requête/Réponse
- ✅ Système de vues
- ✅ Session
- ✅ Configuration (.env)
- ✅ Helpers globales

---

## 🟡 PHASE 2 : ORM & BASE DE DONNÉES (PROCHAINE)

### 8️⃣ Connexion Base de Données (PDO)

- [ ] Classe `BaseDeDonnees.php`
- [ ] Connexion singleton
- [ ] Méthodes `executer()`, `selectionner()`, `premier()`
- [ ] Gestion des erreurs

**Fonctionnalités à ajouter:**

```php
// config/base_de_donnees.php
return [
    'driver' => 'sqlite|mysql|postgresql',
    'host' => env('HOTE_BD'),
    'database' => env('NOM_BD'),
    'username' => env('UTILISATEUR_BD'),
    'password' => env('MOT_DE_PASSE_BD'),
];

// BaseDeDonnees::connexion()
// BaseDeDonnees::executer('INSERT INTO users ...')
// BaseDeDonnees::selectionner('SELECT * FROM users')
```

### 9️⃣ ORM - Modèle de Base

- [ ] Classe `Modele.php` (héritage pour les modèles)
- [ ] Méthodesstatiques CRUD
- [ ] `tous()`, `trouver()`, `creer()`, `mettre()`, `supprimer()`
- [ ] Requêtes fluides `where()`, `get()`, `premier()`
- [ ] Attributs mapping

**Fonctionnalités à ajouter:**

```php
// app/Modeles/Utilisateur.php
class Utilisateur extends Modele {
    protected string $table = 'utilisateurs';
    protected array $fillable = ['nom', 'email', 'password'];
}

// Utilisation
Utilisateur::tous()                           // SELECT *
Utilisateur::trouver(1)                       // SELECT * WHERE id=1
Utilisateur::where('email', '=', '...')      // WHERE
Utilisateur::where('age', '>', 18)->get()    // Fluent

$user = new Utilisateur();
$user->nom = 'Jean';
$user->sauvegarder();                        // INSERT/UPDATE

$user->supprimer();                           // DELETE
```

### 🔟 Migrations (Bonus Avancé)

- [ ] Classe `Migration.php`
- [ ] Fichiers migration
- [ ] Versioning BD
- [ ] Commandes CLI

---

## 🟠 PHASE 3 : SÉCURITÉ (APRÈS PHASE 2)

### 1️⃣1️⃣ Sessions Avancées

- [ ] Flash messages
- [ ] Cookies sécurisés
- [ ] Gestion authentification

### 1️⃣2️⃣ CSRF Protection

- [ ] Tokens CSRF
- [ ] Middleware CSRF
- [ ] Vérification automatique

### 1️⃣3️⃣ Authentification

- [ ] Login/Logout
- [ ] Hash mot de passe
- [ ] `estConnecte()`, `utilisateur()`
- [ ] Routes protégées

### 1️⃣4️⃣ Middleware

- [ ] Système middleware
- [ ] Auth middleware
- [ ] CSRF middleware
- [ ] Middleware custom

---

## 🔵 PHASE 4 : VALIDATION & SERVICES

### 1️⃣5️⃣ Validation

- [ ] Classe `Validateur.php`
- [ ] Règles : `requis`, `email`, `min`, `max`, `regex`
- [ ] Messages personnalisés
- [ ] Validation en cascade

**Utilisation:**

```php
$validateur = new Validateur($donnees);
$erreurs = $validateur->valider([
    'email' => 'requis|email',
    'password' => 'requis|min:8',
    'age' => 'numeric|min:18|max:120'
]);

if ($validateur->echoue()) {
    return redirect('/form')->avecErreurs($erreurs);
}
```

### 1️⃣6️⃣ Services

- [ ] `Authentification.php`
- [ ] `Validation.php`
- [ ] `Upload.php` (fichiers)
- [ ] `Email.php` (notifications)

---

## 🟣 PHASE 5 : OUTILS & CONFORT

### 1️⃣7️⃣ Helpers Avancées

- [ ] `auth()`, `utilisateur()`
- [ ] `csrf_token()`, `csrf_field()`
- [ ] `route()` (URL génération)
- [ ] `old()` (ancien input)

### 1️⃣8️⃣ Gestion Erreurs Avancée

- [ ] Pages 404/500/403
- [ ] Logging JSON
- [ ] Error reporting
- [ ] Debug bar (optionnel)

### 1️⃣9️⃣ Cache

- [ ] Cache fichier
- [ ] Cache config
- [ ] Cache routes
- [ ] TTL

---

## ⚫ PHASE 6 : CLI & PROFESSIONNALISATION

### 2️⃣0️⃣ CLI BMVC

```bash
php bmvc make:controleur UserControleur
php bmvc make:modele User
php bmvc make:migration create_users_table
php bmvc migrate
php bmvc migrate:rollback
php bmvc serve
php bmvc clear:cache
php bmvc list
```

### 2️⃣1️⃣ Internationalisation

- [ ] Traductions
- [ ] Langues multiples
- [ ] `__('key')`

### 2️⃣2️⃣ API REST

- [ ] JSON Response
- [ ] API Auth (tokens)
- [ ] Rate limiting
- [ ] API versioning

---

## ⭐ PHASE 7 : TESTING

### 2️⃣3️⃣ Tests

- [ ] Tests unitaires
- [ ] Tests fonctionnels
- [ ] PHPUnit setup
- [ ] Fixtures/Seeds

---

## 🏆 PHASE 8 : PACKAGING PRO

### 2️⃣4️⃣ Packaging

- [ ] Package Composer
- [ ] Versioning sémantique
- [ ] Changelog
- [ ] Documentation complète
- [ ] Github releases

---

## 🎯 FICHIERS À CRÉER (PAR PHASE)

### PHASE 2

```
core/BaseDeDonnees.php         (PDO)
app/Modeles/Utilisateur.php    (Example)
core/Migration.php             (Bonus)
```

### PHASE 3

```
core/Securite.php              (CSRF)
app/Intergiciels/Auth.php       (Middleware)
app/Services/Authentification.php
```

### PHASE 4

```
core/Validateur.php
app/Services/Validation.php
app/Services/Upload.php
```

### PHASE 5

```
core/Cache.php
app/Exceptions/Authenticated.php
```

### PHASE 6

```
console/Kernel.php
console/Commands/MakeController.php
console/Commands/MakeMigration.php
```

---

## 📚 DOCUMENTATION À ÉCRIRE

- [ ] Installation guide
- [ ] Routing guide
- [ ] Models & ORM guide
- [ ] Validation guide
- [ ] Authentication guide
- [ ] API guide
- [ ] Deployment guide
- [ ] Contributing guide

---

## 🚀 PROCHAINES ÉTAPES IMMÉDIATES

1. **Étape 1 (ce soir)** : BaseDeDonnees.php + PDO
2. **Étape 2** : Modèle ORM de base
3. **Étape 3** : CRUD complet
4. **Étape 4** : Tests et débogage

**Le framework de PHASE 1 est solide et testé.**
On peut commencer PHASE 2 immédiatement ! 💪

---

## 📊 PROGRESSION

```
PHASE 1 : ████████████████████ 100% ✅
PHASE 2 : ░░░░░░░░░░░░░░░░░░░░   0% ⏳
PHASE 3 : ░░░░░░░░░░░░░░░░░░░░   0% ⏳
PHASE 4 : ░░░░░░░░░░░░░░░░░░░░   0% ⏳
PHASE 5 : ░░░░░░░░░░░░░░░░░░░░   0% ⏳
PHASE 6 : ░░░░░░░░░░░░░░░░░░░░   0% ⏳
PHASE 7 : ░░░░░░░░░░░░░░░░░░░░   0% ⏳
PHASE 8 : ░░░░░░░░░░░░░░░░░░░░   0% ⏳
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Total  : ████░░░░░░░░░░░░░░░░  12% 🚀
```

---

**BMVC v1.0.0** - Framework PHP Français  
Créé avec ❤️ pour les développeurs francophones
