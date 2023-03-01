<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 10:25
 */

return [
    "status"=>true,
    "verify_key"=>'',
    /**
     * 加密方式，支持以下几种
     * aes-128-cbc | aes-256-cbc | aes-128-gcm | aes-256-gcm
     */
    "verify_cipher"=>'aes-256-cbc',
    "root"=>[
        "dir"=>"app/RPC",
        "namespace"=>'App\\RPC\\',
    ],
    "database"=>[
        "connection"=>"default",
        "prefix"=>"rpc_"
    ],
    "prefix"=>"rpc",
    "servers"=>[
//        "test"=>[
//            "domain"=>env('RPC_TEST_DOMAIN'),
//            "prefix"=>env('RPC_TEST_PREFIX'),
//            "appid"=>env('RPC_TEST_APPID'),
//            "secret"=>env('RPC_TEST_SECRET'),
//        ]
    ]
];