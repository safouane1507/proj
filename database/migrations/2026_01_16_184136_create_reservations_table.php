<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // L'utilisateur qui réserve
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // La ressource réservée
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');
            
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('justification');
            
            // Cycle de vie : En attente -> Approuvée/Refusée -> Active -> Terminée
            $table->enum('status', ['pending', 'approved', 'rejected', 'active', 'completed'])
                  ->default('pending');
            
            // Note du responsable (ex: motif du refus)
            $table->text('manager_feedback')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};