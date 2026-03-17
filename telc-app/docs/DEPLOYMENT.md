# Guide de Déploiement Production - TELC Management System

## 📋 Table des matières

1. [Prérequis de Production](#1-prérequis-de-production)
2. [Préparation du Serveur](#2-préparation-du-serveur)
3. [Configuration de l'Environnement](#3-configuration-de-lenvironnement)
4. [Déploiement de l'Application](#4-déploiement-de-lapplication)
5. [Configuration du Serveur Web](#5-configuration-du-serveur-web)
6. [Optimisations de Production](#6-optimisations-de-production)
7. [Monitoring et Maintenance](#7-monitoring-et-maintenance)
8. [Déploiement Automatisé](#8-déploiement-automatisé)

---

## 1. Prérequis de Production

### 1.1 Configuration matérielle minimale

#### Serveur dédié recommandé
- **CPU** : 4+ coeurs (Intel i5/AMD Ryzen 5 ou supérieur)
- **RAM** : 8GB+ (16GB+ recommandé pour haute charge)
- **Stockage** : 100GB+ SSD (500GB+ recommandé)
- **Bande passante** : 100Mbps+ (pour les exports)

#### Configuration minimale acceptable
- **CPU** : 2+ coeurs
- **RAM** : 4GB+
- **Stockage** : 50GB+ SSD
- **Bande passante** : 50Mbps+

### 1.2 Logiciels requis

#### Système d'exploitation
- **Linux** : Ubuntu 20.04+ LTS (recommandé)
- **CentOS** : 8+ Stream
- **Debian** : 11+
- **RHEL** : 8+

#### Stack logicielle
- **PHP** : 8.1+ avec extensions requises
- **MySQL** : 8.0+ ou MariaDB 10.5+
- **Nginx** : 1.20+ (recommandé) ou Apache 2.4+
- **Redis** : 6.2+ (pour le cache et les queues)
- **Node.js** : 18+ (pour le build)

---

## 2. Préparation du Serveur

### 2.1 Installation des dépendances système

#### Ubuntu/Debian
```bash
# Mise à jour du système
sudo apt update && sudo apt upgrade -y

# Installation des paquets requis
sudo apt install -y \
    php8.1-fpm \
    php8.1-mysql \
    php8.1-xml \
    php8.1-mbstring \
    php8.1-curl \
    php8.1-zip \
    php8.1-bcmath \
    php8.1-intl \
    php8.1-gd \
    php8.1-opcache \
    nginx \
    redis-server \
    mysql-server \
    git \
    curl \
    unzip \
    composer \
    nodejs \
    npm

# Installation des extensions PHP supplémentaires
sudo apt install -y \
    php8.1-tokenizer \
    php8.1-ctype \
    php8.1-fileinfo \
    php8.1-pdo \
    php8.1-dom
```

#### CentOS/RHEL
```bash
# Installation des dépôts EPEL et Remi
sudo yum install -y epel-release
sudo yum install -y https://rpms.remirepo.net/enterprise/remi-release-8.rpm

# Installation de PHP 8.1
sudo yum module reset php
sudo yum module enable php:remi-8.1
sudo yum install -y php php-fpm php-mysqlnd php-opcache php-gd php-mbstring php-xml php-curl php-bcmath php-intl

# Installation des autres services
sudo yum install -y nginx redis mysql-server git composer nodejs
```

### 2.2 Configuration du pare-feu

#### UFW (Ubuntu)
```bash
# Activation du pare-feu
sudo ufw enable

# Ouverture des ports requis
sudo ufw allow 22/tcp      # SSH
sudo ufw allow 80/tcp      # HTTP
sudo ufw allow 443/tcp     # HTTPS
sudo ufw allow 3306/tcp    # MySQL (si accès distant requis)
sudo ufw allow 6379/tcp    # Redis (si accès distant requis)

# Application des règles
sudo ufw reload
```

#### FirewallD (CentOS/RHEL)
```bash
# Configuration des services
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --permanent --add-service=ssh

# Ouverture des ports spécifiques
sudo firewall-cmd --permanent --add-port=3306/tcp
sudo firewall-cmd --permanent --add-port=6379/tcp

# Rechargement du pare-feu
sudo firewall-cmd --reload
```

---

## 3. Configuration de l'Environnement

### 3.1 Configuration de PHP

#### php.ini optimisé pour production
```ini
; Fichier : /etc/php/8.1/fpm/php.ini

; Limites de mémoire et execution
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
max_input_vars = 3000

; Upload de fichiers
upload_max_filesize = 20M
post_max_size = 20M
max_file_uploads = 20

; Optimisations
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1

; Sécurité
expose_php = Off
display_errors = Off
log_errors = On
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
```

#### Configuration PHP-FPM
```ini
; Fichier : /etc/php/8.1/fpm/pool.d/www.conf

[www]
user = www-data
group = www-data
listen = /run/php/php8.1-fpm.sock
listen.owner = www-data
listen.group = www-data

; Processus
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 2
pm.max_spare_servers = 35

; Optimisations
pm.process_idle_timeout = 60s
pm.max_requests = 500
```

### 3.2 Configuration de MySQL

#### my.cnf optimisé pour production
```ini
# Fichier : /etc/mysql/mysql.conf.d/99-production.cnf

[mysqld]
# Configuration générale
user = mysql
pid-file = /var/run/mysqld/mysqld.pid
socket = /var/run/mysqld/mysqld.sock
port = 3306
bind-address = 127.0.0.1

# InnoDB (recommandé pour Laravel)
default-storage-engine = InnoDB
innodb_file_per_table = 1

# Mémoire et performance
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
innodb_flush_log_at_trx_commit = 1
innodb_flush_method = O_DIRECT

# Connexions
max_connections = 200
max_connect_errors = 1000
wait_timeout = 60
interactive_timeout = 60

# Log
log-error = /var/log/mysql/error.log
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2

# Sécurité
local-infile = 0
skip-show-database = 1
```

### 3.3 Configuration de Redis

#### redis.conf optimisé
```conf
# Fichier : /etc/redis/redis.conf

# Réseau
bind 127.0.0.1
port 6379
timeout 300

# Mémoire
maxmemory 256mb
maxmemory-policy allkeys-lru

# Persistance
save 900 1
save 300 10
save 60 10000

# Sécurité
requirepass votre_redis_password
rename-command FLUSHDB ""
rename-command FLUSHALL ""
rename-command CONFIG ""
```

---

## 4. Déploiement de l'Application

### 4.1 Déploiement manuel

#### Étape 1 : Préparation du code
```bash
# Création du répertoire de déploiement
sudo mkdir -p /var/www/telc-app
sudo chown $USER:$USER /var/www/telc-app

# Clonage du code source
cd /var/www/
git clone https://github.com/votre-org/telc-app.git telc-app
cd telc-app

# Ou utilisation d'un dépôt Git existant
git pull origin main
```

#### Étape 2 : Installation des dépendances
```bash
# Installation des dépendances PHP
composer install --optimize-autoloader --no-dev

# Installation des dépendances frontend
npm ci --production
npm run production
```

#### Étape 3 : Configuration de l'environnement
```bash
# Configuration de l'environnement de production
cp .env.example .env
sudo nano .env

# Variables essentielles
APP_ENV=production
APP_DEBUG=false
APP_URL=https://telc.votre-domaine.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=telc_production
DB_USERNAME=telc_user
DB_PASSWORD=mot_de_passe_securise

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Génération de la clé
php artisan key:generate --force
```

#### Étape 4 : Base de données
```bash
# Création de la base de données
mysql -u root -p -e "CREATE DATABASE telc_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Création de l'utilisateur
mysql -u root -p -e "CREATE USER 'telc_user'@'localhost' IDENTIFIED BY 'mot_de_passe_securise';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON telc_production.* TO 'telc_user'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"

# Exécution des migrations
php artisan migrate --force

# Peuplement des données de base
php artisan db:seed --class=ProductionSeeder
```

### 4.2 Permissions et liens

```bash
# Permissions des répertoires
sudo chown -R www-data:www-data /var/www/telc-app
sudo chmod -R 755 /var/www/telc-app
sudo chmod -R 755 /var/www/telc-app/storage
sudo chmod -R 755 /var/www/telc-app/bootstrap/cache

# Lien du storage
php artisan storage:link

# Permissions du lien symbolique
sudo chown -h www-data:www-data /var/www/telc-app/public/storage
```

---

## 5. Configuration du Serveur Web

### 5.1 Configuration Nginx (Recommandé)

#### /etc/nginx/sites-available/telc-app
```nginx
server {
    listen 80;
    server_name telc.votre-domaine.com;
    root /var/www/telc-app/public;
    index index.php index.html;

    # Redirection HTTP vers HTTPS
    return 301 https://$server_name$request_uri;

    # Configuration HTTPS
    server {
        listen 443 ssl http2;
        server_name telc.votre-domaine.com;
        root /var/www/telc-app/public;
        index index.php index.html;

        # Certificats SSL
        ssl_certificate /etc/ssl/certs/telc.crt;
        ssl_certificate_key /etc/ssl/private/telc.key;
        ssl_protocols TLSv1.2 TLSv1.3;
        ssl_ciphers 'ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384';
        ssl_prefer_server_ciphers off;

        # Headers de sécurité
        add_header X-Frame-Options "SAMEORIGIN" always;
        add_header X-XSS-Protection "1; mode=block" always;
        add_header X-Content-Type-Options "nosniff" always;
        add_header Referrer-Policy "no-referrer-when-downgrade" always;
        add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

        # Taille maximale des uploads
        client_max_body_size 20M;

        # Configuration PHP-FPM
        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass unix:/run/php/php8.1-fpm.sock;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
            
            # Timeout et buffers
            fastcgi_read_timeout 300;
            fastcgi_send_timeout 300;
            fastcgi_connect_timeout 60;
            fastcgi_buffer_size 128k;
            fastcgi_buffers 4 256k;
            fastcgi_busy_buffers_size 256k;
        }

        # Fichiers statiques
        location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
            expires 1y;
            add_header Cache-Control "public, immutable";
            access_log off;
            log_not_found off;
        }

        # Protection des fichiers système
        location ~ /\. {
            deny all;
            access_log off;
            log_not_found off;
        }
    }
}
```

### 5.2 Configuration Apache (Alternative)

#### /etc/apache2/sites-available/telc-app.conf
```apache
<VirtualHost *:80>
    ServerName telc.votre-domaine.com
    DocumentRoot /var/www/telc-app/public
    DirectoryIndex index.php index.html

    # Redirection vers HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName telc.votre-domaine.com
    DocumentRoot /var/www/telc-app/public
    DirectoryIndex index.php index.html

    # SSL
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/telc.crt
    SSLCertificateKeyFile /etc/ssl/private/telc.key
    SSLProtocol all -SSLv3 -TLSv1 -TLSv1.1

    # Headers de sécurité
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "no-referrer-when-downgrade"

    # Configuration Laravel
    <Directory /var/www/telc-app>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Fichiers statiques
    <LocationMatch "\.(css|js|png|jpg|jpeg|gif|ico|svg)$">
        ExpiresActive On
        ExpiresDefault "access plus 1 year"
    </LocationMatch>

    # Protection des fichiers système
    <DirectoryMatch "^\.|\.bak|\.svn">
        Require all denied
    </DirectoryMatch>
</VirtualHost>
```

---

## 6. Optimisations de Production

### 6.1 Cache Laravel

```bash
# Activation du cache de production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimisation de l'autoloader
composer dump-autoload --optimize --classmap-authoritative

# Optimisation des classes
php artisan optimize --force
```

### 6.2 Configuration du cache Redis

```bash
# Configuration Laravel pour Redis
# Dans .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=votre_redis_password
REDIS_PORT=6379

# Configuration de la connexion Redis
# Dans config/database.php
'redis' => [
    'client' => 'phpredis',
    'options' => [
        'cluster' => 'redis',
        'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
    ],
    'default' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_DB', '0'),
    ],
],
```

### 6.3 Optimisations PHP-FPM

```ini
# Dans /etc/php/8.1/fpm/pool.d/www.conf

[www]
; Processus optimisés pour la charge
pm = ondemand
pm.max_children = 100
pm.process_idle_timeout = 30s
pm.max_requests = 1000

; Mémoire
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 2
pm.max_spare_servers = 35

; Logs d'erreur
catch_workers_output = yes
php_admin_value[error_log] = /var/log/php-fpm/www-error.log
php_admin_flag[log_errors] = on
```

---

## 7. Monitoring et Maintenance

### 7.1 Configuration des logs

#### Configuration des logs Laravel
```php
// Dans config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'audit', 'performance'],
    ],
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => 'warning',
        'replace_placeholders' => true,
    ],
    'audit' => [
        'driver' => 'daily',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
        'days' => 90,
    ],
],
```

#### Rotation des logs avec logrotate
```bash
# /etc/logrotate.d/telc-app
/var/www/telc-app/storage/logs/*.log {
    daily
    missingok
    rotate 90
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        /usr/sbin/service php8.1-fpm reload
    endscript
}
```

### 7.2 Monitoring des performances

#### Installation de monitoring
```bash
# Installation de Netdata (monitoring système)
bash <(curl -sSL https://my-netdata.io/kickstart.sh)

# Configuration des alertes
# Dans /etc/netdata/netdata.conf
[health]
    email enabled = to@example.com
    email to = admin@telc.com
```

#### Monitoring applicatif avec Laravel Telescope
```bash
# Installation en environnement de staging uniquement
composer require laravel/telescope --dev

# Publication des assets
php artisan vendor:publish --provider="Laravel\Telescope\TelescopeServiceProvider"

# Configuration
# Dans .env
TELESCOPE_ENABLED=true
```

### 7.3 Sauvegardes automatiques

#### Script de backup MySQL
```bash
#!/bin/bash
# /usr/local/bin/backup-telc.sh

DB_NAME="telc_production"
DB_USER="telc_user"
DB_PASS="mot_de_passe"
BACKUP_DIR="/var/backups/mysql"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup complet
mysqldump -u $DB_USER -p$DB_PASS --single-transaction --routines --triggers $DB_NAME | gzip > $BACKUP_DIR/telc_backup_$DATE.sql.gz

# Nettoyage des anciens backups (30 jours)
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete

echo "Backup terminé : telc_backup_$DATE.sql.gz"
```

#### Configuration de la t�che cron
```bash
# Crontab -e
0 2 * * * /usr/local/bin/backup-telc.sh >> /var/log/backup.log 2>&1

0 3 * * * /usr/bin/find /var/www/telc-app/storage/logs -name "*.log.*" -mtime +90 -delete
```

---

## 8. Déploiement Automatisé

### 8.1 Script de déploiement

#### /usr/local/bin/deploy-telc.sh
```bash
#!/bin/bash

set -e

# Variables
REPO_URL="https://github.com/votre-org/telc-app.git"
DEPLOY_DIR="/var/www/telc-app"
BACKUP_DIR="/var/backups/deployments"
RELEASE_NAME="release-$(date +%Y%m%d_%H%M%S)"
DATE=$(date +%Y-%m-%d %H:%M:%S)

echo "🚀 Déploiement TELC - $DATE"

# 1. Backup de la version actuelle
if [ -d "$DEPLOY_DIR" ]; then
    echo "📦 Backup de la version actuelle..."
    mkdir -p "$BACKUP_DIR"
    tar -czf "$BACKUP_DIR/backup-$RELEASE_NAME.tar.gz" -C "$DEPLOY_DIR" .
fi

# 2. Téléchargement du nouveau code
echo "📥 Téléchargement du code..."
git clone "$REPO_URL" "$DEPLOY_DIR-new"
cd "$DEPLOY_DIR-new"

# 3. Installation des dépendances
echo "📦 Installation des dépendances..."
composer install --optimize-autoloader --no-dev
npm ci --production
npm run production

# 4. Configuration
echo "⚙️ Configuration..."
cp .env.production .env
php artisan key:generate --force

# 5. Migration de la base de données
echo "🗄️ Migration de la base de données..."
php artisan migrate --force

# 6. Optimisations
echo "⚡ Optimisations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Déploiement atomique
echo "🔄 Déploiement atomique..."
rm -rf "$DEPLOY_DIR"
mv "$DEPLOY_DIR-new" "$DEPLOY_DIR"

# 8. Permissions
echo "🔐 Configuration des permissions..."
chown -R www-data:www-data "$DEPLOY_DIR"
chmod -R 755 "$DEPLOY_DIR"
chmod -R 755 "$DEPLOY_DIR/storage"
chmod -R 755 "$DEPLOY_DIR/bootstrap/cache"

# 9. Redémarrage des services
echo "🔄 Redémarrage des services..."
systemctl reload php8.1-fpm
systemctl reload nginx
systemctl restart redis-server

# 10. Vérification
echo "✅ Vérification du déploiement..."
if curl -f http://localhost/health; then
    echo "✅ Déploiement réussi!"
else
    echo "❌ Erreur: l'application n'est pas accessible"
    exit 1
fi

echo "🎉 Déploiement terminé à $DATE"
```

### 8.2 Intégration CI/CD

#### GitHub Actions workflow
```yaml
# .github/workflows/deploy.yml
name: Deploy TELC Application

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - name: Deploy to production
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/telc-app
          git pull origin main
          composer install --no-dev
          npm ci --production
          npm run production
          php artisan migrate --force
          php artisan config:cache
          php artisan route:cache
          php artisan view:cache
          sudo systemctl reload php8.1-fpm
          sudo systemctl reload nginx
```

---

## ✅ Checklist de Déploiement

### Pré-déploiement
- [ ] **Sauvegarde** : Backup complet de l'application actuelle
- [ ] **Test en staging** : Validation dans un environnement de test
- [ ] **Vérification des dépendances** : Versions compatibles
- [ ] **Configuration SSL** : Certificats valides et à jour
- [ ] **Monitoring** : Outils de monitoring configurés

### Post-déploiement
- [ ] **Accessibilité** : Application accessible via HTTPS
- [ ] **Fonctionnalités** : Test des fonctionnalités critiques
- [ ] **Performance** : Temps de réponse acceptable
- [ ] **Logs** : Pas d'erreurs critiques
- [ ] **Sauvegarde** : Première sauvegarde post-déploiement réussie
- [ ] **Monitoring** : Alertes configurées et fonctionnelles

---

## 📞 Support de Production

Pour toute question ou problème de déploiement en production :

- 🚨 **Urgence** : +33 1 234 567 890 (24/7)
- 📧 **Email technique** : support@telc.com
- 📚 **Documentation** : [Documentation complète](TECHNICAL_DOCUMENTATION.md)
- 🐛 **Rapports de bugs** : [Issues GitHub](https://github.com/votre-org/telc-app/issues)

---

*Guide de déploiement créé le 11 Mars 2026*  
*Version : 1.0.0*
