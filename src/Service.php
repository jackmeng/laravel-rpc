<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/10 0010 9:46
 */

namespace LaravelRpc;

class Service
{
    protected ?Client $client = null;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __call(string $name, array $arguments)
    {
        return $this->client->method($name,$arguments);
    }
}