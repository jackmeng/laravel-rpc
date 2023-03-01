<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 10:34
 */

namespace LaravelRpc\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use LaravelRpc\Models\Client;

class RpcGenerateKey extends Command
{
    protected $signature = "rpc_generate_key";
    protected $description = "生成通信密钥（generate aes key）";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $this->info(base64_encode(Encrypter::generateKey(config('laravel_rpc.verify_cipher'))));

        return 0;
    }

}