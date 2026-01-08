# 📚 Documentation BMVC Framework

Bienvenue dans la documentation officielle de **BMVC Framework v1.0.0**!

---

## 🚀 Démarrage Rapide

### Installation en 1 Minute

```bash
composer create-project bmvc/bmvc mon-app
cd mon-app
php bmvc -d
```

Ouvrez: `http://localhost:8000`

### Exécuter les Tests

```bash
composer test
```

Résultat: **35 tests, 100% passants ✅**

---

## 📖 Navigation Principale

### 🟢 Pour Débutants (< 1 heure)

1. **[Chapitre 1: Introduction](introduction/INTRODUCTION.md)** (15 min)

   - Qu'est-ce que BMVC?
   - Qui peut l'utiliser?
   - Quoi construire avec

2. **[Chapitre 2: Démarrage Rapide](guides/getting-started/START_HERE.md)** (20 min)

   - Installation
   - Première application
   - Hello World

3. **[Chapitre 3: Quick Start](guides/getting-started/QUICKSTART.md)** (30 min)
   - Blog Mini
   - Comprendre le MVC
   - Exemples pratiques

### 🟡 Pour Développeurs (2-3 heures)

4. **[Chapitre 4: Guide Complet](guides/usage/GUIDE_UTILISATION.md)** (2h)

   - Contrôleurs
   - Modèles et ORM
   - Vues et templating
   - Routing avancé

5. **[Chapitre 5: Exemples Pratiques](examples/)** (1h)

   - Blog complet
   - API REST
   - Authentification

6. **[Chapitre 6: Tests & Qualité](guides/testing/)** (1.5h)
   - PHPUnit
   - Couverture de code
   - Qualité (PSR-12)

### 🔴 Pour Production (3+ heures)

7. **[Chapitre 7: Déploiement](guides/deployment/)** (1h)

   - Checklist production
   - Sécurité
   - Monitoring

8. **[Chapitre 8: Distribution](guides/packaging/)** (30 min)

   - Packagist
   - Versioning SemVer
   - Changelog

9. **[Chapitre 9: API Reference](api/)** (2h)
   - Classes du framework
   - Méthodes
   - Exemples d'usage

---

## 🎯 Trouver Ce Que Vous Cherchez

### Par Sujet

| Sujet                             | Où Lire                                                  |
| --------------------------------- | -------------------------------------------------------- |
| Installation                      | [Démarrage Rapide](guides/getting-started/START_HERE.md) |
| Créer un contrôleur               | [Quick Start](guides/getting-started/QUICKSTART.md)      |
| Utiliser la base de données       | [Guide Complet](guides/usage/GUIDE_UTILISATION.md)       |
| Écrire des tests                  | [Tests & Qualité](guides/testing/)                       |
| Déployer en production            | [Déploiement](guides/deployment/)                        |
| Publier sur Packagist             | [Distribution](guides/packaging/)                        |
| Trouverdocumentation d'une classe | [API Reference](api/)                                    |

### Par Niveau d'Expérience

| Niveau        | Commencez Par                                                  |
| ------------- | -------------------------------------------------------------- |
| Débutant      | [Chapitre 1: Introduction](introduction/INTRODUCTION.md)       |
| Intermédiaire | [Chapitre 2: Démarrage](guides/getting-started/START_HERE.md)  |
| Avancé        | [Chapitre 4: Guide Complet](guides/usage/GUIDE_UTILISATION.md) |
| Expert        | [Chapitre 9: API Reference](api/)                              |

---

## 📋 Plan de Documentation

```
docs/
├── INDEX.md                          ← Vous êtes ici (navigation)
├── README.md                         ← Ce fichier
│
├── introduction/
│   └── INTRODUCTION.md               ← Chapitre 1
│
├── guides/
│   ├── getting-started/              ← Chapitre 2-3
│   │   ├── START_HERE.md
│   │   └── QUICKSTART.md
│   │
│   ├── usage/                        ← Chapitre 4
│   │   └── GUIDE_UTILISATION.md
│   │
│   ├── testing/                      ← Chapitre 6
│   │   └── (guides complets)
│   │
│   ├── deployment/                   ← Chapitre 7
│   │   └── (guides production)
│   │
│   └── packaging/                    ← Chapitre 8
│       └── (guides distribution)
│
├── examples/                         ← Chapitre 5
│   └── (exemples pratiques)
│
└── api/                              ← Chapitre 9
    └── (référence API complète)
```

---

## ✨ Contenu Disponible

### ✅ Chapitre 1: Introduction (Complet)

- Qu'est-ce que BMVC
- Philosophie et objectifs
- Public cible
- Fonctionnalités principales
- Cas d'usage réels

### ✅ Chapitre 2: Démarrage Rapide (Complet)

- Installation avec Composer
- Lancer le serveur
- Vérifier les tests
- Hello World minimal et avec vue
- Exemples avec paramètres

### ✅ Chapitre 3: Quick Start (Complet)

- Application Blog Mini
- Comprendre le pattern MVC
- Créer contrôleurs, modèles, vues
- Points clés à retenir

### 🔄 Chapitre 4: Guide Complet (En création)

- Contrôleurs avancés
- Modèles et ORM
- Vues et templating
- Routing avancé
- Middleware, validation, sessions

### 🔄 Chapitre 5: Exemples (En création)

- Blog complet avec commentaires
- API REST JSON
- Système d'authentification
- Upload de fichiers

### 🔄 Chapitre 6: Tests & Qualité (En création)

- Exécution des tests PHPUnit
- Couverture de code
- Tests unitaires, fonctionnels, d'intégration
- Qualité du code (PSR-12, lint, phpstan)

### 🔄 Chapitre 7: Déploiement (En création)

- Checklist production
- Optimisation Composer
- Permissions et sécurité
- Monitoring et logs

### 🔄 Chapitre 8: Distribution (En création)

- Package Composer
- Versioning SemVer
- Publication sur Packagist
- Changelog

### 🔄 Chapitre 9: API Reference (En création)

- Classe Requete
- Classe Reponse
- Classe Routeur
- Classe Modele (ORM)
- Classe Validation
- Classe Traduction (i18n)
- Et plus...

### ✅ Chapitre 10: Index & Navigation (Complet)

- Navigation complète
- Index alphabétique
- Liens par sujet et niveau

---

## 🎓 Parcours Recommandés

### 📌 Je Veux Apprendre MVC en 1 Heure

1. [Introduction](introduction/INTRODUCTION.md) - 15 min
2. [Démarrage Rapide](guides/getting-started/START_HERE.md) - 20 min
3. [Quick Start](guides/getting-started/QUICKSTART.md) - 25 min

### 📌 Je Veux Maîtriser BMVC en 5 Heures

1. Chapitres 1-3 (1h)
2. [Guide Complet](guides/usage/GUIDE_UTILISATION.md) (2h)
3. [Exemples Pratiques](examples/) (1h)
4. [Tests & Qualité](guides/testing/) (1h)

### 📌 Je Veux Mettre en Production

1. [Déploiement](guides/deployment/) (1h)
2. [Tests & Qualité](guides/testing/) (1.5h)
3. [Distribution](guides/packaging/) (30 min)

---

## 🔗 Liens Rapides

**📌 Commencez Ici:**

- [Chapitre 1: Introduction](introduction/INTRODUCTION.md)

**📌 Navigation Complète:**

- [INDEX.md](INDEX.md) - Tous les chapitres

**📌 Code Source:**

- `app/` - Application
- `core/` - Framework core
- `tests/` - Tests automatisés

---

## 💡 Conseils Utiles

### 1️⃣ Lisez dans l'Ordre

Les chapitres sont organisés logiquement. Lisez-les dans l'ordre!

### 2️⃣ Pratiquez en Parallèle

Créez des applications en même temps que vous lisez.

### 3️⃣ Consultez l'API au Besoin

Quand vous avez une question, consultez la [Référence API](api/).

### 4️⃣ Exécutez les Tests

```bash
composer test
```

Assurez-vous que tout fonctionne!

---

## 📊 Statistiques

| Métrique          | Valeur            |
| ----------------- | ----------------- |
| **Chapitres**     | 10 chapitres      |
| **Fichiers**      | 20+ fichiers      |
| **Lignes**        | 6000+ lignes      |
| **Temps lecture** | ~3-4 heures total |
| **Couverture**    | 100% du framework |
| **Exemples**      | 20+ exemples      |
| **Cas d'usage**   | 15+ cas réels     |

---

## 🎯 Objectifs de la Documentation

✅ **Apprendre facilement** - Pour débutants PHP  
✅ **Développer rapidement** - Pour professionnels  
✅ **Déployer en production** - Pour DevOps  
✅ **Contribuer facilement** - Pour contributeurs  
✅ **Trouver rapidement** - Index et recherche

---

## 🚀 Commencez Maintenant!

### Pour Débutants

**[👉 Chapitre 1: Introduction](introduction/INTRODUCTION.md)**

### Pour Développeurs

**[👉 Chapitre 2: Démarrage](guides/getting-started/START_HERE.md)**

### Pour Avancés

**[👉 Chapitre 4: Guide Complet](guides/usage/GUIDE_UTILISATION.md)**

### Voir Tous les Chapitres

**[👉 INDEX Complet](INDEX.md)**

---

## 📞 Support

- 📧 **Email:** josuebondojw@gmail.com
- 🐛 **Issues:** [GitHub](https://github.com/Josuebondo/bmvc/issues)
- 💬 **Discussions:** [GitHub Discussions](https://github.com/Josuebondo/bmvc/discussions)

---

## 📝 Statut

| Aspect            | Statut                                   |
| ----------------- | ---------------------------------------- |
| **Framework**     | ✅ 100% Complet                          |
| **Tests**         | ✅ 35/35 Passants                        |
| **Documentation** | 🔄 En Cours (Chapitres 1-3, 10 Complets) |
| **Exemples**      | 🔄 En Cours                              |
| **API Reference** | 🔄 En Cours                              |

---

**Framework BMVC v1.0.0**

_Simple. Puissant. Professionnel._

**[👉 Commencez votre aventure →](introduction/INTRODUCTION.md)**
