<?php

namespace LaravelRpc;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaravelRpc\Console\Commands\RpcClient;
use LaravelRpc\Console\Commands\RpcClients;
use LaravelRpc\Console\Commands\RpcGenerateKey;
use LaravelRpc\Http\Controllers\RpcController;
use LaravelRpc\Http\Middleware\Fixed;
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
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/laravel_rpc.php', 'laravel_rpc');
        }
    }

    public function boot()
    {
        if (app()->runningInConsole()) {
//            $this->registerMigrations();

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ],'laravel_rpc_migration');

            $this->publishes([
                __DIR__.'/../config/laravel_rpc.php' => config_path('laravel_rpc.php'),
            ]);

            $this->commands([
                RpcClient::class,
                RpcClients::class,
                RpcGenerateKey::class,
            ]);
        }

        $this->defineRoutes();
    }

    /**
     * Register laravel rpc migration files.
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
            Route::post('/server/sign',[RpcController::class,'request'] )->middleware(SignatureCheck::class);
            Route::post('/server/fixed',[RpcController::class,'request'] )->middleware(Fixed::class);
        });
    }
}