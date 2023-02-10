<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/10 0010 9:46
 */

namespace LaravelRpc;

class Service
{
    protected function success($data=[],$message='SUCCESS',$code=200)
    {
        return Response::json($code,$message,$data);
    }

    protected function error($message='ERROR',$code=404,$data=[])
    {
        return Response::json($code,$message,$data);
    }
}