<h2 align="center">
 laravel-api
</h2>
<p align="center">
基于 Laravel 5.5.*+VUE+JWT搭建的api+spa应用
</p>


[![Docs](https://github.com/ly525/luban-h5/workflows/docs/badge.svg)](https://github.com/WXiangQian/laravel-api/wiki/目录结构)
[![GitHub issues](https://img.shields.io/github/issues/WXiangQian/laravel-api)](https://github.com/WXiangQian/laravel-api/issues)
[![GitHub stars](https://img.shields.io/github/stars/WXiangQian/laravel-api.svg?style=social&label=Star&maxAge=2592000)](https://github.com/WXiangQian/laravel-api/stargazers/)
[![GitHub forks](https://img.shields.io/github/forks/WXiangQian/laravel-api.svg?style=social&label=fork&maxAge=2592000)](https://github.com/WXiangQian/laravel-api/network/members/)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

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

```
npm install 安装节点依赖项
npm run dev 
npm run watch
npm run hot  热模块替换（或热重新加载）
npm i element-ui -S 安装element-ui
```
```
如果遇到报错'cross-env' 不是内部或外部命令，也不是可运行的程序或批处理文件。
请执行 npm install -save-dev
如发现漏洞(vulnerabilities )
run `npm audit fix` to fix them, or `npm audit` for details
执行 npm audit fix
请记住，npm run dev每次更改Vue组件时都应该运行该命令。
或者
您可以运行npm run watch命令来监视每次修改组件并自动重新编译它们。
```
### 配置好.env以后执行以下命令进行创建数据库
(提示directory already exists 可忽略)

```
php artisan migrate
```

### 如需测试数据，则执行以下命令填充数据库数据

```
php artisan db:seed
如果想多生成点文章，则执行以下命令
php artisan db:seed --class=CreateArticlesSeeder
```

### 生成网站链接
```
php artisan serve

Laravel development server started: <http://127.0.0.1:8000>
http://127.0.0.1:8000为该网站的临时地址
```

```
// 定时任务命令
php artisan  schedule:run
// 自定义command命令
php artisan test:log
```
描述 | 详情
--- |---
log地址 | http://127.0.0.1:8000/wxq/logs
swagger接口文档 | http://127.0.0.1:8000/swagger
接口文档json数据 | http://127.0.0.1:8000/doc/json

### 如何贡献
所有合理的改动、优化、修正，新的组件，或者文档的修正、更新 相关的提交都会被接收