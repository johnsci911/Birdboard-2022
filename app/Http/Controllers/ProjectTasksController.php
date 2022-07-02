<?php

namespace App\Http\Controllers;

use App\Project;
use Auth;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        if (Auth::user()->isNot($project->owner)) {
            abort(403);
        }

        request()->validate([
            'body' => 'required'
        ]);

        $project->addTask(request('body'));

        return redirect($project->path());
    }
}
