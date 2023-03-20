<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/8 0008 11:26
 */

namespace LaravelRpc;

trait ResponseTrait
{
    public function responseJson($data=[])
    {
        return response()->json($data);
    }

    public function responseString(string $str)
    {
        return response($str);
    }

    public function error($message='error',$code=404,$data=[])
    {
        return response()->json(['code'=>$code,'message'=>$message,'data'=>$data],500);
    }
}