<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 14:34
 */

namespace LaravelRpc\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use LaravelRpc\Response;

class Controller extends BaseController
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