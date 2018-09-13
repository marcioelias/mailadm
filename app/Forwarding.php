<?php

namespace App;

use App\Alias;
use Illuminate\Database\Eloquent\Model;

class Forwarding extends Model
{
    protected $table = 'forwardings';
    protected $fillable = [
        'address', 
        'forwarding', 
        'domain', 
        'dest_domain',
        'is_list',
        'is_forwarding',
        'is_alias',
        'active',
        'is_maillist'
    ];
    
    public $timestamps = false;

    public function alias() {
        return $this->belongsTo(Alias::class, 'address');
    }
}
