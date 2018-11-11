
### Category page 만들기  

1. route 추가  
> web.php
```php
Route::get('/category/{id}', [
  'uses'  => 'FrontEndController@category',
  'as'    => 'category.single' 
]);
```
2. controller에 method 추가  
> FrontEndController.php
```php
  public function category($id)
  {
    $category = Category::find($id);

    return view('category')->with('category', $category)
                           ->with('title', $category->name)
                           ->with('settings', Setting::first())
                           ->with('categories', Category::all());
  }
```
3. view 추가  
- resources/views 폴더 밑에 category.blade.php 파일 생성  
- single.blade.php의 내용을 복사-붙여넣기 한 다음 category에 맞게 수정  
> category.blade.php
```php
<!-- Stunning Header -->
<div class="stunning-header stunning-header-bg-lightviolet">
    <div class="stunning-header-content">
        <h2 class="stunning-header-title">Category: {{ $category->name }}</h2>
    </div>
</div>
<!-- End Stunning Header -->
```
그리고 <main> 부분 삭제  

4. index에서 category에 category view 연결  
> resources/views/index.blade.php
```php
                                        <span class="category">
                                            <i class="seoicon-tags"></i>
                                            <a href="{{ route('category.single', ['id' => $first_post->category->id]) }}">{{ $first_post->category->name }}</a>
                                        </span>
```
5. category.html에서 아래 내용을 복사해서 category.blade.php에 집어넣음  
```php
// div container안에 main 부분을 전부 복사 
// 그리고 img요소들은 asset 안으로 집어넣기
```
tag부분은 통째로 삭제  

> category.blade.php
```php
        <main class="main">
            
            <div class="row">
              <div class="case-item-wrap">
  
                 @foreach ($category->posts as $post)

                  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                      <div class="case-item">
                          <div class="case-item__thumb">
                              <img src="{{ $post->featured }}" alt="{{ $post->title }}">
                          </div>
                          <h6 class="case-item__title"><a href="{{ route('post.single', ['slug' => $post->slug ]) }}">{{ $post->title }}</a></h6>
                      </div>
                  </div>

                @endforeach

              </div>
            </div>

        </main>
```
5. 이제 category page를 표시하는 곳을 찾아서 수정  
> single.blade.php
```php
                            <span class="category">
                                <i class="seoicon-tags"></i>
                                <a href="{{ route('category.single', ['id' => $post->category->id]) }}">{{ $post->category->name }}</a>
                            </span>
```
> index.blade.php
```php
                                        <span class="category">
                                            <i class="seoicon-tags"></i>
                                            <a href="{{ route('category.single', ['id' => $first_post->category->id]) }}">{{ $first_post->category->name }}</a>
                                        </span>
```
그리고 second, third post에도 연결되지 않은 링크들을 찾아서 마저 수정해줌  

6. 마지막으로 header에서 카테고리도 연결  

