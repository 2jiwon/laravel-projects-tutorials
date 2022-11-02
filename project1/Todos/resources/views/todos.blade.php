@extends('layout')

@section('content')
  
  <div class="row justify-content-start">
    <div class="col-lg-12">
      <form action="/create/todo" method="post">
        {{ csrf_field() }}
        <input class="form-control input-lg" type="text" name="todo" placeholder="Create a new list">
      </form>

      <hr>

  @foreach($todos as $todo)
    {{ $todo->todo }}

    @if (!$todo->completed)
      <a href="{{ route('todo.completed', ['id' => $todo->id]) }}" class="btn btn-success btn-sm">&check;</a>
    @else
      <span class="alert-success">completed</span>
    @endif

    <a href="{{ route('todo.delete', ['id' => $todo->id]) }}" class="btn btn-danger btn-sm"> X </a>
    <a href="{{ route('todo.update', ['id' => $todo->id]) }}" class="btn btn-primary btn-sm">update</a>
    <hr>
  @endforeach

    </div>
  </div>
@stop

