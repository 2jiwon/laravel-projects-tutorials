
## 퀴즈 4  

### 1. request 에서 'name' 필드가 반드시 들어가야한다는 검증을 어떻게 하는가?  

답: ``$this->validate(request(), [ 'name' => 'required' ]);``  

### 2. 컨트롤러에서 검증이 실패하면 어떻게 되는가?  

답: 라라벨은 요청에 대한 작업을 중지하고, 사용자를 다시 폼으로 되돌려보내고 모든
에러를 출력한다.  

### 3. eloquent를 사용해서 새로운 데이터베이스 records를 생성하는 2가지 방법은?  

답:  
    1. ``new Model()``을 사용해서 새로운 인스턴스를 생성하고, ``save``메소드를
호출한다.  
    2. static create model ``Model::create()``을 호출한다.  

### 4. model records를 업데이트하기 위해 어떤 메소드가 사용될 수 있는가?  

답: ``update()``

