<?php

namespace Tests\Feature;

use App\Models\Center;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CenterManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_super_admin_can_open_centers_page()
    {
        Center::create([
            'name' => 'Centre Test',
            'code' => 'CTR-TST',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->get('/centers');

        $response->assertOk();
        $response->assertSee('Centres');
        $response->assertSee('Liste des centres');
        $response->assertSee('Nouveau centre');
        $response->assertSee('/centers/create', false);
        $response->assertDontSee('Cr&eacute;er le centre', false);
    }

    public function test_super_admin_can_open_center_creation_page()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->get('/centers/create');

        $response->assertOk();
        $response->assertSee('Nouveau centre');
        $response->assertSee('Cr&eacute;er un centre', false);
        $response->assertSee('Cr&eacute;er le centre', false);
    }

    public function test_legacy_center_create_query_redirects_to_dedicated_page()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $response = $this->actingAs($user)->get('/centers?create=1');

        $response->assertRedirect(route('centers.create'));
    }
}