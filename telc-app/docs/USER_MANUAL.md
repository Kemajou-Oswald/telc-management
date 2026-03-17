# Manuel d'Utilisation - TELC Management System

## 📋 Table des matières

1. [Introduction](#1-introduction)
2. [Interface Générale](#2-interface-générale)
3. [Super Administrateur](#3-super-administrateur)
4. [Administrateur de Centre](#4-administrateur-de-centre)
5. [Secrétaire](#5-secrétaire)
6. [Guide des Bonnes Pratiques](#6-guide-des-bonnes-pratiques)
7. [Dépannage et FAQ](#7-dépannage-et-faq)

---

## 1. Introduction

### 1.1 Bienvenue dans TELC Management

TELC Management System est une plateforme intuitive conçue pour simplifier la gestion des inscriptions aux examens TELC. Ce manuel vous guidera à travers toutes les fonctionnalités selon votre rôle.

### 1.2 Accès à la plateforme

- **URL de connexion** : https://telc.votre-domaine.com
- **Identifiants fournis** par votre administrateur système
- **Navigateurs supportés** : Chrome 90+, Firefox 88+, Safari 14+, Edge 90+

### 1.3 Premiers pas

1. **Connectez-vous** avec vos identifiants
2. **Changez votre mot de passe** lors de la première connexion
3. **Familiarisez-vous** avec l'interface du tableau de bord
4. **Consultez ce manuel** pour découvrir les fonctionnalités de votre rôle

---

## 2. Interface Générale

### 2.1 Structure de l'interface

```
┌─────────────────────────────────────────────────────────────┐
│  [LOGO TELC]           [NOTIFICATIONS]  [PROFIL] │
├─────────────────────────────────────────────────────────────┤
│                                                     │
│  ┌─────────────┐    ┌─────────────────────────────┐   │
│  │             │    │                           │   │
│  │  SIDEBAR    │    │        MAIN CONTENT       │   │
│  │             │    │                           │   │
│  │ • Dashboard │    │  • Tableaux de bord       │   │
│  │ • Centres   │    │  • Listes et formulaires   │   │
│  │ • Sessions  │    │  • Rapports et exports     │   │
│  │ • Étudiants │    │                           │   │
│  │ • Inscriptions│    │                           │   │
│  │ • Dépôts    │    │                           │   │
│  │ • Utilisateurs│    │                           │   │
│  │ • Audit     │    │                           │   │
│  │             │    │                           │   │
│  └─────────────┘    └─────────────────────────────┘   │
│                                                     │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 Navigation principale

#### Barre de navigation supérieure
- **Logo TELC** : Retour au tableau de bord
- **Notifications** : Alertes et messages système
- **Profil utilisateur** : Gestion du compte personnel

#### Menu latéral
- **Tableau de bord** : Vue d'ensemble et statistiques
- **Centres** : Gestion des centres d'examen
- **Sessions** : Administration des sessions TELC
- **Étudiants** : Gestion des dossiers étudiants
- **Inscriptions** : Gestion des inscriptions et paiements
- **Dépôts bancaires** : Suivi des versements
- **Utilisateurs** : Administration des comptes
- **Audit** : Journal des activités

### 2.3 Éléments d'interface communs

#### Boutons d'action
- **Primaire (bleu)** : Actions principales (Enregistrer, Valider)
- **Secondaire (gris)** : Actions secondaires (Annuler, Retour)
- **Danger (rouge)** : Actions destructives (Supprimer, Annuler)
- **Fantôme (transparent)** : Actions de navigation

#### Indicateurs de statut
- **Succès (vert)** : Opération réussie, payé, actif
- **Attention (orange)** : En attente, brouillon
- **Danger (rouge)** : Erreur, annulé, expiré

#### Formulaires
- **Champs obligatoires** : Marqués d'un astérisque rouge (*)
- **Validation en temps réel** : Messages d'erreur immédiats
- **Aide contextuelle** : Info-bulles sur les champs complexes

---

## 3. Super Administrateur

### 3.1 Vue d'ensemble du rôle

En tant que Super Administrateur, vous avez un contrôle total sur l'ensemble du système TELC, tous centres confondus.

### 3.2 Tableau de bord Super Admin

#### Indicateurs globaux
```
┌─────────────────────────────────────────────────────────────┐
│              TABLEAU DE BORD GLOBAL               │
├─────────────────────────────────────────────────────────────┤
│                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │
│  │   CENTRES   │  │  UTILISATEURS│  │   SESSIONS   │ │
│  │             │  │             │  │             │ │
│  │ 12 Centres  │  │ 48 Utilisat.│  │ 156 Sessions │ │
│  │ 10 Actifs   │  │ 5 Super Admin│  │ 45 Actives   │ │
│  │ 2 Inactifs  │  │ 15 Admin Ctr │  │ 98 Fermées   │ │
│  │             │  │ 28 Secrétaires│  │ 13 Brouillons│ │
│  └─────────────┘  └─────────────┘  └─────────────┘ │
│                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │
│  │ INSCRIPTIONS│  │   PAIEMENTS │  │    REVENUS  │ │
│  │             │  │             │  │             │ │
│  │ 1,247 Total │  │ 892 Payés   │  │ 22,340,000  │ │
│  │ 892 Actives  │  │ 355 En attente│  │ FCFA         │ │
│  │ 355 Annulées │  │             │  │             │ │
│  │             │  │             │  │             │ │
│  └─────────────┘  └─────────────┘  └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

#### Actions rapides disponibles
- **Créer un nouveau centre** : Ajouter un centre d'examen
- **Ajouter un utilisateur** : Créer un compte avec rôles
- **Voir les inscriptions récentes** : Accès direct aux dernières activités
- **Exporter les données globales** : Rapport système complet

### 3.3 Gestion des centres

#### Création d'un centre
1. **Navigation** : `Centres` → `Nouveau Centre`
2. **Formulaire de création** :
   ```
   ┌─────────────────────────────────────┐
   │         NOUVEAU CENTRE           │
   ├─────────────────────────────────────┤
   │                                     │
   │ Nom du centre *                    │
   │ [_________________________]          │
   │                                     │
   │ Code du centre                     │
   │ [________________]                  │
   │                                     │
   │ Ville *                           │
   │ [_________________________]          │
   │                                     │
   │ Pays *                           │
   │ [_________________________]          │
   │                                     │
   │ Téléphone                         │
   │ [_________________________]          │
   │                                     │
   │ Email                            │
   │ [_________________________]          │
   │                                     │
   │ Adresse                          │
   │ [________________________________] │
   │                                     │
   │ ☑ Activer ce centre               │
   │                                     │
   │ [Annuler]  [Créer le centre]      │
   └─────────────────────────────────────┘
   ```

3. **Validation et sauvegarde** : Le système vérifie l'unicité du code
4. **Confirmation** : Message de succès avec redirection vers la liste

#### Modification d'un centre
1. **Sélection** : `Centres` → cliquer sur le centre à modifier
2. **Formulaire pré-rempli** : Toutes les informations actuelles sont chargées
3. **Modifications** : Apporter les changements nécessaires
4. **Historique** : Consultation de l'audit trail des modifications

#### Activation/Désactivation
- **Centre actif** : Peut recevoir des inscriptions et sessions
- **Centre inactif** : Conservé en lecture seule, plus d'opérations possibles

### 3.4 Gestion des utilisateurs

#### Création d'un utilisateur
1. **Navigation** : `Utilisateurs` → `Nouvel Utilisateur`
2. **Formulaire de création** :
   ```
   ┌─────────────────────────────────────┐
   │        NOUVEL UTILISATEUR         │
   ├─────────────────────────────────────┤
   │                                     │
   │ Nom complet *                     │
   │ [_________________________]          │
   │                                     │
   │ Email *                          │
   │ [_________________________]          │
   │                                     │
   │ Téléphone                        │
   │ [_________________________]          │
   │                                     │
   │ Rôle *                          │
   │ [Super Administrateur ▼]          │
   │ [Administrateur Centre ▼]          │
   │ [Secrétaire ▼]                  │
   │                                     │
   │ Centre assigné *                  │
   │ [Tous les centres ▼]            │
   │ [Centre A ▼]                     │
   │ [Centre B ▼]                     │
   │                                     │
   │ ☑ Activer le compte               │
   │                                     │
   │ [Annuler]  [Créer l'utilisateur] │
   └─────────────────────────────────────┘
   ```

3. **Configuration des permissions** : Selon le rôle sélectionné
4. **Email de bienvenue** : Envoyé automatiquement avec instructions

#### Gestion des rôles
- **Super Administrateur** : Accès total à tous les centres et fonctionnalités
- **Administrateur de Centre** : Gestion complète d'un ou plusieurs centres
- **Secrétaire** : Opérations limitées aux centres assignés

### 3.5 Audit et monitoring

#### Journal d'audit global
```
┌─────────────────────────────────────────────────────────────┐
│                 JOURNAL D'AUDIT GLOBAL              │
├─────────────────────────────────────────────────────────────┤
│                                                     │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ FILTRES                                       │   │
│  │ [Toutes les actions ▼] [Tous les utilisateurs▼] │   │
│  │ [Dernières 24h ▼] [Tous les centres▼]       │   │
│  │ [Super Admin ▼] [Rechercher...]               │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                     │
│  ┌─────────────────────────────────────────────────────┐   │
│  │                ACTIVITÉS RÉCENTES            │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ 2024-03-11 14:30 | Création centre     │   │
│  │    User: Admin Système | Centre: Paris    │   │
│  │    IP: 192.168.1.100 | Action: CREATE   │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ 2024-03-11 14:25 | Création utilisateur │   │
│  │    User: Admin Système | Role: Secrétaire │   │
│  │    IP: 192.168.1.100 | Action: CREATE   │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ 2024-03-11 14:15 | Modification inscription│   │
│  │    User: Secrétaire Paris | Reg: TELC-B1-001│   │
│  │    IP: 192.168.1.105 | Action: UPDATE   │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                     │
│                    [Exporter] [Filtrer] [Rafraîchir]   │
└─────────────────────────────────────────────────────────────┘
```

### 3.6 Rapports globaux

#### Export de données système
1. **Navigation** : `Tableau de bord` → `Exports`
2. **Options d'export** :
   - **Export complet** : Toutes les données système
   - **Export par période** : Sélection personnalisée
   - **Export par centre** : Données d'un centre spécifique
   - **Format** : Excel, CSV, PDF

#### Rapports de performance
- **Utilisation par centre** : Activité et volume
- **Performance des inscriptions** : Temps de traitement
- **Statistiques financières** : Recettes et tendances
- **Adoption des fonctionnalités** : Utilisation des nouvelles features

---

## 4. Administrateur de Centre

### 4.1 Vue d'ensemble du rôle

En tant qu'Administrateur de Centre, vous gérez complètement un ou plusieurs centres qui vous sont assignés avec des permissions étendues sur ces centres.

### 4.2 Tableau de bord Admin Centre

#### Vue personnalisée
```
┌─────────────────────────────────────────────────────────────┐
│           TABLEAU DE BORD - CENTRE DE PARIS      │
├─────────────────────────────────────────────────────────────┤
│                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │
│  │   SESSIONS  │  │ INSCRIPTIONS│  │   PAIEMENTS │ │
│  │             │  │             │  │             │ │
│  │ 8 Sessions  │  │ 156 Inscrits│  │ 892 Payés   │ │
│  │ 3 Actives   │  │ 142 Actifs  │  │ 355 En attente│ │
│  │ 5 Fermées   │  │ 14 Annulés  │  │             │ │
│  │             │  │             │  │             │ │
│  └─────────────┘  └─────────────┘  └─────────────┘ │
│                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │
│  │   ÉTUDIANTS│  │    REVENUS  │  │   DÉPÔTS    │ │
│  │             │  │             │  │             │ │
│  │ 892 Étudiants│  │ 22,340,000  │  │ 15 Dépôts   │ │
│  │ 45 Nouveaux │  │ FCFA         │  │ 850,000 FCFA │ │
│  │             │  │             │  │ 3 En attente │ │
│  └─────────────┘  └─────────────┘  └─────────────┘ │
│                                                     │
│  ┌─────────────────────────────────────────────────────┐   │
│  │           ACTIONS RAPIDES                      │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ [Nouvelle Session] [Nouvel Étudiant]       │   │
│  │ [Nouvelle Inscription] [Nouveau Dépôt]      │   │
│  │ [Export des données] [Voir l'audit]          │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                     │
│                    [Changer de centre ▼]              │
└─────────────────────────────────────────────────────────────┘
```

### 4.3 Gestion des sessions d'examen

#### Création d'une session
1. **Navigation** : `Sessions` → `Nouvelle Session`
2. **Formulaire détaillé** :
   ```
   ┌─────────────────────────────────────────────┐
   │           NOUVELLE SESSION TELC        │
   ├─────────────────────────────────────────────┤
   │                                     │
   │ Titre de la session *               │
   │ [TELC B1 - Session Mars 2024_______] │
   │                                     │
   │ Niveau *                          │
   │ [B1 ▼] [B2 ▼] [C1 ▼]        │
   │                                     │
   │ Centre *                          │
   │ [Centre de Paris ▼]                 │
   │                                     │
   │ Ville d'examen *                  │
   │ [Paris_________________________]        │
   │                                     │
   │ Date d'examen *                   │
   │ [2024-03-25 ___________]        │
   │                                     │
   │ Période d'inscription              │
   │ Du: [2024-02-01 ___________]    │
   │ Au: [2024-03-15 ___________]    │
   │                                     │
   │ Prix d'inscription *               │
   │ [25 000 FCFA_______________]      │
   │                                     │
   │ Places disponibles                  │
   │ [30_________________________]        │
   │                                     │
   │ Statut *                         │
   │ [Brouillon ▼] [Ouvert ▼] [Fermé ▼] │
   │                                     │
   │ Notes                            │
   │ [________________________________] │
   │                                     │
   │ [Annuler]  [Créer la session]      │
   └─────────────────────────────────────────────┘
   ```

3. **Configuration automatique** : Le système génère un numéro de session unique
4. **Notification** : Email de confirmation aux utilisateurs concernés

#### Gestion des périodes
- **Brouillon** : Phase de préparation, non visible aux étudiants
- **Ouvert** : Période d'inscription active
- **Fermé** : Plus d'inscriptions possibles

### 4.4 Gestion des inscriptions

#### Processus d'inscription
1. **Navigation** : `Inscriptions` → `Nouvelle Inscription`
2. **Recherche d'étudiant** :
   ```
   ┌─────────────────────────────────────┐
   │        RECHERCHE D'ÉTUDIANT      │
   ├─────────────────────────────────────┤
   │                                     │
   │ Nom ou Email                     │
   │ [_________________________]        │
   │                                     │
   │ ☑ Rechercher dans tous les centres │
   │                                     │
   │ [Rechercher] [Nouvel Étudiant]   │
   └─────────────────────────────────────┘
   ```

3. **Sélection ou création** : Choix d'un étudiant existant ou création
4. **Configuration de l'inscription** :
   ```
   ┌─────────────────────────────────────┐
   │        NOUVELLE INSCRIPTION        │
   ├─────────────────────────────────────┤
   │                                     │
   │ Étudiant : Martin Dupont           │
   │ Email : martin.dupont@email.com      │
   │                                     │
   │ Session TELC *                  │
   │ [TELC B1 - Mars 2024 ▼]        │
   │                                     │
   │ Montant : 25 000 FCFA             │
   │                                     │
   │ ☑ Envoyer un email de confirmation  │
   │                                     │
   │ [Annuler]  [Confirmer l'inscription]│
   └─────────────────────────────────────┘
   ```

5. **Génération automatique** : Numéro de reçu et confirmation

#### Suivi des paiements
- **Marquage comme payé** : Bouton d'action rapide depuis la liste
- **Génération de reçu** : PDF automatique après paiement
- **Historique** : Suivi complet des statuts de paiement

### 4.5 Gestion des dépôts bancaires

#### Enregistrement d'un dépôt
1. **Navigation** : `Dépôts Bancaires` → `Nouveau Dépôt`
2. **Formulaire de dépôt** :
   ```
   ┌─────────────────────────────────────┐
   │          NOUVEAU DÉPÔT BANCAIRE     │
   ├─────────────────────────────────────┤
   │                                     │
   │ Centre *                         │
   │ [Centre de Paris ▼]                 │
   │                                     │
   │ Session TELC *                  │
   │ [Toutes les sessions ▼]            │
   │ [TELC B1 - Mars 2024 ▼]        │
   │                                     │
   │ Montant du dépôt *               │
   │ [850 000 FCFA_______________]      │
   │                                     │
   │ Date du dépôt *                  │
   │ [2024-03-11 ___________]        │
   │                                     │
   │ Référence bancaire                │
   │ [REF-20240311-001________]     │
   │                                     │
   │ Pièce justificative               │
   │ [Choisir un fichier_______________] │
   │                                     │
   │ Notes                            │
   │ [________________________________] │
   │                                     │
   │ [Enregistrer le brouillon] [Valider] │
   └─────────────────────────────────────┘
   ```

3. **Workflow de validation** : Brouillon → Validation → Approbation
4. **Reconciliation** : Association automatique avec les inscriptions payées

### 4.6 Rapports du centre

#### Export personnalisé
1. **Navigation** : `Tableau de bord` → `Exports`
2. **Options de filtrage** :
   - **Période personnalisée** : Sélection de dates
   - **Sessions spécifiques** : Choix des sessions
   - **Statuts de paiement** : Payés, en attente, annulés
   - **Format d'export** : Excel, CSV, PDF

#### Statistiques du centre
- **Performance par session** : Taux de remplissage
- **Évolution des inscriptions** : Tendances mensuelles
- **Analyse financière** : Recettes et prévisions
- **Indicateurs de qualité** : Satisfaction et réclamations

---

## 5. Secrétaire

### 5.1 Vue d'ensemble du rôle

En tant que Secrétaire, vous gérez les inscriptions et le suivi quotidien pour le ou les centres qui vous sont assignés.

### 5.2 Tableau de bord Secrétaire

#### Interface simplifiée
```
┌─────────────────────────────────────────────────────────────┐
│        TABLEAU DE BORD - SECRÉTAIRE CENTRE PARIS │
├─────────────────────────────────────────────────────────────┤
│                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │
│  │ INSCRIPTIONS│  │   PAIEMENTS │  │   AUJOURD'HUI│ │
│  │   À TRAITER│  │   EN ATTENTE │  │             │ │
│  │             │  │             │  │             │ │
│  │ 12 Nouvelles │  │ 8 En attente │  │ 5 Inscriptions│ │
│  │ 3 Urgentes  │  │ 2 500 FCFA   │  │ 2 Modifications│ │
│  │             │  │             │  │ 1 Paiement   │ │
│  └─────────────┘  └─────────────┘  └─────────────┘ │
│                                                     │
│  ┌─────────────────────────────────────────────────────┐   │
│  │           SESSIONS ACTIVES                      │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ TELC B1 - Mars 2024 | Places: 5/30     │   │
│  │ TELC B2 - Mars 2024 | Places: 12/25    │   │
│  │ TELC C1 - Avril 2024 | Places: 8/20     │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                     │
│  ┌─────────────────────────────────────────────────────┐   │
│  │           ACTIONS RAPIDES                      │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ [Nouvelle Inscription] [Marquer Payé]       │   │
│  │ [Rechercher Étudiant] [Générer Reçu]       │   │
│  │ [Liste du Jour] [Export Rapide]             │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                     │
│                    [Aide] [Déconnexion]              │
└─────────────────────────────────────────────────────────────┘
```

### 5.3 Gestion des inscriptions

#### Workflow d'inscription simplifié
1. **Navigation** : `Inscriptions` → `Nouvelle Inscription`
2. **Recherche rapide** : Recherche par nom, email, ou numéro de reçu
3. **Formulaire d'inscription** :
   ```
   ┌─────────────────────────────────────┐
   │        NOUVELLE INSCRIPTION        │
   ├─────────────────────────────────────┤
   │                                     │
   │ Informations de l'étudiant          │
   │ ┌─────────────────────────────────┐   │
   │ │ Nom *                         │   │
   │ │ [_________________________]    │   │
   │ │                             │   │
   │ │ Email *                      │   │
   │ │ [_________________________]    │   │
   │ │                             │   │
   │ │ Téléphone                    │   │
   │ │ [_________________________]    │   │
   │ └─────────────────────────────────┘   │
   │                                     │
   │ Session TELC *                  │
   │ [TELC B1 - Mars 2024 ▼]        │
   │                                     │
   │ Montant : 25 000 FCFA             │
   │                                     │
   │ ☑ Envoyer confirmation par email   │
   │                                     │
   │ [Réinitialiser] [Inscrire l'étudiant] │
   └─────────────────────────────────────┘
   ```

4. **Confirmation automatique** : Email envoyé à l'étudiant
5. **Génération de reçu** : Numéro automatique unique

#### Modification d'une inscription
1. **Sélection** : Cliquer sur une inscription dans la liste
2. **Actions disponibles** :
   - **Modifier les informations** : Nom, email, téléphone
   - **Changer de session** : Transfert vers une autre session
   - **Marquer comme payé** : Validation du paiement
   - **Annuler l'inscription** : Avec motif obligatoire

### 5.4 Gestion des paiements

#### Marquage des paiements
1. **Liste des paiements en attente** :
   ```
   ┌─────────────────────────────────────────────────────────────┐
   │              PAIEMENTS EN ATTENTE                  │
   ├─────────────────────────────────────────────────────────────┤
   │                                                     │
   │  ┌─────────────────────────────────────────────────────┐   │
   │  │ Martin Dupont | TELC B1-Mars | 25 000 FCFA │   │
   │  │ [Marquer Payé] [Voir Détails]            │   │
   │  ├─────────────────────────────────────────────────────┤   │
   │  │ Sophie Martin | TELC B2-Mars | 25 000 FCFA │   │
   │  │ [Marquer Payé] [Voir Détails]            │   │
   │  └─────────────────────────────────────────────────────┘   │
   │                                                     │
   │  ┌─────────────────────────────────────────────────────┐   │
   │  │ Total en attente : 2 paiements           │   │
   │  │ Montant total : 50 000 FCFA              │   │
   │  │ [Tout Marquer Payé] [Exporter la liste]   │   │
   │  └─────────────────────────────────────────────────────┘   │
│                                                     │
│                    [Filtrer] [Rafraîchir]            │
└─────────────────────────────────────────────────────────────┘
   ```

2. **Validation du paiement** : Confirmation et génération de reçu
3. **Notification automatique** : Email de confirmation envoyé à l'étudiant

#### Historique des paiements
- **Consultation par période** : Filtres de dates
- **Recherche par étudiant** : Historique complet par personne
- **Export des données** : Format Excel ou CSV

### 5.5 Génération des reçus

#### Processus de génération
1. **Accès au reçu** : Cliquer sur "Voir" ou "PDF" depuis une inscription payée
2. **Options de génération** :
   - **Affichage web** : Consultation en ligne
   - **Génération PDF** : Impression ou sauvegarde
   - **Envoi par email** : Option disponible

#### Reçu optimisé
```
┌─────────────────────────────────────────────────────────────┐
│                   REÇU TELC N° TELC-20240311-001        │
├─────────────────────────────────────────────────────────────┤
│                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │
│  │   ÉTUDIANT │  │   CENTRE    │  │   SESSION   │ │
│  │             │  │             │  │             │ │
│  │ Martin      │  │ Centre de   │  │ TELC B1    │ │
│  │ Dupont      │  │ Paris       │  │ Mars 2024   │ │
│  │ martin@     │  │             │  │             │ │
│  │ email.com   │  │             │  │             │ │
│  └─────────────┘  └─────────────┘  └─────────────┘ │
│                                                     │
│  ┌─────────────────────────────────────────────────────┐   │
│  │                PAIEMENT                       │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ Montant payé : 25 000 FCFA               │   │
│  │ Date paiement : 11/03/2024 à 14:30        │   │
│  │ Statut : ✅ PAYÉ                           │   │
│  │ Mode : Espèce                               │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                     │
│  ┌─────────────────────────────────────────────────────┐   │
│  │              VALIDATION                       │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ Cachet du centre : _______________________     │   │
│  │                                           │   │
│  │ Signature : _____________________________     │   │
│  │                                           │   │
│  │ Chef de centre : _______________________     │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                     │
│  Ce document fait foi de l'inscription et doit être  │
│  conservé pour toute réclamation future.               │
│                                                     │
│                    [Imprimer] [Télécharger PDF]        │
└─────────────────────────────────────────────────────────────┘
```

### 5.6 Rapports et exports

#### Export quotidien
1. **Navigation** : `Tableau de bord` → `Export du Jour`
2. **Options disponibles** :
   - **Inscriptions du jour** : Liste complète
   - **Paiements reçus** : Montants et détails
   - **Statut des sessions** : Places disponibles
   - **Format** : Excel recommandé pour la manipulation

#### Rapports personnalisés
- **Export par session** : Données d'une session spécifique
- **Export par période** : Sélection personnalisée de dates
- **Liste d'attente** : Paiements en attente par date
- **Statistiques mensuelles** : Évolution et tendances

---

## 6. Guide des Bonnes Pratiques

### 6.1 Sécurité des comptes

#### Gestion des mots de passe
- **Complexité requise** : 12 caractères minimum avec majuscules, minuscules, chiffres et symboles
- **Rotation régulière** : Changer tous les 90 jours
- **Pas de partage** : Ne jamais partager vos identifiants
- **Déconnexion sécurisée** : Toujours se déconnecter après utilisation

#### Connexion sécurisée
- **URL officielle** : Utiliser uniquement l'URL fournie par votre administrateur
- **Vérification HTTPS** : Confirmer le cadenas dans le navigateur
- **Session privée** : Éviter les ordinateurs partagés ou utiliser le mode navigation privée

### 6.2 Gestion des données

#### Qualité des informations
- **Vérification systématique** : Confirmer les informations avant validation
- **Orthographe et format** : Noms propres, emails valides, formats de téléphone
- **Documents justificatifs** : Conserver les pièces des étudiants si requis
- **Mise à jour régulière** : Garder les informations à jour

#### Sauvegarde des données
- **Exports réguliers** : Télécharger des copies des données importantes
- **Archivage des reçus** : Conserver les PDF générés
- **Documentation des processus** : Noter les procédures spécifiques à votre centre

### 6.3 Efficacité opérationnelle

#### Organisation du travail
- **Traitement par lot** : Gérer plusieurs inscriptions simultanément
- **Utilisation des filtres** : Optimiser les recherches et exports
- **Raccourcis clavier** : Apprendre les raccourcis pour gagner du temps
- **Signets navigateur** : Enregistrer les pages fréquentes

#### Communication avec les étudiants
- **Réponses rapides** : Traiter les demandes dans les meilleurs délais
- **Templates d'emails** : Utiliser les réponses prédictives
- **Suivi des communications** : Noter les échanges importants
- **Professionnalisme** : Maintenir un ton professionnel dans toutes les communications

### 6.4 Résolution de problèmes

#### Dépannage de base
- **Rafraîchir la page** : F5 ou Ctrl+R pour les problèmes d'affichage
- **Vider le cache** : Si les données ne s'affichent pas correctement
- **Changer de navigateur** : Tester avec un autre navigateur si problème persiste
- **Vérifier la connexion** : S'assurer d'une connexion internet stable

#### Quand contacter le support
- **Erreurs système** : Messages d'erreur répétés
- **Problèmes d'accès** : Impossible de se connecter
- **Fonctionnalités cassées** : Boutons ou formulaires non fonctionnels
- **Performance lente** : Temps de réponse excessifs

---

## 7. Dépannage et FAQ

### 7.1 Problèmes de connexion

#### Question : "J'ai oublié mon mot de passe"
**Solution** : 
1. Cliquer sur "Mot de passe oublié ?"
2. Entrer votre email professionnel
3. Consulter vos emails et suivre le lien de réinitialisation
4. Créer un nouveau mot de passe sécurisé

#### Question : "Mon compte est bloqué"
**Solution** :
- Contacter votre Super Administrateur
- Vérifier vos tentatives de connexion récentes
- Attendre le délai de débloquage automatique (30 minutes)

### 7.2 Problèmes d'inscription

#### Question : "Je ne trouve pas l'étudiant dans la recherche"
**Solution** :
1. Vérifier l'orthographe du nom
2. Essayer avec l'email de l'étudiant
3. Vérifier que l'étudiant est dans le bon centre
4. Créer une nouvelle fiche étudiant si nécessaire

#### Question : "La session TELC n'apparaît pas dans la liste"
**Solution** :
1. Vérifier que la session est bien "Ouverte"
2. Confirmer que vous êtes dans le bon centre
3. Vérifier les dates de période d'inscription
4. Contacter l'administrateur système si problème persiste

### 7.3 Problèmes de paiement

#### Question : "Le bouton 'Marquer Payé' est grisé"
**Solution** :
- Vérifier que vous avez les permissions nécessaires
- Confirmer que l'inscription est bien "En attente"
- Contacter votre administrateur de centre

#### Question : "Le reçu PDF ne se génère pas"
**Solution** :
1. Vérifier que l'inscription est bien marquée comme "Payée"
2. Essayer avec un autre navigateur (Chrome, Firefox)
3. Vérifier les paramètres d'impression PDF de votre navigateur
4. Contacter le support technique si problème persiste

### 7.4 Problèmes d'exports

#### Question : "L'export Excel est vide ou corrompu"
**Solution** :
1. Réduire la période d'export
2. Essayer le format CSV comme alternative
3. Vérifier les filtres appliqués
4. Utiliser un autre navigateur

#### Question : "L'export prend trop de temps"
**Solution** :
1. Réduire le nombre d'enregistrements
2. Utiliser les filtres pour affiner la recherche
3. Exporter pendant les heures creuses
4. Essayer l'export par lots plus petits

### 7.5 Performance et navigation

#### Question : "L'application est très lente"
**Solution** :
1. Vider le cache de votre navigateur
2. Fermer les onglets inutilisés
3. Vérifier votre connexion internet
4. Utiliser la dernière version de votre navigateur

#### Question : "Les boutons ne répondent pas au clic"
**Solution** :
1. Rafraîchir la page (F5)
2. Vérifier les messages d'erreur JavaScript
3. Désactiver les extensions de navigateur
4. Essayer en mode navigation privée

### 7.6 Support technique

#### Informations à fournir lors d'une demande de support
1. **Votre rôle** : Super Admin, Admin Centre, ou Secrétaire
2. **Description du problème** : Ce que vous essayez de faire
3. **Message d'erreur exact** : Copier-coller le texte d'erreur
4. **Navigateur et version** : Ex: Chrome 120.0.6099.129
5. **Capture d'écran** : Si possible, joindre une image
6. **Heure du problème** : Avec votre fuseau horaire

#### Canaux de support
- **Email technique** : support@telc.com
- **Téléphone support** : +33 1 234 567 890
- **Documentation en ligne** : https://docs.telc.com
- **Heures de support** : Lundi-Vendredi 8h-18h (heure locale)

---

## 📞 Contact et Ressources

### Support utilisateur
- **Email** : aide@telc.com
- **Téléphone** : +33 1 234 567 891
- **FAQ en ligne** : https://faq.telc.com
- **Tutoriels vidéo** : https://tutorials.telc.com

### Ressources additionnelles
- **Guide de formation** : Disponible sur demande
- **Webinaires mensuels** : Inscription gratuite
- **Communauté utilisateurs** : https://community.telc.com
- **Mises à jour** : Suivez @telc_system sur Twitter

---

*Manuel d'utilisation créé le 11 Mars 2026*  
*Version : 1.0.0*  
*Prochaine mise à jour : 11 Juin 2026*
