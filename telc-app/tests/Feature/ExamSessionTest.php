<?php

namespace Tests\Feature;

use App\Models\Center;
use App\Models\ExamSession;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExamSessionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_super_admin_can_open_sessions_page()
    {
        $center = Center::create([
            'name' => 'Centre Douala',
            'code' => 'CTR-DLA',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $session = ExamSession::create([
            'title' => 'TELC B1 Juin 2026',
            'center_id' => $center->id,
            'level' => 'B1',
            'city' => 'Douala',
            'price' => 50000,
            'exam_date' => '2026-06-20',
            'opens_at' => '2026-05-01',
            'closes_at' => '2026-06-10',
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->get('/sessions');

        $response->assertOk();
        $response->assertSee("Sessions d'examen");
        $response->assertSee('TELC B1 Juin 2026');
        $response->assertSee('Nouvelle session');
        $response->assertSee('/sessions/create', false);
        $response->assertSee(route('sessions.show', $session), false);
    }

    public function test_super_admin_can_open_session_creation_page()
    {
        $center = Center::create([
            'name' => 'Centre Pilotage',
            'code' => 'CTR-PLT',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->get('/sessions/create?center_id=' . $center->id);

        $response->assertOk();
        $response->assertSee('Nouvelle session');
        $response->assertSee('Cr&eacute;er une session', false);
        $response->assertSee('Cr&eacute;er la session', false);
        $response->assertSee($center->name);
    }

    public function test_super_admin_can_open_session_detail_page()
    {
        $center = Center::create([
            'name' => 'Centre Historique',
            'code' => 'CTR-HIS',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $session = ExamSession::create([
            'title' => 'Session Historique',
            'center_id' => $center->id,
            'level' => 'B1',
            'city' => 'Douala',
            'price' => 55000,
            'exam_date' => '2026-05-20',
            'opens_at' => '2026-04-01',
            'closes_at' => '2026-05-10',
            'status' => ExamSession::STATUS_CLOSED,
            'notes' => 'Ancienne session a conserver.',
        ]);

        $student = Student::create([
            'first_name' => 'Amina',
            'last_name' => 'Njoya',
            'email' => 'amina.njoya@example.com',
            'date_of_birth' => '2001-05-10',
            'place_of_birth' => 'Douala',
            'base_language' => 'francais',
            'gender' => 'F',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'center_id' => $center->id,
        ]);

        $registrar = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $center->id,
        ]);

        Registration::create([
            'student_id' => $student->id,
            'center_id' => $center->id,
            'exam_session_id' => $session->id,
            'registered_by' => $registrar->id,
            'amount' => 55000,
            'payment_status' => Registration::PAYMENT_PAID,
            'paid_at' => '2026-04-20 10:00:00',
            'status' => Registration::STATUS_ACTIVE,
            'receipt_number' => 'DET-001',
            'registered_at' => '2026-04-18 09:30:00',
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->get('/sessions/' . $session->id);

        $response->assertOk();
        $response->assertSee('Session Historique');
        $response->assertSee('Amina Njoya');
        $response->assertSee('55 000 FCFA');
        $response->assertSee('Ancienne session a conserver.');
        $response->assertSee('Informations de la session');
    }

    public function test_legacy_session_create_query_redirects_to_dedicated_page()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->get('/sessions?create=1');

        $response->assertRedirect(route('sessions.create', []));
    }

    public function test_center_user_only_sees_accessible_sessions_and_centers()
    {
        $allowedCenter = Center::create([
            'name' => 'Centre Autorise',
            'code' => 'CTR-YES',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $blockedCenter = Center::create([
            'name' => 'Centre Externe',
            'code' => 'CTR-NO',
            'city' => 'Yaounde',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $visibleSession = ExamSession::create([
            'title' => 'Session Visible',
            'center_id' => $allowedCenter->id,
            'level' => 'B1',
            'city' => 'Douala',
            'price' => 45000,
            'exam_date' => '2026-07-15',
            'opens_at' => '2026-06-01',
            'closes_at' => '2026-07-01',
            'status' => ExamSession::STATUS_DRAFT,
        ]);

        $blockedSession = ExamSession::create([
            'title' => 'Session Cachee',
            'center_id' => $blockedCenter->id,
            'level' => 'C1',
            'city' => 'Yaounde',
            'price' => 35000,
            'exam_date' => '2026-07-16',
            'opens_at' => '2026-06-01',
            'closes_at' => '2026-07-01',
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $allowedCenter->id,
        ]);

        $user->centers()->sync([$allowedCenter->id]);

        $response = $this->actingAs($user)->get('/sessions');

        $response->assertOk();
        $response->assertSee('Session Visible');
        $response->assertDontSee('Session Cachee');
        $response->assertSee('Centre Autorise');
        $response->assertDontSee('Centre Externe');

        $detailResponse = $this->actingAs($user)->get('/sessions/' . $visibleSession->id);
        $detailResponse->assertOk();

        $forbiddenResponse = $this->actingAs($user)->get('/sessions/' . $blockedSession->id);
        $forbiddenResponse->assertNotFound();
    }

    public function test_super_admin_can_toggle_session_status_without_losing_filters()
    {
        $center = Center::create([
            'name' => 'Centre Filtre',
            'code' => 'CTR-FLT',
            'city' => 'Bafoussam',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $session = ExamSession::create([
            'title' => 'Session a basculer',
            'center_id' => $center->id,
            'level' => 'B1',
            'city' => 'Bafoussam',
            'price' => 60000,
            'exam_date' => '2026-08-10',
            'opens_at' => '2026-07-01',
            'closes_at' => '2026-07-31',
            'status' => ExamSession::STATUS_DRAFT,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->post(route('sessions.toggle', $session), [
            'status' => ExamSession::STATUS_OPEN,
            'center_id' => $center->id,
            'status_filter' => ExamSession::STATUS_DRAFT,
        ]);

        $response->assertRedirect(route('sessions.index', [
            'center_id' => $center->id,
            'status' => ExamSession::STATUS_DRAFT,
        ]));

        $this->assertSame(ExamSession::STATUS_OPEN, $session->fresh()->status);
    }
}