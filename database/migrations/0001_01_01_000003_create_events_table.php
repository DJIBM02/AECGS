<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description');
            $table->string('image')->nullable(); // Chemin vers l'image uploadée
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('location');
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->enum('type', ['cultural', 'educational', 'social', 'community', 'sports', 'other'])->default('community');
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->decimal('price', 8, 2)->default(0.00);
            $table->text('requirements')->nullable();
            $table->json('contact_info')->nullable(); // Email, téléphone de contact
            
            // Préparer pour l'authentification future
            $table->unsignedBigInteger('created_by')->nullable(); // ID de l'utilisateur créateur
            $table->unsignedBigInteger('updated_by')->nullable(); // ID de l'utilisateur qui a modifié
            
            $table->timestamps();
            $table->softDeletes(); // Pour la suppression logique
            
            // Index pour améliorer les performances
            $table->index(['status', 'event_date']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};