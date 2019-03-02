
### blog setting 페이지 만들기  

1. setting model 생성  
```bash
vagrant@homestead:~/code/blog$ php artisan make:model Setting -m
Model created successfully.
Created Migration: 2018_11_09_063821_create_settings_table
```
2. 파일 수정  
> 2018_11_09_063821_create_settings_table.php
```php
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_name');
            $table->string('contact_number');
            $table->string('contact_email');
            $table->string('address');
            $table->timestamps();
        });
```
3. setting model 수정  
- fillable
>  app\Setting.php
```php
class Setting extends Model
{
  protected $fillable = ['site_name', 'address', 'contact_number', 'contact_email'];
}
```

**추가** 4. php artisan migrate 해야함.

### blog setting seed 만들기 

1. seeder 생성  
```bash
vagrant@homestead:~/code/blog$ php artisan make:seeder SettingsTableSeeder
Seeder created successfully.
```
2. seeder 파일 수정  
> database/seeds/SettingsTableSeeder.php
```php
    public function run()
    {
      \App\Setting::create([
        'site_name' => "Laravel blog",
        'address'   => 'Seoul, Korea',
        'contact_number' => '+82 1234 5678',
        'contact_email'  => 'info@laravelblog.com'
      ]);
    }
```
- database에 seeder 등록  
> database/seeds/DatabaseSeeder.php
```php
   public function run()
    {
//       $this->call(UsersTableSeeder::class);
         $this->call(SettingsTableSeeder::class);
    }
```
- 기존 UserTableSeeder를 주석처리하고 나서 seeds 처리  
```bash
vagrant@homestead:~/code/blog$ php artisan db:seed 
Seeding: SettingsTableSeeder
Database seeding completed successfully.
```

### blog setting 메뉴에 연결 

1. controller 생성  
```bash
vagrant@homestead:~/code/blog$ php artisan make:controller SettingsController
Controller created successfully.
```
2. controller 수정  
> app/Http/Controllers/SettingsController.php
```php
use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
  public function update() {
    $settings = Setting::first();
  }
}
```
3. settings view 만들기  
- resource/views/admin 아래에 settings 폴더 만들고 settings.blade.php 파일 생성  
- settings 페이지는 users의 create 페이지와 흡사할 것이므로 복사해서 붙여넣음  
> settings.blade.php
```php
<div class="card">
  <div class="card-header">
    블로그 설정 
  </div>
  
  <div class="card-body">
    <form action="{{ route('settings.update') }}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        <label for="name">블로그 이름</label>
        <input class="form-control" type="text" name="site_name" value="{{ $settings->site_name }}">
      </div>

      <div class="form-group">
        <label for="address">주소</label>
        <input class="form-control" type="text" name="address" value="{{ $settings->address }}">
      </div>

      <div class="form-group">
        <label for="contact_number">전화번호</label>
        <input class="form-control" type="text" name="contact_number" value="{{ $settings->contact_number }}">
      </div>

      <div class="form-group">
        <label for="contact_email">이메일</label>
        <input class="form-control" type="email" name="contact_email" value="{{ $settings->contact_email }}">
      </div>

      <div class="form-group">
        <div class="text-center">
          <button class="btn btn-success" type="submit">변경완료</button>
        </div>
      </div>
      

    </form>
  </div>

</div>
```
4. route 추가  
- 우선 index에 대한 것부터 추가함 
> web.php
```php
  Route::get('/settings', [
    'uses' => 'SettingsController@index',
    'as'   => 'settings'
  ]);
```
5. index method 수정  
> SettingsController.php
```php
  public function index() {
    return view('admin.settings.settings')->with('settings', Setting::first());
  }
```
6. index에 대한 view 수정  
> resources/views/layouts/app.blade.php
```php
                  @if(Auth::user()->admin)
                    <li class="list-group-item">
                      <a href="{{ route('settings') }}">블로그 정보 수정</a>
                    </li>
                  @endif
```
7. index route에 middleware 추가  
> web.php
```php
  Route::get('/settings', [
    'uses' => 'SettingsController@index',
    'as'   => 'settings'
  ])->middleware('admin');
```

8. update route 추가  
> web.php
```php
  Route::post('/settings/update', [
    'uses' => 'SettingsController@update',
    'as'   => 'settings.update'
  ])->middleware('admin');
```

9. middleware를 route에서 제거하고 controller에 추가하기  
- web.php에서 middleware 제거  
- controller 수정  
> SettingsController.php
```php
  public function __construct() {
    $this->middleware('admin');
  }
```
10. settings table migrate  
```bash
vagrant@homestead:~/code/blog$ php artisan migrate
Migrating: 2018_11_09_063821_create_settings_table
Migrated:  2018_11_09_063821_create_settings_table
```

### blog setting update  
1. update method 수정  
> SettingsController.php
```php
use Session;
...
  public function update() {
    $this->validate(request(), [
      'site_name' => 'required',
      'contact_number' => 'required',
      'contact_email' => 'required',
      'address' => 'required'
    ]);

    $settings = Setting::first();

    $settings->site_name = request()->site_name;
    $settings->address   = request()->address;
    $settings->contact_email = request()->contact_email;
    $settings->contact_number = request()->contact_number;

    $settings->save();

    Session::flash('success', '블로그 설정 변경이 완료되었습니다.');
    return redirect()->route('settings');
  }
```

