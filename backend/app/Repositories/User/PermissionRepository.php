<?php


namespace App\Repositories\User;


use App\Models\Permission;
use App\Models\PermissionRole;

class PermissionRepository extends \App\Repositories\EloquentRepository
{

    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return Permission::class;
    }

    public function getListPermissionByListRouter($listRouter)
    {
        return $this->_model
            ->select(Permission::_ROUTER)
            ->where(Permission::_ROUTER, $listRouter)
            ->get();
    }


    public function getPermissionByListRoleId($listRoleId, $status = null, $type = null)
    {
        $query = $this->_model
            ->select(Permission::_ID, Permission::_NAME, Permission::_ROUTER, Permission::_TYPE, Permission::_NOTE, Permission::_IS_PUBLIC,
                PermissionRole::_ROLE_ID, PermissionRole::_RULE
            )
            ->join(PermissionRole::TABLE, PermissionRole::TABLE . '.' . PermissionRole::_PERMISSION_ID, Permission::TABLE . '.' . Permission::_ID)
            ->whereIn(PermissionRole::TABLE . '.' . PermissionRole::_ROLE_ID, $listRoleId);

        if (isset($status)) {
            $query = $query->where(Permission::_STATUS, $status);
        }

        if (isset($type)) {
            $query = $query->where(Permission::_TYPE, $type);
        }

        return $query->get();
    }

    public function getPermissionByRouter($router)
    {
        return $this->_model
            ->select(Permission::_ID, Permission::_IS_PUBLIC)
            ->where(Permission::_ROUTER, $router)
            ->first();
    }

    public function getPermissionByRoleId($roleId, $type = null, $status = null, $listPermissionId = [])
    {
        $query = $this->_model
            ->select(Permission::_ID, Permission::_NAME, Permission::_ROUTER, Permission::_TYPE)
            ->join(PermissionRole::TABLE, PermissionRole::TABLE . '.' . PermissionRole::_PERMISSION_ID, Permission::TABLE . '.' . Permission::_ID)
            ->where(PermissionRole::TABLE . '.' . PermissionRole::_ROLE_ID, $roleId);
//
        if (isset($status)) {
            $query = $query->where(Permission::_STATUS, $status);
        }

        if (isset($type)) {
            $query = $query->where(Permission::_TYPE, $type);
        }

        if($listPermissionId) {
            $query = $query->whereIn(Permission::_ID, $listPermissionId);
        }

        return $query->get();
    }
}
