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

          @forelse ($project->tasks as $task)
            <div class="mb-3 card">{{ $task->body }}</div>
          @empty
            <div class="mb-3 card">No tasks</div>
          @endforelse
        </div>

        <div>
          <h2 class="text-grey text-lg font-normal mb-3">General Notes</h2>
          <textarea class="card w-full" style="min-height: 200px;">lorem ipsum dolor sit amet, consectetur adip</textarea>
        </div>
      </div>
      <div class="lg:w-1/4 px-3">
        @include('projects.card')
      </div>
    </div>
  </main>

</body>
@endsection
