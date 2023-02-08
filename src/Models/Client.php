<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 14:15
 */

namespace LaravelRpc\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('laravelrpc.database.connnection'));
        $this->setTable(config('laravelrpc.databse.prefix').'client');
        parent::__construct($attributes);
    }
}