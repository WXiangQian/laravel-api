# laravel-api
基于laravel5.5搭建的api架子

### 克隆仓库
```
git clone git@github.com:WXiangQian/laravel-api.git
```

### 运行环境
```
"php": ">=7.0.0"
```

### 生成配置文件
```
cp .env.example .env
```
你可以根据情况修改 .env 文件里的内容，如数据库连接、缓存、邮件设置等。

### 生成秘钥
```
php artisan key:generate
```

### 生成网站链接
```
php artisan serve

Laravel development server started: <http://127.0.0.1:8000>
http://127.0.0.1:8000为该网站的临时地址
```
### 目录结构详解
~~~
laravel-api             项目目录
├─app                   应用目录
│  ├─Console            注册自定义Artisan命令和你定义的计划任务
│  ├─Events             Events 目录存放了 事件类
│  ├─Exceptions         应用的异常处理器，也是应用抛出异常的地方
│  ├─Http               控制器、中间件和表单请求
│  │  ├─Controller      控制器目录
│  │  │     ├─v1        版本1目录
|  |  |     ├─v2        版本2目录
│  │  ├─Middleware      存储中间件
│  │  ├─Requests        表单请求验证
│  ├─Models             model模型
│  ├─Providers          服务容器中绑定服务、注册事件
│  ├─Services           services服务层进行逻辑处理
│  ├─Transformers       转换数据结构
│  └─helpers.php        全局调用函数
├─bootstrap             引导框架并配置自动加载的文件
├─config                配置文件目录
├─database              数据填充和迁移文件目录
│  ├─factories          工厂Factory添加测试数据
│  ├─migrations         数据迁移
│  └─seeds              数据填充
├─public                资源文件
│  ├─favicon.ico        ico
│  ├─index.php          入口文件
│  ├─robots.txt         网络爬虫排除标准
│  └─.htaccess          用于apache的重写
├─resources             视图和未编译的资源文件
│  ├─assets             未编译的资源文件（如 LESS、SASS）
│  ├─lang               语言文件目录
│  └─views              视图文件
├─routes                所有路由定义
│  ├─web.php
│  ├─api.php
│  ├─console.php
│  └─channels.php       视图文件
├─storage               
│  ├─app                存储应用生成的任何文件
│  ├─framework          存储框架生成的文件和缓存
│  └─logs               应用的日志文件
├─tests                 自动化测试文件
├─vendor                第三方类库目录（Composer依赖库）
├─.env.example          env的例子
├─.gitignore            写入不进入版本管理的文件
├─composer.json         composer 定义文件
└─README.md             README 文件
~~~

