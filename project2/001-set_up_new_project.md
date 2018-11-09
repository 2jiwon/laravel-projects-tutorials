## 새로운 프로젝트 만들기

1. laravel new 프로젝트명
```bash
vagrant@homestead:~/code$ laravel new blog    
Crafting application...              
Loading composer repositories with package information                         
Installing dependencies (including require-dev) from lock file    
Package operations: 72 installs, 0 updates, 0 removals               
```
2. yml 파일 수정  
```bash
jiwon@jiwon-Aspire-V3-371 Homestead $ vi Homestead.yaml 
```
```bash
sites:
    - map: blog.test
      to: /home/vagrant/code/blog/public
```
3. hosts 파일 수정  
```bash
$ sudo vi /etc/hosts

192.168.10.10 homestead.test
192.168.10.10 blog.test
192.168.10.10 todos.test
```
4. vagrant reload
```bash
jiwon@jiwon-Aspire-V3-371 Homestead $ vagrant reload --provision      
```
5. vagrant up, vagrant ssh  

6. 프로젝트명.test 접속  

7. 프로젝트의 env 파일 수정  
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=blog
DB_PASSWORD=1234
```
8. 새 사용자 만들고 권한 이임  
```bash
// 1. root 사용자로 접속(pw는 secret)  
vagrant@homestead:~/code $ mysql -u root -p  
// 2. 데이터베이스 생성 
Mysql > create database blog;
// 3. blog 사용자에게 권한 이임
Mysql > grant all privileges on blog.* to blog@localhost identified by
'1234' with grant option;
Mysql > flush privileges;
```

