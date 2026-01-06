# 🛠️ Configuration BMVC avec XAMPP

Guide complet pour utiliser BMVC avec Apache/XAMPP au lieu du serveur PHP de développement.

## 📋 Prérequis

- ✅ XAMPP installé (Apache + PHP)
- ✅ Apache activé dans XAMPP Control Panel
- ✅ mod_rewrite activé (pour les URLs propres)
- ✅ BMVC dans `c:\xampp\htdocs\BMVC`

## ⚙️ Configuration XAMPP

### 1️⃣ Vérifier que Apache est actif

```
XAMPP Control Panel → Apache → Start
```

Si un port est déjà utilisé :

- Arrêter Apache
- Changer le port dans `apache/conf/httpd.conf`
- Relancer Apache

### 2️⃣ Vérifier que mod_rewrite est activé

**Fichier:** `C:\xampp\apache\conf\httpd.conf`

Cherchez cette ligne et décommentez-la (supprimez le `#`) :

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

Puis relancez Apache.

### 3️⃣ Vérifier les AllowOverride

Dans le même fichier `httpd.conf`, trouvez :

```apache
<Directory "C:/xampp/htdocs">
    ...
    AllowOverride All
    ...
</Directory>
```

Assurez-vous que **`AllowOverride All`** est là (pas `AllowOverride None`).

## 🚀 Utiliser BMVC avec XAMPP

### Option A : BMVC à la racine (http://localhost)

**Étape 1 :** Déplacer BMVC à la racine

```bash
# Supprimer le dossier htdocs existant
rmdir C:\xampp\htdocs

# Renommer BMVC en htdocs
ren C:\xampp\htdocs\BMVC C:\xampp\htdocs
```

**Étape 2 :** Mettre à jour `public/.htaccess`

```apache
# public/.htaccess
RewriteBase /
```

**Étape 3 :** Accéder à BMVC

```
http://localhost/
http://localhost/auth/login
```

### Option B : BMVC dans un dossier (http://localhost/bmvc)

**Étape 1 :** Laisser BMVC où il est

```
C:\xampp\htdocs\BMVC  ✅
```

**Étape 2 :** Mettre à jour `public/.htaccess`

```apache
# public/.htaccess
RewriteBase /bmvc/
```

**Étape 3 :** Accéder à BMVC

```
http://localhost/bmvc/
http://localhost/bmvc/auth/login
```

## 🔍 Tester la configuration

### Test 1 : Accès à la page d'accueil

```
http://localhost/bmvc/
```

Vous devriez voir la page d'accueil BMVC avec le bouton "Se connecter".

### Test 2 : Accès au formulaire de login

```
http://localhost/bmvc/auth/login
```

Vous devriez voir le formulaire de connexion.

### Test 3 : Test 404

```
http://localhost/bmvc/page-inexistante
```

Vous devriez voir un message 404.

## ⚠️ Dépannage

### "404 Not Found"

**Cause :** mod_rewrite n'est pas activé

**Solution :**

1. Ouvrir `C:\xampp\apache\conf\httpd.conf`
2. Chercher `LoadModule rewrite_module`
3. Décommenter (enlever le `#`)
4. Relancer Apache

### "AllowOverride not permitted" ou ".htaccess not working"

**Cause :** AllowOverride n'est pas configuré

**Solution :**

1. Ouvrir `httpd.conf`
2. Trouver la section `<Directory "C:/xampp/htdocs">`
3. S'assurer que **`AllowOverride All`** est présent
4. Relancer Apache

### Les fichiers CSS/JS ne se chargent pas

**Cause :** Les URLs statiques utilisent des chemins absolu

**Solution :**
Utiliser la fonction `asset()` dans les vues :

```php
<!-- ❌ Mauvais -->
<link rel="stylesheet" href="/css/style.css">

<!-- ✅ Bon avec BMVC -->
<link rel="stylesheet" href="<?= url('/css/style.css') ?>">
```

### "APPLICATION NOT RESPONDING" / Apache crash

**Cause :** Port 80 déjà utilisé

**Solution :**

1. Ouvrir `C:\xampp\apache\conf\httpd.conf`
2. Chercher `Listen 80`
3. Changer en `Listen 8080`
4. Accéder à `http://localhost:8080/bmvc/`

## 📝 Fichiers de configuration

### .htaccess (racine)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### .htaccess (public/)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /bmvc/
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

## 🔐 Configuration SSL (HTTPS)

Pour utiliser HTTPS en développement :

**Fichier :** `C:\xampp\apache\conf\extra\httpd-ssl.conf`

```apache
<VirtualHost *:443>
    ServerName localhost
    DocumentRoot "C:\xampp\htdocs"
    ...
</VirtualHost>
```

Puis accéder à `https://localhost/bmvc/`

## 📊 Checklist XAMPP

- [x] Apache installé
- [x] Apache lancé (Control Panel)
- [x] mod_rewrite activé (httpd.conf)
- [x] AllowOverride All configuré
- [x] BMVC dans htdocs
- [x] .htaccess créé (racine + public)
- [x] RewriteBase configuré
- [x] Port 80 disponible

## 🎯 Résumé

**Avec XAMPP + Apache :**

```
http://localhost/bmvc/              ← Page d'accueil
http://localhost/bmvc/auth/login    ← Formulaire login
```

**Pas besoin de ligne de commande :**

```bash
# ✅ Remplace ceci :
php -S localhost:8000 -t public/

# Par XAMPP qui tourne en arrière-plan
```

## 💡 Avantages XAMPP vs Serveur CLI

| Aspect        | PHP CLI                  | XAMPP                    |
| ------------- | ------------------------ | ------------------------ |
| Configuration | Aucune                   | Apache (professionnelle) |
| Performance   | Lent (dev)               | Rapide (prod-like)       |
| Virtual hosts | Non                      | Oui                      |
| SSL/HTTPS     | Non                      | Oui                      |
| Persévérance  | Arrête si terminal fermé | Toujours actif           |
| Logs Apache   | Non                      | Oui                      |

---

**BMVC + XAMPP = Configuration professionnelle** ✅

Maintenant votre framework fonctionne comme une vraie application web !
