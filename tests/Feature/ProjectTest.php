<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
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
            'user_id' => User::factory()->create()->id,
            'client_id' => Client::factory()->create()->id,
            'title' => 'Test 1',
            'description' => 'testing project',
            'deadline' => Carbon::now()->format('Y-m-d'),
            'status' => 'open',
        ];
    }

    // Admin Section
    public function test_admin_can_open_project_page_with_no_data()
    {
        $response = $this->actingAs($this->admin)->get(route('home.projects.index'));
        $response->assertStatus(200);

        $response->assertSee('Project');
        $response->assertSee('No project found.');
    }

    public function test_admin_can_open_project_page_with_data()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('home.projects.index'));
        $response->assertStatus(200);

        $response->assertSee('Project');
        $response->assertDontSee('No project found.');
        $response->assertViewHas('projects', function ($projects) use ($project) {
            return $projects->contains($project);
        });
    }
    public function test_admin_can_open_add_new_project_page()
    {
        $response = $this->actingAs($this->admin)->get(route('home.projects.create'));

        $response->assertSee('Add Project');
    }

    public function test_admin_can_add_new_project()
    {
        $this->actingAs($this->admin)->post(route('home.projects.store', $this->param));

        $this->assertDatabaseHas('projects', $this->param);
    }

    public function test_admin_can_edit_project()
    {
        $project = Project::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('home.projects.edit', $project->id));

        $response->assertStatus(200);
        $response->assertSee('value="' . $project->title . '"', escape: false);
        $response->assertSee($project->description);
        $response->assertSee('value="' . $project->deadline . '"', escape: false);
        $response->assertSee('value="' . $project->status . '"', escape: false);

        $response->assertViewHas('project', $project);
    }

    public function test_admin_edit_project_validation_error_return_back_to_edit_form()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('home.projects.update', $project->id, [
            'user_id' => '',
            'client_id' => '',
            'title' => '',
            'description' => '',
            'status' => 'open',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['user_id', 'client_id', 'title', 'description', 'deadline']);
    }

    public function test_admin_can_update_project()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('home.projects.update', $project->id), $this->param);
        $response->assertStatus(302);
        $response->assertRedirect(route('home.projects.index'));

        $this->assertDatabaseHas('projects', $this->param);

        $response = $this->actingAs($this->admin)->get(route('home.projects.index'));
        $response->assertStatus(200);

        $response->assertSee('Test 1');
    }

    public function test_admin_can_delete_project()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/home/projects/' . $project->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.projects.index'));

        $this->assertDatabaseMissing('projects', $project->toArray());
        $this->assertSoftDeleted('projects', [
            'id' => $project->id,
        ]);
    }

    // Manager Section
    public function test_manager_can_open_project_page_with_no_data()
    {
        $response = $this->actingAs($this->manager)->get(route('home.projects.index'));

        $response->assertSee('Project');
        $response->assertSee('No project found.');
    }

    public function test_manager_can_open_project_page_with_data()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->manager)->get(route('home.projects.index'));

        $response->assertSee('Project');
        $response->assertDontSee('No project found.');
        $response->assertViewHas('projects', function ($projects) use ($project) {
            return $projects->contains($project);
        });
    }

    public function test_manager_can_open_add_new_project_page()
    {
        $response = $this->actingAs($this->manager)->get(route('home.projects.create'));

        $response->assertSee('Add Project');
    }

    public function test_manager_can_add_new_project()
    {
        $this->actingAs($this->manager)->post(route('home.projects.store', $this->param));

        $this->assertDatabaseHas('projects', $this->param);
    }

    public function test_manager_can_edit_project()
    {
        $project = Project::factory()->create();
        $response = $this->actingAs($this->manager)->get(route('home.projects.edit', $project->id));

        $response->assertStatus(200);
        $response->assertSee('value="' . $project->title . '"', escape: false);
        $response->assertSee($project->description);
        $response->assertSee('value="' . $project->deadline . '"', escape: false);
        $response->assertSee('value="' . $project->status . '"', escape: false);

        $response->assertViewHas('project', $project);
    }

    public function test_manager_edit_project_validation_error_return_back_to_edit_form()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->manager)->put(route('home.projects.update', $project->id, [
            'user_id' => '',
            'client_id' => '',
            'title' => '',
            'description' => '',
            'status' => 'open',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['user_id', 'client_id', 'title', 'description', 'deadline']);
    }

    public function test_manager_can_update_project()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->manager)->put('/home/projects/' . $project->id, $this->param);
        $response->assertStatus(302);
        $response->assertRedirect(route('home.projects.index'));

        $this->assertDatabaseHas('projects', $this->param);

        $response = $this->actingAs($this->manager)->get(route('home.projects.index'));
        $response->assertStatus(200);

        $response->assertSee('Test 1');
    }

    public function test_manager_can_delete_project()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->manager)->delete('/home/projects/' . $project->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.projects.index'));

        $this->assertDatabaseMissing('projects', $project->toArray());
        $this->assertSoftDeleted('projects', [
            'id' => $project->id,
        ]);
    }

    // WOrker Section
    public function test_worker_can_open_project_page_with_no_data()
    {
        $response = $this->actingAs($this->worker)->get(route('home.projects.index'));
        $response->assertStatus(200);

        $response->assertSee('Project');
        $response->assertSee('No project found.');
    }

    public function test_worker_can_open_project_page_with_data()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->worker)->get(route('home.projects.index'));
        $response->assertStatus(200);

        $response->assertSee('Project');
        $response->assertDontSee('No project found.');
        $response->assertViewHas('projects', function ($projects) use ($project) {
            return $projects->contains($project);
        });
    }

    public function test_worker_have_no_access_to_crud()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->worker)->get(route('home.projects.create'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->worker)->get(route('home.projects.edit', $project->id));
        $response->assertStatus(403);

        $response = $this->actingAs($this->worker)->put('/home/projects/' . $project->id, $this->param);
        $response->assertStatus(403);

        $response = $this->actingAs($this->worker)->delete('/home/projects/' . $project->id);
        $response->assertStatus(403);
    }

    // User Section
    public function test_user_can_open_project_page_with_no_data()
    {
        $response = $this->actingAs($this->user)->get(route('home.projects.index'));
        $response->assertStatus(200);

        $response->assertSee('Project');
        $response->assertSee('No project found.');
    }

    public function test_user_can_open_project_page_with_data()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->user)->get(route('home.projects.index'));
        $response->assertStatus(200);

        $response->assertSee('Project');
        $response->assertDontSee('No project found.');
        $response->assertViewHas('projects', function ($projects) use ($project) {
            return $projects->contains($project);
        });
    }

    public function test_user_have_no_access_to_crud()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->user)->get(route('home.projects.create'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->get(route('home.projects.edit', $project->id));
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->put('/home/projects/' . $project->id, $this->param);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->delete('/home/projects/' . $project->id);
        $response->assertStatus(403);
    }
}
