<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Throttle extends Model
{
    protected $connection = 'iredapd';
    protected $table = 'throttle';
    protected $fillable = [
        'account', 
        'kind', 
        'priority', 
        'period', 
        'msg_size',
        'max_msgs',
        'max_quota'    
    ];
    
    public $timestamps = false;
}
