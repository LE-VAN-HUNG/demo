<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    public    $timestamps = false;
    protected $table      = self::TABLE;
    const TABLE = 'users';

    const _ID                 = 'id';
    const _NAME          = 'name';
    const _EMAIL              = 'email';
    const _PHONE              = 'phone';
    const _PASSWORD           = 'password';
    const _STATUS             = 'status';
    const _LAST_LOGIN_TIME    = 'last_login_time';
    const _AVATAR             = 'avatar';
    const _TIME_DURING_SYSTEM = 'time_during_system';
    const _CREATED            = 'created';


    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;

    protected $fillable = [
        self::_ID,
        self::_NAME,
        self::_EMAIL,
        self::_PHONE,
        self::_PASSWORD,
        self::_STATUS,
        self::_LAST_LOGIN_TIME,
        self::_AVATAR,
        self::_TIME_DURING_SYSTEM,
        self::_CREATED
    ];

    public function user_role()
    {
        return $this->hasMany(UserRole::class, UserRole::_USER_ID, self::_ID);
    }

}
