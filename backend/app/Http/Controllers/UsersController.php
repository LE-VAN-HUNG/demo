<?php

namespace App\Http\Controllers;

use App\Listeners\MD5Hasher;
use App\Models\Permission;
use App\Models\PermissionDisallow;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Users;
use App\Repositories\User\PermissionDisallowRepository;
use App\Repositories\User\PermissionRepository;
use App\Repositories\User\RoleRepository;
use App\Repositories\User\UserRoleRepository;
use App\Repositories\User\UsersRepository;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $usersRepo;
//    private $roleRepo;
//    private $permissionRepo;
    private $permissionDisallowRepo;
    private $request;
    private $userRoleRepo;


    public function __construct(
        UsersRepository $usersRepo,
        RoleRepository $roleRepo,
//        PermissionRepository $permissionRepo,
        PermissionDisallowRepository $permissionDisallowRepo,
        Request $request,
        UserRoleRepository $userRoleRepo
    ) {
        $this->usersRepo              = $usersRepo;
        $this->roleRepo               = $roleRepo;
//        $this->permissionRepo         = $permissionRepo;
        $this->permissionDisallowRepo = $permissionDisallowRepo;
        $this->request                = $request;
        $this->userRoleRepo = $userRoleRepo;
    }


    public function getListUser()
    {
        $params        = $this->getParamsListUser();
        $data['data']  = $this->usersRepo->getListUserByParams($params);
        $data['total'] = $this->usersRepo->countUserByParams($params);

        $this->status  = 'success';
        $this->message = 'Lấy danh sách người dùng thành công';
        next:
        return $this->responseData($data);
    }
    private function getParamsListUser()
    {
        $params['id']          = $this->request->input('id');
        $params['name']    = $this->request->input('name');
        $params['email']       = $this->request->input('email');
        $params['role']        = $this->request->input('role');
        $params['countryCode'] = $this->request->input('country_code');
        $params['status']      = $this->request->input('status');
        $params['product']     = $this->request->input('product');
        $params['limit']       = $this->request->input('limit', 10);
        $params['page']        = $this->request->input('page', 1);
        if ($params['role'] && !is_array($params['role'])) {
            $params['role'] = explode(',', $params['role']);;
        }
        $params['offset'] = ($params['page'] - 1) * $params['limit'];
        return $params;
    }
    public function createUser()
    {
        $rules = [
            'name' => 'required',
            'email'     => 'required|email',
            'password' => 'required'
        ];
        $this->validateBase($this->request, $rules);

        $id       = $this->request->input('id');
        $name = $this->request->input('name');
        $email    = $this->request->input('email');
        $password = $this->request->input('password');
        $phone    = $this->request->input('phone');

        $user = $this->usersRepo->getUserByEmailAndStatus($email, Users::STATUS_ACTIVE);
        if ($user) {
            if ((isset($id) && $user[Users::_ID] != $id) || !isset($id)) {
                $this->message = 'Email đã tồn tại.';
                goto next;
            }
        }

        $data = [
            Users::_NAME => $name,
            Users::_EMAIL     => $email,
        ];


        if (isset($phone)) {
            $user[Users::_PHONE] = $phone;
        }
        if ($id) {
            $this->usersRepo->update($id, $data);
            $message = "Cập nhật người dùng";
        } else {
            if (!isset($password)) {
                $this->message = 'Thiếu mật khẩu .';
                goto next;
            }

            $MD5Hasher              = new MD5Hasher();
            $data[Users::_PASSWORD] = $MD5Hasher->make($password);
            $data[Users::_STATUS]   = Users::STATUS_ACTIVE;
            $data[Users::_CREATED]  = time();
            $id                     = $this->usersRepo->insertGetId($data);

            $message = "Thêm mới người dùng ";
        }
        if (!$id) {
            $this->message = $message . 'thất bại.';
            goto next;
        }

        $this->message = $message . 'thành công.';
        $this->status  = 'success';

        next:
        return $this->responseData();
    }
    public function getUserByRole()
    {
        $rules = [
            'role_id' => 'required',
        ];
        $this->validateBase($this->request, $rules);
        $roleId       = $this->request->input('role_id');
        $limit        = $this->request->input('limit', 10);
        $page         = $this->request->input('page', 1);
        $permissionId = $this->request->input('permission_id');
        $offset       = ($page - 1) * $limit;

        $listUserRole = $this->userRoleRepo->getUserIdByRoleId($roleId, $limit, $offset)->keyBy(UserRole::_USER_ID)->toArray();
        $total      = $this->userRoleRepo->countUserIdByRoleId($roleId);
        $listUserId = array_keys($listUserRole);
        $listUser   = $this->usersRepo->getUserByListUserId($listUserId)->keyBy(Users::_ID)->toArray();
        if ($permissionId) {
            $userDisallowPermission = $this->permissionDisallowRepo->getDataByListUserIdAndPermissionId($listUserId, $permissionId)->keyBy(PermissionDisallow::_USER_ID)->toArray();
        }
        foreach ($listUser as $userId => &$item) {
            if (isset($userDisallowPermission[$userId])) {
                $item['selected'] = false;
            } else {
                $item['selected'] = true;
            }
        }
        $data          = [
            'listUser' => $listUser,
            'total'    => $total,
        ];
        $this->message = 'Lấy danh sách user thành công';
        $this->status  = 'success';
        return $this->responseData($data);
    }
    public function addRoleForUser()
    {
        $rules = [
            'user_id' => 'required',
            'role_id' => 'required',
        ];
        $this->validateBase($this->request, $rules);
        $userId   = $this->request->input('user_id');
        $roleId   = $this->request->input('role_id');
        $infoUser = $this->usersRepo->getUserByUserId($userId);
        if (!$infoUser) {
            $this->message = 'Không tìm thấy tài khoản.';
            goto next;
        }

        $checkRoleExist = $this->userRoleRepo->getRoleByUserIdAndRoleId($userId, $roleId);
        if ($checkRoleExist) {
            $this->message = 'Tài khoản đã tồn tại quyền này.';
            goto next;
        }

        $dataInsert = [
            UserRole::_ROLE_ID => $roleId,
            UserRole::_USER_ID => $userId,
        ];

        $check = $this->userRoleRepo->insert($dataInsert);
        if (!$check) {
            $this->message = 'Thêm quyền cho tài khoản thất bại.';
            goto next;
        }

        $this->message = 'Thêm quyền cho tài khoản thành công';
        $this->status  = 'success';
        next:
        return $this->responseData();
    }
    public function getInfoUserByUserId()
    {
        $rules = [
            'user_id' => 'required',
        ];
        $this->validateBase($this->request, $rules);
        $userId = $this->request->input('user_id');

        $infoUser = $this->usersRepo->getUserByUserId($userId);
        $data     = [];
        if (!$infoUser) {
            $this->message = 'Không tìm thấy user.';
            goto next;
        }
        $listRoleUser     = $this->userRoleRepo->getInfoRoleByUserId($userId)->toArray();

        $listRole         = [];
        $listRoleId       = [];
        foreach ($listRoleUser as $role) {

            $listRoleId[]    = $role[Role::_ID];
            $listRole[] = $role;
        }

//        $listRoleParent = $this->roleRepo->getRoleByListId($listRoleParentId)->keyBy(Role::_ID)->toArray();
        $data           = [
            'infoUser'       => $infoUser,
//            'listRoleParent' => $listRoleParent,
            'listRole'       => $listRole,
        ];

        $this->message = 'Lấy thông tin user thành công';
        $this->status  = 'success';
        next:
        return $this->responseData($data);
    }
    public function removeRoleUser()
    {
        $rules = [
            'user_id' => 'required',
            'role_id' => 'required',
        ];
        $this->validateBase($this->request, $rules);
        $userId   = $this->request->input('user_id');
        $roleId   = $this->request->input('role_id');
        $infoUser = $this->usersRepo->getUserByUserId($userId);
        if (!$infoUser) {
            $this->message = 'Không tìm thấy tài khoản.';
            goto next;
        }

        $check = $this->userRoleRepo->removeUserRoleByUserIdAndRoleId($userId, $roleId);
        if (!$check) {
            $this->message = 'Xóa quyền cho tài khoản thất bại.';
            goto next;
        }
        $this->message = 'Xóa quyền cho tài khoản thành công';
        $this->status  = 'success';
        next:
        return $this->responseData();
    }
    public function removeUserById()
    {
        $rules = [
            'id' => 'required',
        ];
        $this->validateBase($this->request, $rules);

        $id         = $this->request->input('id');
        $infoUser = $this->usersRepo->getUserByUserId($id);
        if (!$infoUser) {
            $this->message = 'Không tìm thấy tài khoản.';
            goto next;
        }

        $check = $this->usersRepo->removeUserByUserId($id);
        if (!$check) {
            $this->message = 'Xóa tài khoản thất bại.';
            goto next;
        }
        $this->message = 'Xóa tài khoản thành công';
        $this->status  = 'success';
        next:
        return $this->responseData();
    }



}
