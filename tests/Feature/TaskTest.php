<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
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
            'project_id' => Project::factory()->create()->id,
            'title' => 'Test 1',
            'description' => 'testing task',
            'deadline' => Carbon::now()->format('Y-m-d'),
            'status' => 'open',
        ];
    }

    // Admin Section
    public function test_admin_can_open_task_page_with_no_data()
    {
        $response = $this->actingAs($this->admin)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Task');
        $response->assertSee('No task found.');
    }

    public function test_admin_can_open_task_page_with_data()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Task');
        $response->assertDontSee('No task found.');
        $response->assertViewHas('tasks', function ($tasks) use ($task) {
            return $tasks->contains($task);
        });
    }

    public function test_admin_can_open_add_new_task_page()
    {
        $response = $this->actingAs($this->admin)->get(route('home.tasks.create'));

        $response->assertSee('Add Task');
    }

    public function test_admin_can_add_new_task()
    {
        $this->actingAs($this->admin)->post(route('home.tasks.store', $this->param));

        $this->assertDatabaseHas('tasks', $this->param);
    }

    public function test_admin_can_edit_task()
    {
        $task = Task::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('home.tasks.edit', $task->id));

        $response->assertStatus(200);
        $response->assertSee('value="' . $task->title . '"', escape: false);
        $response->assertSee($task->description);
        $response->assertSee('value="' . $task->deadline . '"', escape: false);
        $response->assertSee('value="' . $task->status . '"', escape: false);

        $response->assertViewHas('task', $task);
    }

    public function test_admin_edit_task_validation_error_return_back_to_edit_form()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('home.tasks.update', $task->id, [
            'project_id' => '',
            'title' => '',
            'description' => '',
            'status' => 'open',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['project_id', 'title', 'description', 'deadline']);
    }

    public function test_admin_can_update_task()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('home.tasks.update', $task->id), $this->param);
        $response->assertStatus(302);
        $response->assertRedirect(route('home.tasks.index'));

        $this->assertDatabaseHas('tasks', $this->param);

        $response = $this->actingAs($this->admin)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Test 1');
    }

    public function test_admin_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/home/tasks/' . $task->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.tasks.index'));

        $this->assertDatabaseMissing('tasks', $task->toArray());
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }

    // Manager Section
    public function test_manager_can_open_task_page_with_no_data()
    {
        $response = $this->actingAs($this->manager)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Task');
        $response->assertSee('No task found.');
    }

    public function test_manager_can_open_task_page_with_data()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->manager)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Task');
        $response->assertDontSee('No task found.');
        $response->assertViewHas('tasks', function ($tasks) use ($task) {
            return $tasks->contains($task);
        });
    }

    public function test_manager_can_open_add_new_task_page()
    {
        $response = $this->actingAs($this->manager)->get(route('home.tasks.create'));

        $response->assertSee('Add Task');
    }

    public function test_manager_can_add_new_task()
    {
        $this->actingAs($this->manager)->post(route('home.tasks.store', $this->param));

        $this->assertDatabaseHas('tasks', $this->param);
    }

    public function test_manager_can_edit_task()
    {
        $task = Task::factory()->create();
        $response = $this->actingAs($this->manager)->get(route('home.tasks.edit', $task->id));

        $response->assertStatus(200);
        $response->assertSee('value="' . $task->title . '"', escape: false);
        $response->assertSee($task->description);
        $response->assertSee('value="' . $task->deadline . '"', escape: false);
        $response->assertSee('value="' . $task->status . '"', escape: false);

        $response->assertViewHas('task', $task);
    }

    public function test_manager_edit_task_validation_error_return_back_to_edit_form()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->manager)->put(route('home.tasks.update', $task->id, [
            'project_id' => '',
            'title' => '',
            'description' => '',
            'status' => 'open',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['project_id', 'title', 'description', 'deadline']);
    }

    public function test_manager_can_update_task()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->manager)->put(route('home.tasks.update', $task->id), $this->param);
        $response->assertStatus(302);
        $response->assertRedirect(route('home.tasks.index'));

        $this->assertDatabaseHas('tasks', $this->param);

        $response = $this->actingAs($this->manager)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Test 1');
    }

    public function test_manager_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->manager)->delete('/home/tasks/' . $task->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.tasks.index'));

        $this->assertDatabaseMissing('tasks', $task->toArray());
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }

    // Worker Section
    public function test_worker_can_open_task_page_with_no_data()
    {
        $response = $this->actingAs($this->worker)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Task');
        $response->assertSee('No task found.');
    }

    public function test_worker_can_open_task_page_with_data()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->worker)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Task');
        $response->assertDontSee('No task found.');
        $response->assertViewHas('tasks', function ($tasks) use ($task) {
            return $tasks->contains($task);
        });
    }

    public function test_worker_can_open_add_new_task_page()
    {
        $response = $this->actingAs($this->worker)->get(route('home.tasks.create'));

        $response->assertSee('Add Task');
    }

    public function test_worker_can_add_new_task()
    {
        $this->actingAs($this->worker)->post(route('home.tasks.store', $this->param));

        $this->assertDatabaseHas('tasks', $this->param);
    }

    public function test_worker_can_edit_task()
    {
        $task = Task::factory()->create();
        $response = $this->actingAs($this->worker)->get(route('home.tasks.edit', $task->id));

        $response->assertStatus(200);
        $response->assertSee('value="' . $task->title . '"', escape: false);
        $response->assertSee($task->description);
        $response->assertSee('value="' . $task->deadline . '"', escape: false);
        $response->assertSee('value="' . $task->status . '"', escape: false);

        $response->assertViewHas('task', $task);
    }

    public function test_worker_edit_task_validation_error_return_back_to_edit_form()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->worker)->put(route('home.tasks.update', $task->id, [
            'project_id' => '',
            'title' => '',
            'description' => '',
            'status' => 'open',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['project_id', 'title', 'description', 'deadline']);
    }

    public function test_worker_can_update_task()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->worker)->put(route('home.tasks.update', $task->id), $this->param);
        $response->assertStatus(302);
        $response->assertRedirect(route('home.tasks.index'));

        $this->assertDatabaseHas('tasks', $this->param);

        $response = $this->actingAs($this->worker)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Test 1');
    }

    public function test_worker_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->worker)->delete('/home/tasks/' . $task->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.tasks.index'));

        $this->assertDatabaseMissing('tasks', $task->toArray());
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }

    // User Section
    public function test_user_can_open_task_page_with_no_data()
    {
        $response = $this->actingAs($this->user)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Task');
        $response->assertSee('No task found.');
    }

    public function test_user_can_open_task_page_with_data()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->user)->get(route('home.tasks.index'));
        $response->assertStatus(200);

        $response->assertSee('Task');
        $response->assertDontSee('No task found.');
        $response->assertViewHas('tasks', function ($tasks) use ($task) {
            return $tasks->contains($task);
        });
    }

    public function test_user_can_open_task_show_detail()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->user)->get(route('home.tasks.show', $task->id));
        $response->assertStatus(200);

        $response->assertSee('value="' . $task->title . '"', escape: false);
        $response->assertSee($task->description);
        $response->assertSee('value="' . $task->deadline . '"', escape: false);
        $response->assertSee('value="' . ucwords($task->status) . '"', escape: false);

        $response->assertViewHas('task', $task);
    }

    public function test_user_have_no_access_to_crud()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->user)->get(route('home.tasks.create'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->get(route('home.tasks.edit', $task->id));
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->put('/home/tasks/' . $task->id, $this->param);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->delete('/home/tasks/' . $task->id);
        $response->assertStatus(403);
    }
}
