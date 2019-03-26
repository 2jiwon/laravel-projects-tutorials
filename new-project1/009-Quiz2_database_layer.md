## 퀴즈 2

### 1. migration이란 무엇인가?  

답: migration이란 라라벨의 데이터베이스 스키마와 함께 작동하여 애플리케이션
데이터베이스의 데이터베이스 스키마를 구축하는 PHP class이다. 데이터베이스 구조의
변경을 수행하고 실행취소하는데 사용한다. 

### 2. model이란 무엇인가?  

답: model은 단일 데이터베이스 테이블에 연결하는 class로, 데이터베이스
테이블로부터 레코드를 생성하고, 읽어오거나 업데이트, 삭제하는데 사용한다. 

### 3. factory란 무엇인가?  

답: factory는 거의 대체적으로 테스팅 용도로 fake data를 생성한다. 

### 4. 라라벨에서 up 함수의 용도는 무엇인가?  

답: up 메소드는 migration을 실행할때 호출한다. 

### 5. 라라벨 migration에서 down 함수의 용도는 무엇인가?  

답: down 메소드는 migraion을 rollback 할때 실행한다. 

### 6. database seeder의 용도는 무엇인가?  

답: 데이터베이스에 fake 또는 test data를 생성하는데 사용하는 class이다. 

### 7. 라라벨에서 migration을 실행하는 명령은?  

답: php artisan migrate 

### 8. Article 이라는 이름의 eloquent model을 생성하면서 migration을 동시에 실행하는 명령은? 

답: php artisan make:model Article -m

