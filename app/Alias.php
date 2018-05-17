<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    protected $table = 'alias';
    protected $primaryKey = 'address';
    protected $keyType = 'string';
    protected $fillable = ['address', 'goto', 'domain', 'active'];
    
    public $incremets = false;
    public $timestamps = false;


}
