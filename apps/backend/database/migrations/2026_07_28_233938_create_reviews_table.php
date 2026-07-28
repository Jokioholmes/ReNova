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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('listing_id')->nullable()->constrained()->onDelete('set null');
            
            // Contenu
            $table->integer('rating')->min(1)->max(5); // 1-5 étoiles
            $table->text('comment')->nullable();
            
            // Type de transaction
            $table->enum('transaction_type', ['seller', 'buyer', 'technician'])->default('buyer');
            
            // Métadonnées
            $table->boolean('is_verified')->default(false); // Vérifié (transaction complétée)
            $table->timestamps();
            
            // Indexes
            $table->index('reviewer_id');
            $table->index('reviewee_id');
            $table->index('listing_id');
            $table->index('created_at');
            
            // Unique : un review par reviewer per reviewee per transaction
            $table->unique(['reviewer_id', 'reviewee_id', 'listing_id']);
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};