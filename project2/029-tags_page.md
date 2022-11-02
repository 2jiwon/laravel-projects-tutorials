
### Tag page 만들기  

1. resources/views/ 밑에 tag.blade.php 파일 생성  
- category.blade.php와 내용이 비슷할 것이므로 복사해서 생성  
- tag에 맞게 내용 수정  

```php
            <div class="row">
              <div class="case-item-wrap">
  
                 @foreach ($tag->posts as $post)

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
```

2. tag page에 대한 route 추가  
> web.php
```php
Route::get('/tag/{id}', [
  'uses'  => 'FrontEndController@tag',
  'as'    => 'tag.single'
]);
```

3. method 추가  
> FrontEndController.php
```php
  public function tag($id)
  {
    $tag = Tag::find($id);

    return view('tag')->with('tag', $tag) 
                      ->with('title', $tag->tag)
                      ->with('settings', Setting::first())
                      ->with('categories', Category::all());
  }
```

4. single view에 tag 페이지 연결  
> single.blade.php
```php
                            <div class="widget w-tags">
                                <div class="tags-wrap">
                                    @foreach($post->tags as $tag)
                                    <a href="{{ route('tag.single', ['id' => $tag->id ]) }}" class="w-tags-item">{{ $tag->tag }}</a>
                                    @endforeach
                                </div>
                            </div>
...
                        <div class="tags-wrap">
                            @foreach($tags as $tag)
                            <a href="{{ route('tag.single', ['id' => $tag->id]) }}" class="w-tags-item">{{ $tag->tag }}</a>
                            @endforeach
                        </div>
```
