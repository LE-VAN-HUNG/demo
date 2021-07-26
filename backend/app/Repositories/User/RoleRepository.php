<?php


namespace App\Repositories\User;


use App\Models\Role;
use App\Models\UserRole;
use App\Repositories\EloquentRepository;

class RoleRepository extends EloquentRepository
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Role::class;
    }
    public function getListRoleNameById($listRoleId)
    {
        return $this->_model
            ->select(Role::_ROLE_NAME, Role::_ID)
            ->whereIn(Role::_ID, $listRoleId)
            ->where(Role::_STATUS, Role::STATUS_ACTIVE)
            ->get();
    }

    public function getRoleByUserId($userId, $status = null)
    {
        $query = $this->_model
            ->select(Role::_ID, Role::_ROLE, Role::_ROLE_NAME)
            ->join(UserRole::TABLE, UserRole::TABLE . '.' . UserRole::_ROLE_ID, Role::TABLE . '.' . Role::_ID)
            ->where(UserRole::TABLE . '.' . UserRole::_USER_ID, $userId);

        if (isset($status)) {
            $query = $query->where(Role::_STATUS, $status);
        }

        return $query->get();
    }

    public function getRoleByListId($listId) {
        return $this->_model
            ->select(Role::_ID,
                Role::_ROLE,
                Role::_ROLE_NAME
            )
            ->whereIn(Role::_ID, $listId)
            ->get();
    }

    public function getRoleByRoleName($roleName)
    {
        return $this->_model
            ->select(Role::_ID, Role::_ROLE, Role::_ROLE_NAME)
            ->where(Role::_ROLE_NAME, $roleName)
            ->first();
    }

    public function getRoleByParams($params)
    {
        $query = $this->_model
            ->select(Role::_ID, Role::_ROLE, Role::_ROLE_NAME, Role::_STATUS);

        $query = $this->queryRoleByParams($query, $params);

        return $query
            ->orderBy(Role::_ID)
            ->limit($params['limit'])
            ->offset($params['offset'])
            ->get();
    }


    private function queryRoleByParams($query, $params)
    {
        if (isset($params['role']) && $params['role']) {
            $query = $query->where(Role::_ROLE, 'like', '%' . $params['role'] . '%');
        }
        if (isset($params['role_name']) && $params['role_name']) {
            $query = $query->where(Role::_ROLE_NAME, 'like', '%' . $params['role_name'] . '%');
        }
        if (isset($params['status'])) {
            $query = $query->where(Role::_STATUS, $params['status']);
        }
        return $query;
    }

    public function countRoleByParam($params)
    {
        $query = $this->_model
            ->select(Role::_ID);
        $query = $this->queryRoleByParams($query, $params);
        return $query->count();
    }
}
