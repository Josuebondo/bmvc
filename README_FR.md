# 📚 BMVC - Guide Rapide de Démarrage

## 🚀 Installation rapide (5 minutes)

### Étape 1: Préparer MySQL

1. Ouvrez **XAMPP Control Panel**
2. Cliquez sur **Start** pour MySQL
3. Attendez que MySQL soit en vert ✅

### Étape 2: Initialiser la base de données

```bash
cd C:\xampp\htdocs\BMVC
php install-db.php
```

Vous verrez:

```
============================================================
🚀 INSTALLATION BMVC - Base de Données MySQL
============================================================

1️⃣  Connexion à MySQL...
✅ Connecté à MySQL

2️⃣  Création de la base de données 'bmvc'...
✅ Base de données prête

3️⃣  Connexion à la base de données 'bmvc'...
✅ Base de données sélectionnée

4️⃣  Création de la table 'articles'...
✅ Table 'articles' créée

5️⃣  Création de la table 'contacts'...
✅ Table 'contacts' créée

...

✨ INSTALLATION RÉUSSIE!
```

### Étape 3: Démarrer le serveur

```bash
php -S localhost:8000
```

### Étape 4: Ouvrir le navigateur

Visitez: **http://localhost:8000**

---

## 📝 Créer un article (Pas à pas)

### Option 1: Via le formulaire web 🌐

1. Allez à: **http://localhost:8000/articles/creer**

2. Remplissez le formulaire:

   - **Titre**: "Mon premier article" (min 5 caractères)
   - **Contenu**: "Ceci est le contenu de mon article..." (min 20 caractères)

3. Cliquez **"Publier"**

4. ✨ Vous êtes redirigé à **http://localhost:8000/articles**

5. Votre article apparaît dans la liste!

### Option 2: Via PHP directement 🔧

Créez un fichier `ajouter-article.php`:

```php
<?php
require_once 'public/index.php';

use App\Modeles\Article;

// Créer un article
$article = Article::creer([
    'titre' => 'Mon article via PHP',
    'contenu' => 'Cet article a été créé directement via le code PHP!'
]);

echo "✅ Article créé (ID: " . $article->id . ")\n";
```

Exécutez:

```bash
php ajouter-article.php
```

---

## 🧪 Tests complets

### Test 1: Voir tous les articles

```
http://localhost:8000/articles
```

Devrait afficher les 3 articles d'exemple + vos articles créés

### Test 2: Voir un article détail

```
http://localhost:8000/articles/1
```

Affiche l'article avec l'ID 1

### Test 3: Créer avec erreurs

1. Allez à **http://localhost:8000/articles/creer**
2. Laissez le titre vide → Erreur "requis" ❌
3. Mettez titre = "abc" → Erreur "min:5" ❌
4. Mettez contenu = "test" → Erreur "min:20" ❌

### Test 4: Valeur antérieure

1. Remplissez partiellement le formulaire
2. Soumettez avec erreurs
3. Les valeurs que vous avez écrites restent! ✨

### Test 5: Formulaire contact

```
http://localhost:8000/contact
```

Testez aussi le formulaire de contact!

---

## 📊 Structure des données

### Articles en BD

```sql
SELECT * FROM articles;
```

Colones:

- `id` - Identifiant unique
- `titre` - Titre de l'article (max 200 caractères)
- `contenu` - Contenu long
- `created_at` - Date de création

### Contacts en BD

```sql
SELECT * FROM contacts;
```

Colonnes:

- `id` - Identifiant unique
- `nom` - Nom de la personne
- `email` - Email
- `message` - Message long
- `created_at` - Date de création

---

## 🎓 Comprendre le flux

### Création d'article:

```
1. Utilisateur clique sur "Créer un article"
   ↓
2. Affiche: http://localhost:8000/articles/creer (GET)
   ↓
3. Remplit le formulaire et clique "Publier"
   ↓
4. POST vers: http://localhost:8000/articles
   ↓
5. Contrôleur valide les données
   ↓
6a. Si valide:
    - Sauvegarde en BD via Article::creer()
    - Redirige à /articles

6b. Si erreurs:
    - Stocke les erreurs en session
    - Redirige à /articles/creer
    - Affiche les erreurs dans le formulaire
```

---

## 🛠️ Fichiers importants

| Fichier                                 | Rôle                     |
| --------------------------------------- | ------------------------ |
| `install-db.php`                        | Crée la BD et les tables |
| `core/BaseBD.php`                       | Connexion MySQL (PDO)    |
| `core/Modele.php`                       | ORM (CRUD)               |
| `app/Modeles/Article.php`               | Modèle Article           |
| `app/Controleurs/ArticleControleur.php` | Logique métier           |
| `app/Vues/articles/creer.php`           | Formulaire               |
| `app/Vues/articles/index.php`           | Liste articles           |
| `app/Vues/articles/voir.php`            | Détail article           |

---

## ❓ Problèmes courants

### "Erreur de connexion à la base de données"

**Cause**: MySQL n'est pas démarré
**Solution**: Démarrez MySQL dans XAMPP Control Panel

### "Table articles n'existe pas"

**Cause**: install-db.php n'a pas été exécuté
**Solution**: Exécutez `php install-db.php`

### "Aucun article ne s'affiche"

**Cause**: Pas d'articles en BD
**Solution**: Créez un article via le formulaire

### "Le formulaire ne valide pas"

**Cause**: Les règles de validation sont strictes
**Solution**: Titre min 5 caractères, contenu min 20 caractères

### "Après créer, je reste sur le même formulaire"

**Cause**: Il y a des erreurs de validation
**Solution**: Regardez les messages d'erreur en rouge

---

## 📞 Support

Si vous avez des problèmes:

1. Vérifiez que MySQL est démarré ✅
2. Exécutez `php install-db.php` ✅
3. Visitez `http://localhost:8000` ✅
4. Regardez la console du navigateur (F12) pour les erreurs ✅
5. Vérifiez le fichier `.env` pour la configuration BD ✅

---

**Bon codage! 🚀**
