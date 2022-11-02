
## 라라벨 설치

- 기존에 WSL에 vagrant + homestead + laravel을 설치한 상태라서 설치부분은 패스  

### todos-app(Tasks manager) 설정  

먼저 vagrant server에 접속해서 새 프로젝트 생성  

```bash
~/Homestead$ vagrant up 
~/Homestead$ vagrant ssh  

vagrant@homestead:~/code$ laravel new todos-app 
```
로컬 환경(windows)의 hosts 파일에서 todos-app을 추가  

```bash
192.168.10.10  todos-app.test
```
이렇게 하면 웹브라우저에서 todos-app.test로 접속 가능  
**또는!!** 프로젝트 디렉토리에서 다음을 입력하면 php artisan 개발서버가 동작하고  
브라우저에서 확인이 가능  

```bash
~/code/todos-app$ php artisan serve 
Laravel development server started: <http://127.0.0.1:8000> 
```

브라우저에서 ``http://127.0.0.1:8000/``로 접속해서 확인한다.  

