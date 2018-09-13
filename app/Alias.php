<?php

namespace App;

use App\Forwarding;
use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    protected $table = 'alias';
    protected $primaryKey = 'address';
    protected $keyType = 'string';
    protected $fillable = [
        'address', 
        'name', 
        'accesspolicy', 
        'domain',
        'active'
    ];
    
    public $incremets = false;
    public $timestamps = false;

    public function forwardings() {
        return $this->hasMany(Forwarding::class, 'address');
    }
}
