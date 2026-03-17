<?php

namespace Database\Seeders;

use App\Models\Center;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TelcAccessSeeder extends Seeder
{
    public function run()
    {
        $this->seedSuperAdmins();

        $centersByName = $this->seedCenters();

        $this->seedUsers($centersByName);
    }

    private function seedSuperAdmins(): void
    {
        $superAdmins = [
            [
                'name' => 'Tsafack Aubin Julio',
                'email' => 'tsafackjulio56@gmail.com',
                'phone' => '+49 162 9245146',
                'password' => 'Tsa@ck2024!',
            ],
            [
                'name' => 'Tekazam Dylane',
                'email' => 'tekazamdylane@gmail.com',
                'phone' => '+49 178 6909834',
                'password' => 'Tek@zam2024!',
            ],
        ];

        foreach ($superAdmins as $definition) {
            $email = $this->normalizeEmail($definition['email']);

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $definition['name'],
                    'email' => $email,
                    'phone' => $this->normalizePhone($definition['phone']),
                    'role' => User::ROLE_SUPER_ADMIN,
                    'center_id' => null,
                    'is_blocked' => false,
                    'blocked_at' => null,
                    'password' => Hash::make($definition['password']),
                ]
            );

            if (User::supportsCenterAssignments()) {
                $user->centers()->detach();
            }
        }
    }

    /**
     * @return array<string, \App\Models\Center>
     */
    private function seedCenters(): array
    {
        $definitions = [
            'Der Beste Betreuer Bafoussam' => [
                'city' => 'Bafoussam',
                'country' => 'CM',
            ],
            'Der Beste Betreuer Yaounde Accacia' => [
                'city' => 'Yaounde',
                'country' => 'CM',
            ],
            'Der Beste Betreuer Centre de PK9' => [
                'city' => 'Douala',
                'country' => 'CM',
            ],
            'Der Beste Betreuer Douala Ndogpassi' => [
                'city' => 'Douala',
                'country' => 'CM',
            ],
            'Der Beste Betreuer Deido' => [
                'city' => 'Douala',
                'country' => 'CM',
            ],
            'Der Beste Betreuer Douala' => [
                'city' => 'Douala',
                'country' => 'CM',
            ],
            'TARA Institut Manbanda' => [
                'city' => 'Douala',
                'country' => 'CM',
            ],
            'TARA Institut Yassa' => [
                'city' => 'Douala',
                'country' => 'CM',
            ],
            'TARA Institut Ndobo' => [
                'city' => 'Douala',
                'country' => 'CM',
            ],
            'TARA Institut Yaounde Accacia' => [
                'city' => 'Yaounde',
                'country' => 'CM',
            ],
            'TARA Institut' => [
                'city' => 'Douala',
                'country' => 'CM',
            ],
            'Fit Institut Dschang' => [
                'city' => 'Dschang',
                'country' => 'CM',
            ],
        ];

        $aliases = [
            'Douala Deido Grand Moulin' => 'Der Beste Betreuer Deido',
        ];

        $existingCenters = Center::query()->get();
        $existingByKey = [];
        $usedCodes = [];

        foreach ($existingCenters as $center) {
            $existingByKey[$this->centerKey($center->name)] = $center;
            $usedCodes[strtoupper((string) $center->code)] = true;
        }

        foreach ($aliases as $alias => $canonical) {
            $this->mergeCenterAlias($alias, $canonical, $existingByKey);
        }

        $centersByName = [];

        foreach ($definitions as $name => $data) {
            $key = $this->centerKey($name);

            if (isset($existingByKey[$key])) {
                $centersByName[$name] = $existingByKey[$key];
                continue;
            }

            $code = $this->uniqueCenterCode($name, $usedCodes);

            $center = Center::create([
                'name' => $name,
                'code' => $code,
                'city' => $data['city'] ?? null,
                'country' => $data['country'] ?? 'CM',
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? null,
                'is_active' => true,
            ]);

            $existingByKey[$key] = $center;
            $centersByName[$name] = $center;
            $usedCodes[strtoupper($code)] = true;
        }

        return $centersByName;
    }

    /**
     * @param array<string, \App\Models\Center> $centersByName
     */
    private function seedUsers(array $centersByName): void
    {
        $defaultPassword = env('SEED_DEFAULT_USER_PASSWORD', 'ChangeMe2024!');

        $derBesteDoualaCenters = [
            'Der Beste Betreuer Centre de PK9',
            'Der Beste Betreuer Douala Ndogpassi',
            'Der Beste Betreuer Deido',
        ];

        $taraDoualaCenters = [
            'TARA Institut Manbanda',
            'TARA Institut Yassa',
            'TARA Institut Ndobo',
        ];

        $users = [
            [
                'name' => 'Djeukwe Tatla Alain',
                'email' => 'tatlaalain106@gmail.com',
                'phone' => '698779051',
                'role' => User::ROLE_CENTER_ADMIN,
                'centers' => ['Der Beste Betreuer Bafoussam'],
            ],
            [
                'name' => 'Mbuh Eden Mengwi',
                'email' => 'mbuhmengwi802@gmail.com',
                'phone' => '697_57_51_51',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['Der Beste Betreuer Bafoussam'],
            ],
            [
                'name' => 'DjouBou Saurelle',
                'email' => 'Saurelledjoubou@icloud.com',
                'phone' => '680367212',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['TARA Institut Manbanda'],
            ],
            [
                'name' => 'Stela Makoudem',
                'email' => 'makoudemstella@gmail.com',
                'phone' => '697993433',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['TARA Institut Yassa'],
            ],
            [
                'name' => 'Djoute Reine',
                'email' => 'reinedjoute79@gmail.com',
                'phone' => '658 09 39 33',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['TARA Institut Ndobo'],
            ],
            [
                'name' => 'Idriss Jose LELE',
                'email' => 'josenkoa@gmail.com',
                'phone' => '+237 6 51 12 13 73',
                'role' => User::ROLE_CENTER_ADMIN,
                'centers' => ['Der Beste Betreuer Yaounde Accacia'],
            ],
            [
                'name' => 'Monika Alida Ngo Kelbe N.',
                'email' => 'kelbemonalida@gmail.com',
                'phone' => '654198381',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['Der Beste Betreuer Deido'],
            ],
            [
                'name' => 'Bassong Liza Ornella Danielle',
                'email' => 'ornellabassong@icloud.com',
                'phone' => '691782125',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['Der Beste Betreuer Centre de PK9'],
            ],
            [
                'name' => 'Bedjeme Majurine Effa',
                'email' => 'majurineeffabedjeme@gmail.com',
                'phone' => '673179490',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['TARA Institut Yaounde Accacia'],
            ],
            [
                'name' => 'Marc Francky Ipend',
                'email' => 'franckyipend1@gmail.com',
                'phone' => '691846364',
                'role' => User::ROLE_CENTER_ADMIN,
                'centers' => array_merge(['Der Beste Betreuer Douala'], $derBesteDoualaCenters),
            ],
            [
                'name' => 'Raoul Lekane',
                'email' => 'rlekane54@gmail.com',
                'phone' => '672659183',
                'role' => User::ROLE_CENTER_ADMIN,
                'centers' => array_merge(['TARA Institut'], $taraDoualaCenters),
            ],
            [
                'name' => 'Nkomba Joelle',
                'email' => 'joellesand18@gmail.com',
                'phone' => '680616316',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['Der Beste Betreuer Douala Ndogpassi'],
            ],
            [
                'name' => 'Tsamo Ivanie',
                'email' => 'tsamoivanie@icloud.com',
                'phone' => '690182270',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['Der Beste Betreuer Yaounde Accacia'],
            ],
            [
                'name' => 'Nguimfack Orchelle',
                'email' => 'orchelleruthnguimfack@gmail.com',
                'phone' => '655 50 94 96',
                'role' => User::ROLE_CENTER_ADMIN,
                'centers' => ['Fit Institut Dschang'],
            ],
            [
                'name' => 'Ghenboh Fountchouo Manuella',
                'email' => 'ghenbohmanuella@gmail.com',
                'phone' => '681764486',
                'role' => User::ROLE_SECRETARY,
                'centers' => ['Fit Institut Dschang'],
            ],
            [
                'name' => 'Momo Jordan',
                'email' => 'jm335111@gmail.com',
                'phone' => '+237 90482016',
                'role' => User::ROLE_CENTER_ADMIN,
                'centers' => ['Der Beste Betreuer Deido'],
            ],
        ];

        foreach ($users as $definition) {
            $centerIds = $this->resolveCenterIds($centersByName, $definition['centers']);

            $email = $this->normalizeEmail($definition['email']);

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $definition['name'],
                    'email' => $email,
                    'phone' => $this->normalizePhone($definition['phone'] ?? null),
                    'role' => $definition['role'],
                    'center_id' => $centerIds[0] ?? null,
                    'password' => Hash::make($defaultPassword),
                    'is_blocked' => false,
                    'blocked_at' => null,
                ]
            );

            if ($centerIds && User::supportsCenterAssignments()) {
                $user->centers()->sync($centerIds);
            }
        }
    }

    /**
     * @param array<string, \App\Models\Center> $centersByName
     * @param string[] $centerNames
     * @return int[]
     */
    private function resolveCenterIds(array $centersByName, array $centerNames): array
    {
        $ids = [];
        $missing = [];

        foreach ($centerNames as $name) {
            if (! isset($centersByName[$name])) {
                $missing[] = $name;
                continue;
            }

            $ids[] = $centersByName[$name]->id;
        }

        if (! empty($missing) && $this->command) {
            $this->command->warn('Centres introuvables pour utilisateur: ' . implode(', ', $missing));
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * @param array<string, \App\Models\Center> $existingByKey
     */
    private function mergeCenterAlias(string $alias, string $canonical, array &$existingByKey): void
    {
        $aliasKey = $this->centerKey($alias);
        $canonicalKey = $this->centerKey($canonical);

        if (! isset($existingByKey[$aliasKey])) {
            return;
        }

        $aliasCenter = $existingByKey[$aliasKey];

        if (isset($existingByKey[$canonicalKey]) && $existingByKey[$canonicalKey]->id !== $aliasCenter->id) {
            $canonicalCenter = $existingByKey[$canonicalKey];
            $this->reassignCenterRelations($aliasCenter->id, $canonicalCenter->id);
            $aliasCenter->delete();
            unset($existingByKey[$aliasKey]);

            return;
        }

        if ($aliasCenter->name !== $canonical) {
            $aliasCenter->update(['name' => $canonical]);
        }

        $existingByKey[$canonicalKey] = $aliasCenter;
        unset($existingByKey[$aliasKey]);
    }

    private function reassignCenterRelations(int $fromCenterId, int $toCenterId): void
    {
        $tables = ['users', 'students', 'registrations', 'bank_deposits', 'exam_sessions'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'center_id')) {
                DB::table($table)->where('center_id', $fromCenterId)->update(['center_id' => $toCenterId]);
            }
        }

        if (Schema::hasTable('center_user')) {
            $rows = DB::table('center_user')->where('center_id', $fromCenterId)->get(['user_id']);

            foreach ($rows as $row) {
                DB::table('center_user')->updateOrInsert(
                    ['center_id' => $toCenterId, 'user_id' => $row->user_id],
                    ['center_id' => $toCenterId, 'user_id' => $row->user_id]
                );
            }

            DB::table('center_user')->where('center_id', $fromCenterId)->delete();
        }

        if (Schema::hasTable('exam_session_centers')) {
            $rows = DB::table('exam_session_centers')->where('center_id', $fromCenterId)->get(['exam_session_id']);

            foreach ($rows as $row) {
                DB::table('exam_session_centers')->updateOrInsert(
                    ['center_id' => $toCenterId, 'exam_session_id' => $row->exam_session_id],
                    ['center_id' => $toCenterId, 'exam_session_id' => $row->exam_session_id]
                );
            }

            DB::table('exam_session_centers')->where('center_id', $fromCenterId)->delete();
        }
    }

    private function uniqueCenterCode(string $name, array &$usedCodes): string
    {
        $base = strtoupper((string) Str::slug($name, '-'));
        $base = $base !== '' ? $base : 'CENTER';

        $code = substr($base, 0, 50);
        $suffix = 1;

        while (isset($usedCodes[$code])) {
            $suffix++;
            $suffixLabel = '-' . $suffix;
            $maxBaseLength = 50 - strlen($suffixLabel);
            $code = substr($base, 0, $maxBaseLength) . $suffixLabel;
        }

        $usedCodes[$code] = true;

        return $code;
    }

    private function centerKey(string $name): string
    {
        $normalized = strtolower(Str::ascii($name));
        $normalized = preg_replace('/[^a-z0-9]+/', '', $normalized);

        return $normalized ?? strtolower($name);
    }

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        $phone = trim($phone);
        $phone = preg_replace('/(?!^\+)[^\d]/', '', $phone);

        return $phone !== '' ? $phone : null;
    }
}
