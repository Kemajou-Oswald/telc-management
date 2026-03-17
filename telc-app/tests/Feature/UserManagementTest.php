<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_super_admin_can_open_users_page()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->get('/users');

        $response->assertOk();
        $response->assertSee('Utilisateurs');
        $response->assertSee('Comptes existants');
    }

    public function test_super_admin_cannot_block_own_account()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->post('/users/' . $user->id . '/toggle-block');

        $response->assertRedirect('/users');
        $response->assertSessionHas('error');

        $user->refresh();

        $this->assertFalse($user->is_blocked);
    }
}