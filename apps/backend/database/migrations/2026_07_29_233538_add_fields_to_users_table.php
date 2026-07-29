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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('avatar_url')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('avatar_url');
            $table->enum('user_type', ['particulier', 'boutique', 'revendeur', 'reparateur', 'technicien'])->default('particulier')->after('bio');
            $table->boolean('is_verified')->default(false)->after('user_type');
            $table->boolean('is_active')->default(true)->after('is_verified');
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar_url', 'bio', 'user_type', 'is_verified', 'is_active']);
        });
    }
};