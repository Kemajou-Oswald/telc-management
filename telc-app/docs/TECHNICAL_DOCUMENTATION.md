# Documentation Technique - Application TELC

## Table des matières

1. [Introduction et Vue d'Ensemble](#1-introduction-et-vue-densemble)
2. [Architecture Technique](#2-architecture-technique)
3. [Modèles de Données](#3-modèles-de-données)
4. [API et Endpoints](#4-api-et-endpoints)
5. [Sécurité](#5-sécurité)
6. [Performance et Optimisation](#6-performance-et-optimisation)
7. [Déploiement et Infrastructure](#7-déploiement-et-infrastructure)
8. [Guide d'Installation](#8-guide-dinstallation)
9. [Maintenance et Monitoring](#9-maintenance-et-monitoring)
10. [Annexes Techniques](#10-annexes-techniques)

---

## 1. Introduction et Vue d'Ensemble

### 1.1 Présentation du projet

L'application TELC est une plateforme de gestion complète pour les centres d'examen TELC (The European Language Certificates). Développée avec le framework Laravel 8+, elle permet la gestion des inscriptions, des sessions d'examen, des paiements et des reçus.

### 1.2 Objectifs fonctionnels

- **Gestion des centres** : Administration multi-centres avec permissions granulaires
- **Gestion des étudiants** : Création et modification des dossiers étudiants
- **Gestion des sessions** : Planification et organisation des sessions d'examen
- **Gestion des inscriptions** : Processus complet d'inscription avec validation
- **Gestion des paiements** : Suivi des statuts de paiement et génération de reçus
- **Exports et rapports** : Génération de rapports détaillés et exports de données
- **Audit et traçabilité** : Journalisation complète de toutes les actions

### 1.3 Technologies utilisées

| Technologie | Version | Utilisation |
|-------------|----------|-------------|
| **PHP** | ^7.3|^8.0 | Langage principal |
| **Laravel Framework** | ^8.75 | Framework MVC |
| **MySQL** | 5.7+ | Base de données |
| **Composer** | 2.0+ | Gestion des dépendances |
| **Node.js/NPM** | 16+ | Build frontend |
| **Laravel Mix** | 6.0+ | Compilation assets |
| **Bootstrap** | 5.0+ | Framework CSS |
| **Font Awesome** | 6.0+ | Icônes |

### 1.4 Architecture globale

```
┌─────────────────────────────────────────────────────────────┐
│                    APPLICATION TELC                    │
├─────────────────────────────────────────────────────────────┤
│  Frontend (Blade Templates + Bootstrap)              │
│  - Interface responsive et moderne                     │
│  - Génération PDF via impression navigateur         │
│  - Dashboard par rôle personnalisé                     │
├─────────────────────────────────────────────────────────────┤
│  Backend (Laravel Framework)                         │
│  - Contrôleurs RESTful                              │
│  - Middleware d'authentification                  │
│  - Validation et sécurité                           │
│  - Eloquent ORM                                   │
├─────────────────────────────────────────────────────────────┤
│  Base de Données (MySQL)                            │
│  - 9 tables principales                              │
│  - Relations et contraintes d'intégrité           │
│  - Index optimisés                                  │
├─────────────────────────────────────────────────────────────┤
│  Infrastructure                                     │
│  - Cache Laravel (config, routes, vues)           │
│  - Filesystem storage                                │
│  - Logs d'audit                                   │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. Architecture Technique

### 2.1 Pattern MVC

L'application suit le pattern Modèle-Vue-Contrôleur (MVC) :

#### **Modèles (app/Models/)**
- **User** : Gestion des utilisateurs et rôles
- **Student** : Informations des étudiants
- **Center** : Gestion des centres d'examen
- **ExamSession** : Sessions d'examen TELC
- **Registration** : Inscriptions des étudiants
- **BankDeposit** : Gestion des dépôts bancaires
- **Models d'audit** : LoginAudit, UserAudit, RegistrationAudit, BankDepositAudit

#### **Contrôleurs (app/Http/Controllers/)**
- **Controller** : Classe de base avec méthodes communes
- **AuthController** : Authentification et déconnexion
- **DashboardController** : Tableau de bord par rôle
- **RegistrationController** : Gestion complète des inscriptions
- **StudentController** : Gestion des étudiants
- **CenterController** : Administration des centres
- **ExamSessionController** : Gestion des sessions
- **BankDepositController** : Gestion des dépôts
- **UserManagementController** : Administration des utilisateurs
- **AuditLogController** : Visualisation des logs d'audit

#### **Vues (resources/views/)**
- **layouts/app.blade.php** : Template principal
- **dashboard.blade.php** : Tableau de bord
- Vues modulaires par entité (registrations/, students/, etc.)

### 2.2 Architecture de sécurité

#### **Système de rôles**
```php
class User {
    const ROLE_SUPER_ADMIN = 'super_admin';    // Accès total
    const ROLE_CENTER_ADMIN = 'center_admin';  // Administration d'un centre
    const ROLE_SECRETARY = 'secretary';       // Opérations d'un centre
}
```

#### **Middleware d'autorisation**
- **Auth::class** : Protection des routes
- **Permissions par centre** : Contrôle d'accès multi-centres
- **Validation CSRF** : Protection des formulaires

### 2.3 Architecture de la base de données

#### **Relations principales**
```
User (1) → (N) Center          (Multi-centres)
User (1) → (N) Registration      (Inscriptions créées)
Center (1) → (N) Student        (Étudiants du centre)
Center (1) → (N) ExamSession    (Sessions du centre)
Student (1) → (N) Registration   (Inscriptions étudiant)
ExamSession (1) → (N) Registration (Inscriptions session)
Registration (1) → (1) BankDeposit (Dépôt associé)
```

---

## 3. Modèles de Données

### 3.1 User

```php
class User extends Authenticatable {
    // Rôles
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_CENTER_ADMIN = 'center_admin';
    const ROLE_SECRETARY = 'secretary';
    
    // Relations
    function center() { return $this->belongsTo(Center::class); }
    function centers() { return $this->belongsToMany(Center::class); }
    function registrations() { return $this->hasMany(Registration::class, 'registered_by'); }
    
    // Méthodes de permission
    function isSuperAdmin() { return $this->role === self::ROLE_SUPER_ADMIN; }
    function isCenterAdmin() { return $this->role === self::ROLE_CENTER_ADMIN; }
    function isSecretary() { return $this->role === self::ROLE_SECRETARY; }
}
```

### 3.2 Student

```php
class Student extends Model {
    // Constantes
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';
    const LANGUAGE_FRENCH = 'French';
    const LANGUAGE_ENGLISH = 'English';
    
    // Normalisation
    static function normalizeGender($value) { /* Normalisation intelligente */ }
    static function normalizeLanguage($value) { /* Normalisation intelligente */ }
    
    // Relations
    function center() { return $this->belongsTo(Center::class); }
    function registrations() { return $this->hasMany(Registration::class); }
}
```

### 3.3 Registration

```php
class Registration extends Model {
    // Constantes
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';
    
    // Relations
    function student() { return $this->belongsTo(Student::class); }
    function center() { return $this->belongsTo(Center::class); }
    function examSession() { return $this->belongsTo(ExamSession::class); }
    function registrar() { return $this->belongsTo(User::class, 'registered_by'); }
    function audits() { return $this->hasMany(RegistrationAudit::class); }
}
```

### 3.4 ExamSession

```php
class ExamSession extends Model {
    // Constantes
    const STATUS_DRAFT = 'draft';
    const STATUS_OPEN = 'open';
    const STATUS_CLOSED = 'closed';
    const LEVELS = ['B1', 'B2', 'C1'];
    
    // Relations
    function center() { return $this->belongsTo(Center::class); }
    function registrations() { return $this->hasMany(Registration::class); }
    
    // Méthodes utilitaires
    function isOpen() { return $this->status === self::STATUS_OPEN; }
}
```

---

## 4. API et Endpoints

### 4.1 Routes d'authentification

```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
```

### 4.2 Routes principales (protégées)

#### **Dashboard**
```php
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
```

#### **Centres**
```php
Route::get('/centers', [CenterController::class, 'index'])->name('centers.index');
Route::get('/centers/create', [CenterController::class, 'create'])->name('centers.create');
Route::post('/centers', [CenterController::class, 'store'])->name('centers.store');
```

#### **Inscriptions**
```php
Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
Route::get('/registrations/create', [RegistrationController::class, 'create'])->name('registrations.create');
Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
Route::get('/registrations/{registration}/edit', [RegistrationController::class, 'edit'])->name('registrations.edit');
Route::post('/registrations/{registration}', [RegistrationController::class, 'update'])->name('registrations.update');
Route::post('/registrations/{registration}/pay', [RegistrationController::class, 'pay'])->name('registrations.pay');
Route::get('/registrations/{registration}/receipt', [RegistrationController::class, 'receipt'])->name('registrations.receipt');
```

### 4.3 Réponses API standards

#### **Format de réponse réussie**
```json
{
    "success": true,
    "message": "Opération effectuée avec succès",
    "data": { /* Données spécifiques */ }
}
```

#### **Format de réponse d'erreur**
```json
{
    "success": false,
    "message": "Description de l'erreur",
    "errors": { /* Details des erreurs de validation */ }
}
```

---

## 5. Sécurité

### 5.1 Authentification et Autorisation

#### **Middleware d'authentification**
- **Session sécurisée** : Configuration Laravel standard
- **Protection CSRF** : Token sur tous les formulaires
- **Timeout de session** : 120 minutes configurable

#### **Système de permissions**
```php
// Contrôleur de base
protected function authorizeRegistration(Registration $registration) {
    if ($this->isCenterScoped() && ! in_array((int) $registration->center_id, $this->accessibleCenterIds(), true)) {
        abort(403);
    }
}

// Vérifications par rôle
protected function onlySuperAdmin() {
    abort_unless($this->isSuperAdmin(), 403);
}

protected function onlyCenterOperations() {
    abort_unless($this->isCenterAdmin() || $this->isSecretary(), 403);
}
```

### 5.2 Validation des entrées

#### **Validation côté serveur**
```php
// Exemple : création d'inscription
$data = $request->validate([
    'first_name' => ['required', 'string', 'max:255'],
    'email' => ['nullable', 'email', 'max:255'],
    'exam_session_id' => ['required', 'exists:exam_sessions,id'],
    'country' => ['required', 'string', 'max:255'],
]);
```

#### **Protection contre les injections**
- **Eloquent ORM** : Protection automatique SQL injection
- **Escaping automatique** : Templates Blade sécurisés
- **Validation stricte** : Types et formats contrôlés

### 5.3 Audit et traçabilité

#### **Journalisation complète**
```php
// Méthode d'audit
protected function logAudit(Registration $registration, $action, $details) {
    $registration->audits()->create([
        'user_id' => optional($this->authUser())->id,
        'action' => $action,
        'details' => $details,
        'created_at' => now(),
    ]);
}
```

#### **Types d'actions auditées**
- **created** : Création d'inscription
- **updated** : Modification d'inscription
- **paid** : Marquage comme payé
- **cancelled** : Annulation d'inscription
- **login** : Connexion utilisateur
- **user_updated** : Modification utilisateur

---

## 6. Performance et Optimisation

### 6.1 Optimisations de base de données

#### **Index stratégiques**
```sql
-- Index composites pour les performances
UNIQUE KEY `registrations_student_session_unique` (`student_id`, `exam_session_id`);
INDEX `registrations_center_status` (`center_id`, `status`);
INDEX `registrations_payment_registered` (`payment_status`, `registered_at`);
INDEX `exam_sessions_center_status` (`center_id`, `status`);
```

#### **Requêtes optimisées**
```php
// Eager loading pour éviter N+1
$registrations = Registration::with(['student', 'center', 'examSession.center', 'registrar'])
    ->latest('registered_at')
    ->paginate(15);

// Requêtes filtrées optimisées
$query = $this->filteredRegistrationsQuery($request)
    ->latest('registrations.registered_at');
```

### 6.2 Cache et optimisations applicatives

#### **Configuration du cache**
```bash
# Cache Laravel activé
php artisan config:cache    # Configuration en cache
php artisan route:cache     # Routes en cache
php artisan view:cache      # Templates compilés
```

#### **Stratégies de cache**
- **Configuration** : Variables d'environnement
- **Routes** : Mapping des URLs
- **Vues** : Templates Blade compilés
- **Autoloader** : Optimisation Composer

### 6.3 Optimisations frontend

#### **Assets optimisés**
```javascript
// Compilation avec Laravel Mix
mix.js('resources/js/app.js', 'public/js')
    .options({
        processCssUrls: false,
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true,
                },
            },
        },
    });
```

#### **Performance CSS**
- **CSS minifié** : Compilation automatique
- **Images optimisées** : Compression et formats modernes
- **Fonts préchargées** : Google Fonts avec preconnect

---

## 7. Déploiement et Infrastructure

### 7.1 Prérequis système

#### **Configuration minimale requise**
- **PHP** : 7.3+ avec extensions requises
- **MySQL** : 5.7+ ou MariaDB 10.2+
- **Web Server** : Apache 2.4+ ou Nginx 1.18+
- **Node.js** : 16+ pour le build
- **Memory** : 512MB+ recommandée
- **Disk Space** : 1GB+ minimum

#### **Extensions PHP requises**
```ini
; Extensions PHP obligatoires
extension=pdo_mysql
extension=mbstring
extension=openssl
extension=tokenizer
extension=xml
extension=json
extension=fileinfo
extension=ctype
extension=bcmath
```

### 7.2 Configuration de production

#### **Variables d'environnement**
```bash
# .env de production
APP_NAME="TELC Management"
APP_ENV=production
APP_KEY=base64:generated_key_here
APP_DEBUG=false
APP_URL=https://telc.votre-domaine.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=telc_production
DB_USERNAME=telc_user
DB_PASSWORD=secure_password_here

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=redis_secure_password
REDIS_PORT=6379
```

#### **Configuration serveur web**
```apache
# Apache .htaccess recommandé
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Headers de sécurité
Header always set X-Frame-Options DENY
Header always set X-Content-Type-Options nosniff
Header always set X-XSS-Protection "1; mode=block"
```

### 7.3 Processus de déploiement

#### **Étape 1 : Préparation**
```bash
# 1. Cloner le repository
git clone https://github.com/votre-org/telc-app.git
cd telc-app

# 2. Installer les dépendances
composer install --optimize-autoloader --no-dev

# 3. Configurer l'environnement
cp .env.example .env
# Éditer .env avec les valeurs de production
php artisan key:generate
```

#### **Étape 2 : Base de données**
```bash
# 1. Créer la base de données
mysql -u root -p -e "CREATE DATABASE telc_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Exécuter les migrations
php artisan migrate --force

# 3. Peupler les données initiales (optionnel)
php artisan db:seed --class=ProductionSeeder
```

#### **Étape 3 : Configuration applicative**
```bash
# 1. Lier les storage
php artisan storage:link

# 2. Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Compiler les assets
npm run production
```

#### **Étape 4 : Permissions**
```bash
# Permissions Linux/Unix
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

---

## 8. Guide d'Installation

### 8.1 Installation locale (Développement)

#### **Prérequis**
- PHP 7.3+ avec Composer
- MySQL 5.7+ ou MariaDB 10.2+
- Node.js 16+ et NPM
- Git

#### **Installation étape par étape**
```bash
# 1. Cloner le projet
git clone https://github.com/votre-org/telc-app.git
cd telc-app

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances frontend
npm install

# 4. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 5. Configurer la base de données
# Éditer .env avec les informations de la base locale

# 6. Exécuter les migrations
php artisan migrate

# 7. Peupler les données de test (optionnel)
php artisan db:seed

# 8. Démarrer le serveur de développement
php artisan serve
```

#### **Accès local**
- **Application** : http://localhost:8000
- **Login par défaut** : admin@telc.com / password
- **Documentation** : http://localhost:8000/docs

### 8.2 Installation Docker

#### **Dockerfile recommandé**
```dockerfile
FROM php:8.1-fpm

# Installation des dépendances
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration de l'environnement
WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --optimize-autoloader --no-dev

# Permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage
```

#### **docker-compose.yml**
```yaml
version: '3.8'
services:
  app:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    environment:
      - APP_ENV=local
      - DB_HOST=mysql
      - DB_DATABASE=telc
      - DB_USERNAME=root
      - DB_PASSWORD=secret
    depends_on:
      - mysql
      - redis

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: telc
      MYSQL_USER: root
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: secret
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3306:3306"

  redis:
    image: redis:6-alpine
    ports:
      - "6379:6379"

volumes:
  mysql_data:
```

---

## 9. Maintenance et Monitoring

### 9.1 Logs et monitoring

#### **Types de logs**
```bash
# Logs Laravel
storage/logs/laravel.log          # Logs applicatifs
storage/logs/audit.log           # Logs d'audit personnalisés

# Logs serveur web
/var/log/nginx/error.log           # Erreurs Nginx
/var/log/apache2/error.log        # Erreurs Apache
/var/log/mysql/mysql-slow.log    # Requêtes lentes MySQL
```

#### **Configuration des logs**
```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'audit'],
    ],
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'replace_placeholders' => true,
    ],
    'audit' => [
        'driver' => 'single',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
    ],
],
```

### 9.2 T�ches de maintenance

#### **Commandes artisan personnalisées**
```bash
# Nettoyage des logs anciens
php artisan logs:clear --days=30

# Optimisation de la base de données
php artisan db:optimize

# Backup de la base de données
php artisan db:backup

# Vérification de l'intégrité
php artisan system:check
```

#### **T�ches planifi�es (Cron)**
```bash
# Crontab recommandé
0 2 * * * cd /var/www/telc-app && php artisan schedule:run >> /dev/null 2>&1

# T�ches planifi�es dans app/Console/Kernel.php
protected function schedule(Schedule $schedule) {
    $schedule->command('logs:clear --days=30')->daily();
    $schedule->command('db:backup')->daily();
    $schedule->command('system:check')->hourly();
}
```

### 9.3 Monitoring de performance

#### **Métriques à surveiller**
- **Temps de réponse** : < 500ms pour 95% des requêtes
- **Taux d'erreur** : < 1% des requêtes
- **Utilisation mémoire** : < 80% de la mémoire disponible
- **Utilisation CPU** : < 70% en moyenne
- **Espace disque** : < 85% d'utilisation

#### **Outils de monitoring recommandés**
- **Laravel Telescope** : Debug et monitoring en développement
- **New Relic** : Monitoring APM en production
- **Grafana + Prometheus** : Métriques personnalisées
- **Sentry** : Tracking d'erreurs en temps réel

---

## 10. Annexes Techniques

### 10.1 Schéma de la base de données

#### **Diagramme des relations**
```
┌─────────────┐    ┌─────────────┐    ┌─────────────────┐
│    User    │    │   Center    │    │  ExamSession   │
├─────────────┤    ├─────────────┤    ├─────────────────┤
│ id         │    │ id          │    │ id             │
│ name       │    │ name        │    │ title          │
│ email      │    │ city        │    │ level          │
│ role       │    │ country     │    │ city           │
│ center_id  │◄───┤ is_active   │◄───┤ center_id      │
│ ...       │    │ ...         │    │ exam_date      │
└─────────────┘    └─────────────┘    │ status         │
                                      │ price          │
                                      │ ...           │
                                      └─────────────────┘
                                            ▲
                                            │
                       ┌─────────────────┐    │
                       │  Registration  │◄───┤
                       ├─────────────────┤    │
                       │ id             │    │
                       │ student_id     │    │
                       │ center_id      │    │
                       │ exam_session_id│    │
                       │ amount         │    │
                       │ payment_status │    │
                       │ status         │    │
                       │ receipt_number │    │
                       │ registered_at  │    │
                       │ ...           │    │
                       └─────────────────┘    │
                                            ▲
                                            │
                       ┌─────────────┐    │
                       │   Student    │◄───┤
                       ├─────────────┤    │
                       │ id           │    │
                       │ first_name   │    │
                       │ last_name    │    │
                       │ email        │    │
                       │ center_id    │    │
                       │ ...         │    │
                       └─────────────┘    │
                                            │
                       ┌─────────────┐    │
                       │ BankDeposit  │◄───┤
                       ├─────────────┤    │
                       │ id           │    │
                       │ amount       │    │
                       │ status       │    │
                       │ ...         │    │
                       └─────────────┘    │
                                            └──
```

### 10.2 Configuration des environnements

#### **Développement (.env.local)**
```bash
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=telc_dev
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
```

#### **Staging (.env.staging)**
```bash
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.telc.com

DB_CONNECTION=mysql
DB_HOST=staging-db.example.com
DB_DATABASE=telc_staging
DB_USERNAME=telc_staging
DB_PASSWORD=staging_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

#### **Production (.env.production)**
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://telc.com

DB_CONNECTION=mysql
DB_HOST=prod-db.example.com
DB_DATABASE=telc_production
DB_USERNAME=telc_prod
DB_PASSWORD=secure_production_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 10.3 Scripts de déploiement automatisé

#### **deploy.sh**
```bash
#!/bin/bash

# Script de déploiement TELC
set -e

echo "🚀 Déploiement de l'application TELC..."

# Variables
REPO_URL="https://github.com/votre-org/telc-app.git"
DEPLOY_DIR="/var/www/telc-app"
BACKUP_DIR="/var/backups/telc"

# 1. Backup de la version actuelle
if [ -d "$DEPLOY_DIR" ]; then
    echo "📦 Backup de la version actuelle..."
    mkdir -p "$BACKUP_DIR"
    tar -czf "$BACKUP_DIR/backup-$(date +%Y%m%d-%H%M%S).tar.gz" -C "$DEPLOY_DIR" .
fi

# 2. Téléchargement du code
echo "📥 Téléchargement du code..."
git clone "$REPO_URL" "$DEPLOY_DIR-temp"
cd "$DEPLOY_DIR-temp"

# 3. Installation des dépendances
echo "📦 Installation des dépendances..."
composer install --optimize-autoloader --no-dev
npm ci && npm run production

# 4. Configuration
echo "⚙️ Configuration..."
cp .env.production .env
php artisan key:generate --force

# 5. Base de données
echo "🗄️ Migration de la base de données..."
php artisan migrate --force

# 6. Optimisation
echo "⚡ Optimisation..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Déploiement atomique
echo "🔄 Déploiement atomique..."
rm -rf "$DEPLOY_DIR"
mv "$DEPLOY_DIR-temp" "$DEPLOY_DIR"

# 8. Permissions
echo "🔐 Configuration des permissions..."
chown -R www-data:www-data "$DEPLOY_DIR"
chmod -R 755 "$DEPLOY_DIR/storage"
chmod -R 755 "$DEPLOY_DIR/bootstrap/cache"

# 9. Nettoyage
echo "🧹 Nettoyage..."
php artisan cache:clear

echo "✅ Déploiement terminé avec succès!"
```

### 10.4 Checklist de déploiement

#### **Pré-déploiement**
- [ ] Backup de la base de données actuelle
- [ ] Backup des fichiers de configuration
- [ ] Vérification des prérequis système
- [ ] Test des migrations en environnement de staging
- [ ] Validation des variables d'environnement
- [ ] Vérification des permissions des fichiers

#### **Post-déploiement**
- [ ] Vérification de l'accessibilité de l'application
- [ ] Test des fonctionnalités critiques
- [ ] Validation des logs d'erreurs
- [ ] Monitoring des performances
- [ ] Test des rôles et permissions
- [ ] Vérification des exports et rapports
- [ ] Test de génération des reçus PDF

---

## Conclusion

Cette documentation technique couvre l'ensemble de l'application TELC, de son architecture à son déploiement. Elle est destinée aux développeurs, administrateurs système et équipes de maintenance.

Pour toute question technique ou demande de support, référez-vous aux :
- **Code source** : Repository Git de l'application
- **Logs** : Fichiers de logs dans `/storage/logs/`
- **Monitoring** : Outils de monitoring configurés
- **Documentation API** : Endpoints et formats de réponse

---

*Document généré le 11 Mars 2026*
*Version : 1.0*
*Auteur : Équipe de développement TELC*
