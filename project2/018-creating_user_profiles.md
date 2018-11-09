
## Creating profiles for users

### 사용자 프로필

1. migration 파일 수정  
admin 필드를 추가
> database/migrations/2014_10_12_000000_create_users_table.php
```php
            $table->boolean('admin')->default(0);
```
2. profile table 생성 
- model 생성
```bash
vagrant@homestead:~/code/blog$ php artisan make:model Profile -m
Model created successfully.
Created Migration: 2018_11_06_112223_create_profiles_table
```
- migration 파일 수정  
> database/migrations/2018_11_06_112223_create_profiles_table.php
```php
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('avatar');
            $table->text('about');
            $table->string('facebook');
            $table->string('youtube');
            $table->timestamps();
        });
    }
```
3. default user seeder 수정  
> database/seeds/UserTableSeeder.php
```php
      App\User::create([
        'name'     => 'jiwon',
        'email'    => 'ljwjulian@gmail.com',
        'password' => bcrypt('password'),
        'admin'    => 1
      ]);
```
4. User에 대한 fillable 추가  
> app/User.php
```php
    protected $fillable = [
        'name', 'email', 'password', 'admin'
    ];
```
5. profile table에 user_id 필드 추가  
> database/migrations/2018_11_06_112223_create_profiles_table.php
```php
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('avatar');
            $table->integer('user_id');
            $table->text('about');
            $table->string('facebook');
            $table->string('youtube');
            $table->timestamps();
        });
```
6. user seeder 수정  
> database/seeds/UserTableSeeder.php
```php
    public function run()
    {
      $user = App\User::create([
        'name'     => 'jiwon',
        'email'    => 'ljwjulian@gmail.com',
        'password' => bcrypt('password'),
        'admin'    => 1
      ]);

      App\Profile::create([
        'user_id'     => $user->id,
        'avatar'      => 'link to image',
        'about'       => 'Lorem ipsum dolor sit amet, consectetur adminipisicing elit. Accusantium, est veniam non coporis sunt qua',
        'facebook'    => 'facebook.com',
        'youtubetube' => 'youtube.com',
      ]);
    }
```
7. migrate refresh 실행  
```bash
vagrant@homestead:~/code/blog$ php artisan migrate:refresh
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
```
- profiles 테이블 확인 
```bash
MariaDB [blog]> explain profiles;
+------------+------------------+------+-----+---------+----------------+
| Field      | Type             | Null | Key | Default | Extra          |
+------------+------------------+------+-----+---------+----------------+
| id         | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| avatar     | varchar(255)     | NO   |     | NULL    |                |
| user_id    | int(11)          | NO   |     | NULL    |                |
| about      | text             | NO   |     | NULL    |                |
| facebook   | varchar(255)     | NO   |     | NULL    |                |
| youtube    | varchar(255)     | NO   |     | NULL    |                |
| created_at | timestamp        | YES  |     | NULL    |                |
| updated_at | timestamp        | YES  |     | NULL    |                |
+------------+------------------+------+-----+---------+----------------+
8 rows in set (0.004 sec)
```

8. user profile의 avatar 항목 수정  
- public/uploads 밑에 avatars 폴더 만들고 아무 이미지를 1.png로 해서 넣기.  
```php
      App\Profile::create([
        'user_id'     => $user->id,
        'avatar'      => 'uploads/avatars/1.png',
        'about'       => 'Lorem ipsum dolor sit amet, consectetur adminipisicing elit. Accusantium, est veniam non coporis sunt qua',
        'facebook'    => 'facebook.com',
        'youtube' => 'youtube.com',
      ]);
```
9. seed 실행  
```bash
vagrant@homestead:~/code/blog$ php artisan db:seed
Seeding: UsersTableSeeder
Database seeding completed successfully.
```
### 관계 설정  

1. model 수정  
- user-profile 설정
> User.php
```php
    /*
     *  Relationships
     */
    public function profile() {
      return $this->hasOne('App\Profile');
    }
```
- 반대로 profile-user 설정  
> Profile.php
```php
class Profile extends Model
{
  public function user() {
    return $this->belongsTo('App\User');
  }
}
```
2. test  
- 이전에 만든 test route에서 test  
> web.php
```php
Route::get('/test', function() {
  return App\User::find(1)->profile;
});
```
그리고 test해보면 id(1)인 사용자의 profile이 출력된다.  

- 반대로 profile-user test
```php
Route::get('/test', function() {
  return App\Profile::find(1)->user;
});
```

