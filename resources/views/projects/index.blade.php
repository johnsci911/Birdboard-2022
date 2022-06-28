@extends('layouts.app')

@section('content')
  <div class="flex items-center">
    <a href="/projects/create">New Project</a>
  </div>

  <div class="flex mx-auto">
    @forelse ($projects as $project)
      <div class="mr-4 bg-white rounded shadow">
        <h3>{{ $project->title }}</h3>
        <div>{{ $project->description }}</div>
      </div>
    @empty
      <div>No projects yet</div>
    @endforelse
  </div>

@endsection
