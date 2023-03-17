<?php

namespace LaravelRpc\Middleware;

use Closure;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use LaravelRpc\ResponseTrait;

class Fixed
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $verifyKey = config('laravel_rpc.verify_key');
        if (!$verifyKey){
            return $this->error('请配置 verify_key');
        }
        $input = file_get_contents('php://input');
        try{
            $params = (new Encrypter(base64_decode($verifyKey),config('laravel_rpc.verify_cipher')))->decrypt($input);
        }catch (\Throwable $e){
            return $this->error('参数解析失败');
        }

        $request->merge($params);
        return $next($request);
    }
}
