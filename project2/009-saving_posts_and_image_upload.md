
## posts를 데이터베이스에 저장하기  

### 먼저 image를 업로드하는 것부터 해결  

1. store method를 수정 
> PostsControllers.php
```php
      $featured = $featured->featured;
      $featured_new_name = time().$featured->getClientOriginalName();
```
이것은 업로드하는 이미지의 파일이름에서 충돌이 일어나지 않도록 원래 이름 앞에
업로드 시간을 덧붙이는 작업이다.  

2. uploads/posts 폴더를 public아래에 생성  

3. store method로 다시 돌아와서...
> PostsControllers.php
```php
      $featured = $request->featured;
      $featured_new_name = time().$featured->getClientOriginalName();
      $featured->move('uploads/posts', $featured_new_name);
```

### 나머지 항목들을 해결 
- post model을 추가하고, 다른 항목들에 대한 처리를 추가  

> PostsControllers.php
```php
use App\Post;
...
      $post = Post::create([
        'title'       => $request->title,
        'content'     => $request->content,
        'featured'    => 'uploads/posts/'.$featured_new_name,
        'category_id' => $request->category_id
      ]);
```
### toastr message 
- session을 추가해주고 세션 메시지를 추가한다.  

> PostsControllers.php
```php
use Session;
...
      Session::flash('success', '포스트가 성공적으로 작성되었습니다.');
```

### mass assignment?

여기까지 하고나서 포스트 작성을 하면 다음과 같은 에러가 뜬다.  
```bash
Illuminate \ Database \ Eloquent \ MassAssignmentException
Add [title] to fillable property to allow mass assignment on [App\Post].
```
이것은 뭐냐면, laravel에서 사용자에게 수정을 허락하지 않는 필드가 있다.  
예를 들면, users table의 timestamp와 같은 것이다.  
사용자를 등록한 시간과 수정한 시간 등이 변경되어서는 안되기 때문이다.  
패스워드와 같은 항목도 마찬가지로 함부로 수정되어서는 안된다.  

그래서 기본적으로 laravel에서는 항목들을 대량으로 할당하지 못하도록 되어있다.  

[대량 할당에 대한 설명](https://laravel.kr/docs/5.6/eloquent#%EB%8C%80%EB%9F%89%20%ED%95%A0%EB%8B%B9%20-%20Mass%20Assignment)

1. post 모델에서 대량 할당을 가능하게  
먼저, User.php 파일을 열어보면 다음과 같은 내용이 있다.  
> app/User.php
```php
     * The attributes that are mass assignable.
     ...
    protected $fillable = [
        'name', 'email', 'password',
    ];
```
즉, name, email, password는 대량 할당이 허용되어있다는 것이다.  
이와 같은 방식으로 post에서도 작성해주면 된다. 
> app/Post.php
```php
  protected $fillable = [
      'title', 'featured', 'content', 'category_id'
  ];
```
2. tinker 사용해서 데이터베이스에 들어간 레코드를 확인  
```bash
vagrant@homestead:~/code/blog$ php artisan tinker
Psy Shell v0.9.9 (PHP 7.2.9-1+ubuntu18.04.1+deb.sury.org+1 — cli) by Justin Hileman
>>> App\Post::first();
=> App\Post {#2927
     id: 1,
     category_id: 1,
     title: "새로운 포스트2",
     content: """
어     첫번째 인자는 배열이나 컬렉션의 각 요소를 렌더링하기 위한 부분적 뷰의 이름입니다. 두번째 인자는 반복 처리하는 배열이나 컬렉션이며 세번째 인수는 뷰에서의 반복값이 대입되는 변수의 이름입니다. 예를 들
   jobs 배열을 반복 처리하려면 보통 부분적 뷰에서 각 과제를 job 변수로 접근해야 할 것입니다. 현재 반복에서의 키값은 부분적 뷰에서 key 변수로 접근할 수 있습니다.\r\n
       \r\n
       또한 @each 지시어에 네번째 인수를 전달할 수도 있습니다. 이 인자는 특정 배열이 비었을 경우 렌더링될 뷰를 결정합니다.
       """,
     featured: "uploads/posts/1541266906t-pride.png",
     created_at: "2018-11-03 17:41:46",
     updated_at: "2018-11-03 17:41:46",
   }
>>> App\Post::all();
=> Illuminate\Database\Eloquent\Collection {#2928
     all: [
       App\Post {#2929
         id: 1,
         category_id: 1,
         title: "새로운 포스트2",
         content: """
           첫번째 인자는 배열이나 컬렉션의 각 요소를 렌더링하기 위한 부분적 뷰의 이름입니다. 두번째 인자는 반복 처리하는 배열이나 컬렉션이며 세번째 인수는 뷰에서의 반복값이 대입되는 변수의 이름입니다. 예를
 들어 jobs 배열을 반복 처리하려면 보통 부분적 뷰에서 각 과제를 job 변수로 접근해야 할 것입니다. 현재 반복에서의 키값은 부분적 뷰에서 key 변수로 접근할 수 있습니다.\r\n
           \r\n
           또한 @each 지시어에 네번째 인수를 전달할 수도 있습니다. 이 인자는 특정 배열이 비었을 경우 렌더링될 뷰를 결정합니다.
           """,
         featured: "uploads/posts/1541266906t-pride.png",
         created_at: "2018-11-03 17:41:46",
         updated_at: "2018-11-03 17:41:46",
       },
       App\Post {#2930
         id: 2,
         category_id: 4,
         title: "새로운 포스트3",
         content: """
           create 메소드를 통해 한줄에서 바로 새로운 모델을 추가할 수도 있습니다. 메소드를 통해 추가된 모델 인스턴스가 결과로 반환될 것입니다. 하지만 기본적으로 모든 Eloquent 모델은 대량 할당-Mass Assignment 으로부터 보호되기 때문에, 이렇게 하기 전에 모델의 fillable나 guarded 속성을 지정해야 주어야 합니다.\r\n
           \r\n
자         대량 할당(Mass Assignment)의 취약성은 사용자가 예상치 못한 HTTP 요청 파라미터를 전달했을 때 발생하며, 해당 파라미터는 데이터베이스의 예상하지 못한 컬럼을 변경하게 됩니다. 예를 들어 악의적인 사용
  는 HTTP 요청을 통해 is_admin을 전달할 수 있으며 이 파라미터는 모델의 create 메소드에 전달되어 사용자를 관리자로 승격할 수 있습니다.
           """,
         featured: "uploads/posts/1541267288e558fc334641b0114cbc6d7503c09c08.jpg",
         created_at: "2018-11-03 17:48:08",
         updated_at: "2018-11-03 17:48:08",
       },
     ],
   }
>>> 
```
