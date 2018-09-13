<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'user_id', 'role_id', 'user_type'
    ]; 
}
