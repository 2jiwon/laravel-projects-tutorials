
## post table수정하고 migration

1. migrate file 수정  
- slug column 추가 
> database/migration/2018_11_02_062032_create_posts_table.php
```php
            $table->string('slug');
```
- soft delete추가
```php
            $table->softDeletes();
```

2. post model 수정  
- soft delete추가
> app\Post.php
```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
  use SoftDeletes;
```
- deleted_at 컬럼을 날짜로 지정하기(?)  
> app\Post.php
```php
  protected $dates = ['deleted_at'];
```

3. migration
- refresh
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
- 확인 
```bash
MariaDB [blog]> select * from posts;
Empty set (0.001 sec)

MariaDB [blog]> select * from users;
Empty set (0.001 sec)

MariaDB [blog]> select * from categories;
Empty set (0.001 sec)

MariaDB [blog]> explain posts;                     
+-------------+------------------+------+-----+---------+----------------+
| Field       | Type             | Null | Key | Default | Extra          |
+-------------+------------------+------+-----+---------+----------------+
| id          | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| category_id | int(11)          | NO   |     | NULL    |                |
| slug        | varchar(255)     | NO   |     | NULL    |                |
| title       | varchar(255)     | NO   |     | NULL    |                |
| content     | text             | NO   |     | NULL    |                |
| featured    | varchar(255)     | NO   |     | NULL    |                |
| deleted_at  | timestamp        | YES  |     | NULL    |                |
| created_at  | timestamp        | YES  |     | NULL    |                |
| updated_at  | timestamp        | YES  |     | NULL    |                |
+-------------+------------------+------+-----+---------+----------------+
9 rows in set (0.002 sec)
```


