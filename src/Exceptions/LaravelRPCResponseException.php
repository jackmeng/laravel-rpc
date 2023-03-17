<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/3/17 0017 11:33
 */

namespace LaravelRpc\Exceptions;

class LaravelRPCResponseException extends LaravelRpcException
{
    protected $message = 'RPC 响应错误';

    protected $errorInfo = [];


    public function setErrorInfo($errorInfo)
    {
        $this->errorInfo = $errorInfo;

        return $this;
    }

    public function getErrorInfo()
    {
        return $this->errorInfo;
    }
}