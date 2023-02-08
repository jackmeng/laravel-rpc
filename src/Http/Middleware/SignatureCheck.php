<?php

namespace LaravelRpc\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use LaravelRpc\Models\Client;
use LaravelRpc\Params;
use LaravelRpc\Response;

class SignatureCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $appid = $request->input('appid');
        $client = Client::where('appid',$appid)->first();
        if (!$client){
            return Response::error('未找到客户端信息');
        }
        $secret = $client->secret;
        $inputParams = $request->all();
        $sign = $request->input('sign');
        if((new Params())->check($inputParams,$secret,$sign)){
            return $next($request);
        }else{
            return Response::error('签名校验失败');
        }

    }
}
