<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_guest_can_register()
    {
        $response = $this->post(route('register'), [
            'name' => 'A',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'A',
            'email' => 'test@test.com',
        ]);

        $user = User::first();
        $this->assertEquals('user', $user->getRoleNames()->toArray()[0]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.index'));
    }

    public function test_guest_cannot_access_auth_routes()
    {
        $response = $this->get(route('home.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
