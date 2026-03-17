<?php

namespace Tests\Feature;

use App\Models\BankDeposit;
use App\Models\Center;
use App\Models\ExamSession;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use DatabaseTransactions;

    public function test_super_admin_can_open_dashboard()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('Tableau de bord');
        $response->assertSee('Filtres');
    }

    public function test_center_admin_dashboard_uses_paid_registrations_and_validated_deposits_for_amounts()
    {
        $center = Center::create([
            'name' => 'Centre TARA Test',
            'code' => 'TARA-TST',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $session = ExamSession::create([
            'title' => 'Simulation Test',
            'center_id' => $center->id,
            'level' => 'B1',
            'city' => 'Douala',
            'price' => 50000,
            'exam_date' => now()->addDays(10)->toDateString(),
            'opens_at' => now()->subDay(),
            'closes_at' => now()->addDays(10),
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $paidStudent = Student::create([
            'first_name' => 'Alice',
            'last_name' => 'Test',
            'email' => 'alice.test@example.com',
            'date_of_birth' => '2000-01-01',
            'place_of_birth' => 'Douala',
            'base_language' => 'francais',
            'gender' => 'F',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'center_id' => $center->id,
        ]);

        $pendingStudent = Student::create([
            'first_name' => 'Brice',
            'last_name' => 'Test',
            'email' => 'brice.test@example.com',
            'date_of_birth' => '1999-01-01',
            'place_of_birth' => 'Douala',
            'base_language' => 'francais',
            'gender' => 'M',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'center_id' => $center->id,
        ]);

        $registrar = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $center->id,
        ]);

        Registration::create([
            'student_id' => $paidStudent->id,
            'center_id' => $center->id,
            'exam_session_id' => $session->id,
            'registered_by' => $registrar->id,
            'amount' => 50000,
            'payment_status' => Registration::PAYMENT_PAID,
            'paid_at' => now(),
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'RCP-001',
            'registered_at' => now(),
        ]);

        Registration::create([
            'student_id' => $pendingStudent->id,
            'center_id' => $center->id,
            'exam_session_id' => $session->id,
            'registered_by' => $registrar->id,
            'amount' => 70000,
            'payment_status' => Registration::PAYMENT_PENDING,
            'paid_at' => null,
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'RCP-002',
            'registered_at' => now(),
        ]);

        BankDeposit::create([
            'center_id' => $center->id,
            'exam_session_id' => $session->id,
            'submitted_by' => $registrar->id,
            'amount' => 40000,
            'deposit_date' => now()->toDateString(),
            'slip_path' => 'bank-slips/validated.pdf',
            'status' => BankDeposit::STATUS_VALIDATED,
        ]);

        BankDeposit::create([
            'center_id' => $center->id,
            'exam_session_id' => $session->id,
            'submitted_by' => $registrar->id,
            'amount' => 90000,
            'deposit_date' => now()->toDateString(),
            'slip_path' => 'bank-slips/pending.pdf',
            'status' => BankDeposit::STATUS_PENDING,
        ]);

        BankDeposit::create([
            'center_id' => $center->id,
            'exam_session_id' => $session->id,
            'submitted_by' => $registrar->id,
            'amount' => 110000,
            'deposit_date' => now()->toDateString(),
            'slip_path' => 'bank-slips/rejected.pdf',
            'status' => BankDeposit::STATUS_REJECTED,
        ]);

        $manager = User::factory()->create([
            'role' => User::ROLE_CENTER_ADMIN,
            'center_id' => $center->id,
        ]);

        $manager->centers()->sync([$center->id]);

        $response = $this->actingAs($manager)->get('/');

        $response->assertOk();
        $response->assertViewHas('stats', function ($stats) {
            return (float) $stats['recorded_amount'] === 120000.0
                && (float) $stats['collected_amount'] === 50000.0
                && (float) $stats['deposited_amount'] === 40000.0;
        });
    }

    public function test_secretary_dashboard_shows_open_and_closed_sessions()
    {
        $center = Center::create([
            'name' => 'Centre Suivi',
            'code' => 'CTR-SUI',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        ExamSession::create([
            'title' => 'Session Ouverte',
            'center_id' => $center->id,
            'level' => 'B1',
            'city' => 'Douala',
            'price' => 50000,
            'exam_date' => '2026-04-10',
            'opens_at' => '2026-03-20',
            'closes_at' => '2026-04-05',
            'status' => ExamSession::STATUS_OPEN,
        ]);

        ExamSession::create([
            'title' => 'Session Fermee',
            'center_id' => $center->id,
            'level' => 'B2',
            'city' => 'Douala',
            'price' => 52000,
            'exam_date' => '2026-03-15',
            'opens_at' => '2026-02-01',
            'closes_at' => '2026-03-01',
            'status' => ExamSession::STATUS_CLOSED,
        ]);

        $secretary = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $center->id,
        ]);

        $secretary->centers()->sync([$center->id]);

        $response = $this->actingAs($secretary)->get('/');

        $response->assertOk();
        $response->assertSee('Session Ouverte');
        $response->assertSee('Session Fermee');
        $response->assertSee('1 ouverte');
        $response->assertSee('1 fermee');
    }

    public function test_super_admin_can_filter_dashboard_by_center_level_and_period()
    {
        $centerA = Center::create([
            'name' => 'Centre A',
            'code' => 'CTR-A',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $centerB = Center::create([
            'name' => 'Centre B',
            'code' => 'CTR-B',
            'city' => 'Yaounde',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $sessionMatch = ExamSession::create([
            'title' => 'Session Match',
            'center_id' => $centerA->id,
            'level' => 'B1',
            'city' => 'Douala',
            'price' => 30000,
            'exam_date' => '2026-04-10',
            'opens_at' => '2026-03-20',
            'closes_at' => '2026-04-05',
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $sessionWrongLevel = ExamSession::create([
            'title' => 'Session Mauvais Niveau',
            'center_id' => $centerA->id,
            'level' => 'C1',
            'city' => 'Douala',
            'price' => 20000,
            'exam_date' => '2026-04-11',
            'opens_at' => '2026-03-20',
            'closes_at' => '2026-04-05',
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $sessionWrongCenter = ExamSession::create([
            'title' => 'Session Mauvais Centre',
            'center_id' => $centerB->id,
            'level' => 'B1',
            'city' => 'Yaounde',
            'price' => 40000,
            'exam_date' => '2026-04-12',
            'opens_at' => '2026-03-20',
            'closes_at' => '2026-04-05',
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $sessionWrongDate = ExamSession::create([
            'title' => 'Session Mauvaise Date',
            'center_id' => $centerA->id,
            'level' => 'B1',
            'city' => 'Douala',
            'price' => 50000,
            'exam_date' => '2026-06-12',
            'opens_at' => '2026-05-20',
            'closes_at' => '2026-06-05',
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $studentMatch = Student::create([
            'first_name' => 'Mila',
            'last_name' => 'Match',
            'email' => 'mila.match@example.com',
            'date_of_birth' => '2000-01-01',
            'place_of_birth' => 'Douala',
            'base_language' => 'francais',
            'gender' => 'F',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'center_id' => $centerA->id,
        ]);

        $studentWrongLevel = Student::create([
            'first_name' => 'Mila',
            'last_name' => 'C1',
            'email' => 'mila.c1@example.com',
            'date_of_birth' => '2001-01-01',
            'place_of_birth' => 'Douala',
            'base_language' => 'francais',
            'gender' => 'F',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'center_id' => $centerA->id,
        ]);

        $studentWrongCenter = Student::create([
            'first_name' => 'Mila',
            'last_name' => 'Centre',
            'email' => 'mila.centre@example.com',
            'date_of_birth' => '2002-01-01',
            'place_of_birth' => 'Yaounde',
            'base_language' => 'francais',
            'gender' => 'F',
            'city' => 'Yaounde',
            'country' => 'Cameroun',
            'center_id' => $centerB->id,
        ]);

        $studentWrongDate = Student::create([
            'first_name' => 'Mila',
            'last_name' => 'Date',
            'email' => 'mila.date@example.com',
            'date_of_birth' => '2003-01-01',
            'place_of_birth' => 'Douala',
            'base_language' => 'francais',
            'gender' => 'F',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'center_id' => $centerA->id,
        ]);

        $registrarA = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $centerA->id,
        ]);

        $registrarB = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $centerB->id,
        ]);

        Registration::create([
            'student_id' => $studentMatch->id,
            'center_id' => $centerA->id,
            'exam_session_id' => $sessionMatch->id,
            'registered_by' => $registrarA->id,
            'amount' => 30000,
            'payment_status' => Registration::PAYMENT_PAID,
            'paid_at' => '2026-04-06 10:00:00',
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'FLT-001',
            'registered_at' => '2026-04-05 09:00:00',
        ]);

        Registration::create([
            'student_id' => $studentWrongLevel->id,
            'center_id' => $centerA->id,
            'exam_session_id' => $sessionWrongLevel->id,
            'registered_by' => $registrarA->id,
            'amount' => 20000,
            'payment_status' => Registration::PAYMENT_PAID,
            'paid_at' => '2026-04-06 10:00:00',
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'FLT-002',
            'registered_at' => '2026-04-05 09:00:00',
        ]);

        Registration::create([
            'student_id' => $studentWrongCenter->id,
            'center_id' => $centerB->id,
            'exam_session_id' => $sessionWrongCenter->id,
            'registered_by' => $registrarB->id,
            'amount' => 40000,
            'payment_status' => Registration::PAYMENT_PAID,
            'paid_at' => '2026-04-06 10:00:00',
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'FLT-003',
            'registered_at' => '2026-04-05 09:00:00',
        ]);

        Registration::create([
            'student_id' => $studentWrongDate->id,
            'center_id' => $centerA->id,
            'exam_session_id' => $sessionWrongDate->id,
            'registered_by' => $registrarA->id,
            'amount' => 50000,
            'payment_status' => Registration::PAYMENT_PAID,
            'paid_at' => '2026-06-06 10:00:00',
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'FLT-004',
            'registered_at' => '2026-06-05 09:00:00',
        ]);

        BankDeposit::create([
            'center_id' => $centerA->id,
            'exam_session_id' => $sessionMatch->id,
            'submitted_by' => $registrarA->id,
            'amount' => 15000,
            'deposit_date' => '2026-04-07',
            'slip_path' => 'bank-slips/filter-match.pdf',
            'status' => BankDeposit::STATUS_VALIDATED,
        ]);

        BankDeposit::create([
            'center_id' => $centerA->id,
            'exam_session_id' => $sessionWrongLevel->id,
            'submitted_by' => $registrarA->id,
            'amount' => 10000,
            'deposit_date' => '2026-04-07',
            'slip_path' => 'bank-slips/filter-level.pdf',
            'status' => BankDeposit::STATUS_VALIDATED,
        ]);

        BankDeposit::create([
            'center_id' => $centerB->id,
            'exam_session_id' => $sessionWrongCenter->id,
            'submitted_by' => $registrarB->id,
            'amount' => 12000,
            'deposit_date' => '2026-04-07',
            'slip_path' => 'bank-slips/filter-center.pdf',
            'status' => BankDeposit::STATUS_VALIDATED,
        ]);

        BankDeposit::create([
            'center_id' => $centerA->id,
            'exam_session_id' => $sessionWrongDate->id,
            'submitted_by' => $registrarA->id,
            'amount' => 9000,
            'deposit_date' => '2026-06-07',
            'slip_path' => 'bank-slips/filter-date.pdf',
            'status' => BankDeposit::STATUS_VALIDATED,
        ]);

        $superAdmin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($superAdmin)->get('/?center_id=' . $centerA->id . '&level=B1&date_from=2026-04-01&date_to=2026-04-30');

        $response->assertOk();
        $response->assertViewHas('stats', function ($stats) {
            return (int) $stats['centers'] === 1
                && (int) $stats['sessions'] === 1
                && (int) $stats['students'] === 1
                && (int) $stats['registrations'] === 1
                && (float) $stats['recorded_amount'] === 30000.0
                && (float) $stats['collected_amount'] === 30000.0
                && (float) $stats['deposited_amount'] === 15000.0;
        });
        $response->assertSee('Session Match');
        $response->assertDontSee('Session Mauvais Niveau');
        $response->assertDontSee('Session Mauvais Centre');
    }
}
