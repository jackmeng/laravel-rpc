<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class CreateRpcClientTable extends Migration
{
    protected $table_name = '';
    public function __construct()
    {
        $prefix = Config::get('laravelrpc.database.prefix');
        $this->table_name = $prefix.'client';
        $this->connection = Config::get('laravelrpc.database.connection');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable($this->table_name)){
            dump('已存在相同名称的表:'.$this->table_name);
            return ;
        }
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->string('appid',32)->default('');
            $table->string('secret',32)->default('');
            $table->string('comment')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table_name);
    }
}
