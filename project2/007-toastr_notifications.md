
## Install Toastr

[Toastr repo](https://github.com/CodeSeven/toastr)

Toastr을 사용하는 방법은 여러가지이지만, 여기에서는 다운받아서 사용하는 방식을
해보겠다.  

1. public/css에 toastr.min.css를 다운받기(혹은 그냥 내용을 복사해서 붙여넣기를 해도됨)

2. public/js에 toastr.min.js를 다운받기(역시 그냥 내용을 복사,붙여넣기 해도됨)  

3. app.blade.php에 파일을 링크하기  
```php
// js file
    <script src="{{ asset('js/toastr.min.js') }}" defer></script>
// css file
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
```
여기에 쓰인 것은 asset method인데, public폴더에 있는 것을 불러와준다.  

## session 확인 

1. 만약 Session에 success값이 있다면 toastr의 success method를 부른다.
> app.blade.php
```php
<script>
  @if (Session::has('success'))
    toastr.success("{{ Session::get('success') }}")
  @endif
</script>
```
2. CategoriesController 확인  
여기에 Category 관련 작업이 끝나면 session을 설정하는지 확인한다.  
- Session을 추가  
> CategoriesController.php
```php
use Session;
```
- store method에 session 추가  
```php
...
      Session::flash('success', '카테고리가 성공적으로 추가되었습니다.');

      return redirect()->route('categories');
```
- update,delete method에도 추가  
```php
      Session::flash('success', '카테고리가 성공적으로 수정되었습니다.');
...
      Session::flash('success', '카테고리가 성공적으로 삭제되었습니다.');
```

3. toastr이 이 방법으로 안됨... 전부 원래대로 되돌림 

에효... 

## toastr 적용해서 성공한 방법!!

[link](https://www.phpflow.com/php/simple-example-of-toastr-notification-using-laravel-5-6/)  

1. composer로 laravel toastr 설치  
```bash
composer require yoeunes/toastr
```
2. 패키지 include  
> layouts/app.blade.php
```php
@jquery
@toastr_css
@toastr_js
@toastr_render
```
3. 스크립트 추가  
```php
<script>
@if(Session::has('success'))
  toastr.success("{{ Session::get('success') }}")
@endif
</script>
```
