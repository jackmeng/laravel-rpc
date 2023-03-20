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

    protected $errorMsg = '';

    protected $errorData = [];

    /**
     * @return string
     */
    public function getErrorMsg(): string
    {
        return $this->errorMsg;
    }

    /**
     * @param string $errorMsg
     */
    public function setErrorMsg(string $errorMsg)
    {
        $this->errorMsg = $errorMsg;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrorData(): array
    {
        return $this->errorData;
    }

    /**
     * @param array $errorData
     */
    public function setErrorData(array $errorData)
    {
        $this->errorData = $errorData;

        return $this;
    }






}