
## CSS 변경  

1. 기존 스타일 삭제
원래 view 파일에 있던 style내용 
```php
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
               /* font-size: 84px; */
              font-size: 1.2em;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
```
이것때문에 세로도 fix되어버리고 불편한 것 같아서..
어차피 bootstrap을 가져왔으니 굳이 이걸 쓸 필요가 없으므로 삭제  

2. bootstrap으로 변경  

```php
//원본
    <body>
        <div class="flex-center position-ref full-height"> 
        ....
        <div class="title m-b-md"> </div> 

//변경후
    <body class="bg-light">
        <div class="container-fluid">
        ...
        <div class="pt-5">
```

