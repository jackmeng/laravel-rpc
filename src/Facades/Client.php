<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/3/17 0017 10:40
 */

namespace LaravelRpc\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Client
 *
 * @method static \LaravelRpc\Client server(string $server)
 * @method static \LaravelRpc\Client setDomain(string $domain)
 * @method static \LaravelRpc\Client header(string $key,string $value)
 * @method static \LaravelRpc\Service service(string $service)
 * @method static \Illuminate\Http\Client\Response method($method, $params=[])
 * @method static \Illuminate\Http\Client\Response invoke($path,$params=[])
 *
 * @see \LaravelRpc\Client
 * @package LaravelRpc\Facades
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/3/17 0017 11:39
 */
class Client extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel_rpc_client';
    }
}