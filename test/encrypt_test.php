<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 17:37
 */

include "vendor/autoload.php";

use LaravelRpc\Params;

$data = [
    'e'=>[
        [5,12,'c',3,'a',22,13],
        [14,15,16,[66,77,88,[12,344,555,666]]],
    ],
    'd'=>[
        1,2,'c'=>'333','a'=>'bbb'
    ],
    'f'=>[
        'abc'=>[123,'ccc'=>[2342,234345]]
    ],
    'b'=>2,
    'a'=>'1',

    'c'=>[
        7,'b',6,8,'a','c'
    ],
];

$data = [
    1,2,3,
    [
        5,7,9,'a'=>[234,345]
    ]
];

dd((new Params())->signature($data));