<?php

namespace Database\Seeders;

use App\Models\Center;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        if (! filter_var(env('SEED_DEMO_USERS', false), FILTER_VALIDATE_BOOL)) {
            $this->command?->warn('UsersSeeder ignoré. Activez SEED_DEMO_USERS=true pour créer des comptes de démonstration.');

            return;
        }

        $centers = Center::query()->where('is_active', true)->orderBy('name')->get();

        if ($centers->isEmpty()) {
            $this->command?->warn('UsersSeeder ignoré : aucun centre actif disponible.');

            return;
        }

        $demoPassword = env('SEED_DEMO_USERS_PASSWORD', 'ChangeMeBeforeProd123!');

        foreach ($centers->take(3) as $index => $center) {
            $admin = User::firstOrCreate(
                ['email' => sprintf('admin.%s@demo.local', $center->code ?: $center->id)],
                [
                    'name' => 'Admin ' . $center->name,
                    'phone' => null,
                    'role' => User::ROLE_CENTER_ADMIN,
                    'center_id' => $center->id,
                    'password' => Hash::make($demoPassword),
                    'is_blocked' => false,
                ]
            );

            if (User::supportsCenterAssignments()) {
                $admin->centers()->syncWithoutDetaching([$center->id]);
            }

            $secretary = User::firstOrCreate(
                ['email' => sprintf('secretary.%s@demo.local', $center->code ?: $center->id)],
                [
                    'name' => 'Secretaire ' . $center->name,
                    'phone' => null,
                    'role' => User::ROLE_SECRETARY,
                    'center_id' => $center->id,
                    'password' => Hash::make($demoPassword),
                    'is_blocked' => false,
                ]
            );

            if (User::supportsCenterAssignments()) {
                $secretary->centers()->syncWithoutDetaching([$center->id]);
            }
        }

        $this->command?->info('Comptes de démonstration créés avec un mot de passe temporaire. Pensez à le changer avant toute exposition.');
    }
}
