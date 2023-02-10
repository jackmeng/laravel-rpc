<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 10:25
 */

return [
    "status"=>true,
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