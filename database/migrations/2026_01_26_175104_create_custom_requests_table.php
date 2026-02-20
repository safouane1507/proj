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
    Schema::create('custom_requests', function (Blueprint $table) {
        $table->id();
        // Relie la demande à l'utilisateur qui l'a faite
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Les détails de la configuration voulue
        $table->string('type');      // Ex: Serveur de calcul, VM Linux...
        $table->string('cpu');       // Ex: 8 Cores
        $table->string('ram');       // Ex: 32 GB
        $table->string('storage');   // Ex: 1 TB SSD
        
        $table->text('justification'); // Pourquoi il a besoin de ça ?
        
        // Statut : 'pending' (en attente), 'approved' (accepté), 'rejected' (refusé)
        $table->string('status')->default('pending'); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_requests');
    }
};
