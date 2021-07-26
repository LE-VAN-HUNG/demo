<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public    $timestamps = false;
    protected $table      = self::TABLE;
    const TABLE = 'role';

    const _ID        = 'id';
    const _ROLE      = 'role';
    const _STATUS    = 'status';
    const _ROLE_NAME = 'role_name';


    const STATUS_ACTIVE              = 1;

    protected $fillable = [
        self::_ID,
        self::_ROLE,
        self::_STATUS,
        self::_ROLE_NAME,
    ];
}
