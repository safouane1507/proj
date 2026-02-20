<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            // État physique/opérationnel de la ressource (distinct du status de disponibilité)
            $table->enum('condition', ['bon', 'dégradé', 'critique'])->default('bon')->after('status');
            // Notes internes visibles uniquement par les managers/admins
            $table->text('internal_notes')->nullable()->after('condition');
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['condition', 'internal_notes']);
        });
    }
};
