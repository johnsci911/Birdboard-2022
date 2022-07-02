<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
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

    public function update(Project $project, Task $task)
    {
        if (Auth::user()->isNot($project->owner)) {
            abort(403);
        }

        request()->validate([
            'body' => 'required'
        ]);

        $task->update([
            'body' => request('body'),
            'completed' => request()->has('completed')
        ]);

        return redirect($project->path());
    }
}
