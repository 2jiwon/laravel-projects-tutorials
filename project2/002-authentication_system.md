
## Authentication system

1. Authentication system을 실행(설치?)
```bash
vagrant@homestead:~/code/blog$ php artisan make:auth
Authentication scaffolding generated successfully.
```
이렇게 하면 브라우저에서 login register를 바로 확인할 수 있다.  

- 이 버튼들은 어떻게 보여지는 것인가?  
welcome.blade.php를 열어보면 다음의 내용을 확인할 수 있다.  
```php
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
```
여기에서 보면 'login' 이라는 Route가 있으면..이라고 되어있다.  
- 그러면 route를 확인해보자.  
```php
Auth::routes();
```
이전과는 다르게 이런 부분이 추가되었다.  
그러면 이것은 어떻게 사용할 수 있을까?  

```php
vagrant@homestead:~/code/blog$ php artisan route:list
+--------+----------+------------------------+------------------+------------------------------------------------------------------------+--------------+
| Domain | Method   | URI                    | Name             | Action                                                                 | Middleware   |
+--------+----------+------------------------+------------------+------------------------------------------------------------------------+--------------+
|        | GET|HEAD | /                      |                  | Closure                                                                | web          |
|        | GET|HEAD | api/user               |                  | Closure                                                                | api,auth:api |
|        | GET|HEAD | home                   | home             | App\Http\Controllers\HomeController@index                              | web,auth     |
|        | GET|HEAD | login                  | login            | App\Http\Controllers\Auth\LoginController@showLoginForm                | web,guest    |
|        | POST     | login                  |                  | App\Http\Controllers\Auth\LoginController@login                        | web,guest    |
|        | POST     | logout                 | logout           | App\Http\Controllers\Auth\LoginController@logout                       | web          |
|        | POST     | password/email         | password.email   | App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail  | web,guest    |
|        | GET|HEAD | password/reset         | password.request | App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm | web,guest    |
|        | POST     | password/reset         | password.update  | App\Http\Controllers\Auth\ResetPasswordController@reset                | web,guest    |
|        | GET|HEAD | password/reset/{token} | password.reset   | App\Http\Controllers\Auth\ResetPasswordController@showResetForm        | web,guest    |
|        | GET|HEAD | register               | register         | App\Http\Controllers\Auth\RegisterController@showRegistrationForm      | web,guest    |
|        | POST     | register               |                  | App\Http\Controllers\Auth\RegisterController@register                  | web,guest    |
+--------+----------+------------------------+------------------+------------------------------------------------------------------------+--------------+

```

2. login, register form

- login을 눌러보면 login form이 뜨는데, 이것은 어디에서 확인할 수 있을까?  
> resources/views/auth/login.blade.php

3. migrate
테스트를 위해 migrate를 실행해보자.
```bash
vagrant@homestead:~/code/blog$ php artisan migrate 
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
```
4. 브라우저에서 register 실행  
register를 누르고 등록하면 바로 login이 된다. 놀랍구리..

5. 과정을 따라가기  

이 과정은 바로 다음 부분인데  
> web.php
```php
Route::get('/home', 'HomeController@index')->name('home');
```
그래서 HomeController를 열어보면  
> HomeController.php
```php
    public function index()
    {
        return view('home');
    }
```
이렇게 home이라는 view를 반환하고 있음을 알 수 있다.  

> home.blade.php
```php
...
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
...
```
6. register 과정   
이번에는 RegisterController를 확인해보자.  
```php
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }
```
이것을 보면 name은 최대 255자까지 입력할 수 있고, email은 unique해야하고  
password는 최소 6글자 이상이어야 하고..등등의 조건을 확인할 수 있다.  

