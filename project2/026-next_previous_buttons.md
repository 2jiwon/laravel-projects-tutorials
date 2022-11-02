
### 문제점  

``blog.test/login``하면 로그인이 안되고 오류가 남.  
왜냐하면 현재  route에 ``'/{slug}'``가 있다보니 login부분이 slug라고
판단해버리기 때문.  

1. route 수정 

> web.php
```php
Route::get('/post/{slug}', [
  'uses'  => 'FrontEndController@singlePost',
  'as'    => 'post.single'
]);
```
2. single view에서 img등을 asset method이용해서 정리  

---

### Next, Prev button 

1. controller에 데이터를 전달  
> FrontEndController.php
```php
    $next_id = Post::where('id', '>', $post->id)->min('id');
```
현재 post_id보다 숫자가 큰 id들을 가져오고 그 중에 min, 즉 가장 작은 숫자의 id에
해당하는 것을 가져오게 된다.  
즉, 예를 들어 현재  post_id가 3 이라면, where(...)을 통해 가져오는 값은
4,5,6...이고 min(..)을 통해 '4'가 전달된다.  

반대로 prev_id는 다음과 같이 적어서 값을 찾는다.  
```php
    $prev_id = Post::where('id', '<', $post->id)->max('id');
```
반환값에 이들 값을 전달   
```php
    return view('single')->with('post', $post)
                         ->with('title', $post->title)
                         ->with('categories', Category::all())
                         ->with('settings', Setting::first())
                         ->with('next', Post::find($next_id))
                         ->with('prev', Post::find($prev_id));
```

2. single view 수정  
> single.blade.php
```php
                <!-- Pagination -->
                <div class="pagination-arrow">

                    @if($prev)
                    <a href="{{ route('post.single', ['slug' => $prev->slug ]) }}" class="btn-next-wrap">
                        <div class="btn-content">
                            <div class="btn-content-title">Previous Post</div>
                            <p class="btn-content-subtitle">{{ $prev->title }}</p>
                        </div>
                        <svg class="btn-next">
                            <use xlink:href="#arrow-right"></use>
                        </svg>
                    </a>
                    @endif

                    @if($next)
                    <a href="{{ route('post.single', ['slug' => $next->slug ]) }}" class="btn-prev-wrap">
                        <svg class="btn-prev">
                            <use xlink:href="#arrow-left"></use>
                        </svg>
                        <div class="btn-content">
                            <div class="btn-content-title">Next Post</div>
                            <p class="btn-content-subtitle">{{ $next->title }}</p>
                        </div>
                    </a>
                    @endif

                </div>
                <!-- End Pagination -->
```

