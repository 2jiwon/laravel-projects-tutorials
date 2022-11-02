## Form에서 category 선택할 수 있게 하기  

1. PostsController에서 store method 수정  
category_id에 대한 validation추가  

> PostsController.php
```php
      $this->validate($request, [
        'title'    => 'required|max:255',
        'featured' => 'required|image',
        'content'  => 'required',
        'category_id' => 'required'
      ]);
```

2. create method 수정  
post를 생성할때 category들을 보여준다.  
category model을 추가하고, create method를 수정한다.  
```php
use App\Category;
...
    public function create()
    {
      return view('admin.posts.create')->with('categories', Category::all());
    }
```
3. posts/create.blade.php 수정  
create method에서 category들을 불러올 수 있게 되었으니 이제 view를 수정한다.  
> resources/views/admin/posts/create.blade.php
```php
      <div class="form-group">
        <label for="">카테고리 선택</label>
        <select id="category" class="form-control" name="category_id">
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>
```
이제 브라우저에서 확인해보면, 새로운 포스트를 추가할 때 카테고리를 선택할 수
있게 되었다.  

