@extends('layout')

@section('content')

  <div class="row justify-content-center">
    <div class="col-lg-12">
      <form action="{{ route('todo.save', ['id' => $todo->id ]) }}" method="post">
        {{ csrf_field() }}
        <input class="form-control input-lg" type="text" name="todo" value="{{ $todo->todo }}" placeholder="Create a new list">
      </form>
    </div>
  </div>
@stop
