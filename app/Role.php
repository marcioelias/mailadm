<?php

namespace App;

use App\User;
use App\RoleUser;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    protected $fillable = [
        'id', 'name', 'display_name', 'description'
    ];

    /* public function users() {
        return $this->belongsToMany(User::class)->withPivot('user_type');
    } */
}
