<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 14:09
 */

namespace LaravelRpc;

class Request
{
    protected $params = [];

    public function __construct()
    {
        $this->params = request()->input('params');
    }

    public function input($key='',$default=null)
    {
        return isset($params[$key])?$params[$key]:$default;
    }

}