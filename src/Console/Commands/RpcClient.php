<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 10:34
 */

namespace LaravelRpc\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use LaravelRpc\Models\Client;

class RpcClient extends Command
{
    protected $signature = "rpc_client {--appid=}";
    protected $description = "创建或编辑客户端（create or edit client）";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $appid = $this->option('appid');
        if (empty($appid)){
            $this->create();
        }else{
            $this->edit($appid);
        }
        return 0;
    }

    protected function create()
    {
        $this->info('创建客户端（create client）');
        if (!$this->confirm('确定创建一个客户端吗？(Are you sure to create a client?)', true)) {
            return ;
        }
        $comment = $this->ask('备注(comment)：');
        $num = 0;
        do{
            $appid = Str::random(16);
            $secret = Str::random(32);

            $client = Client::firstOrCreate([
                'appid'=>$appid,'secret'=>$secret
            ],['comment'=>$comment]);
            if ($client && $client->wasRecentlyCreated){
                break;
            }
            if ($num >= 10){
                $this->warn('经过10次生成仍未成功！！！');
                $this->warn('After 10 builds still unsuccessfu！！！');
                return ;
            }
            $num++;
        }while(true);

        $this->info('Create successfully');
        $this->line("===============================================");
        $this->line('appid:'.$client->appid);
        $this->line('secret:'.$client->secret);
        $this->line('comment:'.$client->comment);
        $this->line("===============================================");

    }

    protected function edit($appid)
    {
        $client = Client::where('appid',$appid)->first();
        if (!$client){
            $this->error('客户端不存在（client does not exist）');
            return;
        }

        $this->line("===============================================");
        $this->line('appid:'.$client->appid);
        $this->line('secret:'.$client->secret);
        $this->line('comment:'.$client->comment);
        $this->line("===============================================");
        if ($this->confirm('是否重新生成密钥？(Whether to regenerate the secret)', false)) {
            $client->secret = Str::random(32);
            $client->save();
            $this->info('新密钥（new secret）：'.$client->secret);
        }
        if ($this->confirm('是否重新输入备注？(Whether to re-enter the comment？)', false)) {
            $client->comment = $this->ask('备注(comment)：');
            $client->save();
            $this->info('新备注（new comment）：'.$client->comment);
        }
    }


}