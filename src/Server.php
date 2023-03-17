<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/3/17 0017 14:19
 */

namespace LaravelRpc;

class Server
{
    protected string $domain = '';
    protected string $appid = '';
    protected string $secret = '';
    protected string $prefix = '';

    /**
     * sign 注册客户端，利用appid 和 secret 验证签名
     * fixed 利用aes加密，进行双向通信
     */
    protected string $verifyType = 'sign';
    protected string $verify_cipher = '';

    public function __construct(array $config)
    {
        $this->setDomain($config['domain']??'');
        $this->setAppid($config['appid']??'');
        $this->setSecret($config['secret']??'');
        $this->setPrefix($config['prefix']??'');
        $this->setVerifyType($config['type']??'');
        $this->setVerifyCipher($config['verify_cipher']??'');
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
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
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
     * @return string
     */
    public function getAppid(): string
    {
        return $this->appid;
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
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
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
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setVerifyType($type)
    {
        $this->verifyType = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getVerifyType(): string
    {
        return $this->verifyType;
    }

    /**
     * @param $key
     * @return $this
     * @author jackmeng <jiekemeng@gmail.com>
     * @date 2023/2/28 0028 17:54
     */
    public function setVerifyCipher($cipher)
    {
        $this->verify_cipher = $cipher;

        return $this;
    }

    /**
     * @return string
     */
    public function getVerifyCipher(): string
    {
        return $this->verify_cipher;
    }

    public function getUrl(): string
    {
        return $this->domain.'/'.$this->prefix.'/server/'.$this->verifyType;
    }
}