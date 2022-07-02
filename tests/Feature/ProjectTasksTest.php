<?php

namespace Tests\Feature;

use App\Project;
use Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_project_can_have_tasks()
    {
        $this->signIn();

        $project = factory(Project::class)->create([
            'owner_id' => auth()->id()
        ]);

        $this->post($project->path() . '/tasks', [
            'body' => 'Test task'
        ]);

        $this->get($project->path())
            ->assertSee('Test task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $this->signIn();

        $project = Auth::user()->projects()->create(
            factory(Project::class)->raw()
        );

        $task = $project->addTask('Test task');

        $this->patch($project->path() . '/tasks/' . $task->id, [
            'body' => 'Changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'Changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();

        $project = factory(Project::class)->create([
            'owner_id' => auth()->id()
        ]);

        $attributes = factory('App\Task')->raw([
            'body' => ''
        ]);

        $this->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->post($project->path() . '/tasks', [
            'body' => 'Test task'
        ])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', [
            'body' => 'Test task'
        ]);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $task = $project->addTask('Test task');

        $this->patch($task->path(), ['body' => 'Changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', [
            'body' => 'Changed'
        ]);
    }
}
