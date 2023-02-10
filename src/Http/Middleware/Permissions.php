<?php


namespace LaravelRpc\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use LaravelRpc\Models\Permissions as PermissionsModel;
use LaravelRpc\Response;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $appid = $request->input('appid');
        $service = $request->input('service');
        $method = $request->input('method');
        $permissions = PermissionsModel::where('appid',$appid)->where(function($query) use ($service,$method){
            $query->where('service','*')->orWhere(function($query) use ($service){
                $query->where('service',$service)->where('method','*');
            })->orWhere(function($query)use($service,$method){
                $query->where('service',$service)->where('method',$method);
            });
        })->first();
        if ($permissions){
            return $next($request);
        }else{
            return Response::error('没有相关接口的权限');
        }
    }
}
