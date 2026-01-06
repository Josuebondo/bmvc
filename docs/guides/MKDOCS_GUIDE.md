# 🎯 Guide Installation et Utilisation MkDocs

**Générer un site ou PDF professionnel à partir de la documentation**

---

## 📋 Prérequis

- Python 3.8+ installé
- pip (gestionnaire de paquets Python)
- Git (optionnel, pour GitHub Pages)

---

## 🚀 Installation Rapide

### 1. Installer MkDocs et Dépendances

```bash
pip install mkdocs mkdocs-material pymdown-extensions
```

### 2. Construire le Site

```bash
cd C:\xampp\htdocs\BMVC
mkdocs build
```

Cela crée un dossier `site/` avec le site HTML prêt à être déployé.

### 3. Tester Localement

```bash
mkdocs serve
```

Le site est accessible à: **http://localhost:8000**

---

## 📦 Configuration Avancée

### Installer Toutes les Dépendances

```bash
pip install \
    mkdocs \
    mkdocs-material \
    pymdown-extensions \
    mkdocs-minify-plugin \
    mkdocs-privacy-plugin
```

### Créer un Environnement Virtuel (Recommandé)

```bash
# Windows
python -m venv venv
venv\Scripts\activate

# Linux/Mac
python -m venv venv
source venv/bin/activate
```

Puis installer les dépendances:

```bash
pip install -r requirements.txt
```

### Créer requirements.txt

```bash
pip freeze > requirements.txt
```

---

## 🌐 Générer le Site Web

### Build Local

```bash
mkdocs build
```

**Résultat:** Dossier `site/` avec HTML/CSS/JS prêt

### Serveur de Développement

```bash
mkdocs serve
```

**Accès:** http://localhost:8000 avec hot-reload

### Déployer sur GitHub Pages

```bash
# Installer gh-deploy
pip install mkdocs-ghp

# Déployer
mkdocs gh-deploy
```

---

## 📄 Générer un PDF

### Avec Pandoc

#### 1. Installer Pandoc

**Windows:**

```bash
choco install pandoc
# ou
scoop install pandoc
```

**Linux:**

```bash
apt-get install pandoc wkhtmltopdf
```

**Mac:**

```bash
brew install pandoc wkhtmltopdf
```

#### 2. Convertir la Documentation en PDF

```bash
# Un seul fichier
pandoc docs/guides/getting-started/QUICKSTART.md -o QUICKSTART.pdf

# Tous les guides
pandoc docs/guides/*/*.md -o BMVC_Complete_Guide.pdf

# Avec table des matières et numérotation
pandoc -N --toc \
    docs/guides/getting-started/START_HERE.md \
    docs/guides/usage/*.md \
    docs/guides/deployment/*.md \
    -o BMVC_Documentation.pdf
```

### Avec MkDocs et Plugin

```bash
# Installer le plugin PDF
pip install mkdocs-pdf-export-plugin

# Ajouter à mkdocs.yml:
plugins:
  - pdf_export

# Build avec PDF
mkdocs build
```

Le PDF sera généré automatiquement à `site/pdf/index.html`

---

## 📚 Structure Recommandée pour MkDocs

```
BMVC/
├── mkdocs.yml                  # Configuration MkDocs (✅ créée)
├── docs/
│   ├── index.md                # Accueil du site
│   ├── guides/
│   │   ├── getting-started/    # (✅ organisée)
│   │   ├── usage/              # (✅ organisée)
│   │   ├── testing/            # (✅ organisée)
│   │   ├── deployment/         # (✅ organisée)
│   │   └── packaging/          # (✅ organisée)
│   ├── api/                    # (✅ API docs créées)
│   ├── examples/               # (À créer)
│   └── support/                # (À créer)
│
├── site/                       # Généré par mkdocs build
└── BMVC.pdf                    # PDF généré
```

---

## 🎨 Personnalisation du Thème

### Couleurs personnalisées

Éditer `mkdocs.yml`:

```yaml
theme:
  palette:
    # Light mode
    - scheme: light
      primary: blue # Couleur primaire
      accent: indigo # Couleur d'accent

    # Dark mode
    - scheme: dark
      primary: deep orange
      accent: deep orange
```

**Couleurs disponibles:** blue, indigo, purple, pink, red, orange, yellow, lime, green, teal, cyan, white, etc.

### Logo et Favicon

```yaml
theme:
  logo: assets/logo.png
  favicon: assets/favicon.png
```

### Personnaliser les Templates

Créer un dossier `overrides/`:

```
docs/
└── overrides/
    ├── base.html
    ├── home.html
    └── main.html
```

---

## 🔧 Tâches Courantes

### Ajouter une Nouvelle Page

1. Créer le fichier Markdown dans `docs/`
2. L'ajouter dans `mkdocs.yml` sous `nav:`
3. Relancer `mkdocs serve` pour voir les changements

### Modifier le Logo

```bash
# Placer le logo dans docs/assets/
docs/assets/logo.png

# Modifier mkdocs.yml
theme:
  logo: assets/logo.png
```

### Ajouter des Emojis

```markdown
:smile: :heart: :rocket: :warning:
```

Les emojis sont supportés via pymdownx.emoji

### Créer des Boîtes d'Alerte

```markdown
!!! note "Titre"
Contenu de la note

!!! warning "Attention"
Contenu d'avertissement

!!! error "Erreur"
Contenu d'erreur
```

---

## 📊 Commandes MkDocs

```bash
# Installer les dépendances
pip install mkdocs mkdocs-material

# Serveur de développement
mkdocs serve

# Builder le site
mkdocs build

# Nettoyer la build
mkdocs build --clean

# Nouvelle version (si utilisant mkdocs)
mkdocs new [project-name]

# Déployer sur GitHub Pages
mkdocs gh-deploy
```

---

## 🚀 Workflow Complet

### 1. Développement Local

```bash
# Terminal 1: Démarrer le serveur
mkdocs serve

# Terminal 2: Éditer les fichiers docs/
# Le site se met à jour automatiquement
```

### 2. Build pour Production

```bash
mkdocs build
```

### 3. Générer un PDF

```bash
pandoc -N --toc docs/**/*.md -o BMVC_Documentation.pdf
```

### 4. Déployer

```bash
# Sur GitHub Pages
mkdocs gh-deploy

# Sur serveur personnalisé
# Copier le contenu du dossier site/ sur votre serveur
```

---

## 💾 Sauvegarder la Configuration

Créer un fichier `requirements.txt`:

```bash
pip freeze > requirements.txt
```

**Contenu typique:**

```
mkdocs==1.5.0
mkdocs-material==9.4.0
pymdown-extensions==10.2
mkdocs-minify-plugin==0.6.0
mkdocs-privacy-plugin==0.1.0
```

**Pour restaurer:**

```bash
pip install -r requirements.txt
```

---

## 🐛 Dépannage

### "Module not found: mkdocs"

```bash
pip install mkdocs
```

### Port 8000 déjà utilisé

```bash
mkdocs serve --dev-addr 127.0.0.1:8001
```

### Changements non reflétés

```bash
# Relancer le serveur
mkdocs serve
```

### PDF n'est pas généré

```bash
pip install mkdocs-pdf-export-plugin
# Ajouter au mkdocs.yml
```

---

## 📖 Ressources

- [MkDocs Documentation](https://www.mkdocs.org/)
- [Material for MkDocs](https://squidfunk.github.io/mkdocs-material/)
- [Pandoc Manual](https://pandoc.org/MANUAL.html)
- [Python Virtual Environments](https://docs.python.org/3/tutorial/venv.html)

---

## ✅ Checklist

- [x] Configuration MkDocs créée (mkdocs.yml)
- [x] Structure des docs organisée
- [ ] MkDocs et dépendances installés
- [ ] Site généré et testé localement
- [ ] PDF généré (optionnel)
- [ ] Déploié sur GitHub Pages ou serveur

---

**📚 Documentation BMVC Prête pour Publication!** 🎉

**Commande pour démarrer:** `mkdocs serve`

**Accès:** http://localhost:8000
