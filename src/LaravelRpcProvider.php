<?php

namespace LaravelRpc;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaravelRpc\Console\Commands\RpcClient;
use LaravelRpc\Console\Commands\RpcClients;
use LaravelRpc\Http\Controllers\RpcController;
use LaravelRpc\Http\Middleware\SignatureCheck;

/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/3 0003 18:00
 */
class LaravelRpcProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ]);

            $this->publishes([
                __DIR__.'/../config/laravel_rpc.php' => config_path('laravel_rpc.php'),
            ]);

            $this->commands([
                RpcClient::class,
                RpcClients::class,
            ]);
        }

        $this->defineRoutes();
    }

    /**
     * Register Sanctum's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function defineRoutes()
    {
        if (app()->routesAreCached() || config('laravel_rpc.status') === false) {
            return;
        }

        Route::group(['prefix' => config('laravel_rpc.prefix', 'rpc')], function () {
            Route::post('/server',[RpcController::class,'request'] )->middleware(SignatureCheck::class);
        });
    }
}