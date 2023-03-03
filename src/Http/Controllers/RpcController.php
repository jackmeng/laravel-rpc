<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 14:22
 */

namespace LaravelRpc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RpcController extends Controller
{
    public function request(Request $request)
    {
        $service = $request->input('service');
        $method = $request->input('method');
        $params = $request->input('params');
        $service = str_replace('/','\\',$service);
        $service = config('laravel_rpc.root.namespace').'Services\\'.$service;


        // 检测controller是否存在
        if(!class_exists($service)){
            return $this->error('service不存在');
        }
        // 检测action是否存在
        if(!method_exists($service,$method)){
            return $this->error('method 不存在');
        }
        try{
            $service_obj = app()->make($service);
            $method_params = (new \ReflectionMethod($service_obj,$method))->getParameters();
            $args = [];
            foreach($method_params as $param){
                if(!$param->isDefaultValueAvailable() && !isset($params[$param->name])){
                    return $this->error('请传入参数：'.$param->name);
                }
                $args[] = $params[$param->name]??$param->getDefaultValue();
            }
            return $service_obj->{$method}(...$args);
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