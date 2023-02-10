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
    protected array $headers = [];
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
        $this->service(class_basename(static::class));
    }

    /**
     * @param $server
     * @return $this
     * @throws ServerConfigDoesNotExistException
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/10 0010 11:48
     */
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

    /**
     * @param $domain
     * @return $this
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/10 0010 11:49
     */
    public function setDomain($domain)
    {
        $this->domain = rtrim($domain,'/');
        return $this;
    }

    /**
     * @param $appid
     * @return $this
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/10 0010 11:49
     */
    public function setAppid($appid)
    {
        $this->request_params['appid'] = $appid;
        $this->appid = $appid;

        return $this;
    }

    /**
     * @param $secret
     * @return $this
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/10 0010 11:49
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @param $prefix
     * @return $this
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/10 0010 11:49
     */
    public function setPrefix($prefix)
    {
        $this->prefix = trim($prefix,'/');

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/10 0010 11:49
     */
    public function header($key,$value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * @param $service
     * @return $this
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/10 0010 11:49
     */
    public function service($service)
    {
        $this->request_params['service'] = $service;
        return $this;
    }

    public function method($method, $params=[]): \Illuminate\Http\Client\Response
    {
        $this->request_params['method'] = $method;
        $this->request_params['params'] = $params;

        return $this->request();
    }

    protected function request(): \Illuminate\Http\Client\Response
    {
        $this->request_params['nonce_str'] = Str::random();
        $this->request_params['sign'] = (new Params())->signature($this->request_params,$this->secret);

        return Http::withHeaders($this->headers)->post($this->getUrl(), $this->request_params);
    }

    protected function getUrl(): string
    {
        return $this->domain.'/'.$this->prefix.'/server';
    }
}