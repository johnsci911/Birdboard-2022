@extends('layouts.app')

@section('content')
  <header class="flex items-center mb-3 py-4">
    <div class="flex justify-between items-end w-full">
      <p class="text-grey text-sm font-normal">
        <a href="/projects" class="text-grey text-sm font-normal no-underline">My projects</a> / {{ $project->title }}
      </p>
      <a href="/projects/create" class="button">New Project</a>
    </div>
  </header>

  <main>
    <div class="lg:flex -mx-3">
      <div class="lg:w-3/4 px-3 mb-6">
        <div class="mb-8">
          <h2 class="text-grey text-lg font-normal mb-3">Tasks</h2>

          @foreach ($project->tasks as $task)
            <div class="mb-3 card">
              <form method="POST" action="{{ $task->path() }}">
                @method('PATCH')
                @csrf
                <div class="flex">
                  <input value="{{ $task->body }}" name="body" class="w-full {{ $task->completed ? 'text-grey' : '' }}"></input>
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
          <h2 class="text-grey text-lg font-normal mb-3">General Notes</h2>

          <form action="{{ $project->path() }}" method="POST">
            @method('PATCH')
            @csrf
            <textarea
              class="card w-full mb-4"
              style="min-height: 200px;"
              placeholder="Add notes..."
            >{{ $project->notes }}</textarea>

            <button class="button" type="submit">Save</button>
          </form>
        </div>
      </div>
      <div class="lg:w-1/4 px-3">
        @include('projects.card')
      </div>
    </div>
  </main>

</body>
@endsection
