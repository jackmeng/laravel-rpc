<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/8 0008 9:50
 */


//$client = new \LaravelRpc\Client('');

class base{
    function test()
    {
        var_dump(self::class);
        var_dump(static::class);
    }
}

class demo extends base{

}

(new demo())->test();