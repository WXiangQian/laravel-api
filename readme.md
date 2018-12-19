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
php artisan jwt:secret
```

### 配置好.env以后执行以下命令进行创建数据库
(提示directory already exists 可忽略)

```
php artisan admin:install
```

### 如需测试数据，则执行以下命令填充数据库数据

```
php artisan db:seed
```

### 生成网站链接
```
php artisan serve

Laravel development server started: <http://127.0.0.1:8000>
http://127.0.0.1:8000为该网站的临时地址
```
描述 | 详情
--- |---
log地址 | http://127.0.0.1:8000/wxq/logs
swagger接口文档 | http://127.0.0.1:8000/swagger
接口文档json数据 | http://127.0.0.1:8000/doc/json

[提交问题请点击](https://github.com/WXiangQian/laravel-api/issues)

[目录结构详解](https://github.com/WXiangQian/laravel-api/wiki/)

