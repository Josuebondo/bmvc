#!/bin/bash

# ==============================================================================
# BMVC - Script d'installation automatique pour XAMPP (Linux/Mac)
# ==============================================================================
# Usage: chmod +x installer-xampp.sh && ./installer-xampp.sh

echo ""
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║         BMVC Framework - Installation XAMPP Automatique        ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

# ============================================================================
# 1. Vérifications des prérequis
# ============================================================================

echo "📋 [1/5] Vérification des prérequis..."

# Check PHP
if command -v php &> /dev/null; then
    PHP_VERSION=$(php --version | head -n 1)
    echo "  ✅ PHP installé: $PHP_VERSION"
else
    echo "  ❌ PHP non trouvé"
fi

# Check Composer
if command -v composer &> /dev/null; then
    echo "  ✅ Composer installé"
else
    echo "  ❌ Composer non trouvé"
fi

echo ""

# ============================================================================
# 2. Composer install/update
# ============================================================================

echo "📦 [2/5] Installation des dépendances Composer..."

if [ -f "composer.json" ]; then
    echo "  ⚙️  Exécution: composer install"
    composer install --no-dev
    echo "  ✅ Dépendances installées"
else
    echo "  ❌ composer.json non trouvé"
fi

echo ""

# ============================================================================
# 3. Vérification des fichiers .htaccess
# ============================================================================

echo "📂 [3/5] Vérification des fichiers .htaccess..."

if [ -f ".htaccess" ] && [ -s ".htaccess" ]; then
    SIZE=$(wc -c < ".htaccess")
    echo "  ✅ .htaccess racine OK ($SIZE bytes)"
else
    echo "  ❌ .htaccess racine non trouvé ou vide"
fi

if [ -f "public/.htaccess" ] && [ -s "public/.htaccess" ]; then
    SIZE=$(wc -c < "public/.htaccess")
    echo "  ✅ .htaccess public OK ($SIZE bytes)"
else
    echo "  ❌ .htaccess public non trouvé ou vide"
fi

echo ""

# ============================================================================
# 4. Création des dossiers nécessaires
# ============================================================================

echo "📁 [4/5] Création des dossiers nécessaires..."

DIRS=(
    "stockage"
    "stockage/logs"
    "stockage/cache"
    "app/Controleurs"
    "app/Modeles"
    "app/Vues"
    "config"
)

for dir in "${DIRS[@]}"; do
    if [ ! -d "$dir" ]; then
        mkdir -p "$dir"
        echo "  ✅ Créé: $dir"
    else
        echo "  ✓ Existe: $dir"
    fi
done

echo ""

# ============================================================================
# 5. Vérification des permissions
# ============================================================================

echo "🔐 [5/5] Vérification des permissions..."

WRITABLE_DIRS=("stockage" "stockage/logs" "stockage/cache")

for dir in "${WRITABLE_DIRS[@]}"; do
    if [ -d "$dir" ]; then
        if [ -w "$dir" ]; then
            echo "  ✅ $dir est writable"
        else
            echo "  ⚠️  $dir n'est pas writable - essayez: chmod 755 $dir"
        fi
    fi
done

echo ""

# ============================================================================
# Résumé et recommandations
# ============================================================================

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║                     Installation Terminée ! 🎉                 ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

echo "📋 Prochaines étapes :"
echo "  1. Activez mod_rewrite dans Apache (voir CONFIGURATION_XAMPP.md)"
echo "  2. Démarrez Apache via XAMPP Control Panel"
echo "  3. Vérifiez votre config: http://localhost/bmvc/verifier-apache.php"
echo "  4. Accédez à l'application: http://localhost/bmvc/"
echo ""

echo "📖 Documentation :"
echo "  • CONFIGURATION_XAMPP.md - Guide complet Apache/XAMPP"
echo "  • README.md - Structure du projet"
echo "  • ROADMAP_COMPLETE.md - Feuille de route du développement"
echo ""

echo "✨ Bon développement !"
echo ""
