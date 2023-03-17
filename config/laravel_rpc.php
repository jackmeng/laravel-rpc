<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 10:25
 */

return [
    /**
     * 是否开启服务端，开启后将会注册路由
     */
    "status"=>true,

    /**
     * 通讯验证需要的key，例如加密、签名，等
     */
    "verify_key"=>env('LARAVEL_RPC_VERIFY_KEY'),

    /**
     * 加密方式，支持以下几种
     * aes-128-cbc | aes-256-cbc | aes-128-gcm | aes-256-gcm
     */
    "verify_cipher"=>\LaravelRpc\Enum\VerifyCipher::AES256CBC,

    "root"=>[
        // 服务根目录
        "dir"=>"app/RPC",
        // 服务根命名空间
        "namespace"=>'App\\RPC\\',
    ],
    // 如需采用注册签名验证机制，才需配置此项
    "database"=>[
        "connection"=>"default",
        "prefix"=>"rpc_"
    ],
    // 路由前缀
    "route_prefix"=>"rpc",

    /**
     * 作为客户端时调用的服务配置
     * 可以精简调用代码，优化调用方式
     */
    "servers"=>[
//        "test"=>[
//            "domain"=>env('RPC_TEST_DOMAIN'),
//            "route_prefix"=>'rpc',
//            "verify_type"=>\LaravelRpc\Enum\VerifyType::FIXED,
//            "verify_cipher"=\LaravelRpc\Enum\VerifyCipher::AES256CBC,
//            "appid"=>env('RPC_TEST_APPID'),
//            "secret"=>env('RPC_TEST_SECRET'),
//        ]
    ]
];