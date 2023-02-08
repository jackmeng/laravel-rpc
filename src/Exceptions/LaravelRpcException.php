<?php

namespace LaravelRpc\Exceptions;
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 16:40
 */
class LaravelRpcException extends \Exception
{
    protected $code = 500;
    protected $message = 'laravel rpc exception';
}