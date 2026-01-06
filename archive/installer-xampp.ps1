# ==============================================================================
# BMVC - Script d'installation automatique pour XAMPP
# ==============================================================================
# Usage: powershell -ExecutionPolicy Bypass -File installer-xampp.ps1

Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║         BMVC Framework - Installation XAMPP Automatique        ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# ============================================================================
# 1. Vérifications des prérequis
# ============================================================================

Write-Host "📋 [1/5] Vérification des prérequis..." -ForegroundColor Yellow

$checks = @{
    "PHP" = { php --version }
    "Composer" = { composer --version }
    "XAMPP Apache" = { Test-Path "C:\xampp\apache" }
}

foreach ($name in $checks.Keys) {
    try {
        if ($name -eq "XAMPP Apache") {
            if ($checks[$name]) {
                Write-Host "  ✅ $name présent" -ForegroundColor Green
            } else {
                Write-Host "  ❌ $name non trouvé" -ForegroundColor Red
            }
        } else {
            $checks[$name].Invoke() | Out-Null
            Write-Host "  ✅ $name installé" -ForegroundColor Green
        }
    } catch {
        Write-Host "  ❌ $name non trouvé" -ForegroundColor Red
    }
}

Write-Host ""

# ============================================================================
# 2. Composer install/update
# ============================================================================

Write-Host "📦 [2/5] Installation des dépendances Composer..." -ForegroundColor Yellow

$composerJson = Get-Content "composer.json" -ErrorAction SilentlyContinue
if ($composerJson) {
    Write-Host "  ⚙️  Exécution: composer install"
    composer install --no-dev
    Write-Host "  ✅ Dépendances installées" -ForegroundColor Green
} else {
    Write-Host "  ❌ composer.json non trouvé" -ForegroundColor Red
}

Write-Host ""

# ============================================================================
# 3. Vérification des fichiers .htaccess
# ============================================================================

Write-Host "📂 [3/5] Vérification des fichiers .htaccess..." -ForegroundColor Yellow

$htaccessRoot = ".\.htaccess"
$htaccessPublic = ".\public\.htaccess"

$fileChecks = @{
    ".htaccess racine" = $htaccessRoot
    ".htaccess public" = $htaccessPublic
}

foreach ($file in $fileChecks.Keys) {
    $path = $fileChecks[$file]
    if (Test-Path $path) {
        $content = Get-Content $path
        if ($content -and $content.Length -gt 10) {
            Write-Host "  ✅ $file OK ($(Get-Item $path).Length bytes)" -ForegroundColor Green
        } else {
            Write-Host "  ⚠️  $file vide ou trop petit" -ForegroundColor Yellow
        }
    } else {
        Write-Host "  ❌ $file non trouvé" -ForegroundColor Red
    }
}

Write-Host ""

# ============================================================================
# 4. Création des dossiers nécessaires
# ============================================================================

Write-Host "📁 [4/5] Création des dossiers nécessaires..." -ForegroundColor Yellow

$directories = @(
    "stockage",
    "stockage\logs",
    "stockage\cache",
    "app\Controleurs",
    "app\Modeles",
    "app\Vues",
    "config"
)

foreach ($dir in $directories) {
    if (!(Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
        Write-Host "  ✅ Créé: $dir" -ForegroundColor Green
    } else {
        Write-Host "  ✓ Existe: $dir" -ForegroundColor Gray
    }
}

Write-Host ""

# ============================================================================
# 5. Vérification des permissions
# ============================================================================

Write-Host "🔐 [5/5] Vérification des permissions..." -ForegroundColor Yellow

$writeableDirs = @("stockage", "stockage\logs", "stockage\cache")

foreach ($dir in $writeableDirs) {
    if (Test-Path $dir) {
        $testFile = "$dir\.write-test"
        try {
            "test" | Out-File -FilePath $testFile -Force
            Remove-Item $testFile -Force
            Write-Host "  ✅ $dir est writable" -ForegroundColor Green
        } catch {
            Write-Host "  ❌ $dir n'est pas writable" -ForegroundColor Red
        }
    }
}

Write-Host ""

# ============================================================================
# Résumé et recommandations
# ============================================================================

Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║                     Installation Terminée ! 🎉                 ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

Write-Host "📋 Prochaines étapes :" -ForegroundColor Yellow
Write-Host "  1. Activez mod_rewrite dans Apache (voir CONFIGURATION_XAMPP.md)"
Write-Host "  2. Démarrez Apache via XAMPP Control Panel"
Write-Host "  3. Vérifiez votre config: http://localhost/bmvc/verifier-apache.php"
Write-Host "  4. Accédez à l'application: http://localhost/bmvc/"
Write-Host ""

Write-Host "📖 Documentation :" -ForegroundColor Cyan
Write-Host "  • CONFIGURATION_XAMPP.md - Guide complet Apache/XAMPP"
Write-Host "  • README.md - Structure du projet"
Write-Host "  • ROADMAP_COMPLETE.md - Feuille de route du développement"
Write-Host ""

Write-Host "✨ Bon développement !" -ForegroundColor Green
