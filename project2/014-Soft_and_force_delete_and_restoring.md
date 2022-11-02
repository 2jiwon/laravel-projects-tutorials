
## Soft Delete

1. delete 버튼 추가  
> resources/views/admin/posts/index.blade.php
```php
              <a class="btn btn-danger" href="{{ route('post.delete', ['id' => $post->id ]) }}">
                <span><i class="fas fa-trash-alt"></i></span>
              </a>
```

2. route 추가  
> web.php
```php
  Route::get('/post/delete/{id}', [
    'uses' => 'PostsController@destroy',
    'as'   => 'post.delete'
  ]);
```

3. destroy method 작성  
> PostsController.php
```php
    public function destroy($id)
    {
      $post = Post::find($id);
      $post->delete();

      Session::flash('success', '포스트가 삭제되었습니다.');

      return redirect()->back();
    }
```

4. 브라우저에서 확인  
포스트를 하나 삭제해보면 바로 삭제가 되고 메시지도 뜨지만, 실제로 데이터베이스를
확인해보면 삭제되지 않고 'deleted_at'에 시간이 추가되어있는 것을 확인할 수 있다.  


## 삭제한 포스트를 가져오기  

삭제한 포스트들을 확인할 수 있는 휴지통 페이지를 만들자.  

1. posts 밑에 trashed.blade.php 파일 생성  
index.blade.php를 복사해서 파일을 만든다.  

2. 파일 수정  
> trashed.blade.php
그냥 delete버튼 대신에 이번에는 '복구' 버튼으로 수정하자.  

3. route 작성  
> web.php
```php
  Route::get('/posts/trashed', [
    'uses' => 'PostsController@trashed',
    'as'   => 'posts.trashed'
  ]);
```

4. trashed method 작성  
> PostsController.php
```php
    public function trashed() {
      $posts = Post::onlyTrashed();
      dd($posts);
    }
```
브라우저에서 확인해보면 다음과 같은 내용이 뜬다.  
```php
Builder {#251 ▼
  #query: Builder {#249 ▶}
  #model: Post {#253 ▶}
  #eagerLoad: []
  #localMacros: array:4 [▶]
  #onDelete: Closure {#260 ▶}
  #passthru: array:13 [▶]
  #scopes: []
  #removedScopes: array:1 [▶]
}
```
onlyTrashed()뒤에 get()을 추가해야한다.  
```php
    public function trashed() {
      $posts = Post::onlyTrashed()->get();
      dd($posts);
```
다시 확인해보면  
```php
Collection {#270 ▼
  #items: array:1 [▼
    0 => Post {#271 ▶}
  ]
}
```

5. return 추가  
```php
    public function trashed() {
      $posts = Post::onlyTrashed()->get();

      return view('admin.posts.trashed')->with('posts', $posts);
    }
```
이제 브라우저에서 삭제된 포스트를 확인할 수 있다.  

6. 이제 휴지통 페이지를 추가해주자  
> app.blade.php
```php
                  <li class="list-group-item">
                    <a href="{{ route('posts.trashed') }}">휴지통</a>
                  </li>
```
7. 복구 버튼 수정  
> trashed.blade.php
```php
            <td>
              <a class="btn btn-warning" href="{{ route('post.delete', ['id' => $post->id ]) }}">
                <span><i class="fas fa-undo-alt"></i></span>
              </a>
            </td>
```

## 영구 삭제하기  

1. 영구 삭제버튼으로 수정  
> trashed.blade.php
```php
            <td><!-- 완전 삭제 -->
              <a class="btn btn-sm btn-danger" href="{{ route('post.delete', ['id' => $post->id ]) }}">
                <span><i class="fas fa-times"></i></span>
              </a>
            </td>
```
2. 잠깐! 버튼에 전부 btn-sm 추가  

3. trashed 페이지에서 '삭제'를 '완전 삭제'로 수정  

4. 완전 삭제는 route 이름을 kill이라 붙이자  
> trashed.blade.php
```php
              <a class="btn btn-sm btn-danger" href="{{ route('post.kill', ['id' => $post->id ]) }}">
```
> web.php
```php
  Route::get('/post/kill/{id}', [
    'uses' => 'PostsController@kill',
    'as'   => 'post.kill'
  ]);
```
5. kill method 추가  
- 일단 die and dump
> PostsController.php
```php
    public function kill($id) {
      $post = Post::withTrashed()->where('id', $id)->get();
      dd($post);
    }
```
- forceDelete를 써서 삭제  
```php
    public function kill($id) {
      $post = Post::withTrashed()->where('id', $id)->first();
      $post->forceDelete();

      Session::flash('success', '포스트가 완전히 삭제되었습니다.');

      return redirect()->back();
    }
```

## 삭제한 포스트 복구  

1. 복구 버튼으로 수정  
> trashed.blade.php
```php
            <td><!-- 복구 -->
              <a class="btn btn-sm btn-warning" href="{{ route('post.restore', ['id' => $post->id ]) }}">
                <span><i class="fas fa-undo-alt"></i></span>
              </a>
            </td>
```
2. route 추가  
> web.php
```php
  Route::get('/post/restore/{id}', [
    'uses' => 'PostsController@restore',
    'as'   => 'post.restore'
  ]);
```
3. method 추가  
> PostsController.php
```php
    public function restore($id) {
      $posts = Post::onlyTrashed()->where('id', $id)->first();
      $post->restore();

      Session::flash('success', '포스트가 성공적으로 복구되었습니다.');

      return redirect()->route('posts');
    }
```

