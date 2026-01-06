# 🚀 Déploiement - Checklist Complète

**Guide de Déploiement pour BMVC v1.0.0 Production**

---

## 📋 Checklist Pré-Déploiement

### 1. Vérification du Code

- [ ] Tous les tests unitaires passent: `composer test`
- [ ] Code coverage ≥ 80%: `composer coverage`
- [ ] Pas d'erreurs PHP: `composer lint`
- [ ] Code propre: `composer cs-check`
- [ ] Analyse statique: `composer phpstan`
- [ ] Pas de dépendances non sécurisées: `composer audit`

```bash
# Exécuter tous les contrôles
composer check
```

### 2. Sécurité

- [ ] Pas de secrets hardcodés dans le code
- [ ] Pas de credentials dans .env.example
- [ ] CORS configuré correctement
- [ ] Authentification activée
- [ ] Validation des entrées en place
- [ ] Headers de sécurité configurés
- [ ] SQL Injection prévenu (utiliser les requêtes paramétrées)
- [ ] XSS prévenu (échapper les outputs)

### 3. Configuration

- [ ] Fichier .env.production créé
- [ ] DATABASE_URL configurée
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] SESSION_NAME unique
- [ ] TIMEZONE correct
- [ ] LOCALES défini
- [ ] API_SECRET forte

```env
APP_NAME=BMVCProduction
APP_ENV=production
APP_DEBUG=false
DATABASE_URL=mysql://user:pass@host/db
SESSION_NAME=bmvc_prod
TIMEZONE=Europe/Paris
API_SECRET=random_strong_secret_min_32_chars
```

### 4. Dépendances

- [ ] Composer dependencies à jour: `composer update`
- [ ] Pas de dev dependencies en production
- [ ] vendor/ optimisé: `composer install --no-dev`
- [ ] Autoload optimisé: `composer dump-autoload --optimize`

```bash
# Installation production
composer install --no-dev --optimize-autoloader
```

### 5. Permissions

- [ ] Répertoires writable: storage/, logs/, cache/
- [ ] Permissions 755 sur répertoires
- [ ] Permissions 644 sur fichiers
- [ ] public/ accessible au serveur web
- [ ] .env readable par PHP

```bash
# Permissions Linux/Unix
chmod 755 storage/ logs/ cache/
chmod 644 .env
```

### 6. Base de Données

- [ ] Migrations exécutées: `php bmvc -cmd migrate`
- [ ] Seeds exécutées (si nécessaire): `php bmvc -cmd seed`
- [ ] Backups en place
- [ ] Compression des données vérifiée
- [ ] Indexes optimisés

### 7. Assets

- [ ] CSS minifié
- [ ] JavaScript minifié
- [ ] Images optimisées
- [ ] Cache busting en place
- [ ] CDN configuré (si applicable)

```bash
# Exemple structure
public/
├── css/
│   └── app.min.css (minifié)
├── js/
│   └── app.min.js (minifié)
├── images/
│   └── (optimisées)
└── index.php
```

### 8. Documentation & Logs

- [ ] Logs accessibles à /var/log/bmvc/
- [ ] Rotation des logs en place
- [ ] Documentation déployée
- [ ] README.md à jour
- [ ] CHANGELOG.md à jour
- [ ] API docs générées

---

## 🌐 Configuration Serveur Web

### Apache

```apache
<VirtualHost *:80>
    ServerName example.com
    ServerAlias www.example.com

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

    <Directory /var/www/bmvc>
        Require all denied
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/bmvc_error.log
    CustomLog ${APACHE_LOG_DIR}/bmvc_access.log combined
</VirtualHost>
```

### Nginx

```nginx
server {
    listen 80;
    server_name example.com www.example.com;
    root /var/www/bmvc/public;

    index index.php;

    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastio_script_name;
    }

    location ~ /\. {
        deny all;
    }

    access_log /var/log/nginx/bmvc_access.log;
    error_log /var/log/nginx/bmvc_error.log;
}
```

### SSL/TLS

```bash
# Utiliser Let's Encrypt avec Certbot
certbot certonly --webroot -w /var/www/bmvc/public -d example.com

# Certificat auto-renouvelable
sudo certbot renew --quiet --no-eff-email
```

---

## 🗄️ Configuration Base de Données

### MySQL/MariaDB

```bash
# Créer la base de données
mysql -u root -p
CREATE DATABASE bmvc_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'bmvc'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON bmvc_prod.* TO 'bmvc'@'localhost';
FLUSH PRIVILEGES;
```

### PostgreSQL

```bash
# Créer la base de données
sudo -u postgres createdb bmvc_prod
sudo -u postgres createuser bmvc
sudo -u postgres psql -c "ALTER USER bmvc WITH PASSWORD 'strong_password';"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE bmvc_prod TO bmvc;"
```

---

## 🚀 Déploiement Automatisé

### Déploiement avec Git

```bash
#!/bin/bash
# deploy.sh

set -e

cd /var/www/bmvc

# Pull latest code
git fetch origin
git reset --hard origin/main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php bmvc -cmd migrate

# Clear cache
php bmvc -cmd cache:clear

# Set permissions
chmod 755 storage/ logs/ cache/
chown www-data:www-data -R storage/ logs/ cache/

echo "✅ Déploiement réussi!"
```

### Déploiement avec Docker

```dockerfile
FROM php:8.0-apache

WORKDIR /var/www/bmvc

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chmod 755 storage/ logs/ cache/
RUN chown www-data:www-data -R .

# Enable mod_rewrite
RUN a2enmod rewrite

EXPOSE 80
```

```bash
# Construire et lancer le container
docker build -t bmvc:1.0.0 .
docker run -d -p 80:80 --name bmvc-prod bmvc:1.0.0
```

### CI/CD avec GitHub Actions

```yaml
# .github/workflows/deploy.yml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v2

      - name: Run Tests
        run: composer test

      - name: Deploy to Production
        run: |
          ssh deploy@example.com "cd /var/www/bmvc && ./deploy.sh"
```

---

## 📊 Monitoring & Logs

### Logs

```bash
# Afficher les logs
tail -f /var/log/bmvc/app.log

# Logs Apache
tail -f /var/log/apache2/bmvc_error.log

# Logs Nginx
tail -f /var/log/nginx/bmvc_error.log
```

### Monitoring

```bash
# Vérifier que le site répond
curl -I https://example.com

# Vérifier le status du serveur
systemctl status apache2
systemctl status nginx
systemctl status mysql
```

### Alertes

```bash
# Vérifier les erreurs PHP
grep "ERROR" /var/log/bmvc/app.log

# Vérifier les problèmes de performance
grep "SLOW" /var/log/bmvc/app.log

# Vérifier les erreurs de base de données
grep "Database" /var/log/bmvc/app.log
```

---

## 🔧 Dépannage Commun

### Problème: 500 Error

```bash
# Vérifier les logs
tail -f /var/log/apache2/error.log

# Vérifier les permissions
ls -la /var/www/bmvc/storage/

# Vérifier la connexion base de données
php -r "require 'vendor/autoload.php'; ..."
```

### Problème: Lenteur

```bash
# Profiler l'application
php -d xdebug.mode=profile ...

# Vérifier les requêtes slow de MySQL
SHOW VARIABLES LIKE 'long_query_time';
SELECT * FROM mysql.slow_log;
```

### Problème: Erreurs d'authentification

```bash
# Vérifier les credentials
grep DATABASE_URL .env

# Tester la connexion
mysql -u bmvc -p -h localhost bmvc_prod
```

---

## 📈 Optimisations Performance

### PHP

```php
// .htaccess ou php.ini
memory_limit = 256M
upload_max_filesize = 50M
post_max_size = 50M
```

### Cache

```bash
# Activer le cache d'opcodes
opcache.enable = 1
opcache.memory_consumption = 128
```

### Database

```sql
-- Ajouter des indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_posts_user_id ON posts(user_id);
```

### Compression

```apache
# .htaccess
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml
    AddOutputFilterByType DEFLATE text/css text/javascript
    AddOutputFilterByType DEFLATE application/javascript application/json
</IfModule>
```

---

## 🎯 Post-Déploiement

### Vérifications Finales

- [ ] Site accessible: https://example.com
- [ ] Redirects HTTP → HTTPS actifs
- [ ] Performance acceptable: < 2 secondes
- [ ] Pas d'erreurs 404/500
- [ ] Base de données responsive
- [ ] Envoi d'emails fonctionnel
- [ ] Analytics tracking en place
- [ ] Backups exécutés

### Maintenance Régulière

```bash
# Hebdomadaire
- Vérifier les logs
- Exécuter les tests
- Mettre à jour Composer
- Vérifier la performance

# Mensuel
- Analyser les métriques
- Optimiser la base de données
- Nettoyer les anciens logs
- Vérifier la sécurité

# Trimestriel
- Audit de sécurité complet
- Mise à jour des dépendances majeure
- Révision de la documentation
- Planning du prochain release
```

---

## 📞 Support & Escalade

### Équipe Support

```
Niveau 1: Monitoring automatisé
├─ Logs monitoring
├─ Uptime checking
└─ Performance alerts

Niveau 2: Équipe technique
├─ Diagnostique des problèmes
├─ Corrections urgentes
└─ Optimisations

Niveau 3: Escalade développeur
├─ Issues majeures
├─ Regressions
└─ Nouvelles features
```

---

## ✅ Checklist Finale

- [ ] Tests: 100% passants
- [ ] Security: Audit OK
- [ ] Performance: < 2s response time
- [ ] Monitoring: En place
- [ ] Backups: Configurés
- [ ] Logs: Accessibles
- [ ] Alertes: Actives
- [ ] Documentation: Mise à jour
- [ ] Team: Formée au déploiement
- [ ] Rollback: Procédure documentée

---

**🚀 Déploiement - Framework BMVC**

**Version:** 1.0.0  
**Statut:** Production Prêt ✅  
**Last Updated:** 2024-01-06

**Votre application est prête pour la production!** 🎉
