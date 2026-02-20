<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('category');
            $table->text('description')->nullable();
            $table->json('specifications')->nullable(); // Important pour les specs
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'maintenance', 'occupied', 'inactive'])->default('available');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};