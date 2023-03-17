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
use LaravelRpc\Enum\VerifyType;
use LaravelRpc\Exceptions\ServerConfigDoesNotExistException;
use LaravelRpc\Exceptions\UnknownVerifyTypeException;

class Client
{
    protected ?Server $server = null;

    protected array $request_params = [];

    protected array $headers = [];


    public function __construct($server='')
    {
        if (!empty($server)){
            $this->server($server);
        }elseif(!empty($this->server)){
            $this->server($this->server);
        }
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
        $this->server = new Server($server_config);

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
     * @return Service
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/10 0010 11:49
     */
    public function service($service): Service
    {
        $this->request_params['service'] = Str::studly($service);

        return new Service($this);
    }

    public function method($method, $params=[]): \Illuminate\Http\Client\Response
    {
        $this->request_params['method'] = $method;
        $this->request_params['params'] = $params;

        return $this->request();
    }

    public function invoke($path,$params=[])
    {
        $parsePath = explode($path,'/');
        $method = array_pop($parsePath);
        return $this->service(implode('/',$parsePath))->method($method,$params);
    }

    protected function request(): \Illuminate\Http\Client\Response
    {
        $this->header('verify-type',$this->server->getVerifyType());

        if ($this->server->getVerifyType() === VerifyType::SIGN){
            return $this->verifySign();
        }elseif($this->server->getVerifyType() === VerifyType::FIXED){
            return $this->verifyFixed();
        }
        throw new UnknownVerifyTypeException();
    }

    protected function verifySign()
    {
        $this->request_params['nonce_str'] = Str::random();
        $this->request_params['sign'] = (new Params())->signature($this->request_params,$this->server->getSecret());

        return Http::withHeaders($this->headers)->post($this->server->getUrl(), $this->request_params);
    }

    protected function verifyFixed()
    {
        $encryptParams = (new Encrypter(base64_decode($this->server->getSecret()),$this->server->getVerifyCipher()))->encrypt($this->request_params);

        return Http::withHeaders($this->headers)->withBody($encryptParams,'text/plain')->post($this->server->getUrl());
    }

    public function __get(string $name)
    {
        return $this->service($name);
    }
}