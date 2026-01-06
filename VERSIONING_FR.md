# 📦 Gestion des Versions - Framework BMVC

**Stratégie de Versioning Sémantique pour BMVC v1.0.0**

---

## 📌 Versioning Sémantique (SemVer)

### Format: MAJOR.MINOR.PATCH

```
1.0.0 = MAJEURE.MINEURE.CORRECTIF

Exemple:
1.0.0  → Version courante (Production)
1.1.0  → Nouvelles fonctionnalités (Mineure)
1.0.1  → Correctifs de bugs (Correctif)
2.0.0  → Changements non-compatibles (Majeure)
```

### Règles SemVer

- **MAJEURE** (X.0.0): Changements incompatibles avec les versions précédentes
- **MINEURE** (1.X.0): Nouvelles fonctionnalités compatibles avec les versions précédentes
- **CORRECTIF** (1.0.X): Correctifs de bugs sans nouvelles fonctionnalités

---

## 🚀 Stratégie de Versioning BMVC

### Version Actuelle

```
Version: 1.0.0
Status: Production Prêt ✅
Date de Sortie: 2024-01-06
```

### Feuille de Route (Timeline)

```
2024-Q1: 1.0.0  ✅ ACTUEL
├── Core MVC complet
├── CLI système
├── i18n (8 langues)
├── API REST
├── 35 tests (85%+ couverture)
└── Documentation complète

2024-Q2: 1.1.0  📅 Planifié
├── Améliorations de la base de données
├── Améliorations du Query Builder
├── Optimisations de performance
└── Nouvelles fonctionnalités mineurs

2024-Q3: 1.2.0  📅 Planifié
├── Améliorations des API
├── Système de cache amélioré
├── Support d'authentification avancée
└── Nouvelles fonctionnalités mineurs

2024-Q4: 2.0.0  📅 Planifié
├── Réécriture majeure
├── Nouvelles architectures
├── Changements incompatibles
└── Fonctionnalités majeures
```

---

## 📋 Historique des Versions (Changelog)

### Version 1.0.0 (2024-01-06) - Production Release

**Fonctionnalités:**

- ✅ Framework MVC complet
- ✅ Système de routage avec paramètres et contraintes
- ✅ Requête/Réponse HTTP
- ✅ Gestion des sessions
- ✅ ORM Modele avec requêtes WHERE, CREATE, UPDATE, DELETE
- ✅ Validation des entrées (10 règles)
- ✅ Traductions i18n (8 langues)
- ✅ Réponses API REST
- ✅ Système CLI (php bmvc -cmd)
- ✅ Support Middleware
- ✅ Assistants d'authentification
- ✅ 15+ fonctions utilitaires

**Tests:**

- ✅ 10 tests unitaires (Requete, Reponse, Session)
- ✅ 19 tests ORM/Validation/Helpers
- ✅ 23 tests fonctionnels (Routeur, Traduction, API, CLI, Auth, Middleware)
- ✅ Total: 35 tests, 85%+ couverture, 100% passants

**Documentation:**

- ✅ 17 fichiers documentation (5650+ lignes)
- ✅ Guide rapide (5 minutes)
- ✅ Guide d'utilisation complet (800+ lignes)
- ✅ Exemple complet d'application blog
- ✅ Guide de tests
- ✅ Guide de déploiement
- ✅ Gestion des versions

**Packaging:**

- ✅ Package Composer (bmvc/framework)
- ✅ Type: library (pour distribution)
- ✅ PSR-4 Autoloading
- ✅ 7 composer scripts
- ✅ Prêt pour Packagist

### Version 0.7.0 (Phase 7) - CLI, i18n, API REST

- ✅ Système CLI complet
- ✅ Module de génération automatique (Gallery example)
- ✅ i18n avec 8 langues
- ✅ API REST Response
- ✅ 25 tests Phase 7
- ✅ 9 fichiers documentation

### Version 0.6.0 (Phase 6) - Validation & Helpers

- ✅ Framework de validation complet
- ✅ 15+ fonctions utilitaires
- ✅ Tests d'intégration

### Version 0.5.0 (Phase 5) - Validation

- ✅ Validation des entrées
- ✅ Règles de validation multiples

### Version 0.4.0 (Phase 4) - Middleware & Sessions

- ✅ Support Middleware
- ✅ Gestion des sessions

### Version 0.3.0 (Phase 3) - ORM & Models

- ✅ ORM Modele complet
- ✅ Requêtes WHERE, CREATE, UPDATE, DELETE
- ✅ Chainable methods

### Version 0.2.0 (Phase 2) - Contrôleurs & Routes

- ✅ Système de routage
- ✅ Contrôleurs de base
- ✅ Requête/Réponse HTTP

### Version 0.1.0 (Phase 1) - Core MVC

- ✅ Architecture MVC de base
- ✅ Clases Requete, Reponse, Routeur

---

## 🔐 Stratégie de Correctifs de Sécurité

### Patch Versions (1.0.x)

```
1.0.0 → 1.0.1: Correctifs de sécurité critiques
1.0.1 → 1.0.2: Correctifs de sécurité importants
1.0.2 → 1.0.3: Correctifs de sécurité mineurs
```

### Processus

1. **Découverte**: Signaler la vulnérabilité
2. **Analyse**: Évaluer la gravité
3. **Correction**: Développer le correctif
4. **Test**: Vérifier la correction
5. **Release**: Publier la version 1.0.x
6. **Annonce**: Communiquer le correctif

### Exemple

```
Vulnérabilité: SQL Injection possible dans Modele::where()
Gravité: Critique (CVSS 9.0)
Fix: Vérifier les paramètres in Modele::where()
Version: 1.0.1
Date: 2024-02-15
```

---

## 🏷️ Git Tags

### Créer une Release

```bash
# Créer un tag pour la version
git tag -a v1.0.0 -m "Release version 1.0.0"

# Pousser le tag
git push origin v1.0.0

# Ou pousser tous les tags
git push origin --tags
```

### Voir les Tags

```bash
# Lister tous les tags
git tag -l

# Voir tags avec descriptions
git tag -l -n

# Voir le tag courant
git describe --latest-tag
```

### Format du Tag

```
v1.0.0          Stable release
v1.0.0-alpha    Alpha release
v1.0.0-beta     Beta release
v1.0.0-rc1      Release candidate
```

---

## 📦 Composer Versioning

### composer.json

```json
{
  "name": "bmvc/framework",
  "version": "1.0.0",
  "type": "library",
  "description": "Framework PHP MVC professionnel",
  "keywords": ["framework", "mvc", "php", "français"],
  "require": {
    "php": ">=8.0"
  }
}
```

### Installation des Versions

```bash
# Dernière version stable
composer require bmvc/framework

# Version spécifique
composer require bmvc/framework:1.0.0

# Version mineure
composer require bmvc/framework:^1.0

# Version majeure
composer require bmvc/framework:~1.0
```

### Mise à Jour

```bash
# Mettre à jour vers la dernière version mineure
composer update

# Mettre à jour vers une version spécifique
composer require bmvc/framework:1.1.0
```

---

## 📈 Statistiques des Versions

### Ligne de Temps

```
Phase 1 (v0.1.0):  100 lignes de code
Phase 2 (v0.2.0):  500 lignes
Phase 3 (v0.3.0): 1500 lignes
Phase 4 (v0.4.0): 2500 lignes
Phase 5 (v0.5.0): 3500 lignes
Phase 6 (v0.6.0): 4500 lignes
Phase 7 (v0.7.0): 5000+ lignes (avec docs 2650+)
Phase 8 (v1.0.0): 5000+ lignes (avec docs 5650+)
```

### Comparaison des Versions

| Métrique      | 0.7.0 | 1.0.0 | Changement |
| ------------- | ----- | ----- | ---------- |
| Lignes code   | 5000  | 5000+ | -          |
| Tests         | 25    | 35    | +40%       |
| Couverture    | 70%   | 85%+  | +15%       |
| Documentation | 2650+ | 5650+ | +113%      |
| Fichiers docs | 9     | 17    | +89%       |
| Features      | 40+   | 50+   | +25%       |

---

## 🔄 Migration Entre Versions

### De 0.7.0 à 1.0.0

```bash
# Mettre à jour Composer
composer require bmvc/framework:^1.0

# Exécuter les tests
composer test

# Vérifier la compatibility
# Pas de changements majeurs!
```

### Changements Compatibles

```
✅ Nouvelles fonctionnalités (non-breaking)
✅ Améliorations de performance
✅ Corrections de bugs
✅ Améliorations de documentation
❌ Pas de changements d'API
❌ Pas de modifications de classes publiques
```

---

## 📢 Processus de Release

### 1. Planification (1-2 semaines avant)

```
- Définir les fonctionnalités pour la version
- Créer une branche de release
- Planifier le testing
```

### 2. Développement (2-3 semaines)

```
- Développer les nouvelles fonctionnalités
- Ajouter les tests
- Écrire la documentation
```

### 3. Test (1 semaine)

```
- Tests unitaires
- Tests fonctionnels
- Tests d'intégration
- Test de couverture
```

### 4. Release Candidate (3-5 jours)

```
- Publier RC (Release Candidate)
- Collecte de feedback
- Corrections finales
```

### 5. Release (1 jour)

```bash
# Fusionner sur main
git checkout main
git merge release/1.1.0

# Créer le tag
git tag -a v1.1.0 -m "Release v1.1.0"

# Pousser
git push origin main
git push origin v1.1.0

# Publier sur Packagist
# (automatique si connecté)
```

### 6. Post-Release

```
- Annonce sur les réseaux
- Documentation mise à jour
- Forum/GitHub discussions
```

---

## 🎯 Politique de Support

### Support Actif (Active Support)

```
v1.0.0: 12 mois (jusqu'à v1.1.0 release)
v1.1.0: 12 mois (jusqu'à v1.2.0 release)
v1.2.0: 24 mois (jusqu'à v2.0.0 release)
```

### Support Critique (Critical Support)

```
v1.0.0: 3 mois après v1.1.0 (seulement correctifs critiques)
v1.1.0: 3 mois après v1.2.0
v1.2.0: 6 mois après v2.0.0
```

### Support Terminé (EOL - End of Life)

```
v0.7.0 et antérieures: EOL (fin de support)
Pas de correctifs ou mises à jour
```

---

## ✅ Checklist Release

### Avant la Release

- [ ] Tous les tests passent
- [ ] Code coverage ≥ 80%
- [ ] Documentation mise à jour
- [ ] CHANGELOG complété
- [ ] Version incrémentée dans composer.json
- [ ] Git tag créé

### Après la Release

- [ ] Tag poussé vers GitHub
- [ ] Release notes publiées
- [ ] Packagist mis à jour
- [ ] Annonce faite
- [ ] Branche main fusionnée

---

## 📞 Support de Versioning

### Questions Fréquentes

**Q: Quelle version utiliser?**
A: Toujours la dernière version stable (actuellement 1.0.0)

**Q: Comment mettre à jour?**
A: `composer update bmvc/framework`

**Q: Comment rester à une version?**
A: Utiliser `"bmvc/framework": "^1.0"` dans composer.json

**Q: Comment contributeur?**
A: Voir CONTRIBUTING.md (à créer)

---

**Gestion des Versions - BMVC Framework**

**Version Actuelle:** 1.0.0  
**Statut:** Production Prêt ✅  
**Date de Sortie:** 2024-01-06  
**Prochaine:** 1.1.0 (Q2 2024)

**Suivez la Stratégie SemVer!** 📦
