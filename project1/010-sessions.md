
## session

1. controller 수정  
- session사용을 추가  
> TodosController.php
```php
use Session; 
```
- store method내에 session관련 내용을 추가  
```php
public function store(Request $request)
  ....

    Session::flash('success', 'New list was created.');
    return redirect()->back();
```
- delete에도 session추가  
```php
    Session::flash('success', 'The list was deleted.');
    return redirect()->back();
```
- save에도 session추가  
```php
    Session::flash('success', 'The list was updated.');
    return view('update')->with('todo', $todo);
```
- completed에도 추가  
```php
    Session::flash('success', 'The list was marked as complated.');
```
2. view에서 해당 내용이 표시될 자리를 만듬
> layout.blade.php
```php
    <body class="bg-light">

    @if (Session::has('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif
```
3. (추가) alert 사라지는 효과때문에 JS 파일도 집어넣음  


---

일단 강좌는 여기에서 끝남.

내가 추가하고 싶은 것  
1. social login

2. list save and new list

3. select each list and the whole list 

4. reorder list (mouse grab and drag)

5. modify update in the same page(using Ajax?)


