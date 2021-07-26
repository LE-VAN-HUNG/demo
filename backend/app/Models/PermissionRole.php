<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    public    $timestamps = false;
    protected $table      = self::TABLE;
    const TABLE = 'permission_role';

    const _ROLE_ID       = 'role_id';
    const _PERMISSION_ID = 'permission_id';
    const _RULE          = 'rule';
    const _TIME_CREATED  = 'time_created';
    const _TIME_UPDATED  = 'time_updated';

    protected $fillable = [
        self::_ROLE_ID,
        self::_PERMISSION_ID,
        self::_RULE,
        self::_TIME_CREATED,
        self::_TIME_UPDATED,
    ];
}
