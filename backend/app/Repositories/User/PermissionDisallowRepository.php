<?php


namespace App\Repositories\User;


use App\Models\PermissionDisallow;

class PermissionDisallowRepository extends \App\Repositories\EloquentRepository
{

    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return PermissionDisallow::class;
    }

    public function getPermissionDisallowByUserId($userId)
    {
        return $this->_model
            ->select(PermissionDisallow::_USER_ID, PermissionDisallow::_PERMISSION_ID)
            ->where(PermissionDisallow::_USER_ID, $userId)
            ->get();
    }

    public function getDataByListUserIdAndPermissionId($listUserId, $permissionId)
    {
        return $this->_model
            ->select(PermissionDisallow::_USER_ID, PermissionDisallow::_PERMISSION_ID)
            ->whereIn(PermissionDisallow::_USER_ID, $listUserId)
            ->where(PermissionDisallow::_PERMISSION_ID, $permissionId)
            ->get();
    }

    public function getDataByListUserIdAndListPermissionId($listUserId, $listPermissionId)
    {
        return $this->_model
            ->select(PermissionDisallow::_USER_ID, PermissionDisallow::_PERMISSION_ID)
            ->whereIn(PermissionDisallow::_USER_ID, $listUserId)
            ->whereIn(PermissionDisallow::_PERMISSION_ID, $listPermissionId)
            ->get();

    }
}
