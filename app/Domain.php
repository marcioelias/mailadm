<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = 'domain';
    protected $primaryKey = 'domain';
    protected $keyType = 'string';
    protected $fillable = ['domain', 'description'];
    
    public $incremets = false;
    public $timestamps = false;
}
