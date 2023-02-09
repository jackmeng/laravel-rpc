<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 15:06
 */

namespace LaravelRpc;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use LaravelRpc\Exceptions\ServerConfigDoesNotExistException;

class Client
{
    protected array $request_params = [];
    protected string $domain = '';
    protected string $appid = '';
    protected string $secret = '';
    protected string $prefix = '';
    protected string $server = '';

    public function __construct($server='')
    {
        if (!empty($server)){
            $this->server($server);
        }elseif(!empty($this->server)){
            $this->server($this->server);
        }
        $this->controller(class_basename(static::class));
    }

    public function server($server)
    {
        $server_config = config('laravel_rpc.servers.'.$server);
        if (empty($server_config)){
            throw new ServerConfigDoesNotExistException();
        }
        $this->server = $server;
        $this->setDomain($server_config['domain']??'');
        $this->setAppid($server_config['appid']??'');
        $this->setSecret($server_config['secret']??'');
        $this->setPrefix($server_config['prefix']??'');

        return $this;
    }

    public function setDomain($domain)
    {
        $this->domain = rtrim($domain,'/');
        return $this;
    }

    public function setAppid($appid)
    {
        $this->request_params['appid'] = $appid;
        $this->appid = $appid;

        return $this;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = trim($prefix,'/');

        return $this;
    }

    public function controller($controller): Client
    {
        $this->request_params['controller'] = $controller;
        return $this;
    }

    public function method($method, $params): \Illuminate\Http\Client\Response
    {
        $this->request_params['method'] = $method;
        $this->request_params['params'] = $params;

        return $this->request();
    }

    protected function request(): \Illuminate\Http\Client\Response
    {
        $this->request_params['nonce_str'] = Str::random();
        $this->request_params['sign'] = (new Params())->signature($this->request_params,$this->secret);

        return Http::post($this->getUrl(), $this->request_params);
    }

    protected function getUrl()
    {
        return $this->domain.'/'.$this->prefix.'/server';
    }
}