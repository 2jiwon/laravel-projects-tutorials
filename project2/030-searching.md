
### searching post

1. frontend 수정  
> frontend.blade.php
```php
<!-- Overlay Search -->

<div class="overlay_search">
    <div class="container">
        <div class="row">
            <div class="form_search-wrap">
                <form method="GET" action="/results">
                    <input class="overlay_search-input" name="query" placeholder="Type and hit Enter..." type="text">
                    <a href="#" class="overlay_search-close">
                        <span></span>
                        <span></span>
                    </a>
                </form>
```
- index.blade.php에도 똑같이 수정해줌!!

2. route 추가  
> web.php
```php
Route::get('/results', function() {
  $posts = \App\Post::where('title', 'like', '%'.request('query').'%')->get();

  return view('results')->with('posts', $posts)
                        ->with('query', request('query'))
                        ->with('title', '검색 결과: '.request('query'))
                        ->with('settings', \App\Setting::first())
                        ->with('categories', \App\Category::all());
});
```

3. view 추가  
- resources/views/ 밑에 results.blade.php 파일생성 
- tag.blade.php와 비슷하기때문에 복사해서 붙여넣기함  
- 그리고 search results에 맞게 수정함  
> results.blade.php
```php
        <h2 class="stunning-header-title">검색 결과: {{ $query }}</h2>
        ...
        
              <div class="case-item-wrap">
  
                 @foreach ($posts as $post)
```

4. 검색하면 다음과 같이나온다.  
![img](./blog13.png)  


5. 검색결과가 없을 경우의 표시  
> resources/views/results.blade.php
```php
        <main class="main">
            
            @if ($posts->count() > 0)
            <div class="row">
            ...

              </div>
            </div>

            @else
            
              <h2 class="text-center">일치하는 포스트가 없습니다.</h2>
            @endif
        </main>
```

