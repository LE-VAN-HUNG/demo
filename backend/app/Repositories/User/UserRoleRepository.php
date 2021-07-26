<?php


namespace App\Repositories\User;


use App\Models\Role;
use App\Models\UserRole;
use App\Repositories\EloquentRepository;

class UserRoleRepository extends EloquentRepository
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return UserRole::class;
    }

    public function getRoleByUserId($userId)
    {
        return $this->_model
            ->select(UserRole::_ROLE_ID)
            ->where(UserRole::_USER_ID, $userId)
            ->get();
    }
    public function getUserIdByRoleId($roleId, $limit = 10, $offset = 0)
    {
        $query = $this->_model
            ->select(UserRole::_USER_ID, UserRole::_ROLE_ID)
            ->where(UserRole::_ROLE_ID, $roleId);

        if ($limit) {
            $query = $query->limit($limit)->offset($offset);
        }

        return $query->get();
    }

    public function countUserIdByRoleId($roleId)
    {
        return $this->_model
            ->select(UserRole::_USER_ID)
            ->where(UserRole::_ROLE_ID, $roleId)
            ->count();
    }

    public function getRoleByUserIdAndRoleId($userId, $roleId)
    {
        return $this->_model
            ->select(UserRole::_ROLE_ID)
            ->where(UserRole::_USER_ID, $userId)
            ->where(UserRole::_ROLE_ID, $roleId)
            ->first();
    }

    public function getInfoRoleByUserId($userId)
    {
        return $this->_model
            ->select(UserRole::_USER_ID, Role::_ID, Role::_ROLE_NAME, Role::_ROLE)
            ->join(Role::TABLE, UserRole::TABLE . '.' . UserRole::_ROLE_ID, '=', Role::TABLE . '.' . Role::_ID)
            ->where(UserRole::_USER_ID, $userId)
            ->get();
    }
    public function removeUserRoleByUserIdAndRoleId($userId, $roleId) {
        return $this->_model
            ->where(UserRole::_USER_ID, $userId)
            ->where(UserRole::_ROLE_ID, $roleId)
            ->delete();
    }
}
