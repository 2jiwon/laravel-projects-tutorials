
## Views and Controllers

### View

우리가 첫 페이지로 보게되는 화면은 ``/resources/views/welcome.blade.php`` 파일이다.  

즉, MVC 중에 V(View)를 담당하는 것이다.

참고로 M은 Model, 데이터베이스로부터 데이터를 fetching하는 것을 담당한다.  
C는 Controller, 데이터베이스로부터 데이터를 받아서 view로 보내고 하는 것들이다.  

MVC에 포함되지는 않지만 중요한 것이 바로 'Router'이다.  
Router는 채널링 같은 것이다.  
사용자의 URL요청에 따라 Controller에게 '사용자가 이것을 요청했으니 보내라'하는
식으로 전달하게 된다.   

Router 파일을 보면 '/'가 쓰이는데, 이것은 사이트의 home이다.  
```php
Route::get('/', function () {
    return view('welcome');
});
```
그리고 보다시피 'view'를 반환하는데 이 view가 바로 resources 디렉토리 아래에
view에 해당한다.  

### 새로운 view 만들기  

1. welcome.blade.php 를 복사해서 new.blade.php 를 만든다.

2. title 및 내용을 조금 바꾸고 저장한다.  

3. routes/web.php 파일에 새로운 view에 대한 route를 추가
```php
Route::get('/new', function () {
  return view('new');
});
```
4. todos.test/new로 접속해서 새로운 view확인 

### Controller

컨트롤러는 데이터베이스로부터 데이터를 가져와서 view에 전달하는 일을 담당한다.  

1. 새 컨트롤러 생성  
```bash
vagrant@homestead:~/code/Todos$ php artisan make:controller PagesController
Controller created successfully.
```
생성된 컨트롤러는 ``app/Http/Controllers``아래에 위치한다.  

2. 컨트롤러와 router의 연결  
아까 만든 new view에 대한 route를 작성한 것을 수정해서 새로운 컨트롤러와
연결해보자.  

function대신 array 형태로 바꾸고, 먼저 
``무엇을 사용할 것인지 => 컨트롤러 이름 @ 메서드 이름`` 
```php
Route::get('/new', [
  'uses' => 'PagesController@new'
]);
```
3. 컨트롤러 파일 수정  
방금 우리는 PagesController@메서드 이름에서 'new'라는 메서드를 지정했으므로
여기에 new라는 메서드를 만들어 줘야한다.  
> app/Http/Controllers/PagesController.php
```php
    // new method
    public function new() {

    }
```
이 method안에서 여러가지를 할 수 있지만, 일단 기존과 같이 new view를 반환하게끔
해보자.  
```php
    // new method
    public function new() {
      return view('new');
    }
```
4. 브라우저에서 확인  
결과는 아까와 똑같다. 우리는 지금 단지 router에서 controller로 그 담당만
변경했을 뿐이니까.  

