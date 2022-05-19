<!DOCTYPE html>
<html>
<head>
  <title>Projects</title>
</head>
<body>
  <h1>Birdboard</h1>
  <ul>
    @forelse ($projects as $project)
	  <li>{{ $project->title }} <a href="{{ $project->path() }}">show</a></li>
	@empty
	  <li>No projects yet.</li>
	@endforelse
  </ul>
</body>
</html>
