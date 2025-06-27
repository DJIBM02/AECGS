# AECGS - Site Web Officiel

Site web officiel de l'Association Ethnoculturelle des Camerounais du Grand-Sudbury (AECGS).

## À propos du projet

L'AECGS est une organisation à but non lucratif qui vise à promouvoir la solidarité, l'intégration et la richesse culturelle des Camerounais vivant à Sudbury, Ontario, Canada.

### Objectifs de l'association

-   **Rassembler la Communauté** : Rassembler tous les Camerounaises et Camerounais résidant dans le Grand Sudbury et ses environs dans un esprit de convivialité, de fraternité et d'entraide.
-   **Accompagnement des Nouveaux Arrivants** : Accueillir et accompagner les nouveaux arrivants camerounais au Grand Sudbury pour favoriser leur intégration dans la communauté.
-   **Promotion Culturelle** : Promouvoir et préserver la richesse culturelle camerounaise à travers des événements et activités.
-   **Entraide et Solidarité** : Créer un environnement d'entraide, de solidarité et de soutien mutuel.

## Technologies utilisées

-   **Framework** : Laravel 12.x
-   **Frontend** : React, TypeScript, Tailwind CSS, Inertia.js
-   **Base de données** : SQLite (développement)
-   **Build tool** : Vite
-   **PHP** : 8.2+

## Fonctionnalités

-   **Page d'accueil** : Présentation de l'association avec carousel d'images
-   **À propos** : Informations détaillées sur l'association et son équipe
-   **Événements** : Présentation des événements passés et à venir
-   **Contact** : Formulaire de contact avec validation
-   **Responsive Design** : Compatible avec tous les appareils
-   **Tableau de bord** : Interface utilisateur pour les membres de l'association
-   **Authentification** : Système d'authentification pour les membres
-   **Profil utilisateur** : Gestion des informations de profil

## Installation

### Prérequis

-   PHP 8.2 ou supérieur
-   Composer
-   Node.js et npm

### Prérequis dans VsCode

-   PHP Intelephense
-   PHP Server
-   PHP Debug
-   TypeScript
-   ESLint
-   Tailwind CSS IntelliSense

### Étapes d'installation

1. **Cloner le projet**

    ```bash
    git clone <url-du-projet>
    cd aecgs-laravel
    ```

2. **Installer les dépendances PHP**

    ```bash
    composer install
    ```

3. **Installer les dépendances Node.js**

    ```bash
    npm install
    ```

4. **Configuration de l'environnement**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Base de données**

    ```bash
    touch database/database.sqlite
    php artisan migrate
    ```

6. **Compiler les assets**

    ```bash
    npm run build
    ```

7. **Générer des données de test (optionnel)**
    ```bash
    php artisan db:seed
    ```

## Développement

### Lancer le serveur de développement

```bash
php artisan serve
```

### Compilation des assets en mode développement

```bash
npm run dev
```

### Mode développement complet

```bash
composer run dev
```

Cette commande lance simultanément :

-   Le serveur Laravel
-   La compilation des assets avec Vite
-   Les workers de queue
-   Les logs en temps réel

### Tests

```bash
composer test
```

Cette commande exécute les tests avec Pest PHP.

## Structure du projet

```
app/
├── Http/
│   ├── Controllers/     # Contrôleurs
│   ├── Middleware/      # Middlewares personnalisés
│   └── Requests/        # Form Requests pour validation
├── Mail/                # Classes de mail
│   └── ContactFormMail.php
├── Models/              # Modèles Eloquent
│   └── User.php
├── Providers/           # Fournisseurs de service
│   └── AppServiceProvider.php
└── View/
    └── Components/      # Composants de vue

resources/
├── views/               # Templates Blade
│   ├── layouts/         # Layouts principaux
│   ├── components/      # Composants réutilisables
│   ├── partials/        # Sections partielles
│   ├── emails/          # Templates d'emails
│   ├── auth/            # Pages d'authentification
│   └── profile/         # Pages de profil utilisateur
├── css/                 # Styles CSS
└── js/                  # Scripts TypeScript/React
    ├── Components/      # Composants React
    ├── Layouts/         # Layouts pour Inertia.js
    └── Pages/           # Pages React pour Inertia.js

routes/
├── web.php              # Routes web
├── auth.php             # Routes d'authentification
└── console.php          # Routes de commandes console

public/
├── build/               # Assets compilés par Vite
│   └── assets/
├── css/                 # Styles CSS
├── js/                  # Scripts JavaScript
└── lib/                 # Bibliothèques tierces
```

## Contact

-   **Email** : info.cameroungrandsudbury@gmail.com
-   **Localisation** : Sudbury, Ontario, Canada

## Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
