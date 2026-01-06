# 📚 Documentation BMVC Framework

Bienvenue dans la documentation officielle du **BMVC Framework v1.0.0** !

---

## 🚀 Démarrage Rapide

### 1. **Vue d'ensemble (5 min)**

👉 [Start Here](guides/getting-started/START_HERE.md)

### 2. **Installation rapide (10 min)**

👉 [Quick Start](guides/getting-started/QUICKSTART.md)

### 3. **Gestion du serveur**

👉 [Server Guide](guides/getting-started/SERVEUR_GUIDE.md)

---

## 📖 Documentation Complète

### 🚀 Démarrage

- [Vue d'ensemble complète](guides/getting-started/START_HERE.md)
- [Installation et configuration rapide](guides/getting-started/QUICKSTART.md)
- [Gestion du serveur de développement](guides/getting-started/SERVEUR_GUIDE.md)

### 📖 Utilisation

- [Guide d'utilisation complet](guides/usage/GUIDE_UTILISATION.md)
- [Exemple: Blog complet](guides/usage/EXEMPLE_BLOG_COMPLET.md)
- [Architecture du projet](guides/usage/PROJECT_MANIFEST.md)

### 🧪 Tests & Qualité

- [Guide d'exécution des tests](guides/testing/GUIDE_TESTS_EXECUTION.md)
- [Tests et packaging](guides/testing/PHASE8_TESTS_PACKAGING.md)
- [Phase 8 - Résumé final](guides/testing/RESUME_FINAL_PHASE8.md)

### 🚀 Déploiement

- [Guide production complet](guides/deployment/GUIDE_PRODUCTION.md)
- [Déploiement en 5 minutes](guides/deployment/PRODUCTION_RAPIDE.md)
- [Checklist de déploiement](guides/deployment/DEPLOYMENT_CHECKLIST.md)
- [Stratégie de versioning](guides/deployment/VERSIONING.md)

### 📦 Distribution

- [Guide Packagist complet](guides/packaging/GUIDE_PACKAGIST.md)
- [Packagist en 5 minutes](guides/packaging/PACKAGIST_RAPIDE.md)

---

## 🔌 Référence API

Explore les classes principales du framework:

- **[Requete](api/Requete.md)** - Gestion des requêtes HTTP
- **[Reponse](api/Reponse.md)** - Gestion des réponses HTTP
- **[Routeur](api/Routeur.md)** - Routage des requêtes
- **[Modele](api/Modele.md)** - ORM pour les bases de données
- **[Validation](api/Validation.md)** - Validation des données
- **[Traduction](api/Traduction.md)** - Internationalisation (i18n)

---

## 💡 Exemples

Apprenez par l'exemple:

- [Blog Complet](examples/blog-example/) - Application blog complète
- [API REST](examples/api-rest.md) - Construire une API
- [Authentification](examples/authentication.md) - Système d'auth
- [Tests](examples/testing.md) - Exemples de tests

---

## 📊 Index Complet

👉 **[INDEX.md](INDEX.md)** - Navigation complète avec recherche par sujet

---

## 🎨 Générer le Site / PDF

### Générer un site web avec MkDocs

```bash
pip install mkdocs mkdocs-material pymdown-extensions
mkdocs serve
```

Visite: **http://localhost:8000**

### Générer un PDF

```bash
# Avec Pandoc
pandoc docs/**/*.md -o BMVC_Documentation.pdf

# Avec MkDocs (avec plugin)
mkdocs build
```

👉 [Guide MkDocs complet](guides/MKDOCS_GUIDE.md)

---

## 🗂️ Structure des Fichiers

```
docs/
├── guides/
│   ├── getting-started/      # Guides de démarrage
│   ├── usage/                # Guide d'utilisation
│   ├── testing/              # Tests et qualité
│   ├── deployment/           # Déploiement
│   └── packaging/            # Distribution
├── api/                      # Références API
├── examples/                 # Exemples de code
├── INDEX.md                  # Navigation centrale
├── STRUCTURE.md              # Plan de la documentation
└── README.md                 # Ce fichier
```

---

## 🌍 Langues Supportées

- 🇫🇷 **Français** (Principal)
- 🇬🇧 **Anglais** (En développement)

Les fichiers en français se terminent par `_FR.md`.

---

## 📞 Support & Ressources

### Questions Fréquemment Posées

👉 **[FAQ](support/faq.md)** (À créer)

### Ressources Externes

- [Documentation officielle](https://bmvc-framework.dev)
- [GitHub Repository](https://github.com/bmvc/bmvc)
- [Packagist Package](https://packagist.org/packages/bmvc/bmvc)

---

## 📚 Chemins d'Apprentissage

### 🟢 Débutant (3 heures)

1. ✅ [Start Here](guides/getting-started/START_HERE.md) (30 min)
2. ✅ [Quick Start](guides/getting-started/QUICKSTART.md) (30 min)
3. ✅ [Utilisation de base](guides/usage/GUIDE_UTILISATION.md) (2 heures)

### 🟡 Intermédiaire (5 heures)

1. ✅ Tous les guides pour débutants
2. ✅ [Exemple Blog](guides/usage/EXEMPLE_BLOG_COMPLET.md) (2 heures)
3. ✅ [Guide Tests](guides/testing/GUIDE_TESTS_EXECUTION.md) (1.5 heures)
4. ✅ [Architecture](guides/usage/PROJECT_MANIFEST.md) (1 heure)

### 🔴 Avancé (6 heures)

1. ✅ Tous les guides précédents
2. ✅ [Guide Production](guides/deployment/GUIDE_PRODUCTION.md) (2 heures)
3. ✅ [Packagist](guides/packaging/GUIDE_PACKAGIST.md) (1.5 heures)
4. ✅ [Référence API complète](api/) (2.5 heures)

---

## ✨ Points Clés

### Installation Rapide

```bash
composer create-project bmvc/bmvc monprojet
cd monprojet
php -S localhost:8000 -t public/
```

### Créer un Module

```bash
php bmvc creer:module Article
```

### Lancer les Tests

```bash
php vendor/bin/phpunit
```

### Déployer en Production

👉 [Guide Production](guides/deployment/GUIDE_PRODUCTION.md)

---

## 🔗 Liens Rapides

| Lien                                                      | Description          |
| --------------------------------------------------------- | -------------------- |
| [START_HERE.md](guides/getting-started/START_HERE.md)     | Vue d'ensemble       |
| [QUICKSTART.md](guides/getting-started/QUICKSTART.md)     | Installation         |
| [GUIDE_UTILISATION.md](guides/usage/GUIDE_UTILISATION.md) | Guide complet        |
| [API Reference](api/)                                     | Classes du framework |
| [INDEX.md](INDEX.md)                                      | Recherche par sujet  |

---

## 📝 Licence

BMVC Framework est sous licence **MIT**.

**Copyright &copy; 2026 BMVC Framework**

---

## 🤝 Contribution

Les contributions sont les bienvenues!

- Signaler des bugs
- Améliorer la documentation
- Ajouter de nouvelles fonctionnalités
- Partager vos exemples

👉 [GitHub](https://github.com/bmvc/bmvc)

---

## 📈 Statut du Framework

| Aspect            | Statut           |
| ----------------- | ---------------- |
| **Version**       | v1.0.0 ✅        |
| **Features**      | 100% complet ✅  |
| **Tests**         | 35/35 passing ✅ |
| **Coverage**      | 85%+ ✅          |
| **Documentation** | Complète ✅      |
| **Production**    | Prêt ✅          |

---

## 🎉 Commençons!

👉 **[START_HERE.md](guides/getting-started/START_HERE.md)** pour débuter

ou

👉 **[INDEX.md](INDEX.md)** pour une navigation complète

---

**Dernière mise à jour:** 6 janvier 2026

**Documentation Version:** 1.0.0

**BMVC Framework - Modern PHP Framework** 🚀
