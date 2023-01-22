<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $admin, $manager, $worker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->admin = User::factory()->create()->assignRole('admin');
    }

    // Admin Section
    public function test_admin_can_open_user_page_with_no_data()
    {
        $response = $this->actingAs($this->admin)->get(route('home.users.index'));
        $response->assertStatus(200);

        $response->assertSee('User');
        $response->assertSee('No user found.');
    }

    public function test_admin_can_open_user_page_with_data()
    {
        $user = User::factory()->create()->assignRole('user');

        $response = $this->actingAs($this->admin)->get(route('home.users.index'));
        $response->assertStatus(200);

        $response->assertSee('User');
        $response->assertDontSee('No User found.');
        $response->assertViewHas('users', function ($users) use ($user) {
            return $users->contains($user);
        });
    }

    public function test_admin_can_edit_user()
    {
        $user = User::factory()->create()->assignRole('user');

        $response = $this->actingAs($this->admin)->get(route('home.users.edit', $user->id));
        $response->assertStatus(200);

        $response->assertSee('Edit User');
        $response->assertSee('value="' . $user->name . '"', escape: false);
        $response->assertSee('value="' . $user->email . '"', escape: false);

        $response->assertViewHas('user', $user);
    }

    public function test_admin_edit_user_validation_error_return_back_to_edit_form()
    {
        $user = User::factory()->create()->assignRole('user');

        $response = $this->actingAs($this->admin)->put(route('home.users.update', $user->id, [
            'role' => '',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['role']);
    }

    public function test_admin_can_update_user()
    {
        $user = User::factory()->create()->assignRole('user');

        $updateRole = [
            'role' => 'manager',
        ];

        $response = $this->actingAs($this->admin)->put(route('home.users.update', $user->id), $updateRole);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.users.index'));

        $response = $this->actingAs($this->admin)->get(route('home.users.index'));
        $response->assertStatus(200);

        $response->assertSee($user->name);
    }

    // Manager Section
    public function test_manager_cannot_access_user_page()
    {
        $manager = User::factory()->create()->assignRole('manager');
        $user = User::factory()->create();

        $response = $this->actingAs($manager)->get(route('home.users.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($manager)->get(route('home.users.edit', $user->id));
        $response->assertStatus(403);

        $updateRole = [
            'role' => 'worker',
        ];

        $response = $this->actingAs($manager)->put(route('home.users.update', $user->id), $updateRole);
        $response->assertStatus(403);
    }

    // Worker Section
    public function test_worker_cannot_access_user_page()
    {
        $worker = User::factory()->create()->assignRole('worker');
        $user = User::factory()->create();

        $response = $this->actingAs($worker)->get(route('home.users.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($worker)->get(route('home.users.edit', $user->id));
        $response->assertStatus(403);

        $updateRole = [
            'role' => 'manager',
        ];

        $response = $this->actingAs($worker)->put(route('home.users.update', $user->id), $updateRole);
        $response->assertStatus(403);
    }

    // User Section
    public function test_user_cannot_access_user_page()
    {
        $user = User::factory()->create()->assignRole('user');

        $response = $this->actingAs($user)->get(route('home.users.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('home.users.edit', $user->id));
        $response->assertStatus(403);

        $updateRole = [
            'role' => 'manager',
        ];

        $response = $this->actingAs($user)->put(route('home.users.update', $user->id), $updateRole);
        $response->assertStatus(403);
    }
}
