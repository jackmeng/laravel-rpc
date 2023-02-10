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
创建目录 app/RPC/Services
在上述目录中创建 XXX.php 命名的文件及类
继承 `LaravelRpc\Service`
编写自己的代码即可，
返回数据请使用
```php
$this->success([]);
$this->error('');
```

### 客户端
在服务端执行以下命令注册客户端

```php
php artisan rpc_client
```

在 `config/laravel_rpc.php` 中的servers下配置服务端信息

然后创建目录 app/RPC/Clients
创建 XXClient.php 文件及类，并继承`LaravelRcp\Client`
定义 $server 变量，该变量的值为laravel_rpc配置中servers下的key

调用客户端
```php
(new TestClient())->service('Demo')->method('list',["page"=>1]);
```
也可以按Service定义client文件
```php
# Demo.php
class Demo extends \LaravelRcp\Client
{
    protected $server = 'test';
    public static function list($page=1)
    {
        return (new self())->method('list',compact('page'));
    } 
}

#调用
Demo::list()
```

### 配置文件
```php
"status"=>true, // 设置为true 表示开启rpc服务，会注册路由，客户端请设置为false
"root"=>[
    "dir"=>"app\RPC", // RPC服务的根目录
    "namespace"=>'App\\RPC\\', // RPC服务的命名空间
],
"database"=>[
    "connection"=>"default", // 服务端需要用到的数据库连接，客户端无需配置
    "prefix"=>"rpc_" // 数据表前缀
],
"prefix"=>"rpc", // 服务端路由前缀
"servers"=>[ // 客户端需配置此选项，用以注册服务端
    "test"=>[
        "domain"=>env('RPC_TEST_DOMAIN'), // 服务端域名
        "prefix"=>env('RPC_TEST_PREFIX'), // 路由前缀
        "appid"=>env('RPC_TEST_APPID'), // appid 在服务端获取,用于表示客户端id
        "secret"=>env('RPC_TEST_SECRET'), // secret 在服务端获取，用于通信签名及校验
    ]
]
```
