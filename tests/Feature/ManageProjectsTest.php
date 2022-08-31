<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\ProjectFactory;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_manage_project()
    {
        $project = app(ProjectFactory::class)->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $this->followingRedirects()
            ->post('/projects', $attributes = factory(Project::class)->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->create();

        $this->patch($project->path(), $attributes = [
            'title' => 'Changed',
            'description' => 'Changed',
            'notes' => 'Changed'
        ])->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    function unauthorized_users_cannot_delete_a_project()
    {
        $project = app(ProjectFactory::class)->create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())
            ->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)->delete($project->path())
            ->assertStatus(403);
    }

    /** @test */
    function a_user_can_delete_a_project()
    {
        $this->withoutExceptionHandling();

        $project = app(ProjectFactory::class)->create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->create();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_project_of_others()
    {
        $this->signIn();

        $project = app(ProjectFactory::class)
            ->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_project_of_others()
    {
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->patch($project->path(), [])->assertStatus(403);
    }
    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_user_can_update_a_projects_general_notes()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->create();

        $this->patch($project->path(), $attributes = [
            'notes' => 'Changed'
        ]);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        // Given we're signed in
        // and we've been invited to a project that was not created by us
        $project = tap(app(ProjectFactory::class)->create())->invite($this->signIn());

        // when I visit my dashboard, I should see my project
        $this->get('/projects')
            ->assertSee($project->title);
    }
}
