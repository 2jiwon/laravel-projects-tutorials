
### Install 'AddThis'  

[AddThis](https://www.addthis.com/)  

- AddThis에 계정 등록하고 ShareButton > Tool type은 inline으로 선택  

- Tool 생성 후 마지막에 제공되는 소스 카피  
```php
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5be8f2ec0eb16d0b"></script>
```
- frontend.blade.php의 body에 붙이기  

- 다음 소스를 버튼이 나타나야할 곳에 붙이기
> single.blade.php
```php
                <!-- Go to www.addthis.com/dashboard to customize your tools -->
                <div class="addthis_inline_share_toolbox"></div>
```

### 문제 - 동작 안함... 표시가 안됨 


