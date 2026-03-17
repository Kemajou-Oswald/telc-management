# Guide de Sécurité - TELC Management System

## 📋 Table des matières

1. [Vue d'ensemble de la sécurité](#1-vue-densemble-de-la-sécurité)
2. [Authentification et Autorisation](#2-authentification-et-autorisation)
3. [Protection des Données](#3-protection-des-données)
4. [Sécurité Réseau](#4-sécurité-réseau)
5. [Sécurité Applicative](#5-sécurité-applicative)
6. [Audit et Monitoring](#6-audit-et-monitoring)
7. [Bonnes Pratiques](#7-bonnes-pratiques)
8. [Plan de Réponse aux Incidents](#8-plan-de-réponse-aux-incidents)

---

## 1. Vue d'ensemble de la sécurité

TELC Management System implémente une approche de sécurité en profondeur ("defense in depth") avec plusieurs couches de protection :

### 1.1 Principes de sécurité

- **Principe du moindre privilège** : Les utilisateurs n'accédent qu'aux ressources nécessaires
- **Séparation des responsabilités** : Rôles et permissions clairement définis
- **Validation en profondeur** : Contrôle des entrées à tous les niveaux
- **Traçabilité complète** : Journalisation de toutes les actions sensibles
- **Sécurité par défaut** : Configuration sécurisée out-of-the-box

### 1.2 Modèle de menaces

| Type de menace | Probabilité | Impact | Mesures de protection |
|----------------|-------------|---------|----------------------|
| **Injection SQL** | Moyenne | Élevé | ORM Eloquent, validation stricte |
| **XSS (Cross-Site Scripting)** | Moyenne | Moyen | Templates Blade sécurisés, CSP |
| **CSRF (Cross-Site Request Forgery)** | Élevée | Moyenne | Tokens CSRF sur tous formulaires |
| **Brute force** | Élevée | Moyenne | Rate limiting, mots de passe forts |
| **Élévation de privilèges** | Faible | Élevé | Contrôle d'accès granulaire |
| **Fuite de données** | Faible | Élevé | Chiffrement, logs d'audit |

---

## 2. Authentification et Autorisation

### 2.1 Gestion des identifiants

#### Politique de mots de passe
```php
// Validation des mots de passe dans app/Http/Controllers/AuthController.php
'password' => [
    'required',
    'string',
    'min: 12',                    // Minimum 12 caractères
    'confirmed',                   // Confirmation obligatoire
    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', // Complexité requise
],
```

#### Configuration de la sécurité des sessions
```php
// config/session.php
'lifetime' => env('SESSION_LIFETIME', 120),  // 2 heures par défaut
'expire_on_close' => true,
'encrypt' => true,
'files' => true,
'connection' => null,
'store' => env('SESSION_DRIVER', 'redis'),  // Redis pour la production
```

#### Configuration du rate limiting
```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... autres middleware
        \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,5', // 5 tentatives par minute
    ],
];
```

### 2.2 Système de rôles et permissions

#### Hiérarchie des rôles
```php
// app/Models/User.php
class User extends Authenticatable {
    const ROLE_SUPER_ADMIN = 'super_admin';    // Accès total système
    const ROLE_CENTER_ADMIN = 'center_admin';  // Administration d'un centre
    const ROLE_SECRETARY = 'secretary';       // Opérations d'un centre
    
    // Méthodes de vérification
    public function isSuperAdmin() {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }
    
    public function isCenterAdmin() {
        return $this->role === self::ROLE_CENTER_ADMIN;
    }
    
    public function isSecretary() {
        return $this->role === self::ROLE_SECRETARY;
    }
}
```

#### Contrôle d'accès par centre
```php
// app/Http/Controllers/Controller.php
protected function authorizeRegistration(Registration $registration) {
    // Vérification que l'utilisateur peut accéder à ce centre
    if ($this->isCenterScoped() && ! in_array((int) $registration->center_id, $this->accessibleCenterIds(), true)) {
        abort(403, 'Accès non autorisé à ce centre');
    }
}

protected function accessibleCenterIds() {
    $user = $this->authUser();
    
    if ($user->isSuperAdmin()) {
        return []; // Accès à tous les centres
    }
    
    return $user->centers()->pluck('centers.id')->all();
}
```

### 2.3 Protection contre les attaques par force brute

#### Configuration de la sécurité
```bash
# Configuration PHP pour la sécurité
# /etc/php/8.1/fpm/php.ini

; Protection contre les attaques
max_execution_time = 30
max_input_time = 30
memory_limit = 256M

; Upload sécurisé
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20

; Exposition réduite
expose_php = Off
display_errors = Off
```

#### Configuration du pare-feu applicatif
```nginx
# Configuration Nginx pour la protection
server {
    # Limitation de la taille des requêtes
    client_max_body_size 10M;
    
    # Protection contre les attaques
    limit_req_zone $binary_remote_addr zone=one:10m rate=10r/s;
    limit_req zone=one burst=20 nodelay;
    
    # Protection des fichiers système
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }
    
    # Headers de sécurité
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
}
```

---

## 3. Protection des Données

### 3.1 Chiffrement des données sensibles

#### Chiffrement des mots de passe
```php
// Utilisation de Laravel Hash
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable {
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }
}
```

#### Configuration de la clé d'application
```bash
# Génération d'une clé forte
php artisan key:generate --show

# Résultat attendu (32 caractères aléatoires)
base64:ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz
```

### 3.2 Validation et nettoyage des entrées

#### Validation stricte des entrées
```php
// app/Http/Requests/StoreRegistrationRequest.php
class StoreRegistrationRequest extends FormRequest {
    public function rules() {
        return [
            'first_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\-\'��������������]+$/'  // Noms fran�ais valides
            ],
            'email' => [
                'required',
                'email:rfc,dns',  // Validation stricte avec vérification DNS
                'max:255',
                'unique:students,email'
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d{1,6}(\.\d{1,2})?$/'  // Format monétaire
            ],
        ];
    }
    
    public function sanitize() {
        return array_map(function ($value) {
            return is_string($value) ? trim(strip_tags($value)) : $value;
        }, $this->validated());
    }
}
```

#### Protection contre l'injection SQL
```php
// Utilisation systématique de l'ORM Eloquent
$registrations = Registration::with(['student', 'center', 'examSession'])
    ->where('payment_status', Registration::PAYMENT_PAID)
    ->orderBy('registered_at', 'desc')
    ->paginate(15);

// Jamais de requêtes SQL brutes comme :
// $results = DB::select("SELECT * FROM registrations WHERE email = '$email'");
```

### 3.3 Protection des fichiers

#### Validation des uploads
```php
// app/Http/Controllers/ProfileController.php
public function updateAvatar(Request $request) {
    $request->validate([
        'avatar' => [
            'required',
            'image',
            'mimes:jpeg,png,jpg,gif',
            'max:2048',  // 2MB maximum
            'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
        ],
    ]);
    
    // Traitement sécurisé de l'image
    if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')
            ->store('avatars', 'public');  // Stockage dans public/avatars
        
        // Redimensionnement et optimisation
        $this->optimizeImage($path);
    }
}
```

#### Configuration du stockage sécurisé
```php
// config/filesystems.php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
    'avatars' => [
        'driver' => 'local',
        'root' => storage_path('app/avatars'),
        'url' => env('APP_URL').'/avatars',
        'visibility' => 'private',
    ],
],
```

---

## 4. Sécurité Réseau

### 4.1 Configuration SSL/TLS

#### Configuration HTTPS obligatoire
```nginx
server {
    listen 443 ssl http2;
    server_name telc.votre-domaine.com;
    
    # Certificats SSL
    ssl_certificate /etc/ssl/certs/telc.crt;
    ssl_certificate_key /etc/ssl/private/telc.key;
    
    # Configuration TLS moderne
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers 'ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384';
    ssl_prefer_server_ciphers off;
    
    # HSTS pour la sécurité
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
}
```

#### Redirection automatique HTTP vers HTTPS
```nginx
server {
    listen 80;
    server_name telc.votre-domaine.com;
    
    # Redirection permanente vers HTTPS
    return 301 https://$server_name$request_uri;
}
```

### 4.2 Configuration du pare-feu

#### Règles UFW recommandées
```bash
# Configuration du pare-feu
sudo ufw enable

# Ports SSH (limité)
sudo ufw limit ssh

# Ports web
sudo ufw allow 80/tcp   # HTTP
sudo ufw allow 443/tcp  # HTTPS

# Ports de base de données (si accès distant nécessaire)
sudo ufw allow from 192.168.1.0/24 to any port 3306  # MySQL depuis réseau local uniquement

# Refus des autres connexions
sudo ufw default deny incoming
sudo ufw default allow outgoing
```

#### Configuration Fail2Ban pour la protection
```bash
# /etc/fail2ban/jail.local
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3

[sshd]
enabled = true
port = ssh
filter = sshd
logpath = /var/log/auth.log
maxretry = 3
bantime = 3600

[nginx-http-auth]
enabled = true
port = http,https
filter = nginx-http-auth
logpath = /var/log/nginx/error.log
maxretry = 5
bantime = 1800
```

---

## 5. Sécurité Applicative

### 5.1 Protection CSRF

#### Configuration automatique Laravel
```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... autres middleware
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ],
];

// Dans les templates Blade
<form method="POST" action="/registrations">
    @csrf
    <!-- Champs du formulaire -->
</form>
```

#### Protection pour les requêtes AJAX
```javascript
// resources/js/app.js
// Configuration globale pour les requêtes AJAX
window.Laravel = {
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

// Dans les appels fetch
fetch('/api/registrations', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.Laravel.csrfToken
    },
    body: JSON.stringify(data)
});
```

### 5.2 Politique de Sécurité de Contenu (CSP)

#### Configuration CSP stricte
```nginx
add_header Content-Security-Policy "
    default-src 'self';
    script-src 'self' 'unsafe-inline' 'unsafe-eval';
    style-src 'self' 'unsafe-inline';
    img-src 'self' data: https:;
    font-src 'self' https://fonts.gstatic.com;
    connect-src 'self' https://fonts.googleapis.com;
    frame-ancestors 'none';
    base-uri 'self';
    form-action 'self';
" always;
```

### 5.3 Protection contre les attaques d'injection

#### Échappement automatique dans Blade
```blade
{{-- Les variables sont automatiquement échappées --}}
<p>Bonjour, {{ $user->name }}</p>

{{-- Pour afficher du HTML non échappé (avec prudence) --}}
{!! $trustedHtml !!}

{{-- Filtrage des entrées utilisateur --}}
{{ $userInput | e }}
```

#### Validation des entrées API
```php
// app/Http/Controllers/APIController.php
class APIController extends Controller {
    public function store(Request $request) {
        // Validation stricte des entrées API
        $validated = $request->validate([
            'data' => 'required|string|max:1000',
            'type' => 'required|in:registration,payment,session',
        ]);
        
        // Nettoyage des entrées
        $cleanData = $this->sanitizeInput($validated);
        
        return response()->json(['success' => true, 'data' => $cleanData]);
    }
    
    private function sanitizeInput(array $data): array {
        return array_map(function ($value) {
            return is_string($value) ? htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8') : $value;
        }, $data);
    }
}
```

---

## 6. Audit et Monitoring

### 6.1 Système d'audit complet

#### Configuration des logs d'audit
```php
// app/Http/Controllers/Controller.php
protected function logAudit($model, $action, $details = null) {
    $model->audits()->create([
        'user_id' => optional($this->authUser())->id,
        'action' => $action,
        'details' => $details,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'created_at' => now(),
    ]);
}

// Utilisation dans les contrôleurs
public function update(Request $request, Registration $registration) {
    $this->logAudit($registration, 'updated', 'Modification de l\'inscription par ' . $this->authUser()->name);
    
    // Logique de mise à jour...
}
```

#### Configuration des logs structurés
```php
// config/logging.php
'channels' => [
    'audit' => [
        'driver' => 'daily',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
        'days' => 90,
        'replace_placeholders' => true,
    ],
    'security' => [
        'driver' => 'daily',
        'path' => storage_path('logs/security.log'),
        'level' => 'warning',
        'days' => 365,
    ],
],
```

### 6.2 Monitoring de la sécurité

#### Alertes de sécurité en temps réel
```php
// app/Providers/EventServiceProvider.php
protected $listen = [
    'Illuminate\Auth\Events\Failed' => [
        'App\Listeners\LogFailedLogin::class',
    ],
    'Illuminate\Auth\Events\Lockout' => [
        'App\Listeners\LogLockout::class',
    ],
    'App\Events\SecurityIncident' => [
        'App\Listeners\NotifySecurityIncident::class',
    ],
];

// app/Listeners/LogFailedLogin.php
class LogFailedLogin {
    public function handle(Failed $event) {
        Log::channel('security')->warning('Tentative de connexion échouée', [
            'email' => $event->credentials['email'],
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        // Vérification du rate limiting
        $this->checkBruteForce($event->credentials['email'], request()->ip());
    }
}
```

### 6.3 Surveillance des accès anormaux

#### Détection des comportements suspects
```php
// app/Http/Middleware/DetectAnomalousAccess.php
class DetectAnomalousAccess {
    public function handle($request, Closure $next) {
        $response = $next($request);
        
        // Détection d'accès depuis des IP suspectes
        if ($this->isSuspiciousIP(request()->ip())) {
            Log::channel('security')->warning('Accès depuis IP suspecte', [
                'ip' => request()->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => request()->userAgent(),
            ]);
        }
        
        // Détection d'accès à des ressources sensibles
        if ($this->isSensitiveAccess($request)) {
            Log::channel('security')->info('Accès à ressource sensible', [
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'resource' => $request->path(),
            ]);
        }
        
        return $response;
    }
}
```

---

## 7. Bonnes Pratiques

### 7.1 Sécurité du développement

#### Environnement de développement sécurisé
```bash
# .env.local (jamais commité)
APP_ENV=local
APP_DEBUG=true  # Uniquement en développement
DB_DATABASE=telc_dev  # Base de données séparée

# Utilisation de données de test uniquement
php artisan tinker
>>> factory(User::class)->create(['email' => 'test@example.com']);
```

#### Validation en continu
```yaml
# .github/workflows/security-scan.yml
name: Security Scan

on: [push, pull_request]

jobs:
  security:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    - name: Run security scan
      run: |
        composer audit
        npm audit
```

### 7.2 Gestion des secrets

#### Utilisation des secrets Git
```bash
# Stockage des secrets dans GitHub Secrets
# Variables d'environnement en production
DB_PASSWORD=${{ secrets.DB_PASSWORD }}
REDIS_PASSWORD=${{ secrets.REDIS_PASSWORD }}
APP_KEY=${{ secrets.APP_KEY }}
```

#### Rotation des clés
```bash
# Script de rotation mensuelle des clés
#!/bin/bash
# /usr/local/bin/rotate-keys.sh

# Génération d'une nouvelle clé
NEW_KEY=$(php artisan key:generate --show)

# Sauvegarde de l'ancienne clé
echo "$(date): $APP_KEY" >> /var/backups/app-keys.log

# Mise à jour de la clé
sed -i "s/APP_KEY=.*/APP_KEY=$NEW_KEY/" .env

# Redémarrage des services
systemctl reload php8.1-fpm
systemctl reload nginx
```

### 7.3 Mises à jour de sécurité

#### Processus de mise à jour
```bash
# Script de mise à jour automatique
#!/bin/bash
# /usr/local/bin/security-update.sh

echo "🔒 Mise à jour de sécurité..."

# Mise à jour des paquets système
apt update && apt upgrade -y

# Mise à jour des dépendances Composer
composer update --no-dev

# Vérification des vulnérabilités
composer audit

# Mise à jour des dépendances npm
npm update

# Vérification des vulnérabilités npm
npm audit fix

echo "✅ Mise à jour terminée"
```

---

## 8. Plan de Réponse aux Incidents

### 8.1 Classification des incidents

#### Niveaux de sévérité
| Niveau | Description | Temps de réponse | Escalade |
|---------|-------------|------------------|------------|
| **Critique** | Service indisponible, perte de données | 15 minutes | Immédiate |
| **Élevé** | Impact fonctionnel majeur | 1 heure | 2 heures |
| **Moyen** | Impact fonctionnel limité | 4 heures | 8 heures |
| **Faible** | Impact mineur, contournement possible | 24 heures | 48 heures |

### 8.2 Procédures d'urgence

#### Incident critique (ex: fuite de données)
```bash
# 1. Isolation immédiate
sudo systemctl stop nginx
sudo systemctl stop php8.1-fpm

# 2. Préservation des logs
tar -czf /var/backups/emergency-$(date +%Y%m%d_%H%M%S).tar.gz \
    /var/log/nginx/ \
    /var/log/php/ \
    /var/www/telc-app/storage/logs/

# 3. Notification
echo "INCIDENT CRITIQUE - Service arrêté" | mail -s "URGENT: Incident TELC" admin@telc.com

# 4. Investigation
# Analyse des logs et détection de la cause
```

#### Plan de communication
```bash
# Communication aux parties prenantes
# 1. Équipe technique (immédiat)
# 2. Management (15 minutes)
# 3. Utilisateurs (1 heure)
# 4. Public (si nécessaire)

# Canaux de communication
- Email : security@telc.com
- Slack : #security-alerts
- SMS : +33 1 234 567 890 (critique uniquement)
```

### 8.3 Récupération post-incident

#### Checklist de récupération
- [ ] **Cause identifiée** : Analyse complète des logs
- [ ] **Vulnérabilité corrigée** : Patch appliqué
- [ ] **Système sécurisé** : Renforcement des mesures
- [ ] **Tests de validation** : Vérification de la correction
- [ ] **Monitoring renforcé** : Alertes améliorées
- [ ] **Documentation mise à jour** : Rapport d'incident rédigé
- [ ] **Communication post-incident** : Information aux utilisateurs

#### Analyse post-mortem
```php
// Template de rapport d'incident
$incidentReport = [
    'incident_id' => 'INC-' . date('YmdHis'),
    'date' => now()->toDateTimeString(),
    'severity' => 'critical',
    'duration' => '2 heures 15 minutes',
    'impact' => 'Service indisponible, 0 utilisateurs affectés',
    'root_cause' => 'Vulnérabilité CVE-2024-1234 dans Laravel',
    'resolution' => 'Mise à jour vers Laravel 8.83',
    'prevention_measures' => [
        'Mise en place de scans de vulnérabilités automatiques',
        'Processus de mise à jour accéléré',
        'Monitoring renforcé des accès anormaux'
    ],
    'lessons_learned' => [
        'Importance des mises à jour régulières',
        'Nécessité de monitoring proactif',
        'Importance des tests de pénétration'
    ]
];
```

---

## 📞 Contact Sécurité

Pour toute question ou rapport de sécurité :

### 🚨 Signalement de vulnérabilités
- **Email** : security@telc.com
- **PGP Key** : Disponible sur demande
- **Response time** : 24 heures pour les rapports critiques
- **Programme de bug bounty** : rewards@telc.com

### 📚 Ressources de sécurité
- **OWASP Top 10** : [https://owasp.org/www-project-top-ten/](https://owasp.org/www-project-top-ten/)
- **CWE Mitre** : [https://cwe.mitre.org/](https://cwe.mitre.org/)
- **NIST Cybersecurity Framework** : [https://www.nist.gov/cyberframework](https://www.nist.gov/cyberframework/)
- **Laravel Security** : [https://laravel.com/docs/security](https://laravel.com/docs/security)

---

*Guide de sécurité créé le 11 Mars 2026*  
*Version : 1.0.0*  
*Prochaine révision : 11 Juin 2026*
