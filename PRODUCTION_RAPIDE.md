# ⚡ Guide Rapide Production (5 Minutes)

**Déployer BMVC en Production en 5 étapes**

---

## 🚀 Déploiement Express

### Étape 1: Préparer l'Environnement (30 sec)

```bash
# Créer le fichier .env pour la production
cat > .env.production << 'EOF'
NOM_APPLICATION=BMVC Production
ENVIRONNEMENT=production
DEBOGAGE=false
CLE_SECRETE=generated-secure-key-here-min-32-chars
FUSEAU_HORAIRE=Europe/Paris
URL_APPLICATION=https://votredomaine.com

HOTE_BD=localhost
NOM_BD=bmvc_prod
UTILISATEUR_BD=bmvc_prod
MOT_DE_PASSE_BD=your-db-password
EOF
```

### Étape 2: Vérifier les Tests (1 min)

```bash
# Exécuter les tests
composer test

# Résultat attendu: ✓ 35/35 tests PASSING
```

### Étape 3: Installer les Dépendances (1 min)

```bash
# Installation optimisée pour production
composer install --no-dev --optimize-autoloader
```

### Étape 4: Préparer la Base de Données (1 min)

```bash
# Créer la base de données
mysql -u root -p << 'SQL'
CREATE DATABASE bmvc_prod CHARACTER SET utf8mb4;
CREATE USER 'bmvc_prod'@'localhost' IDENTIFIED BY 'your-password';
GRANT ALL PRIVILEGES ON bmvc_prod.* TO 'bmvc_prod'@'localhost';
FLUSH PRIVILEGES;
SQL

# Copier la config
cp .env.production .env

# Exécuter les migrations
php bmvc migrate
```

### Étape 5: Configurer le Serveur Web (1.5 min)

**Apache:**

```bash
# Créer la configuration
sudo tee /etc/apache2/sites-available/bmvc.conf > /dev/null << 'CONF'
<VirtualHost *:80>
    ServerName votredomaine.com
    DocumentRoot /var/www/bmvc/public

    <Directory /var/www/bmvc/public>
        AllowOverride All
        Require all granted
        RewriteEngine On
        RewriteBase /
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^ index.php [L]
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/bmvc_error.log
    CustomLog ${APACHE_LOG_DIR}/bmvc_access.log combined
</VirtualHost>
CONF

# Activer
sudo a2enmod rewrite
sudo a2ensite bmvc
sudo systemctl restart apache2
```

**Nginx:**

```bash
# Créer la configuration
sudo tee /etc/nginx/sites-available/bmvc > /dev/null << 'CONF'
server {
    listen 80;
    server_name votredomaine.com;
    root /var/www/bmvc/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }
}
CONF

# Activer
sudo ln -s /etc/nginx/sites-available/bmvc /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## ✅ Vérification Finale (30 sec)

```bash
# Tester l'accès
curl https://votredomaine.com

# Vérifier les logs
tail -20 /var/log/apache2/bmvc_error.log
# ou
tail -20 /var/log/nginx/bmvc_error.log

# Vérifier l'état du serveur
systemctl status apache2
# ou
systemctl status nginx
```

**Résultat attendu:** Page d'accueil BMVC ✅

---

## 🔒 Sécurité Minimale

```bash
# SSL avec Let's Encrypt
sudo certbot certonly --webroot -w /var/www/bmvc/public -d votredomaine.com

# Permissions
chmod 755 /var/www/bmvc/storage
chmod 755 /var/www/bmvc/logs
chmod 755 /var/www/bmvc/cache

# Owner
sudo chown -R www-data:www-data /var/www/bmvc/storage
sudo chown -R www-data:www-data /var/www/bmvc/logs
sudo chown -R www-data:www-data /var/www/bmvc/cache
```

---

## 📊 Checklist Ultra-Rapide

- [ ] Tests passent ✅
- [ ] .env.production créé ✅
- [ ] Dépendances installées ✅
- [ ] BD créée et migrée ✅
- [ ] Serveur web configuré ✅
- [ ] Site accessible ✅
- [ ] SSL actif (optionnel mais recommandé) ✅

---

## 🆘 Problèmes Courants

| Problème                      | Solution                                   |
| ----------------------------- | ------------------------------------------ |
| 404 Not Found                 | Vérifier `public/index.php`, rewrite rules |
| 500 Error                     | Vérifier `/var/log/bmvc/app.log`           |
| Permission denied             | `chmod 755 storage/ logs/ cache/`          |
| Base de données non connectée | Vérifier `.env`, credentials               |
| Port 80/443 occupé            | Tuer le processus ou changer le port       |

---

## 🚀 C'est fait!

Votre application BMVC est maintenant en production! 🎉

**Prochaines étapes:**

1. Configurer un backup automatique
2. Mettre en place un monitoring
3. Configurer les alertes
4. Documenter la procédure

---

**⚡ Guide Rapide Production - BMVC**

**Version:** 1.0.0  
**Temps:** ~5 minutes  
**Difficulté:** Facile ⭐

**Bienvenue en production!** 🌟
