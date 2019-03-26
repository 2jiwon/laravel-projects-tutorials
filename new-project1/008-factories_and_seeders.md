## Factories와 Seeders  

개발을 할 때 실제 어플리케이션을 제작하기 앞서 dummy data가 필요할 때가 있다.  
factories와 seeders가 그래서 필요한 것이다.  

### Factory 생성

```bash
php artisan make:factory 팩토리이름  

vagrant@homestead:~/code/todos-app$ php artisan make:factory TodoFactory
Factory created successfully. 
```
생성된 factory 파일은 ``database/factories`` 디렉토리에 있다.  

### Factory 파일 구성  

기존에 있던 UserFactory.php 파일을 열어보면 다음과 같다.  

```php
<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
```
여기에서 보면 ``$factory->define``으로 시작되는 줄에 ``App\User::class``부분이
있는데, 이것은 ``'App\User'``라고 쓰는 것과 같다.
다음으로는 데이터베이스에 넣을 수 있는 가짜 데이터가 들어간 배열을 정의하고 있다.  

이를 바탕으로 우리가 만든 TodoFactory를 작성해보자.  

### Factory 파일 작성  

```php
// database/factories/TodoFactory.php

<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
--> Model을 \App\Todo로 변경

$factory->define(\App\Todo::class, function (Faker $faker) {
    return [
        --> 다음의 내용 추가    
        'name' => $faker->sentence(3),
        'description' => $faker->paragraph(4),
        'completed' => false
    ];
});
```

### Factory 사용 -> seed 생성

이것을 어떻게 사용하는가, seeder 안에서(?) 사용한다.   

``database/seeds`` 디렉토리에 가면 ``DatabaseSeeder.php`` 파일이 있다.  
우리는 Todos 테이블에 데이터를 'seed' 해야한다.(??)  

```bash
php artisan make:seed seeder이름 

vagrant@homestead:~/code/todos-app$ php artisan make:seed TodosSeeder
Seeder created successfully.                                            
```

### seeder 파일 구성  

생성된 TodosSeeder 파일을 열어보면 다음의 내용이 있다.  

```php
// database/seeds/TodosSeeder.php

<?php

use Illuminate\Database\Seeder;

class TodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }
}
```

다시 DatabaseSeeder 파일로 돌아와서, run 메소드에서 TodosSeeder를 호출할 것이다.  

```php
// database/seeds/DatabaseSeeder.php

...
    public function run()
    {
        $this->call(TodosSeeder::class);
    }
```

이번에는 TodosSeeder 파일에서 factory 메소드를 호출한다.  

```php
// database/seeds/TodosSeeder.php
...

    public function run()
    {
        factory(App\Todo::class)->create(); 
    }
```
이것은 조금전에 생성한 TodoFactory를 create()를 통해 하나의 factory를
만들어낸다. ``factory(App\Todo::class, 10)->create()`` 이렇게 하면 10개의
factory를 만든다.  

### 정리

Seeder를 만들기 위해서 seeder class를 작성한다.  
작성한 seeder class를 DatabaseSeeder class에서 호출한다.  
Factory를 준비하고 ``php artisan db:seed`` 명령으로 데이터베이스에 seeding을
완성한다.  

```bash
vagrant@homestead:~/code/todos-app$ php artisan db:seed
Seeding: TodosSeeder
Database seeding completed successfully.                      
```

### 데이터베이스 확인  

```bash
mysql> show tables;
+---------------------+
| Tables_in_todos_app |
+---------------------+
| migrations          |
| password_resets     |
| todos               |
| users               |
+---------------------+
4 rows in set (0.00 sec)
  mysql> select * from todos;
  +----+--------------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-----------+---------------------+---------------------+
  | id | name                           | description | completed | created_at          | updated_at          |
  +----+--------------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-----------+---------------------+---------------------+
  |  1 | Libero dolor sunt.             | Ut sed nihil sapiente assumenda.  Voluptate harum molestias incidunt quas quis quisquam. Quibusdam totam magni commodi. Ipsam saepe fuga sed dolore quia. Est tempore quam expedita aut in.  Odio laboriosam provident accusamus quibusdam impedit quos nihil.  |         0 | 2019-03-26 02:59:24 | 2019-03-26 02:59:24 |
  |  2 | Ea cumque.                     | Impedit autem velit ut facere ut quos est in. Odit rerum quibusdam culpa quo fugit ex. Voluptates et commodi perspiciatis mollitia debitis.  |         0 | 2019-03-26 02:59:25 | 2019-03-26 02:59:25 |
  |  3 | Natus commodi quia temporibus. | Molestiae iure soluta odit quo. Sint repellat necessitatibus suscipit commodi placeat aspernatur aut. Veritatis sunt vitae numquam eum sint voluptas saepe. Magni veritatis magni voluptas et dolorem repudiandae. Tempora ut consectetur laborum quos et itaque.  Repudiandae voluptas possimus recusandae veritatis. | 0 | 2019-03-26 02:59:25 | 2019-03-26 02:59:25 |
  |  4 | Nesciunt tenetur unde.         | Recusandae iusto nesciunt fugit id molestiae. Quod et labore ut consequatur culpa repudiandae. Blanditiis explicabo similique voluptatem dolores aliquam. Eum voluptatem a vel dolores in eaque cupiditate. Ducimus quia labore dicta et. Quas et rem quae aperiam unde eaque quia.                                   |         0 | 2019-03-26 02:59:25 | 2019-03-26 02:59:25 |
  |  5 | Dolore iusto velit magni.      | Quis quo ut excepturi. Corrupti esse quia id esse ratione et ad. Qui harum impedit magni. Eum voluptates cumque blanditiis voluptate quia velit. Est minus assumenda praesentium est iure dolore. Aut a voluptatem illo.  |         0 | 2019-03-26 02:59:25 | 2019-03-26 02:59:25 |
  |  6 | Nisi esse dicta at recusandae. | Eligendi molestiae consequuntur soluta saepe accusamus earum in. Quaerat itaque eius eligendi libero repellat.  Consectetur ut vel sed totam. Alias aut perspiciatis soluta voluptas modi rem aperiam. Ut rerum aut amet eum ut.  |         0 | 2019-03-26 02:59:25 | 2019-03-26 02:59:25 |
  |  7 | Laborum ipsum.                 | Recusandae necessitatibus quidem dolores occaecati. Aut rerum qui vel eius optio. Id dolor voluptas rem impedit quis. Reprehenderit vero non inventore.  |         0 | 2019-03-26 02:59:25 | 2019-03-26 02:59:25 |
  |  8 | Molestiae et quia.             | Veritatis est veritatis sed officia.  Tenetur veritatis non maiores ut reprehenderit ea est. Deleniti sint earum rerum voluptas enim rem unde.  |         0 | 2019-03-26 02:59:25 | 2019-03-26 02:59:25 |
  |  9 | Sit ut iure accusantium.       | Quia provident eveniet qui cum asperiores rerum quod. Magni molestiae suscipit totam enim qui repudiandae laboriosam similique. Fugiat quia distinctio ullam nostrum aliquid.  Praesentium dolores aliquam dolor laudantium ipsum dolorum.  |         0 | 2019-03-26 02:59:25 | 2019-03-26 02:59:25 |
  | 10 | Est libero pariatur qui.       | Doloremque non non voluptas ab est quod. Dolores necessitatibus quod praesentium et. Et omnis dolorum omnis doloremque est alias. Qui veniam nobis eum voluptatem corporis vel. Dolores fugiat laudantium delectus tempora ad excepturi. Velit incidunt quo voluptatibus.                                             |         0 | 2019-03-26 02:59:25 | 2019-03-26 02:59:25 |
  +----+--------------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-----------+---------------------+---------------------+
  10 rows in set (0.01 sec)
  mysql>                                                                                                                          
```
10개의 fake 데이터가 테이블에 들어간 것을 확인할 수 있다.  


### 다시 정리  

1. php artisan make:factory 명령으로 factory 생성  
2. 생성된 factory 파일에 데이터베이스에 넣고싶은 데이터를 구성  
3. php artisan make:seed 명령으로 seeder 생성  
4. seeder 파일 안에서 factory 함수와 create 함수를 호출  
5. DatabaseSeeder 파일에서 생성한 seeder를 호출  
6. php artisan db:seed 명령으로 데이터베이스에 seed 작업  

