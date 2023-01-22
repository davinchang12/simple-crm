<?php

namespace Tests\Feature;

use App\Models\Client;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->user = User::factory()->create()->assignRole('admin');
    }

    /**
     * Can open add new client page
     *
     * @return void
     */
    public function test_can_open_add_new_client_page()
    {
        $response = $this->actingAs($this->user)->withViewErrors([])->view('clients.create');

        $response->assertSee('Add Client');
    }
    
    /**
     * Can add new client
     *
     * @return void
     */
    public function test_can_add_new_client()
    {
        $this->actingAs($this->user)->post(route('home.clients.store', [
            'company_name' => 'A',
            'company_vat' => '123',
            'company_address' => 'B Street',
        ]));

        $this->assertDatabaseHas('clients', [
            'company_name' => 'A',
            'company_vat' => '123',
            'company_address' => 'B Street',
        ]);
    }
}
