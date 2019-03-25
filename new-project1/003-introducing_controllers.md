
## Controllers 소개  

Controllers는 사용자가 특정 route로 접근했을때 무엇을 보여줄지를 결정하는 class이다.  

[컨트롤러는 MVC 패턴의 구성 요소로 입력된 정보를 처리하고 모델을 호출하고 뷰를
생성하여 결과를 전달하는 역할을 수행합니다.](https://www.lesstif.com/pages/viewpage.action?pageId=26084501)


### controller 생성  

직접 controller를 생성할 수도 있지만, php artisan을 이용해서 controller를 생성한다.  

*코딩 규약을 확인해보면, controller이름은 StudlyCaps 형식으로 작성한다.*

```bash  
$ php artisan make:controller 컨트롤러이름  
```
```bash  
vagrant@homestead:~/code/todos-app$ php artisan make:controller AboutController
Controller created successfully.                                                 
```

### controller 파일 구성  

처음에 생성된 controller 파일 내용은 다음과 같다.  
간단히, namespace는 폴더 개념으로 생각하면 된다.  

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
  //
}               
```

### controller 사용  

다시 ``routes/web.php`` 파일로 돌아가서 about route를 다음과 같이 변경한다.  

```php
// routes/web.php  

Route::get('about', 'AboutController@index');
```
즉, 이전에 함수를 사용하던 방식을 대신해서 그 자리에 '컨트롤러@메소드' 형식으로 사용할 수 있다.  
하지만 아직 AboutController class안에 index 메소드를 작성하지 않았으므로 다시
브라우저에서 about 으로 접속하면 에러가 난다.  

다시 AboutController 파일을 열고 index 메소드를 작성하자.  

```php 
// app/Http/Controllers/AboutController.php

...

class AboutController extends Controllers
{
    public function index()
    {
        // 여기에서 무엇이든 할 수 있지만, 우선 처음과 같이 view를 반환해보자.  
        return view('about');
    } 
}
```
이제 브라우저에서 about으로 접속하면 맨 처음과 같이 about page를 볼 수 있다.  
하지만 함수로 작성했던 것보다 훨씬 깔끔하고 직관적이게 되었다.  

