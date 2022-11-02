
## Marking completed

1. view에 completed 버튼 추가  
> todos.blade.php
```php
    <a href="" class="btn btn-success btn-sm">V</a>
```
2. 여기에 if문으로 조건 추가  
completed이면 버튼이 안보이고 completed가 아니면 버튼이 보여야 한다. 
> todos.blade.php
```php
    @if (!$todo->completed)
      <a href="" class="btn btn-success btn-sm">&check;</a>
    @endif
```
3. completed route에 연결하고 completed route 작성  

> todos.blade.php
```php
    @if (!$todo->completed)
      <a href="{{ route('todo.completed', ['id' => $todo->id]) }}" class="btn btn-success btn-sm">&check;</a>
    @endif
```
> web.php
```php
Route::get('/todo/completed/{id}', [
  'uses' => 'TodosController@completed',
  'as'   => 'todo.completed'
]);
```

4. Controller 수정  
completed 컬럼의 값을 0에서 1로 바꾼 후 저장한다.
> TodosController.php
```php
  public function completed($id)
  {
    $todo = Todo::find($id);

    $todo->completed = 1;
    $todo->save();

    return redirect()->back();
  }
```

5. view를 다시 수정  
completed되면 버튼이 안나오는 대신 완료되었음을 표시해보자.  
> todos.blade.php
```php
    @if (!$todo->completed)
      <a href="{{ route('todo.completed', ['id' => $todo->id]) }}" class="btn btn-success btn-sm">&check;</a>
    @else
      <span class="alert-success">completed</span>
    @endif
```

