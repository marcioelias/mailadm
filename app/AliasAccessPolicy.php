<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AliasAccessPolicy extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'description',
        'alias_access_policy', 
    ];
}
