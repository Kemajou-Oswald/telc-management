# Production Ready

## Statut

- Le dépôt ne contient plus de comptes administrateur en clair.
- Le seeding de super admin passe désormais par des variables d'environnement.
- Les scripts de debug/réparation manuels ont été retirés du dépôt applicatif.

## Variables à définir avant seed

```env
SEED_SUPER_ADMIN_NAME="Super Admin"
SEED_SUPER_ADMIN_EMAIL="admin@example.com"
SEED_SUPER_ADMIN_PASSWORD="ChangeMeBeforeProd123!"
SEED_SUPER_ADMIN_PHONE=""
```

Variables optionnelles pour un second compte :

```env
SEED_SUPER_ADMIN_2_NAME=""
SEED_SUPER_ADMIN_2_EMAIL=""
SEED_SUPER_ADMIN_2_PASSWORD=""
SEED_SUPER_ADMIN_2_PHONE=""
```

## Comptes de démonstration

- Désactivés par défaut.
- Activez `SEED_DEMO_USERS=true` seulement pour une démo ou un environnement de recette.
- Changez immédiatement `SEED_DEMO_USERS_PASSWORD` si vous l'utilisez.

## Vérifications recommandées

- `APP_ENV=production`
- `APP_DEBUG=false`
- HTTPS activé
- rotation des mots de passe existants si des identifiants historiques ont déjà été utilisés
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`
