<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\User;

class ResourceSeeder extends Seeder
{
   public function run()
{
    $manager = \App\Models\User::where('role', 'manager')->first();
    
    $categories = ['Serveur Physique', 'Machine Virtuelle', 'Stockage', 'Réseau'];
    
    for ($i = 1; $i <= 20; $i++) {
        \App\Models\Resource::create([
            'label' => 'Equipement #' . $i,
            'category' => $categories[array_rand($categories)],
            'location' => 'Baie ' . chr(rand(65, 70)) . rand(1, 10),
            'description' => 'Description automatique pour la ressource technique numéro ' . $i,
            'status' => 'available',
            'manager_id' => $manager->id ?? 1,
        ]);
    }
}
}