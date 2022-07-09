<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\ProjectFactory;

class ActivityFeedTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function creating_a_project_generate_activity()
	{
		$project = app(ProjectFactory::class)->create();

		$this->assertCount(1, $project->activity);
		$this->assertEquals('created', $project->activity[0]->description);
	}

	/** @test */
	function updating_a_project_generate_activity()
	{
		$project = app(ProjectFactory::class)->create();

		$project->update([
			'title' => 'Changed'
		]);

		$this->assertCount(2, $project->activity);
		$this->assertEquals('updated', $project->activity->last()->description);
	}
}
