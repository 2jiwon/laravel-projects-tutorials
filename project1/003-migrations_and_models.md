## Migrations and models

### .env 파일  
.env파일은 환경변수를 설정하는 파일이다.  
database에 대한 설정도 여기에서 할 수 있다.  

(강좌에서는 root에 패스워드 없이 접속했지만 나는 todos,secret을 만들어서 접속)  
```bash
// 1. root 사용자로 접속(pw는 secret)  
vagrant@homestead:~/code/Todos$ mysql -u root -p  
// 2. 데이터베이스 생성 
Mysql > create database todos;
// 3. todos사용자에게 권한 이임
Mysql > grant all privileges on todos.* to todos@localhost identified by
'secret' with grant option;
Mysql > flush privileges;
```

### 데이터베이스와 상호작용을 하는 Model 만들기  
Model은 데이터베이스의 특정 테이블을 담당한다.  
하나의 모델은 하나의 테이블을 맡는 것이다.  

1. 데이터베이스 테이블과 모델 만들기  
Laravel의 migration을 사용한다.  
모델 이름은 테이블명의 단수형을 사용해야 하고 첫 문자는 대문자로 써야 한다.
즉, todos 테이블이라면 모델명은 s를 제외한 Todo여야 한다.  

```bash
// -m을 붙이면 migration을 같이 실행하라는 뜻 

vagrant@homestead:~/code/Todos$ php artisan make:model Todo -m
Model created successfully.
Created Migration: 2018_10_31_071204_create_todos_table
```
이렇게하면 app 밑에 Todo.php 파일이 생성된다.  
그리고 database/migrations 밑에 '....create_todos_table'이라는 파일도 생성된다.  

Migration이라는 것은 이 파일 안에 class가 가진 up, down 함수를 실행하게 되는
것이다.  

2. todos 테이블 안에 todo,completed 컬럼추가  
> 2018_10_31_071204_create_todos_table.php 
```php
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('todo');
            $table->boolean('completed');
            $table->timestamps();
        });
    }
```
completed 컬럼에 default 값을 설정  
```php
            $table->boolean('completed')->default(0);
```
이제 우리는 Laravel에게 마이그레이션 작업을 끝냈으니 이걸 '적용하게끔' 해야한다.  
```bash
$ php artisan migrate
```
이 명령을 보내면 Laravel은 migration파일들을 모아서 데이터베이스로 보내서
실행하게 한다.  
```bash
vagrant@homestead:~/code/Todos$ php artisan migrate
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2018_10_31_071204_create_todos_table
Migrated:  2018_10_31_071204_create_todos_table
```
```bash
MariaDB [todos]> show tables;                      
+-----------------+
| Tables_in_todos |
+-----------------+
| migrations      |
| password_resets |
| todos           |
| users           |
+-----------------+
4 rows in set (0.001 sec)

MariaDB [todos]> explain todos;                    
+------------+------------------+------+-----+---------+----------------+
| Field      | Type             | Null | Key | Default | Extra          |
+------------+------------------+------+-----+---------+----------------+
| id         | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| todo       | varchar(255)     | NO   |     | NULL    |                |
| completed  | tinyint(1)       | NO   |     | 0       |                |
| created_at | timestamp        | YES  |     | NULL    |                |
| updated_at | timestamp        | YES  |     | NULL    |                |
+------------+------------------+------+-----+---------+----------------+
5 rows in set (0.003 sec)
```

