
### summernote 설치  

[summernote](https://summernote.org/)  

1. layout 수정  
> resources/views/layouts/app.blade.php
```php
    @yield('styles')

</head>
...
@yield('scripts')

</body>
```

2. create view 수정  
> resources/views/admin/posts/create.blade.php
```php
기존 내용 아래에 붙임 

@section('styles')
<!-- include summernote css -->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
@stop

@section('scripts')
<!-- include summernote js -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
@stop
```
- summernote 스크립트 추가 

```php
<script>
  $(document).ready(function() {
    $('#summernote').summernote();
  });
</script>
```
- 우리는 content니까 summernote 대신 content로 수정  
```php
<script>
  $(document).ready(function() {
    $('#content').summernote();
  });
</script>
```

3. 강좌대로 하니까 계속 오류 나서 찾아보고 나름 대로 수정한 것  

- 위에있던 js 파일 밑으로 내리고 
> app.blade.php
```php

<!-- Scripts --> // 이거 원래 위에 있었음
<script src="{{ asset('js/app.js') }}" ></script>

// @jquery 이거 빼도 되길래 뺐음
@toastr_css
@toastr_js
@toastr_render

<script>
@if(Session::has('success'))
  toastr.success("{{ Session::get('success') }}")
@endif

@if(Session::has('info'))
  toastr.info("{{ Session::get('info') }}")
@endif
</script>

@yield('scripts')

</body>
```

- bs4를 안넣으면 스타일이 이상하게 나온다. 아이콘이 다 까맣게 나오거나 겹쳐서
나옴..
> create.blade.php
```php
@section('styles')
<!-- include summernote css -->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
@stop

@section('scripts')
<!-- include summernote js -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
<script>
  $(document).ready(function() {
    $('#content').summernote();
  });
</script>
@stop
```

