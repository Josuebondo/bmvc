# ⚡ Publication Packagist Express (5 min)

**Publier BMVC sur Packagist en 5 minutes**

---

## 📦 Qu'est-ce que Packagist?

Packagist est le dépôt officiel pour les packages Composer. Mettre votre framework sur Packagist le rend installable facilement:

```bash
composer create-project bmvc/bmvc monprojet
```

---

## ⚡ Étapes Rapides

### 1️⃣ Vérifier composer.json (30 sec)

```bash
# Valider la configuration
composer validate
```

**Doit afficher:** `✓ Valid`

### 2️⃣ Créer un Repository GitHub Public (1 min)

```bash
# Si vous n'avez pas encore Github, créer un compte sur https://github.com

# Pousser le code
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/yourusername/bmvc.git
git branch -M main
git push -u origin main
```

### 3️⃣ Créer un Tag Git (1 min)

```bash
# Créer la version 1.0.0
git tag -a v1.0.0 -m "Release v1.0.0 - Production Ready"

# Pousser le tag
git push origin v1.0.0
```

### 4️⃣ Créer Compte Packagist (1 min)

1. Aller sur https://packagist.org
2. Cliquer sur "Sign Up"
3. Créer un compte

### 5️⃣ Publier le Package (1 min)

1. Dans Packagist, cliquer sur "Submit Package"
2. Entrer l'URL du repository:
   ```
   https://github.com/yourusername/bmvc.git
   ```
3. Cliquer sur "Check"
4. Cliquer sur "Submit"

---

## ✅ Vérifier la Publication

```bash
# Attendre quelques minutes, puis:
composer create-project bmvc/bmvc test-app

# Devrait fonctionner!
cd test-app
composer test
```

---

## 🔄 Auto-Update (Optionnel mais Recommandé)

Pour que Packagist se mette à jour automatiquement quand vous poussez:

**Dans GitHub:**

1. Settings → Webhooks
2. Add webhook
3. Payload URL: `https://packagist.org/api/github`
4. Content type: `application/json`
5. Cliquer sur "Add webhook"

**Dans Packagist:**

1. Aller à votre package
2. Cliquer sur "Show API Token"
3. Copier le token
4. Dans GitHub webhook, ajouter le token

---

## 🎯 Résultat Final

Votre framework sera installable comme:

```bash
# Installation simple
composer require bmvc/bmvc

# Ou créer un nouveau projet
composer create-project bmvc/bmvc monprojet

# Démarrer immédiatement
cd monprojet
php bmvc demarrer
```

---

## 📊 Voir les Stats

```
https://packagist.org/packages/bmvc/bmvc/stats
```

---

## 🔐 Important

Avant publication, s'assurer:

- ✅ Pas de `.env` commité
- ✅ Pas de secrets dans le code
- ✅ Tous les tests passent
- ✅ composer.json valide
- ✅ README.md complet
- ✅ LICENSE (MIT) présent

---

**⚡ Publication Express - 5 minutes**

**C'est facile!** 🚀
