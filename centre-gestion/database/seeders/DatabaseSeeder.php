<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    User,
    Level,
    Centre,
    TuitionFee,
    Group,
    Student,
    Payment,
    CashBook,
    CashTransaction,
    Product,
    SupportRequest,
    StudentAttendance,
    StockMovement,
    TeacherAttendance,
    Expense
};
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Référentiels de base
        $levels = $this->seedLevels();
        $centres = $this->seedCentres();
        $centresById = [];
        foreach ($centres as $centre) {
            $centresById[$centre->id] = $centre;
        }

        $users = $this->seedUsers($centres);

        // 2. Tarifs par niveau / langue / centre
        $tuitionFees = $this->seedTuitionFees($levels, $centres);

        // 3. Groupes complets
        $groupsData = $this->seedGroups($centres, $levels, $users['profsByCentreLang']);

        // 4. Produits & stock
        $this->seedProductsAndStock($centres, $users['secretairesByCentre']);

        // 5. Étudiants massifs
        $students = $this->seedStudents(
            $centres,
            $levels,
            $groupsData['byCentre'],
            $tuitionFees['byLevelLanguage']
        );

        // 6. Paiements complets
        $payments = $this->seedPayments($students);

        // 7. Présences étudiants & profs
        $attendanceData = $this->seedStudentAttendances($students, $groupsData['byId']);
        $this->seedTeacherAttendances(
            $groupsData['byId'],
            $attendanceData['groupDates'],
            $users['validator']
        );

        // 8. Dépenses & tickets support
        $expenses = $this->seedExpenses($centres);
        $this->seedSupportRequests($centres, $users);

        // 9. Caisse journalière avec liens vers paiements / dépenses
        $this->seedCashBooksAndTransactions($centresById, $payments, $expenses);

        echo "\n [✓] BASE DE DONNÉES DÉMO GÉNÉRÉE (connexion : superadmin@tara.com / password123) \n";
    }

    /**
     * Niveaux (A1, A2, B1...) avec descriptions.
     */
    private function seedLevels(): array
    {
        $definitions = [
            'A1' => 'Débutant complet',
            'A2' => 'Faux débutant',
            'B1' => 'Intermédiaire',
            'B2' => 'Intermédiaire avancé',
            'C1' => 'Avancé',
            'C2' => 'Maîtrise de la langue',
            'Vorbereitung' => 'Préparation examen (Vorbereitung Telc)',
        ];

        $levels = [];
        foreach ($definitions as $code => $description) {
            $levels[$code] = Level::create([
                'name' => $code,
                'description' => $description,
            ]);
        }

        return $levels;
    }

    /**
     * Centres physiques.
     */
    private function seedCentres(): array
    {
        $data = [
            [
                'name' => 'CFPL TARA MAMBANDA',
                'city' => 'Douala',
                'country' => 'Cameroun',
                'phone' => '+237 655 00 01 01',
                'email' => 'mambanda@tara.com',
            ],
            [
                'name' => 'BONABERI NDOBO',
                'city' => 'Douala',
                'country' => 'Cameroun',
                'phone' => '+237 655 00 01 02',
                'email' => 'ndobo@tara.com',
            ],
            [
                'name' => 'NEPTUNE YASSA',
                'city' => 'Douala',
                'country' => 'Cameroun',
                'phone' => '+237 655 00 01 03',
                'email' => 'yassa@tara.com',
            ],
            [
                'name' => 'BIYEMASSI ACASSIA',
                'city' => 'Yaoundé',
                'country' => 'Cameroun',
                'phone' => '+237 655 00 01 04',
                'email' => 'biyemassi@tara.com',
            ],
        ];

        $centres = [];
        foreach ($data as $centreData) {
            $centres[] = Centre::create($centreData);
        }

        return $centres;
    }

    /**
     * Utilisateurs : super admin, directeur, secrétaires, professeurs.
     *
     * Retourne un tableau contenant notamment:
     * - superAdmin
     * - directeur
     * - secretairesByCentre[centre_id][]
     * - profsByCentreLang[centre_id][language][]
     * - validator (utilisé pour valider les présences prof)
     */
    private function seedUsers(array $centres): array
    {
        $pwd = Hash::make('password123');
        $mainCentre = $centres[0];

        $superAdmin = User::create([
            'name' => 'Super Admin TARA',
            'email' => 'superadmin@tara.com',
            'password' => $pwd,
            'role' => 'super_admin',
            'centre_id' => $mainCentre->id,
        ]);

        $directeur = User::create([
            'name' => 'Directeur',
            'email' => 'directeur@tara.com',
            'password' => $pwd,
            'role' => 'directeur',
            'centre_id' => $mainCentre->id,
        ]);

        $secretairesByCentre = [];
        $secretaireIndex = 1;

        foreach ($centres as $centre) {
            $count = $centre->id === $mainCentre->id ? 2 : 1;
            for ($i = 1; $i <= $count; $i++) {
                $secretaire = User::create([
                    'name' => 'Secrétaire ' . $centre->name . ' #' . $i,
                    'email' => 'sec' . $secretaireIndex . '@tara.com',
                    'password' => $pwd,
                    'role' => 'secretaire',
                    'centre_id' => $centre->id,
                ]);
                $secretairesByCentre[$centre->id][] = $secretaire;
                $secretaireIndex++;
            }
        }

        $langs = ['Allemand', 'Anglais', 'Italien'];
        $profsByCentreLang = [];
        $profIndex = 1;

        foreach ($centres as $centre) {
            foreach ($langs as $lang) {
                $nbProfs = 2;
                for ($i = 1; $i <= $nbProfs; $i++) {
                    $email = 'prof' . $profIndex . '.' . strtolower(str_replace(' ', '', $lang)) . '.c' . $centre->id . '@tara.com';
                    $prof = User::create([
                        'name' => 'Prof ' . $lang . ' ' . $centre->name . ' #' . $i,
                        'email' => $email,
                        'password' => $pwd,
                        'role' => 'professeur',
                        'centre_id' => $centre->id,
                    ]);
                    $profsByCentreLang[$centre->id][$lang][] = $prof;
                    $profIndex++;
                }
            }
        }

        return [
            'superAdmin' => $superAdmin,
            'directeur' => $directeur,
            'secretairesByCentre' => $secretairesByCentre,
            'profsByCentreLang' => $profsByCentreLang,
            'validator' => $directeur ?: $superAdmin,
        ];
    }

    /**
     * Tarifs par niveau / langue, éventuellement spécifiques à un centre.
     *
     * Retourne:
     * - all: liste de tous les TuitionFee
     * - byLevelLanguage[level_id][language][]: pour retrouver facilement un tarif.
     */
    private function seedTuitionFees(array $levels, array $centres): array
    {
        $languages = array_keys(TuitionFee::languageOptions());

        $baseTotals = [
            'A1' => 130000,
            'A2' => 140000,
            'B1' => 150000,
            'B2' => 160000,
            'C1' => 180000,
            'C2' => 200000,
            'Vorbereitung' => 220000,
        ];

        $fees = [];
        $byLevelLanguage = [];

        foreach ($levels as $code => $level) {
            foreach ($languages as $language) {
                $total = $baseTotals[$code] ?? 150000;
                $inscription = 10000 + (rand(0, 1) ? 5000 : 0);

                $durationWeeks = 12;
                if (in_array($code, ['B1', 'B2'], true)) {
                    $durationWeeks = 10;
                } elseif (in_array($code, ['C1', 'C2'], true)) {
                    $durationWeeks = 8;
                } elseif ($code === 'Vorbereitung') {
                    $durationWeeks = 6;
                }

                $durationLabel = $durationWeeks >= 12
                    ? '3 mois'
                    : ($durationWeeks >= 8 ? '2 mois' : '1 mois');

                $courseType = $code === 'Vorbereitung' ? 'vorbereitung' : 'standard';

                // Tarif global (centre_id = null)
                $fee = TuitionFee::create([
                    'centre_id' => null,
                    'level_id' => $level->id,
                    'language' => $language,
                    'label' => $code . ' ' . $language . ' standard',
                    'total_amount' => $total,
                    'inscription_fee' => $inscription,
                    'currency' => 'FCFA',
                    'duration_weeks' => $durationWeeks,
                    'duration_label' => $durationLabel,
                    'course_type' => $courseType,
                    'is_active' => true,
                ]);

                $fees[] = $fee;
                $byLevelLanguage[$level->id][$language][] = $fee;

                // De temps en temps, un tarif spécifique à un centre
                if (rand(1, 100) <= 25) {
                    $centre = $centres[array_rand($centres)];
                    $feeCentre = TuitionFee::create([
                        'centre_id' => $centre->id,
                        'level_id' => $level->id,
                        'language' => $language,
                        'label' => $code . ' ' . $language . ' - ' . $centre->city,
                        'total_amount' => $total + rand(-10000, 10000),
                        'inscription_fee' => $inscription,
                        'currency' => 'FCFA',
                        'duration_weeks' => $durationWeeks,
                        'duration_label' => $durationLabel,
                        'course_type' => $courseType,
                        'is_active' => (bool) rand(0, 1),
                    ]);

                    $fees[] = $feeCentre;
                    $byLevelLanguage[$level->id][$language][] = $feeCentre;
                }
            }
        }

        return [
            'all' => $fees,
            'byLevelLanguage' => $byLevelLanguage,
        ];
    }

    /**
     * Groupes avec capacité, langue, type et horaires.
     *
     * Retourne:
     * - all: liste de tous les groupes
     * - byCentre[centre_id][]
     * - byId[id]
     */
    private function seedGroups(array $centres, array $levels, array $profsByCentreLang): array
    {
        $groups = [];
        $byCentre = [];
        $byId = [];

        $schedules = [
            'matin' => [
                'days' => ['Lun', 'Mer', 'Ven'],
                'start' => '08:00:00',
                'end' => '10:00:00',
            ],
            'apresmidi' => [
                'days' => ['Mar', 'Jeu'],
                'start' => '14:00:00',
                'end' => '16:00:00',
            ],
            'soir' => [
                'days' => ['Lun', 'Mer', 'Ven'],
                'start' => '18:00:00',
                'end' => '20:00:00',
            ],
        ];

        $mainLevels = ['A1', 'A2', 'B1'];

        foreach ($centres as $centre) {
            foreach ($mainLevels as $levelCode) {
                if (!isset($levels[$levelCode])) {
                    continue;
                }

                $level = $levels[$levelCode];
                $languages = isset($profsByCentreLang[$centre->id])
                    ? array_keys($profsByCentreLang[$centre->id])
                    : [];

                foreach ($languages as $language) {
                    $nbGroups = rand(1, 3);
                    for ($i = 1; $i <= $nbGroups; $i++) {
                        $scheduleKey = array_rand($schedules);
                        $schedule = $schedules[$scheduleKey];

                        $teachers = $profsByCentreLang[$centre->id][$language] ?? [];
                        if (empty($teachers)) {
                            continue;
                        }
                        $teacher = $teachers[array_rand($teachers)];

                        $type = $levelCode === 'Vorbereitung' ? 'vorbereitung' : 'standard';
                        $status = rand(1, 10) <= 8 ? 'active' : 'archived';

                        $group = Group::create([
                            'name' => $levelCode . '-' . substr($language, 0, 2) . '-' . ucfirst($scheduleKey) . '-' . $i,
                            'centre_id' => $centre->id,
                            'level_id' => $level->id,
                            'teacher_id' => $teacher->id,
                            'capacity' => rand(15, 25),
                            'status' => $status,
                            'language' => $language,
                            'type' => $type,
                            'days' => $schedule['days'],
                            'start_time' => $schedule['start'],
                            'end_time' => $schedule['end'],
                        ]);

                        $groups[] = $group;
                        $byCentre[$centre->id][] = $group;
                        $byId[$group->id] = $group;
                    }
                }
            }
        }

        return [
            'all' => $groups,
            'byCentre' => $byCentre,
            'byId' => $byId,
        ];
    }

    /**
     * Produits par centre + mouvements de stock cohérents.
     */
    private function seedProductsAndStock(array $centres, array $secretairesByCentre): void
    {
        $templates = [
            ['name' => 'Livre Allemand A1', 'sku' => 'BK-DE-A1', 'min_stock' => 10],
            ['name' => 'Livre Anglais B1', 'sku' => 'BK-EN-B1', 'min_stock' => 8],
            ['name' => "Kit d'inscription", 'sku' => 'KIT-INS', 'min_stock' => 20],
            ['name' => "Manuel d'exercices", 'sku' => 'BK-PRAT', 'min_stock' => 5],
        ];

        foreach ($centres as $centre) {
            $creator = null;
            if (!empty($secretairesByCentre[$centre->id])) {
                $creator = $secretairesByCentre[$centre->id][array_rand($secretairesByCentre[$centre->id])];
            }

            foreach ($templates as $template) {
                $product = Product::create([
                    'centre_id' => $centre->id,
                    'name' => $template['name'] . ' - ' . $centre->name,
                    'sku' => $template['sku'] . '-' . $centre->id,
                    'unit' => 'pièce',
                    'min_stock' => $template['min_stock'],
                ]);

                $initialQty = rand(30, 80);

                StockMovement::create([
                    'product_id' => $product->id,
                    'centre_id' => $centre->id,
                    'type' => 'in',
                    'quantity' => $initialQty,
                    'label' => 'Stock initial',
                    'created_by' => $creator ? $creator->id : null,
                ]);

                $nbOut = rand(1, 3);
                for ($i = 0; $i < $nbOut; $i++) {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'centre_id' => $centre->id,
                        'type' => 'out',
                        'quantity' => rand(1, 10),
                        'label' => 'Sortie stock (kits remis)',
                        'created_by' => $creator ? $creator->id : null,
                    ]);
                }

                if (rand(1, 100) <= 20) {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'centre_id' => $centre->id,
                        'type' => 'adjust',
                        'quantity' => rand(-3, 3),
                        'label' => 'Ajustement inventaire',
                        'created_by' => $creator ? $creator->id : null,
                    ]);
                }
            }
        }
    }

    /**
     * Génère ~300 étudiants répartis sur tous les centres / groupes / tarifs.
     */
    private function seedStudents(array $centres, array $levels, array $groupsByCentre, array $tuitionByLevelLanguage): array
    {
        $firstNames = ['Jean', 'Marie', 'Paul', 'Aline', 'Brice', 'Luc', 'Nadine', 'Samuel', 'Estelle', 'Kevin', 'Sandra', 'Lionel', 'Olga', 'Patrice', 'Ines'];
        $lastNames = ['Tchoumi', 'Nguekam', 'Nana', 'Mbappe', 'Ndongo', 'Ekanga', 'Fofana', 'Kamdem', 'Talla', 'Awono'];

        $statuses = ['actif', 'actif', 'actif', 'suspendu', 'abandonne'];
        $transferReasons = [
            'Transfert vers groupe du soir pour contraintes horaires.',
            'Transfert vers niveau supérieur après validation du test.',
            'Changement de centre pour rapprochement du domicile.',
            'Reprise après suspension pour raisons personnelles.',
        ];

        $students = [];
        $nbStudents = 320;

        for ($i = 1; $i <= $nbStudents; $i++) {
            // On choisit d’abord un centre qui a des groupes
            $centre = null;
            $tries = 0;
            while ($centre === null && $tries < 10) {
                $candidate = $centres[array_rand($centres)];
                if (!empty($groupsByCentre[$candidate->id])) {
                    $centre = $candidate;
                }
                $tries++;
            }
            if ($centre === null) {
                // Aucun groupe disponible : on arrête proprement
                break;
            }

            $group = $groupsByCentre[$centre->id][array_rand($groupsByCentre[$centre->id])];
            $levelId = $group->level_id;
            $language = $group->language;

            $fee = null;
            if (isset($tuitionByLevelLanguage[$levelId][$language])) {
                $fee = $tuitionByLevelLanguage[$levelId][$language][array_rand($tuitionByLevelLanguage[$levelId][$language])];
            } else {
                // Fallback: n'importe quel tarif de ce niveau
                if (isset($tuitionByLevelLanguage[$levelId])) {
                    $someLang = array_key_first($tuitionByLevelLanguage[$levelId]);
                    $fee = $tuitionByLevelLanguage[$levelId][$someLang][0];
                }
            }
            if (!$fee) {
                continue;
            }

            $regDate = Carbon::now()->subDays(rand(15, 120));

            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];

            $status = $statuses[array_rand($statuses)];
            $lastTransferReason = null;
            if (rand(1, 100) <= 15) {
                $lastTransferReason = $transferReasons[array_rand($transferReasons)];
            }

            $student = Student::create([
                'centre_id' => $centre->id,
                'level_id' => $levelId,
                'group_id' => $group->id,
                'tuition_fee_id' => $fee->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => '6' . rand(5, 9) . sprintf('%07d', $i),
                'email' => 'etudiant' . sprintf('%04d', $i) . '@tara.test',
                'registration_date' => $regDate->toDateString(),
                'status' => $status,
                'last_transfer_reason' => $lastTransferReason,
            ]);

            $students[] = $student;
        }

        return $students;
    }

    /**
     * Génère un historique de paiements pour chaque étudiant.
     *
     * Retourne la liste de tous les paiements créés.
     */
    private function seedPayments(array $students): array
    {
        $payments = [];

        $scenarios = [
            'none',
            'registration_only',
            'first_tranche',
            'full_paid',
            'irregular',
        ];

        foreach ($students as $student) {
            $fee = $student->tuitionFee;
            if (!$fee) {
                continue;
            }

            $scenario = $scenarios[array_rand($scenarios)];
            $created = $this->createPaymentsForStudent($student, $fee, $scenario);
            foreach ($created as $payment) {
                $payments[] = $payment;
            }
        }

        return $payments;
    }

    private function createPaymentsForStudent(Student $student, TuitionFee $fee, string $scenario): array
    {
        $inscription = (float) $fee->inscription_fee;
        $total = (float) $fee->total_amount;
        $pension = max(0, $total - $inscription);

        $targetReg = 0.0;
        $targetTuition = 0.0;
        $nbSlices = 0;

        switch ($scenario) {
            case 'registration_only':
                $targetReg = $inscription;
                $targetTuition = 0.0;
                $nbSlices = 1;
                break;
            case 'first_tranche':
                $targetReg = $inscription;
                $targetTuition = $pension / 2;
                $nbSlices = rand(2, 3);
                break;
            case 'full_paid':
                $targetReg = $inscription;
                $targetTuition = $pension;
                $nbSlices = rand(3, 4);
                break;
            case 'irregular':
                $targetReg = rand(0, 1) ? $inscription : round($inscription * 0.5);
                $targetTuition = round($pension * (rand(20, 90) / 100));
                $nbSlices = rand(1, 3);
                break;
            case 'none':
            default:
                return [];
        }

        if ($targetReg <= 0 && $targetTuition <= 0) {
            return [];
        }

        $payments = [];
        $start = Carbon::parse($student->registration_date);
        $end = $fee->duration_weeks
            ? (clone $start)->addWeeks((int) $fee->duration_weeks)
            : Carbon::now();
        $daysRange = max(1, $start->diffInDays($end));

        $remainingReg = $targetReg;
        $remainingTuition = $targetTuition;
        $cumTotal = 0.0;
        $tranche1Target = $fee->getTranche1Target();

        $modes = ['Espèces', 'Mobile Money', 'Banque'];

        for ($i = 1; $i <= $nbSlices; $i++) {
            $isLast = $i === $nbSlices;

            $regPart = 0.0;
            $tuitionPart = 0.0;

            if ($remainingReg > 0) {
                if ($isLast) {
                    $regPart = $remainingReg;
                } else {
                    $max = max(1, (int) ($remainingReg / 2));
                    $regPart = rand(1, $max);
                }
                $remainingReg -= $regPart;
            }

            if ($remainingTuition > 0) {
                if ($isLast) {
                    $tuitionPart = $remainingTuition;
                } else {
                    $max = max(1, (int) ($remainingTuition / 2));
                    $tuitionPart = rand(1, $max);
                }
                $remainingTuition -= $tuitionPart;
            }

            $amount = $regPart + $tuitionPart;
            if ($amount <= 0) {
                continue;
            }

            $offset = rand(0, $daysRange);
            $date = (clone $start)->addDays($offset);

            $cumTotal += $amount;
            $tranche = $cumTotal <= $tranche1Target ? 1 : 2;

            $mode = $modes[array_rand($modes)];
            $type = $regPart > 0 && $tuitionPart === 0.0 ? 'inscription' : 'mensualite';

            $payment = Payment::create([
                'student_id' => $student->id,
                'centre_id' => $student->centre_id,
                'tuition_fee_id' => $fee->id,
                'amount' => $amount,
                'amount_registration' => $regPart,
                'amount_tuition' => $tuitionPart,
                'mode' => $mode,
                'reference' => 'PAY-' . $student->id . '-' . $i,
                'type' => $type,
                'tranche' => $tranche,
                'payment_date' => $date->toDateString(),
                'month' => $date->format('m'),
                'year' => $date->format('Y'),
            ]);

            $payments[] = $payment;
        }

        return $payments;
    }

    /**
     * Présences étudiants (20–40 lignes par étudiant).
     *
     * Retourne:
     * - attendances: liste des StudentAttendance créés
     * - groupDates[group_id] = [dates...]
     */
    private function seedStudentAttendances(array $students, array $groupsById): array
    {
        $attendances = [];
        $groupDates = [];

        foreach ($students as $student) {
            if (!$student->group_id) {
                continue;
            }

            $group = $groupsById[$student->group_id] ?? null;
            if (!$group || !$group->teacher_id) {
                continue;
            }

            $fee = $student->tuitionFee;
            $start = Carbon::parse($student->registration_date);
            $end = $fee && $fee->duration_weeks
                ? (clone $start)->addWeeks((int) $fee->duration_weeks)
                : Carbon::now();
            $daysRange = max(1, $start->diffInDays($end));

            $nbSessions = rand(20, 40);
            for ($i = 0; $i < $nbSessions; $i++) {
                $offset = rand(0, $daysRange);
                $date = (clone $start)->addDays($offset)->toDateString();

                $present = rand(1, 10) <= 8;

                $attendance = StudentAttendance::create([
                    'student_id' => $student->id,
                    'group_id' => $group->id,
                    'teacher_id' => $group->teacher_id,
                    'date' => $date,
                    'present' => $present,
                ]);

                $attendances[] = $attendance;
                $groupDates[$group->id][$date] = true;
            }
        }

        $normalizedGroupDates = [];
        foreach ($groupDates as $groupId => $datesMap) {
            $normalizedGroupDates[$groupId] = array_keys($datesMap);
        }

        return [
            'attendances' => $attendances,
            'groupDates' => $normalizedGroupDates,
        ];
    }

    /**
     * Présences professeurs, alignées sur les dates des groupes.
     */
    private function seedTeacherAttendances(array $groupsById, array $groupDates, User $validator): void
    {
        foreach ($groupDates as $groupId => $dates) {
            $group = $groupsById[$groupId] ?? null;
            if (!$group || !$group->teacher_id) {
                continue;
            }

            foreach ($dates as $dateStr) {
                // Le prof peut parfois être absent
                if (rand(1, 10) <= 2) {
                    continue;
                }

                $baseTime = $group->start_time ?: '08:00:00';
                try {
                    $time = Carbon::createFromFormat('H:i:s', $baseTime);
                } catch (\Exception $e) {
                    $time = Carbon::createFromTime(8, 0, 0);
                }
                $arrival = $time->copy()->subMinutes(rand(5, 15));

                $validated = rand(1, 10) <= 8;

                TeacherAttendance::create([
                    'teacher_id' => $group->teacher_id,
                    'group_id' => $group->id,
                    'date' => $dateStr,
                    'arrival_time' => $arrival->format('H:i:s'),
                    'validated' => $validated,
                    'validated_by' => $validated ? $validator->id : null,
                ]);
            }
        }
    }

    /**
     * Dépenses de fonctionnement pour chaque centre sur plusieurs mois.
     *
     * Retourne la liste de toutes les dépenses créées.
     */
    private function seedExpenses(array $centres): array
    {
        $categories = ['loyer', 'salaire', 'marketing', 'materiel', 'autre'];
        $expenses = [];

        foreach ($centres as $centre) {
            for ($m = 0; $m < 4; $m++) {
                $monthDate = Carbon::now()->subMonths($m);
                $daysInMonth = $monthDate->daysInMonth;

                $nb = rand(3, 6);
                for ($i = 0; $i < $nb; $i++) {
                    $category = $categories[array_rand($categories)];

                    switch ($category) {
                        case 'loyer':
                            $amount = rand(250000, 400000);
                            break;
                        case 'salaire':
                            $amount = rand(300000, 800000);
                            break;
                        case 'marketing':
                            $amount = rand(50000, 200000);
                            break;
                        case 'materiel':
                            $amount = rand(30000, 150000);
                            break;
                        case 'autre':
                        default:
                            $amount = rand(20000, 100000);
                            break;
                    }

                    $day = rand(1, $daysInMonth);
                    $date = Carbon::create($monthDate->year, $monthDate->month, $day);

                    $expense = Expense::create([
                        'centre_id' => $centre->id,
                        'title' => ucfirst($category) . ' ' . $monthDate->format('m/Y'),
                        'amount' => $amount,
                        'category' => $category,
                        'expense_date' => $date->toDateString(),
                        'month' => $monthDate->format('m'),
                        'year' => $monthDate->format('Y'),
                    ]);

                    $expenses[] = $expense;
                }
            }
        }

        return $expenses;
    }

    /**
     * Tickets de support (stock, finance, technique...) pour chaque centre.
     */
    private function seedSupportRequests(array $centres, array $users): void
    {
        $categories = ['stock', 'finance', 'technique', 'autre'];
        $statuses = ['ouvert', 'en_cours', 'resolu'];

        $superAdmin = $users['superAdmin'];
        $directeur = $users['directeur'];
        $secretairesByCentre = $users['secretairesByCentre'];
        $profsByCentreLang = $users['profsByCentreLang'];

        foreach ($centres as $centre) {
            $creators = [];

            if (!empty($secretairesByCentre[$centre->id])) {
                $creators = array_merge($creators, $secretairesByCentre[$centre->id]);
            }

            if (!empty($profsByCentreLang[$centre->id])) {
                foreach ($profsByCentreLang[$centre->id] as $langProfs) {
                    $creators = array_merge($creators, $langProfs);
                }
            }

            if (empty($creators)) {
                $creators[] = $superAdmin;
            }

            $nb = rand(3, 7);
            for ($i = 0; $i < $nb; $i++) {
                $category = $categories[array_rand($categories)];
                $status = $statuses[array_rand($statuses)];

                $creator = $creators[array_rand($creators)];
                $createdAt = Carbon::now()->subDays(rand(3, 60));

                $resolvedBy = null;
                $resolvedAt = null;
                if ($status === 'resolu') {
                    $resolvedBy = $directeur ?: $superAdmin;
                    $resolvedAt = (clone $createdAt)->addDays(rand(1, 7));
                }

                SupportRequest::create([
                    'centre_id' => $centre->id,
                    'created_by' => $creator->id,
                    'category' => $category,
                    'title' => 'Ticket ' . strtoupper($category) . ' #' . ($i + 1) . ' - ' . $centre->city,
                    'description' => 'Demande de support concernant ' . $category . ' pour le centre ' . $centre->name . '.',
                    'status' => $status,
                    'resolved_by' => $resolvedBy ? $resolvedBy->id : null,
                    'resolved_at' => $resolvedAt,
                ]);
            }
        }
    }

    /**
     * Livre de caisse + transactions liées aux paiements et dépenses.
     */
    private function seedCashBooksAndTransactions(array $centresById, array $payments, array $expenses): void
    {
        $cashBooksByKey = [];
        $totals = [];

        // Transactions liées aux paiements (entrées)
        foreach ($payments as $payment) {
            $centreId = $payment->centre_id;
            if (!isset($centresById[$centreId])) {
                continue;
            }

            $centre = $centresById[$centreId];
            $dateStr = Carbon::parse($payment->payment_date)->toDateString();

            $cashBook = $this->getOrCreateCashBook($cashBooksByKey, $centre, $dateStr);
            $key = $centre->id . '|' . $dateStr;

            $student = $payment->student;
            $label = $student ? 'Paiement étudiant ' . $student->full_name : 'Paiement étudiant';

            CashTransaction::create([
                'cash_book_id' => $cashBook->id,
                'centre_id' => $centre->id,
                'direction' => 'entree',
                'amount' => $payment->amount,
                'label' => $label,
                'mode' => $payment->mode,
                'reference' => $payment->reference,
                'source_type' => Payment::class,
                'source_id' => $payment->id,
                'is_cancelled' => false,
                'cancelled_at' => null,
            ]);

            if (!isset($totals[$key])) {
                $totals[$key] = ['entrees' => 0.0, 'sorties' => 0.0];
            }
            $totals[$key]['entrees'] += (float) $payment->amount;
        }

        // Transactions liées aux dépenses (sorties)
        foreach ($expenses as $expense) {
            $centreId = $expense->centre_id;
            if (!isset($centresById[$centreId])) {
                continue;
            }

            $centre = $centresById[$centreId];
            $dateStr = Carbon::parse($expense->expense_date)->toDateString();

            $cashBook = $this->getOrCreateCashBook($cashBooksByKey, $centre, $dateStr);
            $key = $centre->id . '|' . $dateStr;

            CashTransaction::create([
                'cash_book_id' => $cashBook->id,
                'centre_id' => $centre->id,
                'direction' => 'sortie',
                'amount' => $expense->amount,
                'label' => 'Dépense : ' . $expense->title,
                'mode' => 'Espèces',
                'reference' => null,
                'source_type' => Expense::class,
                'source_id' => $expense->id,
                'is_cancelled' => false,
                'cancelled_at' => null,
            ]);

            if (!isset($totals[$key])) {
                $totals[$key] = ['entrees' => 0.0, 'sorties' => 0.0];
            }
            $totals[$key]['sorties'] += (float) $expense->amount;
        }

        // Quelques transactions annulées manuelles
        foreach ($cashBooksByKey as $key => $cashBook) {
            if (rand(1, 100) <= 10) {
                CashTransaction::create([
                    'cash_book_id' => $cashBook->id,
                    'centre_id' => $cashBook->centre_id,
                    'direction' => 'sortie',
                    'amount' => rand(1000, 5000),
                    'label' => 'Ajustement annulé',
                    'mode' => 'Espèces',
                    'reference' => null,
                    'source_type' => null,
                    'source_id' => null,
                    'is_cancelled' => true,
                    'cancelled_at' => Carbon::parse($cashBook->date)->addHours(18),
                ]);
            }
        }

        // Mise à jour des soldes de chaque livre de caisse
        foreach ($cashBooksByKey as $key => $cashBook) {
            $centreId = $cashBook->centre_id;
            $dateStr = Carbon::parse($cashBook->date)->toDateString();
            $totKey = $centreId . '|' . $dateStr;

            $totalEntrees = isset($totals[$totKey]) ? $totals[$totKey]['entrees'] : 0.0;
            $totalSorties = isset($totals[$totKey]) ? $totals[$totKey]['sorties'] : 0.0;

            $soldeVeille = 20000;
            $soldeFinal = $soldeVeille + $totalEntrees - $totalSorties;

            $cashBook->update([
                'solde_veille' => $soldeVeille,
                'total_entrees' => $totalEntrees,
                'total_sorties' => $totalSorties,
                'solde_final' => $soldeFinal,
                'is_closed' => true,
                'date_cloture' => Carbon::parse($cashBook->date)->endOfDay(),
            ]);
        }
    }

    private function getOrCreateCashBook(array &$cache, Centre $centre, string $dateStr): CashBook
    {
        $key = $centre->id . '|' . $dateStr;
        if (isset($cache[$key])) {
            return $cache[$key];
        }

        $cashBook = CashBook::create([
            'centre_id' => $centre->id,
            'date' => $dateStr,
            'solde_veille' => 0,
            'total_entrees' => 0,
            'total_sorties' => 0,
            'solde_final' => 0,
            'is_closed' => false,
            'date_cloture' => null,
        ]);

        $cache[$key] = $cashBook;

        return $cashBook;
    }
}