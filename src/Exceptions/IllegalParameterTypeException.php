<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 16:42
 */

namespace LaravelRpc\Exceptions;

class IllegalParameterTypeException extends LaravelRpcException
{
    protected $message = '非法的参数类型';
}