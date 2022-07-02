@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="flex flex-col">
          You are logged in!
          <a href="/projects" class="text-lg font-normal no-underline mt-3 text-blue">Your Projects</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
