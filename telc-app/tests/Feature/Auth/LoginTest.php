<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_page_loads()
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Se connecter');
        $response->assertDontSee('Super Admin');
        $response->assertDontSee('Admin Centre');
        $response->assertDontSee('Secretaire');
    }

    public function test_user_can_log_in_with_email_and_password_only()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_password()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'bad-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}