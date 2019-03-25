
### Migrations

migration에 대해 더 많이 알려면 [이곳을 참조](https://laravel.com/docs/5.8/migrations)

### Models

#### 소개 

라라벨에 있는 Eloquent ORM은 당신의 데이터베이스와 함께 작업하기 위한 아름답고 심플한 ActiveRecord 실행을 제공한다.  
각 데이터베이스 테이블은 상호작용을 위해 사용되는 "Model"을 가지고 있다. Model은 테이블에 새로운 record를 넣는것과 같은, 
테이블의 데이터를 위한 쿼리를 사용할 수 있다.  

시작하기 전에, ``config/database.php``파일에서 데이터베이스 연결 설정을 확인하라. 
더 자세한 내용은 [이 문서](https://laravel.com/docs/5.8/database#configuration)를 보라.

#### Models 정의  

시작하기 전에, Eloquent model을 생성하자. Model은 보통 ``app`` 디렉토리에
존재하지만, 원한다면 ``composer.json`` 파일에 따른 오토로딩으로 다른 곳에 옮겨도 된다.
모든 Eloquent models는 Illuminate\Database\Eloquent\Model class를 확장한다.  

model 인스턴스를 생성하는 가장 쉬운 방법은 artisan 명령인 ``make:model``이다.  

```bash
php artisan make:model Flight
```
model을 만들면서 동시에 database migration을 만들고 싶다면 ``--migration``이나 ``-m`` 옵션을 사용하라.  

```bash
php artisan make:model Flight --migration  
php artisan make:model Flight -m  
```

#### Elogquent Model 규약 

우리가  flight 데이터베이스 테이블로부터 정보를 가져오고 저장할 Flight 모델을 살펴보자. 

```php
<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Flight extends Model
{
      //
}
```

#### 테이블 이름들  

우리는 Flight 모델을 위해 어떤 테이블을 사용할 거라고 Eloquent에 말해준 적이 없다. 
명확하게 특정지은 다른 이름이 없다면, 규약에 의해서 "snake_case"와 class의 복수형으로 작성된 이름이 
테이블 이름으로 사용된다. 그러므로 이 경우에는, Eloquent는 Flight 모델은 flights
테이블에 데이터를 저장할 것이라고 여긴다.  

다음과 같이 table 속성을 정의함으로써 별도로 테이블 이름을 특정지을 수도 있다.  

```php

<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Flight extends Model
{
   /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'my_flights';
}
```
