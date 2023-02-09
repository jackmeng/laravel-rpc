laravel rpc 用于项目之间进行签名认证通信

该项目客户端和服务端基于一体，下面分别对服务端和客户端的使用方式进行说明

### 安装
```shell
composer require jackmeng/laravel-rpc
#若config目录中没有laravel_rpc.php,请执行一下命令
php artisan vendor:publish --provider="LaravelRpc\\LaravelRPCProvider"
```


### 服务端
```shell
# 发布数据库迁移文件
php artisan vendor:publish --tag=laravel_rpc_migration
# 执行数据库迁移文件
php artisan migration
```
创建目录 app/RPC/controller
在上述目录中创建 XXXController.php 命名的文件及类
继承 `LaravelRpc\Http\Controllers\Controller`
编写自己的代码即可，
返回数据请使用
```php
$this->success([]);
$this->error('');
```

### 客户端
首先在 `config/laravel_rpc.php` 中的servers下增加客户端配置
然后创建目录 app/RPC/Client
创建 XXClient.php 文件及类，并继承`LaravelRcp\Client`
定义 $server 变量

调用客户端
```php
(new TestClient())->Controller('Demo')->method('list',["page"=>1]);
```
也可以按controller定义client文件
```php
# Demo.php
class Demo extends TestClient
{
    public static function list($page=1)
    {
        return (new self())->method('list',compact('page'));
    } 
}

#调用
Demo::list()
```
