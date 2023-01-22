<?php

namespace Tests\Feature;

use App\Models\Client;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private $admin, $manager, $worker, $user, $param;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->admin = User::factory()->create()->assignRole('admin');
        $this->manager = User::factory()->create()->assignRole('manager');
        $this->worker = User::factory()->create()->assignRole('worker');
        $this->user = User::factory()->create()->assignRole('user');

        $this->param = [
            'company_name' => 'A',
            'company_vat' => '123',
            'company_address' => 'B Street',
        ];
    }

    // Admin Section
    public function test_admin_can_open_client_page_with_no_data()
    {
        $response = $this->actingAs($this->admin)->get(route('home.clients.index'));

        $response->assertSee('Client');
        $response->assertSee('No client found.');
    }

    public function test_admin_can_open_client_page_with_data()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('home.clients.index'));

        $response->assertSee('Client');
        $response->assertDontSee('No client found.');
        $response->assertViewHas('clients', function ($clients) use ($client) {
            return $clients->contains($client);
        });
    }

    public function test_admin_can_open_add_new_client_page()
    {
        $response = $this->actingAs($this->admin)->get(route('home.clients.create'));

        $response->assertSee('Add Client');
    }

    public function test_admin_can_add_new_client()
    {
        $this->actingAs($this->admin)->post(route('home.clients.store', $this->param));

        $this->assertDatabaseHas('clients', $this->param);
    }

    public function test_admin_can_edit_client()
    {
        $client = Client::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('home.clients.edit', $client->id));

        $response->assertStatus(200);
        $response->assertSee('value="' . $client->company_name . '"', escape: false);
        $response->assertSee('value="' . $client->company_vat . '"', escape: false);
        $response->assertSee('value="' . $client->company_address . '"', escape: false);

        $response->assertViewHas('client', $client);
    }

    public function test_admin_edit_client_validation_error_return_back_to_edit_form()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('home.clients.update', $client->id, [
            'company_name' => '',
            'company_vat' => '',
            'company_address' => '',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['company_name', 'company_vat', 'company_address']);
    }

    public function test_admin_can_update_client()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('home.clients.update', $client->id), $this->param);
        $response->assertStatus(302);
        $response->assertRedirect(route('home.clients.index'));

        $this->assertDatabaseHas('clients', $this->param);

        $response = $this->actingAs($this->admin)->get(route('home.clients.index'));
        $response->assertStatus(200);

        $response->assertSee('A');
    }

    public function test_admin_can_delete_client()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/home/clients/' . $client->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.clients.index'));

        $this->assertDatabaseMissing('clients', $client->toArray());
        $this->assertSoftDeleted('clients', [
            'id' => $client->id,
        ]);
    }

    // Manager Section
    public function test_manager_can_open_client_page_with_no_data()
    {
        $response = $this->actingAs($this->manager)->get(route('home.clients.index'));

        $response->assertSee('Client');
        $response->assertSee('No client found.');
    }

    public function test_manager_can_open_client_page_with_data()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->manager)->get(route('home.clients.index'));

        $response->assertSee('Client');
        $response->assertDontSee('No client found.');
        $response->assertViewHas('clients', function ($clients) use ($client) {
            return $clients->contains($client);
        });
    }

    public function test_manager_can_open_add_new_client_page()
    {
        $response = $this->actingAs($this->manager)->get(route('home.clients.create'));

        $response->assertSee('Add Client');
    }

    public function test_manager_can_add_new_client()
    {
        $this->actingAs($this->manager)->post(route('home.clients.store', $this->param));

        $this->assertDatabaseHas('clients', $this->param);
    }

    public function test_manager_can_edit_client()
    {
        $client = Client::factory()->create();
        $response = $this->actingAs($this->manager)->get(route('home.clients.edit', $client->id));

        $response->assertStatus(200);
        $response->assertSee('value="' . $client->company_name . '"', escape: false);
        $response->assertSee('value="' . $client->company_vat . '"', escape: false);
        $response->assertSee('value="' . $client->company_address . '"', escape: false);

        $response->assertViewHas('client', $client);
    }

    public function test_manager_edit_client_validation_error_return_back_to_edit_form()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->manager)->put(route('home.clients.update', $client->id, [
            'company_name' => '',
            'company_vat' => '',
            'company_address' => '',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['company_name', 'company_vat', 'company_address']);
    }

    public function test_manager_can_update_client()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->manager)->put('/home/clients/' . $client->id, $this->param);
        $response->assertStatus(302);
        $response->assertRedirect(route('home.clients.index'));

        $this->assertDatabaseHas('clients', $this->param);

        $response = $this->actingAs($this->manager)->get(route('home.clients.index'));
        $response->assertStatus(200);

        $response->assertSee('A');
    }

    public function test_manager_can_delete_client()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->manager)->delete('/home/clients/' . $client->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.clients.index'));

        $this->assertDatabaseMissing('clients', $client->toArray());
        $this->assertSoftDeleted('clients', [
            'id' => $client->id,
        ]);
    }

    // Worker Section
    public function test_worker_dont_have_access_to_client_page()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->worker)->get(route('home.clients.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->worker)->get(route('home.clients.create'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->worker)->get(route('home.clients.edit', $client->id));
        $response->assertStatus(403);

        $response = $this->actingAs($this->worker)->put('/home/clients/' . $client->id, $this->param);
        $response->assertStatus(403);

        $response = $this->actingAs($this->worker)->delete('/home/clients/' . $client->id);
        $response->assertStatus(403);
    }

    // User Section
    public function test_user_dont_have_access_to_client_page()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->get(route('home.clients.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->get(route('home.clients.create'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->get(route('home.clients.edit', $client->id));
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->put('/home/clients/' . $client->id, $this->param);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->delete('/home/clients/' . $client->id);
        $response->assertStatus(403);
    }
}
