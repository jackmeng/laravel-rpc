<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 16:42
 */

namespace LaravelRpc\Exceptions;

class ServerConfigDoesNotExistException extends LaravelRpcException
{
    protected $message = 'RPC服务器配置不存在';
}