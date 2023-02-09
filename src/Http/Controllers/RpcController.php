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
        $controller = config('laravel_rpc.root.namespace').'Controllers\\'.$controller.'Controller';


        // 检测controller是否存在
        if(!class_exists($controller)){
            return $this->error('控制器不存在');
        }
        // 检测action是否存在
        if(!method_exists($controller,$method)){
            return $this->error('方法不存在');
        }
        try{
            $controller_obj = app()->make($controller);
            $method_params = (new \ReflectionMethod($controller_obj,$method))->getParameters();
            $args = [];
            foreach($method_params as $param){
                if(!$param->isDefaultValueAvailable() && !isset($params[$param->name])){
                    return $this->error('请传入参数：'.$param->name);
                }
                $args[] = $params[$param->name]??$param->getDefaultValue();
            }
            return $controller_obj->{$method}(...$args);
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