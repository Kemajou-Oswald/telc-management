<?php

namespace Tests\Feature;

use App\Models\BankDeposit;
use App\Models\Center;
use App\Models\ExamSession;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DepositsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_secretary_can_view_deposits_page()
    {
        $center = Center::create([
            'name' => 'Centre Test',
            'code' => 'CTR-TST',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $session = ExamSession::create([
            'title' => 'TELC B1 Avril 2026',
            'center_id' => $center->id,
            'level' => 'B1',
            'city' => 'Douala',
            'price' => 50000,
            'opens_at' => now()->subDay(),
            'closes_at' => now()->addDays(10),
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $center->id,
        ]);

        $user->centers()->sync([$center->id]);

        BankDeposit::create([
            'center_id' => $center->id,
            'exam_session_id' => $session->id,
            'submitted_by' => $user->id,
            'amount' => 75000,
            'deposit_date' => now()->toDateString(),
            'slip_path' => 'bank-slips/test-slip.pdf',
            'status' => BankDeposit::STATUS_PENDING,
        ]);

        $response = $this->actingAs($user)->get('/deposits');

        $response->assertOk();
        $response->assertSee('Depots bancaires');
        $response->assertSee('Liste des depots');
        $response->assertSee('TELC B1 Avril 2026');
        $response->assertSee(route('deposits.create', []), false);
    }

    public function test_secretary_can_open_dedicated_deposit_creation_page()
    {
        $center = Center::create([
            'name' => 'Centre Depot',
            'code' => 'CTR-DPT',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        ExamSession::create([
            'title' => 'TELC B2 Mai 2026',
            'center_id' => $center->id,
            'level' => 'B2',
            'city' => 'Douala',
            'price' => 52000,
            'opens_at' => now()->subDay(),
            'closes_at' => now()->addDays(10),
            'status' => ExamSession::STATUS_OPEN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
            'center_id' => $center->id,
        ]);

        $user->centers()->sync([$center->id]);

        $response = $this->actingAs($user)->get('/deposits/create');

        $response->assertOk();
        $response->assertSee('Nouveau depot');
        $response->assertSee('Enregistrer un depot');
        $response->assertSee('TELC B2 Mai 2026');
    }

    public function test_super_admin_can_view_pending_deposit_review_actions()
    {
        $center = Center::create([
            'name' => 'Centre Audit',
            'code' => 'CTR-AUD',
            'city' => 'Yaounde',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $submitter = User::factory()->create([
            'role' => User::ROLE_CENTER_ADMIN,
            'center_id' => $center->id,
        ]);

        $submitter->centers()->sync([$center->id]);

        BankDeposit::create([
            'center_id' => $center->id,
            'submitted_by' => $submitter->id,
            'amount' => 90000,
            'deposit_date' => now()->toDateString(),
            'slip_path' => 'bank-slips/review-slip.pdf',
            'status' => BankDeposit::STATUS_PENDING,
        ]);

        $superAdmin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($superAdmin)->get('/deposits');

        $response->assertOk();
        $response->assertSee('Liste des depots');
        $response->assertSee('Traiter');
        $response->assertSee('Centre Audit');
    }
}
