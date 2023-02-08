<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 15:06
 */

namespace LaravelRpc;

use Illuminate\Support\Facades\Http;

class Client
{
    protected array $request_params = [];
    protected string $domain = '';
    protected string $appid = '';
    protected string $secret = '';
    protected string $prefix = '';

    public function __construct($domain='',$appid='',$secret='',$prefix='rpc')
    {
        $this->setDomain($domain);
        $this->setAppid($appid);
        $this->setSecret($secret);
        $this->setPrefix($prefix);
        $this->controller(class_basename(static::class));
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
        $this->request_params['sign'] = (new Params())->signature($this->request_params,$this->secret);

        return Http::post($this->domain, $this->request_params);
    }

    protected function getUrl()
    {
        return $this->domain.'/'.$this->prefix.'/server';
    }
}