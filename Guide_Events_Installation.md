# 🎯 Système de Gestion des Événements AECGS

## 📋 Installation et Configuration

### 1. Prérequis
- PHP 8.1+
- Composer
- Base de données (MySQL, PostgreSQL, SQLite)
- Extension GD ou Imagick pour le traitement d'images

### 2. Installation des dépendances

```bash
# Installer les dépendances Composer
composer install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 3. Configuration de la base de données

Modifier le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aecgs_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Configuration du stockage des fichiers

Dans `.env`, vérifiez :
```env
FILESYSTEM_DISK=public
APP_URL=http://localhost:8000
```

### 5. Setup automatique

```bash
# Commande tout-en-un pour configurer l'application
php artisan aecgs:setup
```

Cette commande :
- ✅ Crée le lien symbolique storage
- ✅ Exécute les migrations
- ✅ Crée les dossiers d'upload
- ✅ Génère des événements de démonstration
- ✅ Optimise l'application

### 6. Installation manuelle (alternative)

```bash
# Créer le lien symbolique
php artisan storage:link

# Exécuter les migrations
php artisan migrate

# Créer les événements de démonstration
php artisan db:seed --class=EventSeeder

# Optimiser l'application
php artisan optimize
```

## 🎨 Fonctionnalités Implémentées

### ✅ Gestion des Événements
- **Création** d'événements avec upload d'images (max 10MB)
- **Modification** et suppression d'événements
- **Types** : Culturel, Éducatif, Social, Communautaire, Sportif, Autre
- **Statuts** : À venir, En cours, Terminé, Annulé
- **Mise en vedette** des événements importants
- **Gestion des participants** (nombre max/actuel)
- **Prix** et informations de contact

### ✅ Upload et Gestion d'Images
- **Formats supportés** : JPEG, JPG, PNG, GIF, WEBP
- **Taille maximale** : 10 MB
- **Validation** côté client et serveur
- **Stockage sécurisé** dans `storage/app/public/events/`
- **Prévisualisation** lors de l'upload
- **Suppression automatique** des anciennes images

### ✅ Interface Utilisateur
- **Responsive design** avec Bootstrap
- **Filtres et recherche** par type, statut, mot-clé
- **Pagination** des événements
- **Vue détaillée** de chaque événement
- **Statistiques** des événements

### ✅ Préparation pour l'Authentification
- **Champs prêts** pour `created_by` et `updated_by`
- **Routes commentées** pour les différents rôles
- **Structure** pour membres, président, admin

## 📁 Structure des Fichiers

### Nouveaux Fichiers Créés

```
app/
├── Console/Commands/SetupAecgs.php ✅
├── Http/Controllers/
│   ├── ContactController.php ✅ (nouveau)
│   ├── EventController.php ✅ (optimisé)
│   └── HomeController.php ✅
├── Helpers/ImageHelper.php ✅ (optimisé)
└── Models/Event.php ✅

config/aecgs.php ✅ (nouveau)

resources/views/
├── events/
│   ├── index.blade.php ✅
│   ├── create.blade.php ✅
│   ├── edit.blade.php ✅ (nouveau)
│   ├── show.blade.php ✅ (nouveau)
│   └── partials/
│       ├── admin-controls.blade.php ✅
│       ├── event-card.blade.php ✅
│       ├── member-registrations.blade.php ✅
│       └── president-controls.blade.php ✅
├── components/
│   └── events-section.blade.php ✅ (à renommer)
├── contact.blade.php ⚠️ (à créer)
├── about.blade.php ⚠️ (à créer)
├── services.blade.php ⚠️ (à créer)
└── membership.blade.php ⚠️ (à créer)

database/
├── migrations/0001_01_01_000003_create_events_table.php ✅
└── seeders/EventSeeder.php ✅

routes/web.php ⚠️ (ajouter routes contact)

## 🔧 Configuration des Permissions

### Permissions des Dossiers
```bash
# S'assurer que Laravel peut écrire dans storage
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Ownership (sur serveur)
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

## 🚀 Utilisation

### 1. Accès aux Événements
- **Liste** : `/events`
- **Créer** : `/events/create`
- **Voir** : `/events/{id}`
- **Modifier** : `/events/{id}/edit`

### 2. Upload d'Images
1. Dans le formulaire de création/modification
2. Cliquer sur "Choisir un fichier"
3. Sélectionner une image (max 10MB)
4. Prévisualisation automatique
5. Sauvegarde lors de la soumission

### 3. Gestion des Événements
- **Filtrer** par type, statut, recherche
- **Mettre en vedette** avec l'icône étoile
- **Statistiques** en haut de la page
- **Pagination** automatique

## 🔐 Prochaines Étapes pour l'Authentification

### 1. Installation de Laravel Breeze
```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate
```

### 2. Système de Rôles
```php
// Créer les modèles
php artisan make:model Role -m
php artisan make:model Permission -m

// Relations Many-to-Many
// User belongsToMany Role
// Role belongsToMany Permission
```

### 3. Middleware de Rôles
```php
// app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check() || !auth()->user()->hasRole($roles)) {
        abort(403);
    }
    return $next($request);
}
```

### 4. Protection des Routes
```php
// Décommenter dans routes/web.php
Route::middleware(['auth', 'role:admin,president'])->group(function () {
    Route::resource('events', EventController::class)->except(['index', 'show']);
});
```

## 🛠️ Maintenance

### Optimisation des Images
```bash
# Si Intervention Image est installé
composer require intervention/image
```

### Nettoyage des Fichiers
```bash
# Supprimer les images orphelines
php artisan images:cleanup
```

### Sauvegarde
```bash
# Sauvegarder la base de données
php artisan backup:run

# Sauvegarder les uploads
tar -czf storage_backup.tar.gz storage/app/public/
```

## 📊 API Endpoints (Prêt pour Admin)

```php
// Liste des événements en JSON
GET /api/events

// Réponse :
{
  "events": [
    {
      "id": 1,
      "title": "Fête de la Culture",
      "date": "15/04/2025",
      "status": "À venir",
      "participants": 45,
      "image_url": "http://localhost/storage/events/image.jpg"
    }
  ]
}
```

## 🆘 Dépannage

### Problème : Images ne s'affichent pas
```bash
php artisan storage:link
```

### Problème : Upload échoue
- Vérifier `upload_max_filesize` dans php.ini
- Vérifier les permissions sur `storage/`

### Problème : Erreur 500
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📞 Support

Pour toute question ou problème :
- 📧 Email : dev@aecgs.ca
- 📱 Téléphone : +1 705 XXX XXXX
- 🌐 Site : https://aecgs.ca

---

**Version** : 1.0  
**Date** : Janvier 2025  
**Auteur** : Équipe Développement AECGS