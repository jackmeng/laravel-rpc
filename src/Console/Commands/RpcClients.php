<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 10:34
 */

namespace LaravelRpc\Console\Commands;

use Illuminate\Console\Command;
use LaravelRpc\Models\Client;

class RpcClients extends Command
{
    protected $signature = "rpc_clients";
    protected $description = "显示客户端列表（show client list）";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->table([
            'Appid',
            'Secret',
            'Comment',
        ],Client::all(['appid','secret','comment'])->toArray());

        return 0;
    }

}