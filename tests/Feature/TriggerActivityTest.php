<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\ProjectFactory;

class TriggerActivityTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function creating_a_project()
	{
		$project = app(ProjectFactory::class)->create();

		$this->assertCount(1, $project->activity);
		$this->assertEquals('created', $project->activity[0]->description);
	}

	/** @test */
	function updating_a_project()
	{
		$project = app(ProjectFactory::class)->create();

		$project->update([
			'title' => 'Changed'
		]);

		$this->assertCount(2, $project->activity);
		$this->assertEquals('updated', $project->activity->last()->description);
	}

	/** @test */
	function creating_a_new_task()
	{
		$project = app(ProjectFactory::class)->create();

		$project->addTask('Some task');

		$this->assertCount(2, $project->activity);

		tap($project->activity->last(), function ($activity) {
			$this->assertEquals('created_task', $activity->description);
			$this->assertInstanceOf(Task::class, $activity->subject);
			$this->assertEquals('Some task', $activity->subject->body);
		});
	}

	/** @test */
	function completing_a_task()
	{
		$project = app(ProjectFactory::class)->withTasks(1)->create();

		$this->actingAs($project->owner)
			->patch($project->tasks[0]->path(), [
				'body' => 'foobar',
				'completed' => true
			]);

		$this->assertCount(3, $project->activity);

		tap($project->activity->last(), function ($activity) {
			$this->assertEquals('completed_task', $activity->description);
			$this->assertInstanceOf(Task::class, $activity->subject);
		});
	}

	/** @test */
	function incompleting_a_task()
	{
		$project = app(ProjectFactory::class)->withTasks(1)->create();

		$this->actingAs($project->owner)
			->patch($project->tasks[0]->path(), [
				'body' => 'foobar',
				'completed' => true
			]);

		$this->assertCount(3, $project->activity);

		$this->patch($project->tasks[0]->path(), [
			'body' => 'foobar',
			'completed' => false
		]);

		$project->refresh();

		$this->assertCount(4, $project->activity);
		$this->assertEquals('incompleted_task', $project->activity->last()->description);
	}

	function deleting_a_task()
	{
		$project = app(ProjectFactory::class)->withTasks(1)->create();

		$project->tasks[0]->delete();

		$this->assertCount(3, $project->activity);
	}
}
