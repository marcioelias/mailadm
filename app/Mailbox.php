<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mailbox extends Model
{
    protected $table = 'mailbox';
    protected $primaryKey = 'username';
    protected $keyType = 'string';
    protected $fillable = [
        'username', 
        'password', 
        'name', 
        'quota', 
        'domain',
        'language',
        'storagebasedirectory',
        'storagenode',
        'maildir',
        'quota',
        'transport',
        'department',
        'rank',
        'employeeid',
        'isadmin',
        'isglobaladmin',
        'enablesmtp',
        'enablesmtpsecured',
        'enablepop3',
        'enablepop3secured',
        'enableimap',
        'enableimapsecured',
        'enabledeliver',
        'enablelda',
        'enablemanagesieve',
        'enablemanagesievesecured',
        'enablesieve',
        'enablesievesecured',
        'enableinternal',
        'enabledoveadm',
        'enablelib-storage',
        'enableindexer-worker',
        'enablelmtp',
        'enabledsync',
        'enablesogo',
        'allownets',
        'active',
        'local_part'    
    ];
    
    public $incremets = false;
    public $timestamps = false;

}
