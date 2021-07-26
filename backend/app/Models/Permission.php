<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $timestamps = false;
    protected $table = self::TABLE;
    const TABLE = 'permission';

    const _ID = 'id';
    const _NAME = 'name';
    const _ROUTER = 'router';
    const _TYPE = 'type';
    const _STATUS = 'status';
    const _NOTE = 'note';
    const _IS_PUBLIC = 'is_public';
    const _TIME_CREATED = 'time_created';
    const _TIME_UPDATED = 'time_updated';

    const PRIVATE = 0;
    const PUBLIC = 1;

    const TYPE_ROUTER = 0;
    const TYPE_API = 1;


    const LIST_TYPE_PERMISSION = [
        ['key' => self::TYPE_ROUTER,'name' => 'Router'],
        ['key'=>self::TYPE_API,'name'=>'Api']
    ];

    const STATUS_ACTIVE = 1;

      protected $fillable = [
          self::_ID,
          self::_NAME,
          self::_ROUTER,
          self::_TYPE,
          self::_STATUS,
          self::_NOTE,
          self::_IS_PUBLIC,
          self::_TIME_CREATED,
          self::_TIME_UPDATED,
      ];
}
