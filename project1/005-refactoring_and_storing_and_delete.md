
## Refactoring Layout

1. bootstrap을 include하기  
bootstrap cdn을 copy해서 view에 붙여넣기  
> todos.blade.php
```php
        <!-- Bootsrap 4.1 -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
```
2. layout 파일을 만들기  
- todos.blade.php를 복사해서 layout.blade.php 파일 생성  
  - 중간에 foreach부분은 삭제해서 내용은 지운다 
  - 대신 ``@yield``를 사용해서 내용을 끌어온다  

3. todos view를 수정  
todos.blade.php는 기존 내용을 전부 지우고 다음과 같이 작성한다.  
> todos.blade.php
```php
@extends('layout')

@section('content')
  @foreach($todos as $todo)
    {{ $todo->todo }}
    <hr>
  @endforeach
@stop
```
4. new, welcome도 똑같이 작업 가능  
> welcome.blade.php
```php
@extends('layout')

@section('content')
  <h1> Welcome to laravel </h1>

@stop
```
---

5. welcome 페이지에서 todos에 대한 링크만들기  
> welcome.blade.php
```php
@extends('layout')

@section('content')
  <a href="/todos"><h1>Visit my todo lists</h1></a>
@stop
```
![img](./Todos3.png)  

---

## Stroing data

이제, 어떻게 하면 새로운 Todo list를 추가할까?  
즉, 어떻게 데이터베이스에 새로 데이터를 넣을 수 있을까?  

1. todos view에 form 추가 
> todos.blade.php
```php
@section('content')

<div class="row justify-content-center">
  <div class="col-lg-6 col-lg-offset-3">
  <form action="" method="post">
    <input class="form-control input-lg" type="text" name="todo" placeholder="Create a new list">
  </form>
  </div>
</div>
```
그리고 이 form의 action에는 ``/create/todo``를 적어준다.  
```php
  <form action="/create/todo" method="post">
```
2. form에서 만든 route 작성  
방금 작성항 /create/todo에 대한 route를 작성하자  
> routes/web.php
```php
Route::post('/create/todo', [
  'uses' => 'TodosController@store'
  // TodosController에서 store method를 사용할 거라는 의미
]);
```
3. TodosController에서 store method 만들기  
> app/Http/Controllers/TodosController.php
```php
  public function store()
  {
  }
```
이 method는 post에 대한 method이므로 인자를 받게 된다.  
Laravel에서는 이미 'Request'라는 class를 제공하므로 이것을 사용하면 된다.  
```php
  public function store(Request $request)
  {
  }
```
일단 무슨 일이 일어나는지를 파악하기 위해 'dd(die and dump)'를 써서 테스트를
해보자.  
```php
  public function store(Request $request)
  {
    dd($request);
  }
```
이렇게 하면 에러가 일어나는데 왜냐하면 laravel이 request 처리 부분을 보안상
막아놓았기 때문(?)이다.그래서 이것을 처리하려면 'token'이라는 것을 같이
보내야한다. Laravel은 이것에 대해서도 방법을 제공하고 있다.  
```php
> todos.blade.php
```php
  <form action="/create/todo" method="post">
    {{ csrf_field() }}
    <input class="form-control input-lg" type="text" name="todo" placeholder="Create a new list">
  </form>
```
이렇게 하면 저절로 token을 같이 form으로 보내면서 처음에 원한대로 데이터를
dump하게 된다.  

4. store method 작성  
> TodosController.php
```php
  public function store(Request $request)
  {
    $todo = new Todo;

    $todo->todo = $request->todo;
    $todo->save();
  }
```
이렇게해서 todo를 저장한 다음에는 다시 redirect를 해줘야 한다.  
```php
    $todo->todo = $request->todo;
    $todo->save();

    return redirect()->back();
```
이제 테스트해보면 새로 작성하는 리스트가 하단에 추가되는 것을 볼 수 있다.  

## delete data

1. todo list에 삭제 버튼 추가하기  
각 todo 목록 옆에 삭제 버튼을 추가하자.  
> todos.blade.php
```php
  @foreach($todos as $todo)
    {{ $todo->todo }}
    <button class="btn btn-danger"> X </button>
    <hr>
  @endforeach
```
그런데 이 delete는 사실 route를 거쳐야하므로 button이 아니라 a태그로 만들자  
```php
    <a href="" class="btn btn-danger"> X </a>
```
2. id 
개별 list를 지우려고 하면 이제 'id'가 필요해진다.  
이 id를 가지고와야 개별로 뭔가 작업이 가능할 것이다.  
> routes/web.php
```php
Route::get('/todo/delete/{id}', [
  'uses' => 'TodosController@delete',
  'as'   => 'todo.delete'
]);
```
3. controller
이제 controller에서 delete method를 추가하자 
일단은 die and dump로 테스트를 해본다.
> TodosController.php
```php
  public function delete($id)
  {
    dd($id);
  }
```
4. view에서 연결  
> todos.blade.php
```php
    <a href="{{ route('todo.delete', ['id' => $todo->id]) }}" class="btn btn-danger"> X </a>
```
이제 여기까지 하면 각 리스트에 /todo/delete/뒤에 id가 붙는 것을 볼 수 있다.
이것을 누르면 현재는 dd로 연결해놨기 때문에 각 id가 dump된다.  

5. delete하기  
Eloquent model을 활용하자  
```php
  public function delete($id)
  {
    $todo = Todo::find($id);
    $todo->delete();

    return redirect()->back();
  }
```
6. (내가 추가함) soft delete활용  
- Todo 모델에 soft delete class 추가  
> app\Todo.php
```php
use Illuminate\Database\Eloquent\SoftDeletes;
...

    use SoftDeletes;
    protected $dates = ['deleted_at'];
```
- migration 파일생성  
```bash
vagrant@homestead:~/code/Todos$ php artisan make:migration add_deleted_at_column_to_todos_table --table=todos
Created Migration: 2018_11_01_064201_add_deleted_at_column_to_todos_table
```
- migration파일 수정  
> 2018_11_01_064201_add_deleted_at_column_to_todos_table.php
```php
    public function up()
    {
        Schema::table('todos', function (Blueprint $table) {
          $table->SoftDeletes();
        });
    }
...
    public function down()
    {
        Schema::table('todos', function (Blueprint $table) {
          $table->dropColumn('deleted_at');
        });
    }
```
- migration 진행  
```bash
vagrant@homestead:~/code/Todos$ php artisan migrate
Migrating: 2018_11_01_064201_add_deleted_at_column_to_todos_table
Migrated:  2018_11_01_064201_add_deleted_at_column_to_todos_table
```
 
이렇게하면 목록에서는 지워지지만 데이터베이스에서는 완전히 사라지는대신에
deleted_at 컬럼에 삭제한 날짜와 시간이 들어간다.  




