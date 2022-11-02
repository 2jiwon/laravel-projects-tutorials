
## 프로젝트 셋팅

### 기존에 Homestead를 설치해놓은 상태에서 새 프로젝트 추가  

( 1과 2는 순서가 바뀌었을 수도 있다... )

1. vagrant의 code 디렉토리에서 새 laravel project 추가  
```bash
laravel new Todos
```
그러면 기존 프로젝트 이름이었던 homestead 디렉토리 옆에 Todos 디렉토리가 생성된다.

2. 로컬의 Homestead 디렉토리에서 Homestead.yml 파일 수정  
site 항목을 똑같이 추가  
```bash
sites:
    - map: homestead.test
      to: /home/vagrant/code/homestead/public

    - map: todos.test
      to: /home/vagrant/code/Todos/public
```

3. hosts 파일 수정  
```bash
$ sudo vi /etc/hosts

192.168.10.10 homestead.test
192.168.10.10 todos.test
```
4. 로컬의 Homestead 디렉토리에서 vagrant reload 실행  
```bash
Homestead $ vagrant reload --provision
```

5. vagrant up, vagrant ssh

접속성공  

6. 사이트 접속  
``todos.test``접속해보면 새로운 Laravel 프로젝트가 생성된 것을 확인할 수 있다.  


### 만약 database를 변경한다면 이렇게  

1. vagrant의 Todos 디렉토리에서 .env 파일 수정  
```bash
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database/todos.sqlite
DB_USERNAME=todos
DB_PASSWORD=secret                                 
```

 
