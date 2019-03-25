
## Views 소개  

``php artisan serve`` 명령으로 브라우저에서 보이는 페이지는 어디서 확인할 수
있을까? 바로 views 디렉토리 아래에 welcome.blade.php 파일로, *View* 파일이다.  

Laravel은 **Model View Controller Framework**라는 것을 기억할 것!  

*View*는 즉 사용자가 브라우저에서 보는 것을 의미한다.  

- Laravel이라는 텍스트를 Todos App으로 변경해보자.  

```bash
vagrant@homestead:~/code/todos-app$ vi resources/views/welcome.blade.php    
```
```php
            <div class="content">                
                <div class="title m-b-md">              
                   Todos App
               </div>               
```

그러면, Laravel은 어떻게 우리가 127.0.0.1:8000 페이지에 접속했을 뿐인데 welcome.blade.php 파일을 보여주는 것일까?  
이때 등장하는 것이 바로 **Route** 이다.  

routes 디렉토리 아래에 web.php 파일을 열어보자.  

```bash  
vagrant@homestead:~/code/todos-app$ vi routes/web.php 
```
그러면 다음과 같은 내용이 들어있다. 

```php
Route::get('/', function () {
    return view('welcome');
});   
```
Route라는 class가 있고, static 함수 get이 있고, 매개변수로 '/', 그리고 다음 매개변수로는 함수를 받는다.   
첫번째 매개변수는 '/', 즉 ``127.0.0.1:8000``을 가리킨다.  
두번째 매개변수인 함수는 단순히 'welcome'이라는 view를 반환하고 있다.  

다시 말해서 사용자가 '/', 즉 index 페이지에 접속하면 그 뒤에오는 함수를 호출하게 되어있다. 

routes는 어떤 특정한 페이지를 보여줄지를 결정하는, 중요한 파일이다.  

- 'about'이라는 route를 추가해보자.  

```php

// routes/web.php  

Route::get('about', function () {
    return view('about');
});
```
- resources/views 디렉터리 아래에 about.blade.php 파일을 만들고
welcome.blade.php의 파일을 복사해서 대충 아래와 같은 기본 내용을 적어준다.  

```php  
// resources/views/about.blade.php  

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compartible" content="ie=edge">
    <title>About page</title>
  </head>

  <body>
    <h1>About Page</h1>
  </body>
  
</html>
```
그리고 브라우저에서 ``todos-app.test/about`` 또는 ``127.0.0.1:8000/about`` (단, php artisan serve가 실행중이어야함)으로 접속하면 about page가 보인다.

