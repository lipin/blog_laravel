# Laravel大型项目系列教程（一）
------
## 一、简介

本教程将使用Laravel完成一个多用户的博客系统，大概会包含如下内容：

路由管理。
用户管理，如用户注册、修改信息、锁定用户等。
文章管理，如发表文章、修改文章等。
标签管理，文章会有一到多个标签。
数据库管理，如迁移、填充数据等。
Web表单验证。
Blade模版引擎。
分页处理。
安全处理。
单元测试。
部署到应用服务器Apache。
尽量保证每节教程完整并能运行，会在教程的最后附上这节教程的代码下载地址。

Tip：教程中必要的知识点都会有一个超链接
## 二、环境要求

PHP 5.4+
MySQL 5.1+
Composer（中国镜像）
## 三、Let's go!

### 1.新建一个Laravel项目

使用如下命令创建一个名为blog的Laravel项目：

`$ composer create-project laravel/laravel blog --prefer-dist`
创建完成之后进入到blog目录，修改`app/config/app`.php中的timezone为RPC、locale为zh，然后在blog目录下启动它自带的开发服务器：

`$ php artisan serve`
`Laravel development server started on http://localhost:8000`
打开浏览器输入`localhost:8000`，如果页面如下图就说明项目搭建完成了：
![tool-editor](http://image.golaravel.com/3/ee/b98440dfa6df971f4cfc930f9dd1c.jpg)
## 2.安装插件

在`composer.json`中增加：
```python
"require-dev": {
    "way/generators": "~2.0"
},
```
运行`composer update`安装，完成后在`app/config/app.php`的`providers`中增加：

`'Way\Generators\GeneratorsServiceProvider'`
运行`php artisan`是不是多了`generate`选项，它可以快速地帮我们创建想要的组件。

## 3.建立数据库

把`app/config/database.php`中`connections`下的`mysql`改成你自己的配置：
```python
'mysql' => array(
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'blog',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
),
```
> * 需要在MySQL中先创建一个名为blog的数据库

配置完成之后，创建users表的数据库迁移文件：

`$ php artisan migrate:make create_users_table --create=users`
我们会发现在`app\database\migrations`下多了一个*`_create_users_table.php`文件，在这个文件中修改：
```python
Schema::create('users', function(Blueprint $table)
{
    $table->increments('id');
    $table->string('email');
    $table->string('password');
    $table->string('nickname');
    $table->boolean('is_admin')->default(0);
    $table->boolean('block')->default(0);
    $table->timestamps();
});
```
之后进行数据库迁移：

`$ php artisan migrate`
你会惊讶地发现在数据库中多了两张表`users`和`migrations`，`users`表就是我们定义的表，`migrations`表记录了迁移的信息。

## 4.创建User模型

数据库迁移完成之后我们将使用`Eloquent ORM`，这是Laravel让人着迷的重要原因之一。我们会发现在`app\models`下已经有一个`User.php`文件了，对其修改：
```python
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserTrait;

class User extends Eloquent implements UserInterface {
    use UserTrait;

    protected $table = 'users';
    protected $hidden = array('password', 'remember_token');
    protected $guard = array('email', 'password');
}
```
## 5.填充数据

有了User模型后，我们就可以向数据库填充数据了，在`app/database/seeds`下创建一个名为`UsersSeeder.php`的文件，增加如下：
```python
class UsersSeeder extends Seeder {
    public function run()
    {
        User::create([
            'email'    => 'admin@shiyanlou.com',
            'password' => Hash::make(''),
            'nickname' => 'admin',
            'is_admin' => 1,
        ]);
    }
}
```
然后在`DatabaseSeeder.php`中增加：

`$this->call('UserTableSeeder');`
之后就真正地向数据库填充数据：

`$ php artisan db:seed`
你可以查看数据库，会发现users表中多了一条记录。

详情可以查看Laravel中[数据库的迁移和填充](http://v4.golaravel.com/docs/4.2/migrations)
## 6.创建试图模版
我们将使用Laravel中的Blade模版引擎，使用下面命令创建三个视图：
```python
php artisan generate:view _layouts.default
php artisan generate:view _layouts.nav
php artisan generate:view _layouts.footer
php artisan generate:view index
```
之后你可以在`app/views`下发现多了一个`index.blade.php`和一个_`layouts`文件夹，在`_layouts`文件夹下有三个文件`default.blade.php`、`footer.blade.php`和`nav.blade.php`。我们将使用[AmazeUI](http://amazeui.org/)框架来做为前端框架，修改`default.blade.php`：
```python
<!DOCTYPE html>
<html>
<head lang="zh">
  <meta charset="UTF-8"/>
  <title>ShiYanLou Blog</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no"/>
  <meta name="renderer" content="webkit"/>
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <link rel="alternate icon" type="image/x-icon" href="{{ URL::asset('i/favicon.ico') }}"/>
  <link rel="stylesheet" href="//cdn.amazeui.org/amazeui/2.1.0/css/amazeui.min.css"/>
  {{ HTML::style('css/custom.css') }}
</head>
<body>
<header class="am-topbar am-topbar-fixed-top">
  <div class="am-container">
    <h1 class="am-topbar-brand">
      <a href="/">ShiYanLou Blog</a>
    </h1>
    @include('_layouts.nav')
  </div>
</header>

@yield('main')

@include('_layouts.footer')

<script src="//cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdn.amazeui.org/amazeui/2.1.0/js/amazeui.min.js"></script>
</body>
</html>
```