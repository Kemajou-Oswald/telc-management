<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAudit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->seedSuperAdmins();
        $this->call(TelcAccessSeeder::class);
    }

    private function seedSuperAdmins(): void
    {
        $definitions = collect([
            [
                'name' => env('SEED_SUPER_ADMIN_NAME'),
                'email' => env('SEED_SUPER_ADMIN_EMAIL'),
                'password' => env('SEED_SUPER_ADMIN_PASSWORD'),
                'phone' => env('SEED_SUPER_ADMIN_PHONE'),
            ],
            [
                'name' => env('SEED_SUPER_ADMIN_2_NAME'),
                'email' => env('SEED_SUPER_ADMIN_2_EMAIL'),
                'password' => env('SEED_SUPER_ADMIN_2_PASSWORD'),
                'phone' => env('SEED_SUPER_ADMIN_2_PHONE'),
            ],
        ])->filter(function (array $definition) {
            return ! empty($definition['email']) && ! empty($definition['password']);
        });

        if ($definitions->isEmpty()) {
            $this->command?->warn('Aucun super admin seedé. Définissez SEED_SUPER_ADMIN_EMAIL et SEED_SUPER_ADMIN_PASSWORD si nécessaire.');

            return;
        }

        foreach ($definitions as $definition) {
            $user = User::firstOrCreate(
                ['email' => $definition['email']],
                [
                    'name' => $definition['name'] ?: 'Super Admin',
                    'email' => $definition['email'],
                    'phone' => $definition['phone'],
                    'role' => User::ROLE_SUPER_ADMIN,
                    'center_id' => null,
                    'is_blocked' => false,
                    'blocked_at' => null,
                    'password' => Hash::make($definition['password']),
                ]
            );

            if ($user->wasRecentlyCreated) {
                UserAudit::create([
                    'affected_user_id' => $user->id,
                    'performed_by' => null,
                    'action' => 'created',
                    'details' => 'Compte super admin initialise pour la production.',
                ]);
            }
        }
    }
}
