<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Added this import

class SetupAecgs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aecgs:setup {--force : Force recreation of directories}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup AECGS application with events management';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎯 Configuration de l\'application AECGS...');
        $this->newLine();

        // 1. Créer le lien symbolique storage
        $this->info('📁 Création du lien symbolique storage...');
        Artisan::call('storage:link');
        $this->info('✅ Lien symbolique créé');

        // 2. Créer les dossiers nécessaires
        $this->info('📂 Création des dossiers pour les uploads...');
        $this->createStorageDirectories();

        // 3. Migrations
        $this->info('🗄️ Exécution des migrations...');
        if ($this->confirm('Voulez-vous exécuter les migrations ?', true)) {
            Artisan::call('migrate', ['--force' => true]);
            $this->info('✅ Migrations exécutées');
        }

        // 4. Seeders
        $this->info('🌱 Exécution des seeders...');
        if ($this->confirm('Voulez-vous créer des événements de démonstration ?', true)) {
            Artisan::call('db:seed', ['--class' => 'EventSeeder']);
            $this->info('✅ Événements de démonstration créés');
        }

        // 5. Optimisations
        $this->info('⚡ Optimisation de l\'application...');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        $this->info('✅ Application optimisée');

        // 6. Vérifications
        $this->info('🔍 Vérifications finales...');
        $this->performChecks();

        $this->newLine();
        $this->info('🎉 Configuration terminée avec succès !');
        $this->info('🌐 Votre application est prête à l\'adresse : ' . config('app.url'));
        $this->newLine();
        
        $this->displayNextSteps();
    }

    /**
     * Créer les dossiers de stockage nécessaires
     */
    private function createStorageDirectories()
    {
        $directories = [
            'public/events',
            'public/avatars',
            'public/documents',
            'temp'
        ];

        foreach ($directories as $dir) {
            if (!Storage::exists($dir) || $this->option('force')) {
                Storage::makeDirectory($dir);
                $this->line("  📁 Dossier créé : storage/app/{$dir}");
            } else {
                $this->line("  ✅ Dossier existe : storage/app/{$dir}");
            }
        }

        // Créer un fichier .gitkeep pour maintenir les dossiers
        foreach ($directories as $dir) {
            Storage::put($dir . '/.gitkeep', '');
        }

        $this->info('✅ Dossiers de stockage configurés');
    }

    /**
     * Effectuer des vérifications de configuration
     */
    private function performChecks()
    {
        $checks = [
            'Storage link' => file_exists(public_path('storage')),
            'Events directory' => Storage::exists('public/events'),
            'Database connection' => $this->checkDatabaseConnection(),
            'File upload permissions' => is_writable(storage_path('app/public')),
        ];

        foreach ($checks as $check => $result) {
            $status = $result ? '✅' : '❌';
            $this->line("  {$status} {$check}");
        }

        $allPassed = array_reduce($checks, function($carry, $item) {
            return $carry && $item;
        }, true);

        if (!$allPassed) {
            $this->warn('⚠️ Certaines vérifications ont échoué. Vérifiez votre configuration.');
        }
    }

    /**
     * Vérifier la connexion à la base de données
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo(); // Now this will work properly
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Afficher les prochaines étapes
     */
    private function displayNextSteps()
    {
        $this->info('📋 Prochaines étapes recommandées :');
        $this->line('');
        $this->line('1. 🔐 Configurer l\'authentification :');
        $this->line('   composer require laravel/breeze --dev');
        $this->line('   php artisan breeze:install');
        $this->line('');
        $this->line('2. 🖼️ Installer le package d\'optimisation d\'images (optionnel) :');
        $this->line('   composer require intervention/image');
        $this->line('');
        $this->line('3. 👥 Créer le système de rôles :');
        $this->line('   - Créer les modèles User, Role, Permission');
        $this->line('   - Implémenter les middlewares de rôles');
        $this->line('   - Protéger les routes admin');
        $this->line('');
        $this->line('4. 📧 Configurer les emails :');
        $this->line('   - Paramétrer MAIL_* dans .env');
        $this->line('   - Tester l\'envoi d\'emails');
        $this->line('');
        $this->line('5. 🚀 Déploiement :');
        $this->line('   - Configurer le serveur web');
        $this->line('   - Paramétrer SSL/HTTPS');
        $this->line('   - Configurer les sauvegardes');
    }
}