<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Centre;
use App\Models\Level;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Créer les centres d'abord (indispensable pour les clés étrangères)
        $c1 = Centre::create(['name' => 'CFPL TARA MAMBANDA', 'city' => 'Douala']);
        $c2 = Centre::create(['name' => 'BONABERI NDOBO', 'city' => 'Douala']);

        // 2. Créer les niveaux
        $niveaux = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'Vorbereitung'];
        foreach ($niveaux as $n) {
            Level::create(['name' => $n]);
        }

        // 3. Créer les utilisateurs (Logins)
        $pwd = Hash::make('password123');

        // SUPER ADMIN
        User::create([
            'name' => 'Super Admin TARA',
            'email' => 'superadmin@tara.com',
            'password' => $pwd,
            'role' => 'super_admin',
            'centre_id' => $c1->id
        ]);

        // DIRECTEUR
        User::create([
            'name' => 'Directeur',
            'email' => 'directeur@tara.com',
            'password' => $pwd,
            'role' => 'directeur',
            'centre_id' => $c1->id
        ]);

        // SECRÉTAIRES
        User::create(['name' => 'Secrétaire 1', 'email' => 'sec1@tara.com', 'password' => $pwd, 'role' => 'secretaire', 'centre_id' => $c1->id]);
        User::create(['name' => 'Secrétaire 2', 'email' => 'sec2@tara.com', 'password' => $pwd, 'role' => 'secretaire', 'centre_id' => $c2->id]);

        echo "\n [OK] Utilisateurs créés avec succès ! \n";
    }
}