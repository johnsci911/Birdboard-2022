<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

	/** @test */
	public function a_user_has_accessible_projects()
	{
		$john = $this->signIn();

		app(ProjectFactory::class)->ownedBy($john)->create();

		$this->assertCount(1, $john->accessibleProjects());

		$sally = factory(User::class)->create();
		$nick = factory(User::class)->create();

		$sallyProject = tap(app(ProjectFactory::class)->ownedBy($sally)->create())->invite($nick);

		$this->assertCount(1, $john->accessibleProjects());

		$sallyProject->invite($john);

		$this->assertCount(2, $john->accessibleProjects());
	}
}
