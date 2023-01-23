<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectAPITest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_returns_projects_list()
    {
        $project = Project::factory()->create();
        $response = $this->getJson('v1/api/projects');
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Projects data list',
            'data' => $project->paginate(5)->toArray()
        ]);
    }

    public function test_project_store_successful()
    {
        $param = [
            'user_id' => User::factory()->create()->id,
            'client_id' => Client::factory()->create()->id,
            'title' => 'Test 1',
            'description' => 'testing project',
            'deadline' => Carbon::now()->format('Y-m-d'),
            'status' => 'open',
        ];

        $response = $this->postJson('v1/api/projects', $param);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Successfully added new project.',
            'data' => [$param]
        ]);
    }

    public function test_project_invalid_user_id_and_client_id_returns_error_message()
    {
        $param = [
            'user_id' => 100,
            'client_id' => 100,
            'title' => 'Test 1',
            'description' => 'testing project',
            'deadline' => Carbon::now()->format('Y-m-d'),
            'status' => 'open',
        ];

        $response = $this->postJson('v1/api/projects', $param);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'User or client data not found.']);
    }

    public function test_project_invalid_store_returns_error()
    {
        $param = [
            'user_id' => User::factory()->create()->id,
            'client_id' => Client::factory()->create()->id,
            'title' => '',
            'description' => 'testing project',
            'deadline' => Carbon::now()->format('Y-m-d'),
            'status' => 'open',
        ];

        $response = $this->postJson('v1/api/projects', $param);

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('title');
    }
}
