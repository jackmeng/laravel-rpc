<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class CreateRpcPermissionsTable extends Migration
{
    protected string $table_name = '';

    public function __construct()
    {
        $prefix = Config::get('laravelrpc.database.prefix');
        $this->table_name = $prefix.'permissions';
        $this->connection = Config::get('laravelrpc.database.connection');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->string('appid',32)->default(0);
            $table->string('service',100)->default('');
            $table->string('method',100)->default('');
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
        Schema::dropIfExists('rpc_permissions');
    }
}
