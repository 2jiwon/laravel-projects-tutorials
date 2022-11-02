
## Migration과 model 소개  

- Model은 database와의 커뮤니케이션을 도와주는 class이다.  

### Model 생성

Model을 생성하는 방법은 다음과 같다.  

```bash  
php artisan make:model 모델이름  

vagrant@homestead:~/code/todos-app$ php artisan make:model Todo
Model created successfully.  
```
*모델 이름은 단수형으로 작성해야한다. 또한 모델은 class이므로 첫글자는 대문자여야 한다.*

모델을 생성하면 app 폴더 아래에 동일한 이름의 파일이 생성된다.  

이 Todo 모델은 데이터베이스의 'Todos' 테이블과 커뮤니케이션을 도와줄 것이다.  
하지만 우리는 아직 Todos 테이블이 없다.  
심지어 데이터베이스 자체가 없다.  

어떻게 데이터베이스를 설정할까?  

### database 생성  

``.env``파일을 열면 여기에 데이터베이스 정보가 들어있다.  

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret    
```
먼저 mysql 에 접속해서 'todos_app'이라는 이름의 데이터베이스를 생성한다.  

```bash
vagrant@homestead:~/code/todos-app$ mysql
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 9
Server version: 5.7.25-0ubuntu0.18.04.2 (Ubuntu)
Copyright (c) 2000, 2019, Oracle and/or its affiliates. All rights reserved.
Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.
Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.
mysql> create database todos_app;
Query OK, 1 row affected (0.00 sec)
mysql>  
```
그리고 ``.env``파일을 열고 다음과 같이 수정한다.  

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todos_app
DB_USERNAME=root
DB_PASSWORD=secret
```
(강좌에서는 비밀번호를 비워두지만, 나는 vagrant에서 이미 root 비밀번호를 secret으로 설정해서 이렇게 작성함)  

### table 생성  

mysql에서 직접 table을 생성할 수도 있지만, 라라벨에서는 migration을 이용한다.  
migration파일은 데이터베이스 테이블을 생성하고 관리하는데 사용한다.  

```bash
php artisan make:migration 마이그레이션 목적  

vagrant@homestead:~/code/todos-app$ php artisan make:migration create_todos_table
Created Migration: 2019_03_25_102408_create_todos_table        
```

### migration 파일  

생성된 migration파일은 ``database/migration`` 디렉토리 아래에 있다.  
모든 migration 파일에는 up, down 함수가 존재한다.  

migration 실행에는 up이 사용되고, 실행 취소를 위해서는 down 함수가 사용된다.  

migration 실행은 다음과 같다.  

```bash
vagrant@homestead:~/code/todos-app$ php artisan migrate         
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2019_03_25_102408_create_todos_table
Migrated:  2019_03_25_102408_create_todos_table             
```
이렇게 하면 라라벨은 migration파일의 up 함수를 호출하고 그 안의 내용을 실행한다.  

### database 접속해서 확인  

database 툴을 사용해서 생성된 테이블을 확인한다.  

```bash
vagrant@homestead:~/code/todos-app$ mysql -u root -p
Enter password:                                             

mysql> use todos_app;
Reading table information for completion of table and column names
You can turn off this feature to get a quicker startup with -A

Database changed
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
mysql>                                        
```
보면 migrations라는 테이블도 존재하는데, 확인해보면 

```bash
mysql> select * from migrations;
+----+------------------------------------------------+-------+
| id | migration                                      | batch |
+----+------------------------------------------------+-------+
|  1 | 2014_10_12_000000_create_users_table           |     1 |
|  2 | 2014_10_12_100000_create_password_resets_table |     1 |
|  3 | 2019_03_25_102408_create_todos_table           |     1 |
+----+------------------------------------------------+-------+
3 rows in set (0.00 sec)                                                                                                                                                                                                                                                                     
```
이렇게 migration 내역이 들어가있다.  

### migration rollback

다음과 같이 migration rollback을 실행하면 이번에는 migration 파일의 down 함수가 실행된다.

```bash
vagrant@homestead:~/code/todos-app$ php artisan migrate:rollback
Rolling back: 2019_03_25_102408_create_todos_table
Rolled back:  2019_03_25_102408_create_todos_table
Rolling back: 2014_10_12_100000_create_password_resets_table
Rolled back:  2014_10_12_100000_create_password_resets_table
Rolling back: 2014_10_12_000000_create_users_table
Rolled back:  2014_10_12_000000_create_users_table          
```
이렇게 하고 다시한번 접속해서 테이블을 확인해보면 

```bash
mysql> show tables;
+---------------------+
| Tables_in_todos_app |
+---------------------+
| migrations          |
+---------------------+
1 row in set (0.00 sec)                                                                                                                                                                                                               
```
migrations 테이블만 남아있는 것을 볼 수 있다.

### migration 파일 수정  

이제 migration 파일을 수정해서 테이블 안에 우리가 원하는(사용할) 컬럼을 추가하자.  

```php
public function up()
{
    Schema::create('todos', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('name');
        $table->text('description');
        $table->boolean('completed');
        $table->timestamps();
    });
}
```

### migrate refresh  

파일을 수정했으면 다음과 같이 refresh를 해준다. 이것은 지금까지의 migration을
취소하고(rollback), 다시 migration을 실행하는 것과 같다.  

```bash
vagrant@homestead:~/code/todos-app$ php artisan migrate:refresh
Nothing to rollback.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2019_03_25_102408_create_todos_table
Migrated:  2019_03_25_102408_create_todos_table          
```
다시한번 테이블을 확인해보면  

```bash
mysql> use todos_app;
Reading table information for completion of table and column names
You can turn off this feature to get a quicker startup with -A
Database changed
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
mysql> desc todos;
+-------------+---------------------+------+-----+---------+----------------+
| Field       | Type                | Null | Key | Default | Extra          |
+-------------+---------------------+------+-----+---------+----------------+
| id          | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
| name        | varchar(255)        | NO   |     | NULL    |                |
| description | text                | NO   |     | NULL    |                |
| completed   | tinyint(1)          | NO   |     | NULL    |                |
| created_at  | timestamp           | YES  |     | NULL    |                |
| updated_at  | timestamp           | YES  |     | NULL    |                |
+-------------+---------------------+------+-----+---------+----------------+
6 rows in set (0.00 sec)
mysql>                                                                                   
```

