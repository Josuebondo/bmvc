# 🚀 BMVC - Guide de Test Complet

## Phase 1-2 ✅

Framework MVC complet avec routing, vues et validation

## Phase 3 ✅

Base de données et ORM opérationnels

---

## 📋 Étapes de test

### 1️⃣ Setup initial (UNE SEULE FOIS)

**⚠️ Avant tout, vérifiez que MySQL est démarré dans XAMPP Control Panel!**

```bash
cd C:\xampp\htdocs\BMVC
php install-db.php
```

Cela:

- ✅ Crée la base de données `bmvc`
- ✅ Crée les tables `articles` et `contacts`
- ✅ Insère 3 articles d'exemple
- ✅ Configure tout automatiquement

### 2️⃣ Démarrer le serveur

```bash
cd C:\xampp\htdocs\BMVC
php -S localhost:8000
```

Puis visitez: **http://localhost:8000**

---

## 🧪 Tests à effectuer

### Test 1: Consulter les articles

1. Allez à **http://localhost:8000/articles**
2. Vérifiez que les 3 articles s'affichent
3. Cliquez sur "Lire plus" pour voir le détail

### Test 2: Voir un article détail

1. Cliquez sur un article
2. Vérifiez que le contenu s'affiche
3. Cliquez "Retour aux articles"

### Test 3: Créer un article

1. Allez à **http://localhost:8000/articles/creer**
2. Remplissez le formulaire:
   - Titre: "Mon super article" (min 5 caractères)
   - Contenu: "Contenu du nouvel article..." (min 20 caractères)
3. Cliquez "Publier"
4. Vérifiez que vous êtes redirigé à la liste
5. **Vérifiez que votre article est en haut de la liste!** ✨

### Test 4: Validation des erreurs

1. Allez à **http://localhost:8000/articles/creer**
2. Essayez de soumettre avec:
   - Titre vide → Erreur "requis"
   - Titre court ("abc") → Erreur "min:5"
   - Titre très long → Erreur "max:200"
   - Contenu court ("test") → Erreur "min:20"
3. Vérifiez que les erreurs s'affichent en rouge
4. Vérifiez que les anciens inputs sont restituées dans le formulaire

### Test 5: Formulaire de contact

1. Allez à **http://localhost:8000/contact**
2. Remplissez le formulaire avec:
   - Nom: "Jean Dupont"
   - Email: "jean@example.com"
   - Message: "Bonjour, j'aime BMVC!"
3. Cliquez "Envoyer"
4. Vérifiez le message de succès ✓

### Test 6: Erreurs de contact

1. Allez à **http://localhost:8000/contact**
2. Essayez:
   - Email invalide ("notanemail")
   - Nom trop court ("ab")
   - Message trop court ("test")
3. Vérifiez les messages d'erreur en rouge

---

## 💾 Structure BD

### Table `articles`

```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- titre (VARCHAR 200)
- contenu (LONGTEXT)
- created_at (TIMESTAMP)
```

### Table `contacts`

```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- nom (VARCHAR 100)
- email (VARCHAR 255)
- message (LONGTEXT)
- created_at (TIMESTAMP)
```

---

## 🔧 Fichiers importants

| Fichier                                 | Rôle                        |
| --------------------------------------- | --------------------------- |
| `setup-bd.php`                          | Crée les tables et articles |
| `core/BaseBD.php`                       | Connexion singleton PDO     |
| `core/Modele.php`                       | ORM (CRUD)                  |
| `app/Modeles/Article.php`               | Modèle Article              |
| `app/Modeles/Contact.php`               | Modèle Contact              |
| `app/Controleurs/ArticleControleur.php` | Logique articles            |
| `app/Controleurs/ContactControleur.php` | Logique contact             |
| `app/Vues/articles/`                    | Templates articles          |
| `app/Vues/contact/`                     | Template contact            |

---

## 📚 Fonctionnalités testées

✅ **Routing** - Routes dynamiques avec paramètres
✅ **Vues** - Layouts et sections
✅ **Validation** - Règles: requis, min, max, email
✅ **Erreurs** - Affichage des erreurs validées
✅ **Sessions** - Ancien inputs, flash messages
✅ **ORM** - CRUD complet (Create, Read, Update, Delete)
✅ **BD** - MySQL avec prepared statements
✅ **AJAX** - Contact form avec fetch
✅ **HTTP** - GET, POST, redirections

---

## 🎯 Prochaines phases

- Phase 4: Relations ORM (belongsTo, hasMany)
- Phase 5: Authentification & autorisation
- Phase 6: Cache & performance
- Phase 7: API REST
- Phase 8: Tests unitaires

---

## ❓ Troubleshooting

**Les articles n'apparaissent pas?**
→ Exécutez `php setup-bd.php`

**Erreur de connexion BD?**
→ Vérifiez `.env` (DB_HOST, DB_USERNAME, etc)

**Formulaire ne valide pas?**
→ Vérifiez APP_DEBUG=true dans `.env`

**AJAX de contact ne fonctionne pas?**
→ Ouvrez la console du navigateur (F12) pour voir les erreurs
