
## Default Admin

- 우선 버그 수정..
> app/Http/Controllers/RegisterController.php
> app/Http/Controllers/LoginController.php
```php
// redirect 주소가 그냥 /home으로만 되어있어서 앞에 admin을 붙여줘야 맞다.
    protected $redirectTo = '/admin/home';
```

데이터베이스를 refresh하고 나면 데이터들이 다 사라지므로 우리는 다시 register를
해야하는데, 생각해보면 관리자인데 등록부터 하는 것이 좀 이상한 것 같다.  

이때 필요한 것이 'seed', 'seeder' 이다.  

1. seeder 생성  
```bash
vagrant@homestead:~/code/blog$ php artisan make:seeder UsersTableSeeder
Seeder created successfully.
```
이제 database/seeds 디렉토리 아래에 가보면 UsersTableSeeder.php 파일이 있을 것이다.  
거기에서 default user를 설정해준다.  
> database/seeds/UsersTableSeeder.php
```php
    public function run()
    {
      App\User::create([
        'name'     => 'jiwon',
        'email'    => 'ljwjulian@gmail.com',
        // 비밀번호를 'password'로 설정한 것임
        'password' => bcrypt('password')
      ]);
    }
```
2. seeder를 등록  
주석처리를 해제만 하면 된다.  
> database/seeds/DatabaseSeeder.php
```php
   public function run()
    {
         $this->call(UsersTableSeeder::class);
    }
```

3. migrate refresh
```bash
vagrant@homestead:~/code/blog$ php artisan migrate:refresh                                            
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
```
4. db:seed 
```bash
vagrant@homestead:~/code/blog$ php artisan db:seed 
Database seeding completed successfully.
```
5. 접속해서 default user로 로그인  


## 의문점

그런데 UsersTableSeeder.php 파일은 .gitignore파일에 등록되어있지도 않은데
기본 사용자 정보가 바로 오픈되는 단점이 있다.
이 파일을 gitignore에 등록해야 하는건지, 아니면 다른 방식으로 이 정보가 오픈되지
않게 할 수 있을지를 봐야할듯... .env 파일을 사용한다던가.


