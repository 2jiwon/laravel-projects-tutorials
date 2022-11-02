
## todos 삭제하기 

라라벨의 데이터베이스에서 데이터를 삭제하는 방법을 알아보자.

### view 수정 - delete 링크 만들기  

show.blade.php 에 delete에 대한 링크를 추가한다.   
(강좌하고 스타일이 약간 차이날 수 있음)  

```php
// resources/views/todos/show.blade.php
...
        <a class="btn btn-danger btn-sm my-2 mx-2 float-right" href="/todos/{{ $todo->id }}/delete">Delete</a>
...
```
### delete route 추가

```php
// routes/web.php

Route::get('todos/{todo}/delete', 'TodosController@destroy');
```

### destroy 메소드 추가  

아래와 같이 작성하는 것으로 간단하게 데이터베이스에서 데이터를 삭제할 수 있게
된다.

```php
// app/Http/Controllers/TodosController.php

    public function destroy($todoId)
    {
      $todo = Todo::find($todoId);

      $todo->delete();

      return redirect('/todos');
    }
```

