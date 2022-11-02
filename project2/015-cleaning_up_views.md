
## Update posts

1. 수정 버튼  
> resources/views/admin/posts/index.blade.php
```php
            <td><!-- 수정 -->
              <a class="btn btn-sm btn-info" href="{{ route('post.edit', ['id' => $post->id ]) }}">
                <span><i class="fas fa-pencil-alt"></i></span>
              </a>
            </td>
```

2. route 추가  
> web.php
```php
  Route::get('/post/edit/{id}', [
    'uses' => 'PostsController@edit',
    'as'   => 'post.edit'
  ]);
```

3. edit method 추가  
> PostsController.php
```php
    public function edit($id)
    {
      $post = Post::find($id);

      return view('admin.posts.edit')->with('post', $post);
    }
```

4. edit.blade.php 생성  
- resources/views/admin/posts/edit.blade.php 파일 만듬   
- create.blade.php를 복사해서 만듬  
> edit.blade.php

5. edit에서 수정할때 category_id 때문에 edit method에서 이 category_id를 같이
   넘겨줘야함  
> PostsController.php
```php
      return view('admin.posts.edit')->with('post', $post)->with('categories', Category::all());
```
6. edit.blade.php 수정  
```php
    <form action="{{ route('post.update') }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="form-group">
        <label for="title">제목</label>
        <input class="form-control" type="text" name="title" value="{{ $post->title }}">
      </div>

      <div class="form-group">
        <label for="">카테고리 선택</label>
        <select id="category" class="form-control" name="category_id">
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>


      <div class="form-group">
        <label for="featured">본문 이미지</label>
        <input class="form-control" type="file" name="featured">
      </div>

      <div class="form-group">
        <label for="content">본문 내용</label>
        <textarea name="content" id="content" class="form-control" cols="5" rows="5">{{ $post->content }}</textarea>
      </div>

      <div class="form-group">
        <div class="text-center">
          <button class="btn btn-success" type="submit">수정완료</button>
        </div>
      </div>
```
7. update route 추가  
> web.php
```php
  Route::post('/post/update/{id}', [
    'uses' => 'PostsController@update',
    'as'   => 'post.update'
  ]);
```
8. edit.blade.php 다시 수정  
- update route에 매개변수 부분 추가  
> edit.blade.php
```php
    <form action="{{ route('post.update', ['id' => $post->id ]) }}" method="post" enctype="multipart/form-data">
```
9. update method 추가  
- validation부터 추가  
> PostsController.php
```php
    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'title' => 'required',
        'content' => 'required',
        'category_id' => 'required'
      ]);
    }
```
이미지에는 required를 부여할 필요가 없다. 사용자가 꼭 사진을 올리지 않아도 된다.  
하지만 사용자가 이미지를 업로드했는지를 판별해서 다르게 동작해야한다.  
```php
      $post = Post::find($id);

      if ($request->hasFile('featured'))
      {
        $featured = $request->featured;
        $featured_new_name = time().$featured->getClientOriginalName();
        $featured->move('uploads/pots', $featured_new_name);
        $post->featured = 'uploads/posts/'.$featured_new_name;
      }
```

그리고 사용자가 업로드한 이미지가 없다면 다른것들을 처리해야 한다.  
```php
      $post->title       = $request->title;
      $post->content     = $request->content;
      $post->category_id = $request->category_id;

      $post->save();
```
- 세션 메시지 추가  
```php
      Session::flash('success', '포스트가 성공적으로 수정되었습니다.');
```
- redirect 추가  
```php
      return redirect()->route('posts');
```

이제 브라우저에서 확인해보면, 이미지를 수정하거나 수정하지 않거나 포스트를 수정하면  
잘 반영되어 출력되는 것을 볼 수 있다.  


## Cleaning up views

1. 아무것도 없을 때의 표시 

- 휴지통에 내용이 없을때는 없다는 메시지를 표시하기  
> trashed.blade.php  
```php
      @if($posts->count() > 0)

        @foreach($posts as $post)
      ...

        @endforeach

      @else
        <tr>
          <td colspan=5" class="text-center">삭제된 포스트가 없습니다.
          </td>
        </tr>
      @endif
```
- post 목록 페이지에서도 비슷하게 표시하기  
> resources/views/admin/posts/index.blade.php

- category 목록 페이지에서도 비슷하게 표시하기  
> resources/views/admin/categories/index.blade.php


그런데 한가지 버그가 발견되었다.  
카테고리를 전부 삭제해도, 해당 카테고리에 속한 포스트가 그대로 남아있다.  
이것은 프로젝트를 진행하면서 나중에 해결할 것이다.  

2. 테이블 표시(?)

> resources/views/admin/categories/index.blade.php
> resources/views/admin/posts/index.blade.php
> resources/views/admin/posts/trashed.blade.php

아래와 같은 card-header 추가해줌  
```php
  <div class="card-header"> 휴지통 </div>
```

