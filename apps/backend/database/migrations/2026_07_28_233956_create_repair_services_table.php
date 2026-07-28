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
        Schema::create('repair_services', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('technician_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('listing_id')->nullable()->constrained()->onDelete('set null');
            
            // Détails du service
            $table->string('title', 255);
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->integer('estimated_days')->nullable(); // Jours estimés pour réparation
            
            // Statut
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'])->default('pending');
            
            // Localisation
            $table->string('location', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Dates importantes
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('technician_id');
            $table->index('client_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_services');
    }
};