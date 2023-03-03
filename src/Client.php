<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 15:06
 */

namespace LaravelRpc;

use Illuminate\Encryption\Encrypter;
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

    /**
     * sign 注册客户端，利用appid 和 secret 验证签名
     * fixed 利用aes加密，进行双向通信
     */
    protected string $verifyType = 'sign';
    protected string $verifyKey = '';

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
        $this->setVerifyKey($server_config['verify_key']??'');

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
     * @return $this
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/28 0028 17:54
     */
    public function setVerifyKey($key)
    {
        $this->verifyKey = $key;

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
        $this->header('verify-type',$this->verifyType);

        if ($this->verifyType === 'sign'){
            return $this->verifySign();
        }elseif($this->verifyType === 'fixed'){
            return $this->verifyFixed();
        }

    }

    protected function verifySign()
    {
        $this->request_params['nonce_str'] = Str::random();
        $this->request_params['sign'] = (new Params())->signature($this->request_params,$this->secret);
        return Http::withHeaders($this->headers)->post($this->getUrl(), $this->request_params);
    }

    protected function verifyFixed()
    {
        $encryptParams = (new Encrypter(base64_decode(config('laravel_rpc.verify_key')),config('laravel_rpc.verify_cipher')))->encrypt($this->request_params);
        return Http::withHeaders($this->headers)->withBody($encryptParams,'text/plain')->post($this->getUrl());
    }

    protected function getUrl(): string
    {
        return $this->domain.'/'.$this->prefix.'/server/'.$this->verifyType;
    }
}