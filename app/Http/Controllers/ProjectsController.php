<?php

namespace App\Http\Controllers;

use App\Project;
use Auth;

class ProjectsController extends Controller
{
    public function index()
	{
		$projects = Auth::user()->projects;

		return view('projects.index', compact('projects'));
	}

	public function show(Project $project)
	{
		if (Auth::user()->isNot($project->owner)) {
			abort(403);
		}

		return view('projects.show', compact('project'));
	}

	public function store()
	{
		Auth::user()->projects()->create(
			request()->validate([
				'title' => 'required',
				'description' => 'required',
			])
		);

		return redirect('/projects');
	}
}
