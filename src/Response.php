<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/8 0008 11:26
 */

namespace LaravelRpc;

class Response
{
    public static function success($data=[],$message='success',$code=200)
    {
        return self::json($code,$message,$data);
    }

    public static function error($message='error',$code=404,$data=[])
    {
        return self::json($code,$message,$data);
    }

    public static function json(int $code,string $message,array $data)
    {
        return response()->json(compact('code','message','data'));
    }
}