<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionDisallow extends Model
{
    public    $timestamps = false;
    protected $table      = self::TABLE;
    const TABLE = 'permission_disallow';

    const _USER_ID       = 'user_id';
    const _PERMISSION_ID = 'permission_id';

    protected $fillable = [
        self::_USER_ID,
        self::_PERMISSION_ID
    ];
}
