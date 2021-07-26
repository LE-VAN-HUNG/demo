<?php


namespace App\Repositories\User;


use App\Models\UserRole;
use App\Models\Users;

class UsersRepository extends \App\Repositories\EloquentRepository
{

    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Users::class;
    }

    public function getUserByEmailAndStatus($email,$status)
    {
        return $this->_model
            ->select(
                Users::_ID,
                Users::_EMAIL,
                Users::_PASSWORD,
                Users::_NAME
            )
            ->where(Users::_EMAIL, $email)
            ->where(Users::_STATUS, $status)
            ->first();
    }

    public function getUserByListEmail($listEmail)
    {
        return $this->_model
            ->select(Users::_ID)
            ->whereIn(Users::_EMAIL, $listEmail)
            ->get();
    }

    public function getUserByUserId($userId)
    {

        return $this->_model
            ->select(Users::_ID,Users::_NAME,Users::_STATUS)
            ->where(Users::_ID, $userId)
            ->first();

    }

    public function getListUserByParams($params) {
        $query = $this->_model
            ->select(
                Users::TABLE . '.' . Users::_ID,
                Users::TABLE . '.' . Users::_NAME,
                Users::TABLE . '.' . Users::_EMAIL,
                Users::TABLE . '.' . Users::_LAST_LOGIN_TIME,
                Users::TABLE . '.' . Users::_STATUS,
                Users::TABLE . '.' . Users::_CREATED
            )
            ->with('user_role');


        $query = $this->queryListUserByParams($query, $params);

        if ($params['limit'] != -1) {
            $query->limit($params['limit'])
                ->offset($params['offset']);
        }

        return $query->orderBy(Users::_ID)
            ->get();

    }

    private function queryListUserByParams($query, $params)
    {
        if ($params['id']) {
            $query->where(Users::TABLE . '.' . Users::_ID,  $params['id']);
        }
        if ($params['name']) {
            $query->where(Users::TABLE . '.' . Users::_NAME, 'like', trim($params['name']) . '%');
        }
        if ($params['email']) {
            $query->where(Users::TABLE . '.' . Users::_EMAIL, 'like', trim($params['email']) . '%');
        }
        if ($params['role'] && count($params['role']) > 0) {
            $query->leftJoin(UserRole::TABLE, Users::TABLE . '.' . Users::_ID, UserRole::TABLE . '.' . UserRole::_USER_ID);
            $query->whereIn(UserRole::TABLE . '.' . UserRole::_ROLE_ID, $params['role']);
        }
        if (isset($params['status'])) {
            $query->where(Users::TABLE . '.' . Users::_STATUS, $params['status']);
        }

        return $query;
    }


    public function countUserByParams($params)
    {
        $query = $this->_model
            ->select(Users::_ID);

        $query = $this->queryListUserByParams($query, $params);

        return $query->count();
    }

    public function getUserByListUserId($listUserId)
    {
        return $this->_model
            ->select(Users::_ID, Users::_NAME, Users::_EMAIL)
            ->whereIn(Users::_ID, $listUserId)
            ->where(Users::_STATUS, Users::STATUS_ACTIVE)
            ->get();
    }

    public function removeUserByUserId($id)
    {
        return $this->_model
            ->where(Users::_ID, $id)
            ->delete();

    }
}
