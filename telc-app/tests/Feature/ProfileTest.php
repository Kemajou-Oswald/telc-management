<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function test_authenticated_user_can_view_profile_page()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SECRETARY,
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('Mon profil');
        $response->assertSee($user->email);
    }

    public function test_authenticated_user_can_update_profile_details()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_CENTER_ADMIN,
            'phone' => '600000000',
        ]);

        $response = $this->actingAs($user)->post('/profile', [
            'name' => 'Nom Modifie',
            'email' => 'profil.modifie@example.test',
            'phone' => '699111222',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();

        $this->assertSame('Nom Modifie', $user->name);
        $this->assertSame('profil.modifie@example.test', $user->email);
        $this->assertSame('699111222', $user->phone);
    }

    public function test_authenticated_user_can_change_password_with_current_password()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'current_password' => 'password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();

        $this->assertTrue(Hash::check('new-password-123', $user->password));
    }
}