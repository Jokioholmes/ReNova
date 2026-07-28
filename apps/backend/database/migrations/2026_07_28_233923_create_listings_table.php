<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter la migration.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Contenu
            $table->string('title', 255);
            $table->text('description');
            $table->decimal('price', 12, 2);
            
            // Images (JSON array)
            $table->json('images')->nullable();
            
            // Caractéristiques de l'appareil
            $table->enum('condition', ['new', 'excellent', 'good', 'fair', 'poor'])->default('good');
            $table->string('device_type', 100); // 'smartphone', 'laptop', 'tablet', etc.
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            
            // Statut
            $table->enum('status', ['active', 'sold', 'archived', 'draft'])->default('draft');
            
            // Localisation
            $table->string('location', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Métadonnées
            $table->integer('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('device_type');
            $table->index('published_at');
            $table->fullText(['title', 'description']); // Pour recherche full-text
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};