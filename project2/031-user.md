
### 테이블 수정하기  

현재 우리는 posts 테이블에 user 정보가 없기때문에 어떤 사용자가 어떤 게시물을
작성했는지 알 수가 없다. 그러므로 migration을 사용해서 이것을 수정한 후에 게시물
작성자를 표시해보자.  

기존 데이터는 그대로 두고 테이블을 수정해야 한다.  

1. migration 

```bash
vagrant@homestead:~/code/blog$ php artisan make:migration insert_user_id_column_to_posts_table --table="posts"
Created Migration: 2018_11_11_152835_insert_user_id_column_to_posts_table
```

2. migration file 수정  
> 2018_11_11_152835_insert_user_id_column_to_posts_table.php
```php
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('user_id');
        });
    }
...
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
```

3. migrate  
```bash
vagrant@homestead:~/code/blog$ php artisan migrate 
Migrating: 2018_11_11_152835_insert_user_id_column_to_posts_table
Migrated:  2018_11_11_152835_insert_user_id_column_to_posts_table
```
```bash
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
| user_id     | int(11)          | NO   |     | NULL    |                |
+-------------+------------------+------+-----+---------+----------------+
10 rows in set (0.002 sec)
```

4. 그리고 일단 강의에서는 임의로 user_id 항목에 기존 user_id를 적당히 배분해서
   넣었음..

### controller와 model 수정  

1. postsController 수정  
> app/Http/Controllers/PostsController.php
```php
use Auth;
...

// store method 
      $post = Post::create([
        'title'       => $request->title,
        'content'     => $request->content,
        'featured'    => 'uploads/posts/'.$featured_new_name,
        'category_id' => $request->category_id,
        'slug'        => make_slug($request->title),
        'user_id'     => Auth::id()
      ]);
```

2. Post model에서 user_id를 fillable에 추가  
> app/Post.php
```php
  protected $fillable = [
      'title', 'featured', 'content', 'category_id', 'slug', 'user_id'
  ];
```

3. relationship 설정  
- User는 여러개의 Posts를 가질 수 있으므로 hasMany
> app/User.php
```php
    public function posts() {
      return $this->hasMany('App\Post');
    }
```
- Post는 하나의 User만 가질 수 있으므로 belongsTo 
> app/Post.php
```php
  public function user() {
    return $this->belongsTo('App\User');
  }
```

### view 수정  
> resources/views/single.blade.php
```php
                <div class="blog-details-author">
                    <div class="blog-details-author-thumb">
                        <img src="{{ asset($post->user->profile->avatar) }}" width="80px" style="border-radius:50%;" alt="{{ $post->user->name }}">
                    </div>
                    <div class="blog-details-author-content">
                        <div class="author-info">
                            <h5 class="author-name">{{ $post->user->name }}</h5>
                        </div>
                        <p class="text">{{ $post->user->profile->about }}</p>

                        <div class="socials">
                            <a href="{{ $post->user->profile->facebook }}" class="social__item" target="_blank">
                                <img src="{{ asset('app/svg/circle-facebook.svg') }}" alt="facebook">
                            </a>
                            <a href="#" class="social__item">
                                <img src="{{ asset('app/svg/twitter.svg') }}" alt="twitter">
                            </a>
                            <a href="#" class="social__item">
                                <img src="{{ asset('app/svg/google.svg') }}" alt="google">
                            </a>
                            <a href="{{ $post->user->profile->youtube }}" class="social__item" target="_blank">
                                <img src="{{ asset('app/svg/youtube.svg') }}" alt="youtube">
                            </a>
                        </div>
                    </div>
                </div>
```

