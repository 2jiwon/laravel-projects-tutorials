## Creating Users

### Users 페이지 만들기  

1. resource/views/admin 밑에 users 폴더 만들고 index.blade.php 만듬  
- post와 비슷하니 post에서 index를 복사해서 만들고 user에 맞게 고친다.  
```php
      <thead>
        <th>아바타</th>
        <th>이름</th>
        <th>권한</th>
        <th>수정</th>
        <th>삭제</th>
      </thead>

      ...

      @if($users->count() > 0)

        @foreach($users as $user)
          <tr>
            <td>
              <img src="{{ asset($user->profile->avatar) }}" alt="" width="60px" height="60px" style="border-radius: 50%;">
            </td>
            <td>
                {{ $user->name }}
            </td>
            <td>
              권한
            </td>
            <td><!-- 수정 -->
              <a class="btn btn-sm btn-info" href="{{ route('user.edit', ['id' => $user->id ]) }}">
                <span><i class="fas fa-pencil-alt"></i></span>
              </a>
            </td>
            <td><!-- 삭제 -->
              <a class="btn btn-sm btn-danger" href="{{ route('user.delete', ['id' => $user->id ]) }}">
                <span><i class="fas fa-trash-alt"></i></span>
              </a>
            </td>
          </tr>      
        @endforeach

      @else
        <tr>
          <td colspan=5" class="text-center">등록된 사용자가 없습니다.
          </td>
        </tr>
      @endif
```
2. route 추가  
> web.php
```php
  Route::get('/users', [
    'uses'  => 'UsersController@index',
    'as'    => 'users'
  ]);
```
3. controller 추가  
```bash
vagrant@homestead:~/code/blog$ php artisan make:controller UsersController --resource
Controller created successfully.
```
4. controller 수정  
> app/Http/Controllers/UsersController.php
```php
use App\User;
...
    public function index()
    {
      return view('admin.users.index')->with('users', User::all());
    }
```
5. users 링크 만들기  
> resources/views/layouts/app.blade.php
```php
                  <li class="list-group-item">
                    <a href="{{ route('users') }}">사용자</a>
                  </li>
```

?? 문제 발생 ??  
사용자 이름이 표시가 안됨...  

### 사용자 추가 페이지 만들기  

1. 사용자 추가하는 링크 만들기  
> resources/views/layouts/app.blade.php
```php
                  <li class="list-group-item">
                    <a href="{{ route('user.create') }}">사용자 추가</a>
                  </li>
```
2. route 추가  
> web.php
```php
  Route::get('/user/create', [
    'uses' => 'UsersController@create',
    'as'   => 'user.create'
  ]);
```
3. tags에서 create 복사해서 users밑에 만들어주기  
> resources/views/admin/users/create.blade.php
```php
    <form action="{{ route('user.store') }}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        <label for="name">사용자 이름</label>
        <input class="form-control" type="text" name="name">
      </div>

      <div class="form-group">
        <label for="email">E-mail</label>
        <input class="form-control" type="email" name="email">
      </div>
```
4. route 추가  
> web.php
```php
  Route::post('/user/store', [
    'uses' => 'UsersController@store',
    'as'   => 'user.store'
  ]);
```
5. method 수정  
> UsersController.php
```php
use App\User;
use App\Profile;
...

    public function create()
    {
      return view('admin.users.create');
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'name'  => 'required',
        'email' => 'required|email'
      ]);

      $user = User::create([
        'name'  => $request->name,
        'email' => $request->email,
        'password' => bcrypt('password')  // 모든 사용자의 기본 패스워드
      ]);
    }
```
6. profile을 추가하기 전에 profile table을 수정  
- 일단 avatar, about 등에 null을 허용함  
> database/migrations/2018_11_06_112223_create_profiles_table.php
```php
            $table->string('avatar')->nullable();
            $table->integer('user_id');
            $table->text('about')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
```
7. 다시 controller로 돌아와서 profile 부분 추가  
> UsersController.php
```php
      $profile = Profile::create([
        'user_id' => $user->id
      ]); 
```
8. profile에서 fillable 작성  
> app\Profile.php
```php
  protected $fillable = ['user_id', 'avatar', 'about', 'youtube', 'facebook'];
```
9. controller에서 session 추가  
> UsersController.php
```php
use Session;
...
      Session::flash('success', '사용자가 성공적으로 추가되었습니다.');

      return redirect()->route('users');
```
10. migration 실행  
```bash
vagrant@homestead:~/code/blog$ php artisan migrate:refresh
Rolling back: 2018_11_06_112223_create_profiles_table
Rolled back:  2018_11_06_112223_create_profiles_table
Rolling back: 2018_11_04_150519_create_post_tag_table
Rolled back:  2018_11_04_150519_create_post_tag_table
Rolling back: 2018_11_04_145029_create_tags_table
Rolled back:  2018_11_04_145029_create_tags_table
Rolling back: 2018_11_02_062819_create_categories_table
Rolled back:  2018_11_02_062819_create_categories_table
Rolling back: 2018_11_02_062032_create_posts_table
Rolled back:  2018_11_02_062032_create_posts_table
Rolling back: 2014_10_12_100000_create_password_resets_table
Rolled back:  2014_10_12_100000_create_password_resets_table
Rolling back: 2014_10_12_000000_create_users_table
Rolled back:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2018_11_02_062032_create_posts_table
Migrated:  2018_11_02_062032_create_posts_table
Migrating: 2018_11_02_062819_create_categories_table
Migrated:  2018_11_02_062819_create_categories_table
Migrating: 2018_11_04_145029_create_tags_table
Migrated:  2018_11_04_145029_create_tags_table
Migrating: 2018_11_04_150519_create_post_tag_table
Migrated:  2018_11_04_150519_create_post_tag_table
Migrating: 2018_11_06_112223_create_profiles_table
Migrated:  2018_11_06_112223_create_profiles_table
vagrant@homestead:~/code/blog$ php artisan db:seed
Seeding: UsersTableSeeder
Database seeding completed successfully.
```

이제 브라우저에서 확인해보면 새 사용자를 추가할 수 있다.  
다음에는 사용자 기본 아바타를 등록하는 법을 알아보겠다.  

