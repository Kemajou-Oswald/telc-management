<?php

namespace Tests\Feature;

use App\Models\Center;
use App\Models\ExamSession;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegistrationReportTest extends TestCase
{
    use DatabaseTransactions;

    public function test_super_admin_can_preview_registration_report()
    {
        $center = Center::create([
            'name' => 'Centre Rapport',
            'code' => 'CTR-RAP',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $session = ExamSession::create([
            'title' => 'TELC C1 Mai 2026',
            'center_id' => $center->id,
            'level' => 'C1',
            'city' => 'Douala',
            'price' => 45000,
            'opens_at' => now()->subDays(2),
            'closes_at' => now()->addDays(10),
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $student = Student::create([
            'first_name' => 'Alice',
            'last_name' => 'Meka',
            'email' => 'alice@example.com',
            'country' => 'Cameroun',
            'center_id' => $center->id,
        ]);

        $manager = User::factory()->create([
            'role' => User::ROLE_CENTER_ADMIN,
            'center_id' => $center->id,
        ]);

        $manager->centers()->sync([$center->id]);

        Registration::create([
            'student_id' => $student->id,
            'center_id' => $center->id,
            'exam_session_id' => $session->id,
            'registered_by' => $manager->id,
            'amount' => 45000,
            'payment_status' => Registration::PAYMENT_PAID,
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'TELC-TEST-001',
            'registered_at' => now(),
        ]);

        $superAdmin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($superAdmin)->get('/registrations/export');

        $response->assertOk();
        $response->assertSee('Rapports et export');
        $response->assertSee('Exporter en XLSX');
        $response->assertSee('Exporter en PDF');
        $response->assertSee('TELC C1 Mai 2026');
    }

    public function test_secretary_can_preview_registration_report_for_own_center_only()
    {
        $visibleCenter = Center::create([
            'name' => 'Centre Visible',
            'code' => 'CTR-VIS',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $hiddenCenter = Center::create([
            'name' => 'Centre Cache',
            'code' => 'CTR-HID',
            'city' => 'Yaounde',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $visibleSession = ExamSession::create([
            'title' => 'Session Visible',
            'center_id' => $visibleCenter->id,
            'level' => 'B1',
            'city' => 'Douala',
            'price' => 40000,
            'opens_at' => now()->subDay(),
            'closes_at' => now()->addDays(10),
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $hiddenSession = ExamSession::create([
            'title' => 'Session Cachee',
            'center_id' => $hiddenCenter->id,
            'level' => 'C1',
            'city' => 'Yaounde',
            'price' => 48000,
            'opens_at' => now()->subDay(),
            'closes_at' => now()->addDays(10),
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $visibleStudent = Student::create([
            'first_name' => 'Aline',
            'last_name' => 'Visible',
            'email' => 'aline.visible@example.com',
            'country' => 'Cameroun',
            'center_id' => $visibleCenter->id,
        ]);

        $hiddenStudent = Student::create([
            'first_name' => 'Boris',
            'last_name' => 'Cache',
            'email' => 'boris.cache@example.com',
            'country' => 'Cameroun',
            'center_id' => $hiddenCenter->id,
        ]);

        $secretary = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $visibleCenter->id,
        ]);

        $secretary->centers()->sync([$visibleCenter->id]);

        Registration::create([
            'student_id' => $visibleStudent->id,
            'center_id' => $visibleCenter->id,
            'exam_session_id' => $visibleSession->id,
            'registered_by' => $secretary->id,
            'amount' => 40000,
            'payment_status' => Registration::PAYMENT_PAID,
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'VIS-001',
            'registered_at' => now(),
        ]);

        Registration::create([
            'student_id' => $hiddenStudent->id,
            'center_id' => $hiddenCenter->id,
            'exam_session_id' => $hiddenSession->id,
            'registered_by' => $secretary->id,
            'amount' => 48000,
            'payment_status' => Registration::PAYMENT_PAID,
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'HID-001',
            'registered_at' => now(),
        ]);

        $response = $this->actingAs($secretary)->get('/registrations/export');

        $response->assertOk();
        $response->assertSee('Session Visible');
        $response->assertDontSee('Session Cachee');
        $response->assertSee('Centre Visible');
        $response->assertDontSee('Centre Cache');
    }

    public function test_super_admin_can_download_registration_report()
    {
        $superAdmin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($superAdmin)->get('/registrations/export/download');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition', 'attachment; filename="registrations-report.xlsx"');
    }

    public function test_secretary_can_download_pdf_registration_report()
    {
        $center = Center::create([
            'name' => 'Centre PDF',
            'code' => 'CTR-PDF',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $secretary = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $center->id,
        ]);

        $secretary->centers()->sync([$center->id]);

        $response = $this->actingAs($secretary)->get('/registrations/export/pdf');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="registrations-report.pdf"');
    }
}
