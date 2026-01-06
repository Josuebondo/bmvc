# 📦 Publier BMVC sur Packagist

**Guide Complet pour Publier le Framework BMVC sur Packagist**

---

## 🎯 Objectif

Publier BMVC pour qu'il soit installable avec:

```bash
# Installation standard
composer require bmvc/framework

# Création de nouveau projet
composer create-project bmvc/bmvc monprojet
```

---

## ✅ Checklist Avant Publication

- [ ] Repository GitHub public
- [ ] composer.json valide
- [ ] LICENSE file (MIT)
- [ ] README.md complet
- [ ] Tags Git pour les versions
- [ ] Tests passent (35/35)
- [ ] Code coverage ≥ 85%
- [ ] Pas d'erreurs PHP

---

## 📝 Configuration composer.json

### Version Actuelle (Library)

```json
{
  "name": "bmvc/framework",
  "type": "library",
  "version": "1.0.0"
}
```

### Pour Create-Project (Deux Options)

#### Option 1: Skeleton Project (Recommandé)

Créer un repository séparé `bmvc/skeleton` avec:

```json
{
  "name": "bmvc/bmvc",
  "description": "BMVC Framework - Skeleton pour create-project",
  "type": "project",
  "version": "1.0.0"
}
```

**Structure du skeleton:**

```
bmvc-skeleton/
├── app/                    (vide ou avec exemples)
├── public/
├── config/
├── storage/
├── tests/
├── composer.json
├── README.md
└── .gitignore
```

#### Option 2: Same Repository (Plus Simple)

Si vous gardez BMVC comme project:

```json
{
  "name": "bmvc/bmvc",
  "type": "project",
  "version": "1.0.0",
  "description": "Framework web moderne et professionnel 100% en français"
}
```

**Recommandation:** Option 2 (plus simple pour vous)

---

## 🚀 Étapes de Publication

### Étape 1: Mettre à Jour composer.json

```bash
# Changer le name et type
# AVANT:
"name": "bmvc/framework",
"type": "library",

# APRÈS:
"name": "bmvc/bmvc",
"type": "project",
```

Fichier complet recommandé:

```json
{
  "name": "bmvc/bmvc",
  "description": "Framework web moderne et professionnel 100% en français - MVC avec CLI, i18n, API REST et Tests",
  "type": "project",
  "license": "MIT",
  "version": "1.0.0",
  "keywords": [
    "framework",
    "mvc",
    "php",
    "français",
    "cli",
    "api-rest",
    "i18n",
    "routing",
    "orm"
  ],
  "authors": [
    {
      "name": "Josue Bondo",
      "email": "josuebondojw@gmail.com",
      "role": "Creator"
    }
  ],
  "require": {
    "php": ">=8.0"
  },
  "require-dev": {
    "phpunit/phpunit": "^9.5|^10.0",
    "phpstan/phpstan": "^1.8",
    "squizlabs/php_codesniffer": "^3.7"
  },
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Core\\": "core/",
      "Tests\\": "tests/"
    },
    "files": ["core/Helpers.php"]
  },
  "scripts": {
    "test": "phpunit",
    "coverage": "phpunit --coverage-html tests/coverage/html",
    "lint": "parallel-lint app/ core/",
    "phpstan": "phpstan analyse app/ core/ --level 5",
    "cs-check": "phpcs --standard=PSR12 app/ core/",
    "cs-fix": "phpcbf --standard=PSR12 app/ core/",
    "check": ["@lint", "@test", "@phpstan", "@cs-check"]
  }
}
```

### Étape 2: Valider composer.json

```bash
composer validate
```

**Résultat attendu:**

```
✓ Valid
```

### Étape 3: Créer un Git Tag

```bash
# Créer un tag pour la version
git tag -a v1.0.0 -m "Release version 1.0.0 - Production Ready"

# Vérifier le tag
git tag -l

# Pousser le tag
git push origin v1.0.0

# Ou pousser tous les tags
git push origin --tags
```

### Étape 4: Créer un Compte Packagist

1. Aller sur https://packagist.org
2. Cliquer sur "Sign Up"
3. Créer un compte avec:
   - Email
   - Nom d'utilisateur
   - Mot de passe

### Étape 5: Lier GitHub à Packagist

1. Dans Packagist, cliquer sur "Submit Package"
2. Entrer l'URL du repository:
   ```
   https://github.com/yourusername/bmvc
   ```
3. Cliquer sur "Check"
4. Cliquer sur "Submit"

### Étape 6: Configurer Auto-Update

1. Dans Packagist, aller à Settings
2. Ajouter un GitHub Service Hook:
   - Aller à GitHub Settings → Webhooks
   - Ajouter webhook:
     ```
     Payload URL: https://packagist.org/api/github
     Content type: application/json
     Events: Push events
     ```

---

## 🔄 Vérifier la Publication

### Attendre la Synchronisation

```bash
# Rafraîchir le package dans Packagist
# (peut prendre quelques minutes)

# Vérifier sur Packagist
https://packagist.org/packages/bmvc/bmvc
```

### Tester l'Installation

```bash
# Tester create-project
composer create-project bmvc/bmvc my-app

# Vérifier que tout fonctionne
cd my-app
composer test
```

---

## 📝 Fichiers Importants pour Packagist

### README.md

Doit contenir:

- Description du framework
- Installation rapide
- Exemple d'utilisation
- Lien vers la documentation
- Licence
- Auteurs

### LICENSE

Fichier MIT complet:

```
MIT License

Copyright (c) 2024 Josue Bondo

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, OR ACTION OF
CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
```

### .gitignore

```
/vendor/
/composer.lock
/.env
/.env.local
/node_modules/
/storage/logs/*
/storage/cache/*
/tests/coverage/
.DS_Store
*.swp
*.swo
*~
.idea/
.vscode/
```

---

## 🎯 Après Publication

### Mise à Jour Future

Pour chaque nouvelle version:

```bash
# 1. Mettre à jour composer.json
"version": "1.1.0"

# 2. Commiter
git add composer.json
git commit -m "Bump version to 1.1.0"

# 3. Créer un tag
git tag -a v1.1.0 -m "Release 1.1.0"

# 4. Pousser
git push origin main
git push origin v1.1.0

# Packagist se met à jour automatiquement (si webhook configuré)
```

### Gestion des Versions

```
v1.0.0  Production Ready ✅
v1.1.0  Minor improvements (features)
v1.2.0  Patch fixes and optimizations
v2.0.0  Major release (breaking changes)
```

---

## 📊 Commandes de Maintenance

### Voir les Stats du Package

```bash
# Sur Packagist
https://packagist.org/packages/bmvc/bmvc/stats

# Pour voir les téléchargements
https://packagist.org/packages/bmvc/bmvc
```

### Gestions des Dépendances

```bash
# Vérifier les dépendances obsolètes
composer outdated

# Auditer les vulnérabilités
composer audit

# Mettre à jour
composer update
```

---

## 🔒 Sécurité du Package

### Avant chaque publication:

- [ ] Exécuter `composer audit` - 0 vulnérabilités
- [ ] Exécuter `composer test` - 100% passants
- [ ] Vérifier `composer validate`
- [ ] Tester `composer create-project`
- [ ] Pas de secrets dans le code
- [ ] Pas de .env commité

---

## 💡 Conseils Packagist

### Bonnes Pratiques

1. **Versioning:**

   - Utilisez SemVer (1.0.0)
   - Créez des tags Git
   - Documentez les changements (CHANGELOG)

2. **Documentation:**

   - README complet et clair
   - Installation step-by-step
   - Exemples d'utilisation
   - Lien vers docs complètes

3. **Qualité:**

   - Tests automatisés
   - Code style consistent
   - Bonnes pratiques PHP
   - Pas de dépendances inutiles

4. **Maintenance:**
   - Répondez aux issues GitHub
   - Publiez des mises à jour régulières
   - Gardez les dépendances à jour

### Avatar et Description

Sur Packagist:

- Ajouter une belle description
- Badge README:
  ```markdown
  [![Latest Stable Version](https://poser.pugx.org/bmvc/bmvc/v)](//packagist.org/packages/bmvc/bmvc)
  [![License](https://poser.pugx.org/bmvc/bmvc/license)](//packagist.org/packages/bmvc/bmvc)
  [![Downloads](https://poser.pugx.org/bmvc/bmvc/downloads)](//packagist.org/packages/bmvc/bmvc)
  ```

---

## ✅ Checklist Avant Publication

- [ ] composer.json valide (`composer validate`)
- [ ] Type: "project" configuré
- [ ] Name: "bmvc/bmvc" correct
- [ ] Tests: 35/35 passants
- [ ] LICENSE file présent
- [ ] README.md complet
- [ ] .gitignore configuré
- [ ] Tags Git créés (v1.0.0)
- [ ] Compte Packagist créé
- [ ] Repository GitHub public
- [ ] Webhook Packagist configuré
- [ ] Create-project testé

---

## 🚀 C'est Fait!

Votre framework est maintenant disponible sur Packagist et installable avec:

```bash
composer create-project bmvc/bmvc monprojet
cd monprojet
php bmvc demarrer
```

---

**📦 Publier BMVC sur Packagist - Guide Complet**

**Version:** 1.0.0  
**Status:** Production Ready ✅  
**Difficulty:** Moyen ⭐⭐

**Félicitations! Votre framework est maintenant accessible au monde!** 🌍
