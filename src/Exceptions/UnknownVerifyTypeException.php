<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/3/16 0016 18:32
 */

namespace LaravelRpc\Exceptions;

class UnknownVerifyTypeException extends LaravelRpcException
{
    protected $message = '未知的验证方式';
}