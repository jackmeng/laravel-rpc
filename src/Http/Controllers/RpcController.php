<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 14:22
 */

namespace LaravelRpc\Http\Controllers;

use Illuminate\Http\Request;

class RpcController extends Controller
{
    public function request(Request $request)
    {
        $controller = $request->input('controller');
        $method = $request->input('method');
        $params = $request->input('params');
        $controller = str_replace('/','\\',$controller);
        $controller = config('laravelrpc.root.namespace').$controller.'Controller';

        // 验证参数签名

        // 检测controller是否存在
        if(!class_exists($controller)){
            return $this->error('控制器不存在');
        }
        // 检测action是否存在
        if(!method_exists($controller,$method)){
            return $this->error('方法不存在');
        }
        try{
            return app()->make($controller)->{$method}(...$params);
        }catch (\Throwable $e){
            if (app()->hasDebugModeEnabled() || app()->isLocal()){
                return $this->error($e->getMessage(),500,[
                    'file'=>$e->getFile(),
                    'line'=>$e->getLine(),
                    'trace'=>$e->getTraceAsString()
                ]);
            }else{
                return $this->error('请求异常');
            }
        }
    }
}