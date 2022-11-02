
## Todos App 설정하기  

(먼저, 이전 강좌에서 생성한 AboutController와 about.blade.php 파일은 삭제한다.)  

### 새로운 TodosController 생성  

```bash  
vagrant@homestead:~/code/todos-app$ php artisan make:controller TodosController
Controller created successfully. 
```

### 새로운 view 생성  

``resources/views`` 폴더 아래에 ``todos`` 폴더를 만든다. todos 기능을 위한 모든
view 파일은 이 폴더 안으로 들어가면 된다.  

### 새로운 route 등록  

``routes/web.php``파일을 열고 todos route를 등록한다.  

```php
Route::get('todos', 'TodosController@index');   
```

### index page  

다음으로, 모든 todo list를 보여주는 페이지를 만들려고 한다.  
``resources/views/todos`` 디렉토리 아래에 ``index.blade.php`` 파일을 만들고, 기본적인 html 내용을 넣는다.  

```php
// resources/views/todos/index.blade.php

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>

<body>

</body>

</html>
```
타이틀 및 body에 적당한 내용을 넣는다.  

아직 이 페이지를 생성한 것만으로는 페이지를 볼 수 없다. TodosController로 가서 index 메소드를 생성해야 한다.  

```php
// app/Http/Controllers/TodosController.php
...

public function index()
{
    return view('todos.index');
}
```
todos 폴더 안에 index 파일은 온점(.)으로 연결한다.  

### 링크로 연결하기  

매번 주소창에 todos를 쳐서 들어갈 수는 없으니 href 태그를 사용해서 링크를 생성한다.  
```php
// resources/views/welcome.blade.php

    <a href="/todos">Todos</a>  
```

