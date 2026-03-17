<?php

namespace Tests\Feature;

use App\Models\Center;
use App\Models\ExamSession;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seedTestData();
    }

    private function seedTestData()
    {
        $center = Center::create([
            'name' => 'Test Center',
            'code' => 'TC001',
            'city' => 'Test City',
            'country' => 'Test Country',
            'is_active' => true,
        ]);

        $session = ExamSession::create([
            'center_id' => $center->id,
            'title' => 'Test Session',
            'exam_date' => now()->addDays(30),
            'opens_at' => now()->subDays(10),
            'closes_at' => now()->addDays(20),
            'level' => 'B1',
            'status' => 'open',
            'max_participants' => 50,
        ]);

        $student = Student::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@test.com',
            'date_of_birth' => '1990-01-01',
            'place_of_birth' => 'Test Place',
            'base_language' => 'English',
            'gender' => 'M',
            'city' => 'Test City',
            'country' => 'CM - Cameroon',
            'center_id' => $center->id,
        ]);

        Registration::create([
            'student_id' => $student->id,
            'center_id' => $center->id,
            'exam_session_id' => $session->id,
            'registered_by' => 1,
            'amount' => 15000,
            'payment_status' => 'paid',
            'status' => 'active',
            'registered_at' => now(),
        ]);
    }

    public function test_export_excel_download()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        
        $response = $this->actingAs($user)
            ->get('/registrations/export/download');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition');
        
        $this->assertStringContainsString('TELC_Inscriptions_', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.xlsx', $response->headers->get('Content-Disposition'));
    }

    public function test_export_pdf_download()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        
        $response = $this->actingAs($user)
            ->get('/registrations/export/pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition');
        
        $this->assertStringContainsString('TELC_Inscriptions_', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.pdf', $response->headers->get('Content-Disposition'));
    }

    public function test_export_data_format()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        
        $response = $this->actingAs($user)
            ->get('/registrations/export');

        $response->assertStatus(200);
        $response->assertSee('John');
        $response->assertSee('Doe');
        $response->assertSee('CM - Cameroon');
        $response->assertSee('English');
    }

    public function test_export_unauthorized_access()
    {
        $response = $this->get('/registrations/export/download');
        $response->assertRedirect('/login');
        
        $response = $this->get('/registrations/export/pdf');
        $response->assertRedirect('/login');
    }

    public function test_language_selector_format()
    {
        $user = User::factory()->create(['role' => 'secretary']);
        
        $response = $this->actingAs($user)
            ->get('/registrations/create');

        $response->assertStatus(200);
        $response->assertSee('002-English/English');
        $response->assertSee('003-French/Französisch');
    }

    public function test_default_country()
    {
        $user = User::factory()->create(['role' => 'secretary']);
        
        $response = $this->actingAs($user)
            ->get('/registrations/create');

        $response->assertStatus(200);
        $response->assertSee('CM - Cameroon');
    }
}
