@extends('layouts.app')

@section('content')
  <header class="flex items-center mb-3 py-4">
    <div class="flex flex-col md:flex-row justify-between items-center w-full">
      <p class="text-default text-sm font-normal mb-4">
        <a href="/projects" class="text-default text-sm font-normal no-underline">My projects</a> / {{ $project->title }}
      </p>
      <div class="flex items-center">
        @foreach ($project->members as $member)
          <img
            src="{{ gravatar_url($member->email) }}"
            alt="{{ $member->name }}'s avatar"
            class="rounded-full w-8 mr-2"
          >
        @endforeach

        <img
          src="{{ gravatar_url($project->owner->email) }}"
          alt="{{ $project->owner->name }}'s avatar"
          class="rounded-full w-8 mr-2"
        >

        <a href="{{ $project->path() . '/edit' }}" class="button ml-6">Edit Project</a>
      </div>
    </div>
  </header>

  <main>
    <div class="lg:flex -mx-3">
      <div class="lg:w-3/4 px-3 mb-6">
        <div class="mb-8">
          <h2 class="text-default text-lg font-normal mb-3">Tasks</h2>

          @foreach ($project->tasks as $task)
            <div class="mb-3 card">
              <form method="POST" action="{{ $task->path() }}">
                @method('PATCH')
                @csrf
                <div class="flex">
                  <input value="{{ $task->body }}" name="body" class="bg-card w-full {{ $task->completed ? 'text-slate-500 line-through' : '' }}"></input>
                  <input type="checkbox" name="completed" onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}></input>
                </div>
              </form>
            </div>
          @endforeach

          <form action="{{ $project->path() . '/tasks' }}" method="POST">
            @csrf
            <input class="mb-3 card w-full" placeholder="Add a new task..." name="body"></input>
          </form>
        </div>

        <div>
          <h2 class="text-default text-lg font-normal mb-3">General Notes</h2>

          <form action="{{ $project->path() }}" method="POST">
            @method('PATCH')
            @csrf
            <textarea
              name="notes"
              class="card w-full mb-4"
              style="min-height: 200px;"
              placeholder="Add notes..."
            >{{ $project->notes }}</textarea>

            <button class="button" type="submit">Save</button>
          </form>

          @include('errors')
        </div>
      </div>
      <div class="lg:w-1/4 px-3">
        @include('projects.card')
        @include('projects.activity.card')

        @can ('manage', $project)
          @include('projects.invite')
        @endcan
      </div>
    </div>
  </main>
</body>
@endsection

