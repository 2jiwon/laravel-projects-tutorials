
### 2, 3번째 포스트  

1. controller에 데이터 전달  
> app/Http/Controllers/FrontEndController.php
```php
  public function index()
  {
    return view('index')->with('title', Setting::first()->site_name)
                        ->with('categories', Category::all())
                        ->with('first_post', Post::orderBy('created_at', 'desc')->first())
                        ->with('second_post', Post::orderBy('created_at', 'desc')->skip(1)->take(1)->get()->first());
```
- 3번째 포스트도 추가  
```php
                        ->with('third_post', Post::orderBy('created_at', 'desc')->skip(2)->take(1)->get()->first());
```
2. view 수정  
- first_post와 똑같이 작성하면 된다.  
- timestamp에서 새로운 method를 사용해보자.  
> resources/views/index.blade.php
```php
                                            <time class="published" datetime="2016-04-17 12:00:00">
                                                 {{ $second_post->created_at->diffForHumans() }}
                                            </time>

                                            <time class="published" datetime="2016-04-17 12:00:00">
                                                 {{ $second_post->created_at->toFormattedDateString() }}
                                            </time>
```
둘 중에 마음에 드는 timestamp 표시방식으로 나타내면 되겠다.  

---

### 하단 카테고리와 포스트   

1. controller에 카테고리 데이터 전달  
- '동영상'과 '튜토리얼' 카테고리를 하단에 출력하려고 한다.
- 데이터베이스에서 해당하는 카테고리의 id를 확인해서 다음과 같이 적어준다.
```bash
MariaDB [blog]> select * from categories;          
+----+--------------+---------------------+---------------------+                                     
| id | name         | created_at          | updated_at          |                                     
+----+--------------+---------------------+---------------------+                                     
|  1 | 새 소식      | 2018-11-09 05:23:12 | 2018-11-09 17:44:50 |                                     
|  4 | 동영상       | 2018-11-09 17:43:00 | 2018-11-09 17:45:05 |                                     
|  5 | 토론방       | 2018-11-09 17:43:15 | 2018-11-09 17:45:16 |                                     
|  6 | 튜토리얼     | 2018-11-09 17:45:31 | 2018-11-09 17:45:31 |                                     
|  7 | 뉴스레터     | 2018-11-09 17:45:42 | 2018-11-09 17:45:42 |                                     
+----+--------------+---------------------+---------------------+                                     
5 rows in set (0.001 sec)                          
```
> FrontEndController.php
```php
                        ->with('videos', Category::find(4))
                        ->with('tutorials', Category::find(6));
```

2. view 수정  
> resources/views/index.blade.php
```php
                    <div class="row">
                        <div class="case-item-wrap">
          
                            @foreach($videos->posts()->orderBy('created_at', 'desc')->take(3)->get() as $post)
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="case-item">
                                    <div class="case-item__thumb">
                                        <img src="{{ $post->featured }}" alt="{{ $post->title }}">
                                    </div>
                                    <h6 class="case-item__title"><a href="#">{{ $post->title }}</a></h6>
                                </div>
                            </div>
                            @endforeach

                        </div>
```

3. videos 카테고리 말고 tutorials카테고리도 똑같은 방식으로 아래에 작성해줌  

---

### 하단 메일폼과 사이트 정보 표시  

1. controller에 데이터 전달  
> FrontEndController.php
```php
                        ->with('settings', Setting::first());
```
2. view 수정  
- include방식으로 변경  
> resources/views/index.blade.php
```php
// footer 부분 잘라내고 

@include('includes.footer')
```
- views/includes 폴더 아래에 footer.blade.php 생성한 다음 붙여넣기 

3. footer.blade.php 수정  
> resources/views/includes/footer.blade.php
```php
                        <div class="content">
                            <a href="#" class="title">{{ $settings->contact_number }}</a>
                            <p class="sub-title">Mon-Fri 9am-6pm</p>
                        </div>
...

                        <div class="content">
                            <a href="#" class="title">{{ $settings->contact_email }}</a>
                            <p class="sub-title">online support</p>
                        </div>
```
이런 방식으로 각 자리에 데이터 넣기  
- 추가로 sub-title 내용도 다이나믹하게 출력하고 싶다면 settings의 테이블에
내용을 추가해서 표시하도록 해야 할 것.

