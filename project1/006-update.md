
## Update

1. update 버튼 추가  
delete버튼을 복사해서 update버튼을 만든다.  
> todos.blade.php
```php
    <a href="{{ route('todo.update', ['id' => $todo->id]) }}" class="btn btn-primary btn-xs">update</a>
```

2. route 추가  
> routes/web.php
```php
Route::get('/todo/update/{id}', [
  'uses' => 'TodosController@update',
  'as'   => 'todo.update'
]);
```

3. update method 추가
id를 인자로 해서 데이터베이스에서 찾은 다음 이것을 가지고 view를 반환한다.
> TodosController.php
```php
  public function update($id)
  {
    $todo = Todo::find($id);
    return view('update')->with('todo', $todo);
  }
```
'update'라는 view를 반환하는 것으로 되어있지만, 아직 update라는 view가 없기
때문에 만들어야 한다.  

4. update view 작성  
todos view를 복사해서 update.blade.php를 만든다.  
foreach 부분은 필요없으므로 삭제한다.  
> update.blade.php
```php
@extends('layout')

@section('content')
  
  <div class="row justify-content-center">
    <div class="col-lg-6 col-lg-offset-3">
      <form action="/todo/save" method="post">
        {{ csrf_field() }}
        <input class="form-control input-lg" type="text" name="todo" value="{{ $todo->todo }}" placeholder="Create a new list">
      </form>
    </div>
  </div>

  <hr>
@stop
```
이제 확인해보면 update 버튼을 누르면 해당 리스트를 변경하고 save할 수 있는
페이지로 이동한다. 현재 input form은 좀 이상하지만...
어쨌든 이제 save를 해야하므로 save에 대한 route를 작성해야 한다. 

5. save route 작성  
> routes/web.php
```php
Route::post('/todo/save/{id}', [
  'uses' => 'TodosController@save',
  'as'   => 'todo.save'
]);
```
6. form action에 해당 route를 추가  
> update.blade.php
```php
      <form action="{{ route('todo.save', ['id' => $todo->id ]) }}" method="post">
```
7. save method 추가  
일단 이번에도 die and dump해보자.
> TodosController.php
```php
  public function save(Request $request, $id)
  {
    dd($request->all());
  }
```
8. 브라우저에서 확인  
update를 누르고 수정한 다음 엔터를 치면 dump로 데이터가 출력되는 것을 볼 수
있다.  

9. save method 수정  
die and dump 대신 데이터베이스를 update하도록 수정하자.  
> TodosController.php
```php
  public function save(Request $request, $id)
  {
    $todo = Todo::find($id);
    $todo->todo = $request->todo;
    $todo->save();

    return redirect()->back();
  }
```
10. todos router를 수정  
지금까지대로 하면 뒤로가기를 몇 번 누른다음 다시 새로고침을 해야 리스트를 확인할
수 있다. 
route를 조금 수정해보자.  
> web.php
```php
Route::get('/todos', [
  'uses' => 'TodosController@index',
  'as'   => 'todos'
]);
```
이렇게 하면 todos로 바로 redirect가 가능하다.  
> TodosController.php
```php
  public function save(Request $request, $id)
  {
    $todo = Todo::find($id);
    $todo->todo = $request->todo;
    $todo->save();

    return redirect()->route('todos');
  }
```
이제 확인해보면 update에서 수정하면 바로 리스트로 돌아와 바뀐 목록을 볼 수 있다.  



