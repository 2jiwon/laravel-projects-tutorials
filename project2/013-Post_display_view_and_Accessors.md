
## Post display view

1. category의 index.blade.php를 copy해서 posts에도 똑같이 생성  
> resources/views/admin/posts/index.blade.php
```php
      <thead>
        <th>제목</th>
        <th>이미지</th>
        <th>수정</th>
        <th>삭제</th>
      </thead>
```
tbody부분은 나중에 수정  

2. route추가  
> web.php
```php
  Route::get('/posts', [
    'uses' => 'PostsController@index',
    'as'   => 'posts'
  ]);
```

3. controller에 index method 추가  
- 일단 view를 return하도록 함
> PostsController.php
```php
    public function index()
    {
      return view('admin.posts.index');
    }
```
- 데이터베이스에 있는 것을 가져오도록  
```php
      return view('admin.posts.index')->with('posts', Post::all());
```

4. view를 다시 수정  
- tbody 수정  
> resources/views/admin/posts/index.blade.php
```php
      <tbody>
        @foreach($posts as $post)
          <tr>
            <td>
                {{ $post->title }}
            </td>

            <td>
                {{ $post->featured }}
            </td>

            // 일단 수정,삭제 버튼은 임시로 글자만 만들어놓았음
            <td>Edit
              <!-- <a class="btn btn-sm btn-info" href="{{ route('category.edit', ['id' => $category->id ]) }}">
                <span><i class="fas fa-pencil-alt"></i></span>
              </a> -->
            </td>

            <td>Delete
              <!-- <a class="btn btn-sm btn-danger" href="{{ route('category.delete', ['id' => $category->id ]) }}">
                <span><i class="fas fa-trash-alt"></i></span>
              </a> -->
            </td>

          </tr>      
        @endforeach
      </tbody>
```
5. 포스트에 대한 메뉴 추가  
> resources/views/layouts/app.blade.php
```php
                  <li class="list-group-item">
                    <a href="{{ route('posts') }}">포스트</a>
                  </li>
```
6. 확인  

이제 브라우저에서 포스트 목록을 확인할 수 있다.

## Accessors

### Accessor란 무엇인가?

데이터베이스에서 오는 데이터를 보여주기 전에 변환을 거치기 위한 것?  

[Accessors와 Mutators](https://laravel.kr/docs/5.6/eloquent-mutators#accessors-and-mutators)  

### Accessors를 왜 사용하나?  
업로드한 이미지를 Full-path로 변경해서 보여주기 위함.  

### Accessors 사용하기  
1. Post Model  
> app/Post.php
```php
  /*
   * Use Accessors
   */
  public function getFeaturedAttribute($featured)
  {
    return asset($featured);
  }
```

2. 브라우저에서 확인  

원래는 이미지 부분에서 uploads/posts/~~ 이렇게 나왔는데 이제 앞에
http://blog.test/가 붙은 완전한 경로로 출력된다.  

이제 이것을 가지고 img태그를 쓰면 이미지를 보여줄 수 있다.  

3. img 태그 사용  
> resources/views/admin/posts/index.blade.php
```php
            <td>
                <img src="{{ $post->featured }}" alt="{{ $post->title }}" width="80px">
            </td>
```
