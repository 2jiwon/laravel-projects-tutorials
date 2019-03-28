
## route model binding 소개  

TodosController.php 파일을 열어보면 edit, update 등 여러 메소드에서 ``$todo = Todo::find($todoId)`` 부분이 반복되고 있는 것을 볼 수 있다.  
라라벨에서는 이같은 반복을 피하기 위해 **Route Model Binding**이라는 기능을
가지고 있다.  

이 기능은 기본적으로 disabled 되어있으므로 enable로 바꿔주어야 한다.  

### type hint

일단 todo class를 type hint로 작성하면, 라라벨이 알아서 fetch 해준다.(??)

먼저, show 메서드를 가지고 작업을 해보자.  

*원래 내용*

```php
// app/Http/Controllers/TodosController.php

    public function show($todoId)
    {
        $todo = Todo::find($todoId);

        return view('todos.show')->with('todo', $todo);
    }
```
*변경*

```php
// app/Http/Controllers/TodosController.php

    public function show(Todo $todo)
    {
        return view('todos.show')->with('todo', $todo);
    }
```
타입 힌팅(type hinting)이라는 것을 사용하여 ``$todo = Todo::find($todoId)`` 한
줄을 통째로 지울 수 있다.

[이곳 설명도 참고](https://vegibit.com/route-model-binding-in-laravel/)  

*routes 파일의 와일드 카드 이름은 컨트롤러의 함수에 전달된 타입 힌트 변수와
정확히 일치해야합니다.*  

route 파일을 열어보면 다음과 같이 되어있기 때문에 ``{todo}``

```php
Route::get('todos/{todo}', 'TodosController@show');
```
우리는 ``Todo $todo``라고 쓸 수 있는 것이다.  ``Todo $id``라고 하면 동작하지
않을 것이다.  

show 메서드 외에 다른 메서드에도 똑같이 변경해준다.  


## route model binding의 사용 정리 

1. 먼저, route 파일에서 ``{todo}``와 같이 다이내믹 매개변수의 이름이 모델 단수형인지
   확인한다. 즉, 모델명이 Todo라면 매개변수 이름은 소문자로 된 todo여야 한다. 
2. 컨트롤러에서 동적 라우팅을 사용하는 메소드에 타입 힌트를 입력한다. 그러면
   라라벨은 자동적으로 데이터베이스에서 그 데이터를 찾아서 여기에 주입한다.  

