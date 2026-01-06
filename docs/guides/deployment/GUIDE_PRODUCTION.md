# 🚀 Déploiement en Production - Checklist Complète

**Guide Complet pour Déployer BMVC en Production**

---

## ✅ Checklist Pré-Déploiement

### 1. Tests & Qualité

```bash
# Exécuter tous les tests
composer test

# Résultat attendu: 35/35 tests PASSING ✅
```

- [ ] Tous les tests passent (35/35)
- [ ] Code coverage ≥ 85%
- [ ] Pas d'erreurs PHP
- [ ] Pas d'avertissements

### 2. Configuration Environnement

Créez un fichier `.env.production`:

```bash
cp .env .env.production
```

Modifiez `.env.production`:

```env
# ================================================
# APPLICATION
# ================================================
NOM_APPLICATION=BMVC Production
ENVIRONNEMENT=production
DEBOGAGE=false
CLE_SECRETE=your-very-strong-secret-key-min-32-chars
FUSEAU_HORAIRE=Europe/Paris
LOCALE=fr
URL_APPLICATION=https://votredomaine.com

# ================================================
# BASE DE DONNÉES PRODUCTION
# ================================================
TYPE_CONNEXION=mysql
HOTE_BD=prod-db-server.com
PORT_BD=3306
NOM_BD=bmvc_prod
UTILISATEUR_BD=prod_user
MOT_DE_PASSE_BD=very-strong-password-here

# ================================================
# CACHE & SESSION PRODUCTION
# ================================================
PILOTE_CACHE=file
PILOTE_SESSION=file
TTL_CACHE=86400
```

- [ ] `.env.production` créé et configuré
- [ ] Secrets forts (min 32 caractères)
- [ ] Basedata configurée
- [ ] DEBUG = false
- [ ] URL correcte

### 3. Dépendances

```bash
# Installer les dépendances de production
composer install --no-dev --optimize-autoloader

# Vérifier les dépendances
composer audit
```

- [ ] Pas de dev dependencies en production
- [ ] Pas de vulnérabilités de sécurité
- [ ] Autoloader optimisé

### 4. Permissions

```bash
# Linux/Unix
chmod 755 storage/ logs/ cache/ public/
chmod 644 .env.production
chown -R www-data:www-data storage/ logs/ cache/
```

- [ ] Répertoires writable: storage/, logs/, cache/
- [ ] Permissions 755 sur répertoires
- [ ] Permissions 644 sur .env
- [ ] Propriétaire correct (www-data)

### 5. Base de Données

```bash
# Créer la base de données
mysql -u root -p
CREATE DATABASE bmvc_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'prod_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON bmvc_prod.* TO 'prod_user'@'localhost';
FLUSH PRIVILEGES;

# Exécuter les migrations
php bmvc migrate
```

- [ ] Base de données créée
- [ ] Utilisateur créé
- [ ] Migrations exécutées
- [ ] Données seedées (si nécessaire)

### 6. SSL/TLS

```bash
# Obtenir un certificat Let's Encrypt
certbot certonly --webroot -w /var/www/bmvc/public -d votredomaine.com

# Auto-renouvellement
sudo certbot renew --quiet --no-eff-email
```

- [ ] Certificat SSL obtenu
- [ ] HTTPS configuré
- [ ] Redirection HTTP → HTTPS
- [ ] Auto-renewal activé

### 7. Serveur Web

**Apache - Créer `/etc/apache2/sites-available/bmvc.conf`:**

```apache
<VirtualHost *:80>
    ServerName votredomaine.com
    ServerAlias www.votredomaine.com
    DocumentRoot /var/www/bmvc/public

    <Directory /var/www/bmvc/public>
        AllowOverride All
        Require all granted
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteBase /
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ index.php [L]
        </IfModule>
    </Directory>

    # Redirection HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    ErrorLog ${APACHE_LOG_DIR}/bmvc_error.log
    CustomLog ${APACHE_LOG_DIR}/bmvc_access.log combined
</VirtualHost>

<VirtualHost *:443>
    ServerName votredomaine.com
    ServerAlias www.votredomaine.com
    DocumentRoot /var/www/bmvc/public

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/votredomaine.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/votredomaine.com/privkey.pem

    <Directory /var/www/bmvc/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/bmvc_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/bmvc_ssl_access.log combined
</VirtualHost>
```

**Activer la configuration:**

```bash
a2enmod rewrite
a2enmod ssl
a2ensite bmvc
apache2ctl configtest  # Vérifier la syntaxe
systemctl restart apache2
```

**Nginx - Créer `/etc/nginx/sites-available/bmvc`:**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name votredomaine.com www.votredomaine.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name votredomaine.com www.votredomaine.com;

    root /var/www/bmvc/public;
    index index.php;

    ssl_certificate /etc/letsencrypt/live/votredomaine.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/votredomaine.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\. {
        deny all;
    }

    access_log /var/log/nginx/bmvc_access.log;
    error_log /var/log/nginx/bmvc_error.log;
}
```

**Activer:**

```bash
ln -s /etc/nginx/sites-available/bmvc /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

- [ ] Configuration serveur en place
- [ ] Rewrite rules activées
- [ ] SSL configuré
- [ ] Serveur testé

### 8. Logs & Monitoring

```bash
# Créer les répertoires de logs
mkdir -p /var/log/bmvc
touch /var/log/bmvc/app.log
touch /var/log/bmvc/error.log
touch /var/log/bmvc/access.log

# Permissions
chmod 755 /var/log/bmvc
chown www-data:www-data /var/log/bmvc/*.log
```

- [ ] Répertoire logs créé
- [ ] Permissions correctes
- [ ] Rotation des logs configurée
- [ ] Monitoring en place

### 9. Sauvegardes

```bash
# Script de backup quotidien
#!/bin/bash
BACKUP_DIR="/backups/bmvc"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup base de données
mysqldump -u prod_user -p bmvc_prod > $BACKUP_DIR/db_$DATE.sql

# Backup application
tar -czf $BACKUP_DIR/app_$DATE.tar.gz /var/www/bmvc

# Garder les 7 derniers backups
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
```

- [ ] Stratégie de backup définie
- [ ] Premiers backups exécutés
- [ ] Restauration testée
- [ ] Cron job configuré

### 10. DNS

```
A Record:     votredomaine.com  → votre-ip-serveur
A Record:     www.votredomaine.com → votre-ip-serveur
CNAME:        (optionnel pour sous-domaines)
```

- [ ] Records DNS pointent vers le serveur
- [ ] DNS propagé (attendre 24h si nouveau)
- [ ] Résolution vérifiée

---

## 🚀 Déploiement

### Étape 1: Copier l'Application

```bash
# Cloner du repository
git clone https://github.com/yourusername/bmvc.git /var/www/bmvc
cd /var/www/bmvc

# Ou copier les fichiers
scp -r bmvc/ user@production-server:/var/www/
```

### Étape 2: Installer les Dépendances

```bash
cd /var/www/bmvc
composer install --no-dev --optimize-autoloader
```

### Étape 3: Configurer l'Environnement

```bash
# Copier la configuration
cp .env.production .env
chmod 644 .env
```

### Étape 4: Initialiser la Base de Données

```bash
# Créer les tables
php bmvc migrate

# Seeder les données initiales (optionnel)
php bmvc seed
```

### Étape 5: Vérifier l'Installation

```bash
# Tester l'accès
curl -I https://votredomaine.com

# Vérifier les logs
tail -f /var/log/nginx/bmvc_error.log
tail -f /var/log/bmvc/app.log
```

---

## 📊 Post-Déploiement

### Vérifications Finales

- [ ] Site accessible sur https://votredomaine.com
- [ ] Pas d'erreurs 404/500
- [ ] Performance acceptable (< 2s)
- [ ] HTTPS actif et valide
- [ ] Logs propres (pas d'erreurs)
- [ ] Base de données responsive
- [ ] Emails fonctionnels
- [ ] Analytics actif

### Monitoring

```bash
# Vérifier les services
systemctl status nginx
systemctl status mysql
systemctl status php8.0-fpm

# Espace disque
df -h

# Mémoire
free -h

# Processus PHP
ps aux | grep php
```

---

## 🔍 Vérifications de Sécurité

```bash
# Vérifier les permissions
ls -la /var/www/bmvc
ls -la /var/www/bmvc/storage

# Vérifier les fichiers cachés
find /var/www/bmvc -name ".*" -type f

# Test SSL
curl -I --insecure https://votredomaine.com

# Test HSTS
curl -I https://votredomaine.com | grep Strict
```

---

## 📝 Maintenance Régulière

### Quotidienne

- [ ] Vérifier les logs
- [ ] Monitoring des serveurs
- [ ] Vérifier les alertes

### Hebdomadaire

- [ ] Backup vérifiés
- [ ] Performances analysées
- [ ] Sécurité vérifiée

### Mensuelle

- [ ] Mises à jour système
- [ ] Audit de sécurité
- [ ] Révision documentation
- [ ] Optimisations

---

## 🆘 Dépannage Production

### Le site ne répond pas

```bash
# 1. Vérifier le serveur web
systemctl status nginx
systemctl restart nginx

# 2. Vérifier la base de données
mysql -u prod_user -p bmvc_prod
SHOW TABLES;

# 3. Vérifier les logs
tail -f /var/log/nginx/bmvc_error.log
tail -f /var/log/bmvc/app.log
```

### Erreur 500

```bash
# Vérifier les logs détaillés
tail -100 /var/log/bmvc/app.log

# Vérifier les permissions
chmod 755 storage/ logs/ cache/

# Vérifier la base de données
mysql -u prod_user -p bmvc_prod < query.sql
```

### Performance lente

```bash
# Vérifier l'utilisation CPU/RAM
top

# Profiler les requêtes slow
grep "SLOW" /var/log/bmvc/app.log

# Optimiser la base de données
mysql> ANALYZE TABLE articles;
mysql> OPTIMIZE TABLE articles;
```

---

## ✅ Checklist Final

- [ ] Tests: 100% passants
- [ ] Config: Production
- [ ] Debug: False
- [ ] SSL: Actif
- [ ] Logs: Configurés
- [ ] Backup: Automatisé
- [ ] Monitoring: Actif
- [ ] DNS: Propagé
- [ ] Performance: OK
- [ ] Sécurité: Auditée
- [ ] Support: En place
- [ ] Documentation: À jour

---

**🚀 Déploiement en Production - BMVC**

**Version:** 1.0.0  
**Status:** Production Ready ✅  
**Last Updated:** 2024-01-06

**Félicitations! Votre application est maintenant en production!** 🎉
