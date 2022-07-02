<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\ProjectFactory;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_project_can_have_tasks()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->create();

        $this->post($project->path() . '/tasks', [
            'body' => 'Test task'
        ]);

        $this->get($project->path())
            ->assertSee('Test task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks[0]->path(), [
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

        $project = app(ProjectFactory::class)->ownedBy($this->signIn())
            ->create();

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

        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

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

        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'Changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', [
            'body' => 'Changed'
        ]);
    }
}
