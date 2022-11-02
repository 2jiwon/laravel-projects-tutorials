
## One to Many relationships

### 어떤 post가 어떤 category에 속하는지를 파악하기(?)

1. 새로운 카테고리를 만들고 그 카테고리에 속하는 포스트를 작성  

2. 이제 이 포스트와 해당 카테고리의 관계를 설정해야 한다.  
- 일단, 해당 카테고리 아래에 속한 포스트들을 다 표시하게 하면 어떻게 될까(?)

> web.php
```php
Route::get('/test', function() {
  return App\Category::find(1)->posts;
});
```
``blog.test/test``로 접속해서 확인하면 category(1)에 속한 포스트들이 표시된다.  
(나는 한글이라 전부 unicode 기호로 표시되어버림...흠...)  
이것은 우리가 Category.php에서 확인할 수 있듯이 one to many relationships를
설정해놓았기 때문에 가능한 것이다.  

- 반대로 이것도 가능하다.  
> web.php
```php
Route::get('/test', function() {
  return App\Post::find(1)->category;
});
```
이렇게 하면 Post(1)이 속한 카테고리가 출력된다.  

## Many to Many relationships  

(강좌에서 헷갈리게 설명해서 매우 헷갈렸는데, 여기에서 many to many는 tag와
 post의 관계를 이야기하는 것임..)  

### 포스트 작성할때 여러개의 태그를 선택할 수 있게 하기  

1. post의 create method에 tag를 전달하기  
> PostsController.php
```php
use App\Tag;
...
      return view('admin.posts.create')->with('categories', $categories)
                                       ->with('tags', Tag::all());
```
2. 태그를 여러개 선택하게 checkbox를 만들기  
포스트 작성 폼에 checkbox 추가 
> resources/views/admin/posts/create.blade.php
```php
      <div class="form-group">
        <label for="">태그 선택</label>
        @foreach($tags as $tag)
        <div class="checkbox">
           <label><input type="checkbox" name="tags[]" value="{{ $tag->id }}"> {{ $tag->tag }} </label>
        </div>
        @endforeach
      </div>
```
3. tag와 post의 관계 만들기  
store method안에서 post와 tag의 관계를 정의
> PostsController.php
```php
    public function store(Request $request)
  ...
      $post->tags()->attach($request->tags);
  ...
```
4. database에서 확인  
새로 작성한 post(5)와 선택한 태그(2,3)이 확인된다.
```bash
MariaDB [blog]> select * from post_tag;
+----+---------+--------+------------+------------+
| id | post_id | tag_id | created_at | updated_at |
+----+---------+--------+------------+------------+
|  1 |       5 |      2 | NULL       | NULL       |
|  2 |       5 |      3 | NULL       | NULL       |
+----+---------+--------+------------+------------+
2 rows in set (0.001 sec)
```

5. route test에서 tag-post로 확인  
> web.php
```php
Route::get('/test', function() {
  return App\Tag::find(2)->posts;
});
```
이렇게 하면 tag(2)에 연결된 포스트가 출력됨  
(물론 한글이라서인지 유니코드 출력...)  
```bash
[{"id":5,"category_id":1,"slug":"\uc0c8\ub85c\uc6b4-\ud3ec\uc2a4\ud2b84","title":"\uc0c8\ub85c\uc6b4 \ud3ec\uc2a4\ud2b84","content":"\ub2e4\ub300\ub2e4 \uad00\uacc4\ub294 hasOne\uacfc hasMany \uad00\uacc4\ub4e4\uc5d0 \ube44\ud574\uc11c \uc870\uae08 \ub354 \ubcf5\uc7a1\ud569\ub2c8\ub2e4. \uc774\ub7f0 \uad00\uacc4\uc758 \uc608\ub85c \uc0ac\uc6a9\uc790\uac00 \uc5ec\ub7ec \uc5ed\ud560\uc744 \uac00\uc9c0\uba74\uc11c \uadf8 \uc5ed\ud560\ub4e4\uc774 \ub2e4\ub978 \uc0ac\uc6a9\uc790\uc640 \uacf5\uc720\ub418\ub294 \uacbd\uc6b0\uac00 \uc788\uc2b5\ub2c8\ub2e4. \uc608\ub97c \ub4e4\uc5b4 \uc5ec\ub7ec \uc0ac\uc6a9\uc790\ub4e4\uc774 \"Admin\" \uc5ed\ud560\uc744 \ud560 \uc218 \uc788\uc2b5\ub2c8\ub2e4. \uc774 \uad00\uacc4\ub97c \uc815\uc758\ud558\uae30 \uc704\ud574\ub294 users, roles, \uadf8\ub9ac\uace0 role_user\uc758 3\uac1c\uc758 \ub370\uc774\ud130\ubca0\uc774\uc2a4 \ud14c\uc774\ube14\uc774 \ud544\uc694\ud569\ub2c8\ub2e4. role_user \ud14c\uc774\ube14\uc740 \uad00\ub828\ub41c \ubaa8\ub378 \uc774\ub984\uc758 \uc54c\ud30c\ud3ab \uc21c\uc73c\ub85c\ubd80\ud130 \uc815\ub82c\ub418\uba70 user_id\uc640 role_id \uceec\ub7fc\uc744 \uac00\uc9c0\uace0 \uc788\uc2b5\ub2c8\ub2e4.\r\n\r\n\ub2e4\ub300\ub2e4 \uad00\uacc4\ub294 belongsToMany \uba54\uc18c\ub4dc\uc758 \uacb0\uacfc\ub97c \ubc18\ud658\ud558\ub294 \uba54\uc18c\ub4dc\ub97c \uc791\uc131\ud558\uc5ec \uc815\uc758\ud569\ub2c8\ub2e4. \uc608\ub97c \ub4e4\uc5b4 User \ubaa8\ub378\uc5d0 roles \uba54\uc18c\ub4dc\ub97c \uc815\uc758\ud574\ubcf4\ub3c4\ub85d \ud558\uaca0\uc2b5\ub2c8\ub2e4:","featured":"http:\/\/blog.test\/uploads\/posts\/1541489521pinutized_me_crop.png","deleted_at":null,"created_at":"2018-11-06 07:32:01","updated_at":"2018-11-06 07:32:01","pivot":{"tag_id":2,"post_id":5}}]
```
6. 반대로 post-tag로 확인  
> web.php
```php
  return App\Post::find(5)->tags;
```
결과
```bash
[
  { 
    "id":2,
    "tag":"Laravel",
    "created_at":"2018-11-04 16:14:09",
    "updated_at":"2018-11-04 16:14:09",
    "pivot":{"post_id":5,"tag_id":2}
  },
  { 
    "id":3,
    "tag":"wordpress",
    "created_at":"2018-11-04 16:18:10",
    "updated_at":"2018-11-04 16:18:10",
    "pivot":{"post_id":5,"tag_id":3}
  }
]
```

----

### 포스트 수정화면에서도 태그를 선택할 수 있게 하기  

1. edit view 수정  
tag 선택하는 부분을 추가
> resources/views/admin/posts/edit.blade.php
```php
      <div class="form-group">
        <label for="">태그 선택</label>
        @foreach($tags as $tag)
        <div class="checkbox">
           <label><input type="checkbox" name="tags[]" value="{{ $tag->id }}"> {{ $tag->tag }} </label>
        </div>
        @endforeach
      </div>
```

2. edit method 수정  
> PostsController.php
```php
    public function edit($id)
  ...
      return view('admin.posts.edit')->with('post', $post)
                                     ->with('categories', Category::all())
                                     ->with('tags', Tag::all());
```

3. edit view 수정  
- 이미 선택된 태그는 선택되어있도록 표시하기  
> resources/views/admin/posts/edit.blade.php
```php
           <label>
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
            @foreach($post->tags as $t)
              @if($tag->id == $t->id)
                checked
              @endif
            @endforeach
            > {{ $tag->tag }} 
          </label>
```

4. edit method 수정  
- save다음에 sync를 넣는다.  
> PostsController.php
```php
      $post->save();

      $post->tags()->sync($request->tags);

      Session::flash('success', '포스트가 성공적으로 수정되었습니다.');
```
이렇게 하고 브라우저에서 확인해보면 수정할때 선택한 태그가 잘 반영된다.  

5. category도 tag처럼 선택한 값이 반영되도록 수정하자  
- 기존 코드에 if문으로 선택한 값이 반영되도록 수정함  
> resources/views/admin/posts/edit.blade.php
```php
            <option value="{{ $category->id }}"
            @if($post->category_id == $category->id)
              selected 
            @endif
            >{{ $category->name }}</option>
```
