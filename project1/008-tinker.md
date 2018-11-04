
## Tinker

[Tinker](https://scotch.io/tutorials/tinker-with-the-data-in-your-laravel-apps-with-php-artisan-tinker)

1. tinker 실행
```bash
$ php artisan tinker
```

이렇게 실행하면 command line shell이 실행됨 

2. App\Todo::all
Todos 테이블의 내용이 출력된다.
```bash
$todos = App\Todo:all();
```
3. App\Todo::find(1)
id 1의 내용이 출력
```bash
>>> $todo = App\Todo::find(1);
=> App\Todo {#2926
     id: 1,
     todo: "Magnam animi fugiat rerum magnam delectus consequatur sunt velit.",
     completed: 1,
     created_at: "2018-10-31 13:29:27",
     updated_at: "2018-11-01 10:29:38",
     deleted_at: null,
   }
```
4. 새로운 내용 추가(-> 내용 추가가 아니라 그냥 맨 위의 내용을 덮어버림..)
```bash
$todo->todo = "Save some money";
$todo->save();
```
5. factory 사용해서 dummy data 생성  
```bash
factory(App\Todo::class, 10)->create();
```
6. delete record
```bash
$todos = App\Todo::find(20)->delete();
```

