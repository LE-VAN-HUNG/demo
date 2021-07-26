<?php


namespace App\Repositories\User;


use App\Models\PermissionRole;

class PermissionRoleRepository extends \App\Repositories\EloquentRepository
{

    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return PermissionRole::class;
    }

    public function getByListPermissionIdAndRoleId($permissionId, $roleId) {
        return $this->_model
            ->select(PermissionRole::_PERMISSION_ID, PermissionRole::_ROLE_ID, PermissionRole::_RULE)
            ->whereIn(PermissionRole::_PERMISSION_ID, $permissionId)
            ->where(PermissionRole::_ROLE_ID, $roleId)
            ->get();
    }

    public function updateByPermissionIdAndRoleId($permissionId, $roleId, $dataUpdate) {
        return $this->_model
            ->where(PermissionRole::_PERMISSION_ID, $permissionId)
            ->where(PermissionRole::_ROLE_ID, $roleId)
            ->update($dataUpdate);
    }
}
