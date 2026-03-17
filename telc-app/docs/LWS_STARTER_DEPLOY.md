# Deploiement sur LWS Starter (mutualise) - Laravel 8 / MySQL

Objectif: deployer en production sans supprimer les donnees existantes, avec un maximum de securite et de stabilite sur un mutualise.

## 0) Regles d'or (anti-perte de donnees)

- Ne jamais executer: `php artisan migrate:fresh`, `php artisan db:wipe`, `php artisan schema:drop`, `php artisan optimize:clear` n'efface pas la base mais vide des caches (ok).
- Toujours faire un backup avant `php artisan migrate --force`.

## 1) Pre-requis LWS

- PHP: 7.4+ (8.1/8.2 ideal).
- Extensions conseillees: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `zip`.
- MySQL/MariaDB avec InnoDB.
- HTTPS actif (certificat SSL LWS).

## 2) .env production (sans fuite)

- Base-toi sur `.env.production.example`.
- Variables importantes:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_URL=https://ton-domaine.tld`
  - `APP_KEY=` (a generer)
  - `SESSION_DRIVER=database` (recommande multi-utilisateur) ou `file` si tu n'as pas le droit de migrer
  - `QUEUE_CONNECTION=sync` (safe mutualise). Tu pourras passer a `database` si tu ajoutes un cron/worker.
  - `SESSION_SECURE_COOKIE=true` (si HTTPS)
  - `LOG_LEVEL=warning`

Generation de la cle (si tu as un terminal/SSH):

```bash
php artisan key:generate
```

## 3) Positionner le document root sur `public/` (recommande)

Option A (recommandee): configurer le repertoire racine du site sur `.../telc-app/public`.

Option B (si LWS impose un webroot fixe):

- Mettre le projet dans un sous-dossier (ex: `telc-app/`)
- Copier le contenu de `public/` vers le webroot (ex: `www/`)
- Adapter `index.php` (chemins vers `../telc-app/vendor/autoload.php` et `../telc-app/bootstrap/app.php`)

## 4) Upload du code

Si tu n'as pas SSH/Composer sur le serveur:

- Sur ta machine: `composer install --no-dev --optimize-autoloader`
- Upload: projet + `vendor/` deja genere.

Si tu as SSH:

```bash
composer install --no-dev --optimize-autoloader
```

## 5) Droits fichiers (mutualise)

- Dossiers a rendre ecrivable par PHP: `storage/` et `bootstrap/cache/`.
- Recommandation:
  - Dossiers: `775`
  - Fichiers: `664`
- Eviter `777` si possible.

## 6) Migrations (sans suppression)

Les migrations ajoutent des colonnes/index/tables, elles ne suppriment pas les donnees.

```bash
php artisan migrate --force
```

Notes:
- Les tables `sessions` et `jobs` sont maintenant incluses via migrations (utile si `SESSION_DRIVER=database`).

## 7) Storage symlink

```bash
php artisan storage:link
```

Si `storage:link` est bloque sur mutualise, alternative:
- creer manuellement un dossier public qui pointe vers `storage/app/public` (selon l'outil LWS), ou
- servir les fichiers via un controller (plus lent).

## 8) Caches production (a faire apres upload + migrate)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 9) Cron (optionnel mais conseille)

Scheduler:

```bash
php artisan schedule:run
```

Sur LWS, configure un cron chaque minute qui appelle la commande ci-dessus.

Queues:
- Par defaut, `QUEUE_CONNECTION=sync` (pas de worker requis).
- Si tu passes `QUEUE_CONNECTION=database`, lance un worker via cron en boucle courte (selon limites LWS).

## 10) Limites mutualise (anti-crash)

- Exports: `EXPORT_MAX_ROWS` limite le nombre de lignes exportees (evite timeout/memoire).
- WhatsApp/Twilio: definir des timeouts cote PHP/HTTP si possible.

