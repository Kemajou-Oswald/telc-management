# Guide d'Installation Complet - TELC Management System

## 📋 Table des matières

1. [Prérequis](#1-prérequis)
2. [Installation Locale](#2-installation-locale)
3. [Configuration de l'Environnement](#3-configuration-de-lenvironnement)
4. [Base de Données](#4-base-de-données)
5. [Finalisation](#5-finalisation)
6. [Dépannage](#6-dépannage)

---

## 1. Prérequis

### 1.1 Système d'exploitation

#### Systèmes supportés
- **Linux** : Ubuntu 18.04+, CentOS 7+, Debian 9+
- **Windows** : Windows 10+ (avec WSL2 recommandé)
- **macOS** : macOS 10.15+

#### Configuration minimale recommandée
- **RAM** : 4GB+ (8GB+ recommandé)
- **Stockage** : 20GB+ d'espace libre
- **Processeur** : 2+ coeurs (4+ recommandé)

### 1.2 Logiciels requis

#### PHP
- **Version** : 7.3+ ou 8.0+ (8.1+ recommandé)
- **Extensions requises** :
  ```bash
  php -m | grep -E "(pdo|mbstring|openssl|tokenizer|xml|json|fileinfo|ctype|bcmath)"
  ```

#### Serveur Web
- **Apache** : 2.4+ avec mod_rewrite
- **Nginx** : 1.18+ (recommandé pour la performance)
- **PHP-FPM** : 7.3+ (recommandé)

#### Base de données
- **MySQL** : 5.7+ ou 8.0+ (recommandé)
- **MariaDB** : 10.2+ (alternative compatible)

#### Outils de développement
- **Composer** : 2.0+
- **Node.js** : 16.0+
- **npm** : 8.0+
- **Git** : 2.25+

---

## 2. Installation Locale

### 2.1 Clonage du projet

```bash
# Méthode 1 : HTTPS (recommandé)
git clone https://github.com/votre-org/telc-app.git
cd telc-app

# Méthode 2 : SSH (si vous avez les accès)
git clone git@github.com:votre-org/telc-app.git
cd telc-app

# Méthode 3 : Téléchargement direct
wget https://github.com/votre-org/telc-app/archive/main.zip
unzip main.zip
cd telc-app-main
```

### 2.2 Installation des dépendances PHP

```bash
# Installation des dépendances Composer
composer install

# Installation optimisée pour production
composer install --optimize-autoloader --no-dev

# Vérification des dépendances
composer validate
composer show --tree
```

### 2.3 Installation des dépendances frontend

```bash
# Installation des packages npm
npm install

# Installation pour production
npm ci --production

# Vérification des vulnérabilités
npm audit
npm audit fix
```

### 2.4 Permissions des fichiers

```bash
# Linux/macOS
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/

# Windows (si nécessaire)
icacls storage/ /grant "IIS_IUSRS":(OI)(CI)(F)
icacls bootstrap/cache/ /grant "IIS_IUSRS":(OI)(CI)(F)
```

---

## 3. Configuration de l'Environnement

### 3.1 Fichier d'environnement

```bash
# Copie du fichier d'exemple
cp .env.example .env

# Génération de la clé d'application
php artisan key:generate

# Édition du fichier .env
nano .env  # ou votre éditeur préféré
```

### 3.2 Configuration minimale

```bash
# .env pour développement local
APP_NAME="TELC Management"
APP_ENV=local
APP_KEY=base64:generated_key_here
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=telc_dev
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

### 3.3 Configuration de la base de données

#### MySQL
```bash
# Création de la base de données
mysql -u root -p -e "CREATE DATABASE telc_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Attribution des permissions
mysql -u root -p -e "GRANT ALL PRIVILEGES ON telc_dev.* TO 'votre_user'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"
```

#### MariaDB
```bash
# Création de la base de données
mysql -u root -p -e "CREATE DATABASE telc_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Configuration de l'utilisateur
mysql -u root -p -e "CREATE USER 'votre_user'@'localhost' IDENTIFIED BY 'mot_de_passe_securise';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON telc_dev.* TO 'votre_user'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"
```

---

## 4. Base de Données

### 4.1 Exécution des migrations

```bash
# Exécution de toutes les migrations
php artisan migrate

# Migration avec force (attention en production)
php artisan migrate --force

# Migration avec rollback possible
php artisan migrate --step

# Vérification du statut des migrations
php artisan migrate:status
```

### 4.2 Peuplement des données

```bash
# Exécution de tous les seeders
php artisan db:seed

# Seeder spécifique
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=CenterSeeder

# Rafraîchissement de la base de données
php artisan migrate:fresh --seed
```

### 4.3 Création d'un utilisateur administrateur

```bash
# Via tinker
php artisan tinker
>>> User::create([
...     'name' => 'Administrateur',
...     'email' => 'admin@telc.com',
...     'password' => Hash::make('password'),
...     'role' => 'super_admin',
... ]);
>>> exit
```

---

## 5. Finalisation

### 5.1 Lien des répertoires de stockage

```bash
# Création des liens symboliques
php artisan storage:link

# Vérification des liens
ls -la public/
```

### 5.2 Optimisation pour la production

```bash
# Cache de la configuration
php artisan config:cache

# Cache des routes
php artisan route:cache

# Cache des templates
php artisan view:cache

# Optimisation de l'autoloader
composer dump-autoload --optimize

# Optimisation des classes
php artisan optimize
```

### 5.3 Compilation des assets

```bash
# Environnement de développement
npm run dev

# Environnement de production
npm run production

# Surveillance des changements
npm run watch
```

---

## 6. Dépannage

### 6.1 Problèmes courants

#### Erreur de permissions
```bash
# Vérification des permissions
ls -la storage/
ls -la bootstrap/cache/

# Correction des permissions
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

#### Erreur de base de données
```bash
# Vérification de la connexion
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit

# Test de connexion manuel
mysql -u utilisateur -p base_de_donnees

# Réinitialisation de la base
php artisan migrate:fresh
```

#### Erreur de cache
```bash
# Nettoyage complet du cache
php artisan cache:clear

# Nettoyage spécifique
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 6.2 Logs utiles

#### Emplacement des logs
```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Logs PHP (Apache)
tail -f /var/log/apache2/error.log

# Logs PHP (Nginx)
tail -f /var/log/nginx/error.log

# Logs MySQL
tail -f /var/log/mysql/error.log
```

#### Niveau de verbosité
```bash
# Augmentation du niveau de logs
php artisan --verbose migrate

# Mode debug
APP_DEBUG=true php artisan serve
```

### 6.3 Outils de diagnostic

```bash
# Informations système
php artisan about

# Vérification de l'environnement
php artisan env

# Liste des routes
php artisan route:list

# Liste des événements
php artisan event:list

# Performance des requêtes
php artisan db:show
```

---

## ✅ Vérification d'installation

### Checklist post-installation

- [ ] **Permissions** : Les répertoires storage/ et bootstrap/cache/ sont accessibles en écriture
- [ ] **Base de données** : Les migrations s'exécutent sans erreur
- [ ] **Clé APP_KEY** : Générée et configurée dans .env
- [ ] **Liens storage** : Les liens symboliques sont corrects
- [ ] **Assets compilés** : Les fichiers CSS/JS sont générés
- [ ] **Cache activé** : Configuration, routes et vues en cache
- [ ] **Accès web** : L'application est accessible via le navigateur

### Test de l'installation

```bash
# Démarrage du serveur de développement
php artisan serve --host=0.0.0.0 --port=8000

# Accès à l'application
# Ouvrir : http://localhost:8000
# Login : admin@telc.com / password
```

---

## 🚀 Prochaines étapes

Après une installation réussie :

1. **Configurer les centres** d'examen
2. **Créer les utilisateurs** avec les rôles appropriés
3. **Planifier les sessions** TELC
4. **Importer les étudiants** existants si nécessaire
5. **Configurer les exports** et rapports
6. **Mettre en place** les sauvegardes automatiques

---

## 📞 Support

Pour toute question ou problème lors de l'installation :

- 📧 **Email technique** : support@telc.com
- 📚 **Documentation** : [Documentation complète](TECHNICAL_DOCUMENTATION.md)
- 🐛 **Rapports de bugs** : [Issues GitHub](https://github.com/votre-org/telc-app/issues)
- 💬 **Communauté** : [Discussions GitHub](https://github.com/votre-org/telc-app/discussions)

---

*Guide d'installation créé le 11 Mars 2026*  
*Version : 1.0.0*
