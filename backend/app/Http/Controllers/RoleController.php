<?php

namespace App\Http\Controllers;

use App\Models\PermissionDisallow;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Repositories\User\PermissionDisallowRepository;
use App\Repositories\User\PermissionRoleRepository;
use App\Repositories\User\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    private $roleRepo;
    private $permissionRoleRepo;
    private $permissionDisallowRepo;
    private $request;


    public function __construct(
        RoleRepository $roleRepo,
        PermissionRoleRepository $permissionRoleRepo,
        PermissionDisallowRepository $permissionDisallowRepo,
        Request $request
    ) {
        $this->roleRepo               = $roleRepo;
        $this->permissionRoleRepo     = $permissionRoleRepo;
        $this->permissionDisallowRepo = $permissionDisallowRepo;
        $this->request                = $request;
    }

    public function createRole()
    {
        $rules = [
            'role'      => 'required',
            'role_name' => 'required',
        ];
        $this->validateBase($this->request, $rules);

        $role      = $this->request->input('role');
        $roleName  = $this->request->input('role_name');
        $id        = $this->request->input('id');

        $infoRole = $this->roleRepo->getRoleByRoleName($roleName);
        if ($infoRole) {
            if ($id && $infoRole[Role::_ID] != $id || !$id) {
                $this->message = 'Group permission name already exists.';
                goto next;
            }
        }
        $data = [
            Role::_ROLE      => $role,
            Role::_ROLE_NAME => $roleName,
        ];

        if (!$id) {
            $data[Role::_STATUS] = Role::STATUS_ACTIVE;

            $message = 'Create Group permission ';
            $check   = $this->roleRepo->insertGetId($data);
            $id      = $check;
        } else {
            $message = 'Update Group permission ';
            $check   = $this->roleRepo->update($id, $data);
        }

        if (!$check) {
            $this->message = $message . 'failed';
            goto next;
        }
        $this->message = $message . 'successfully';
        $this->status  = 'success';

        next:
        return $this->responseData();
    }

    public function getListRole()
    {
        $params = $this->getDataInputGetListRole();

        $listRole       = $this->roleRepo->getRoleByParams($params)->toArray();
        $data['total']          = $this->roleRepo->countRoleByParam($params);
        $data['listRole']       = $listRole;

        $this->message = 'Get the list of permission groups successfully';
        $this->status  = 'success';
        next:
        return $this->responseData($data);
    }
    public function addPermissionIntoRole()
    {
        $rules = [
            'role_id'       => 'required',
            'permission_id' => 'required',
        ];
        $this->validateBase($this->request, $rules);

        $roleId           = $this->request->input('role_id');
        $listPermissionId = $this->request->input('permission_id');
        $userDisallow     = $this->request->input('user_disallow');
        $userAllow        = $this->request->input('user_allow');
        $rule             = $this->request->input('rule');
        $dataInsertDisallow = [];
        if (!is_array($userDisallow)) {
            $userDisallow = explode(',', $userDisallow);;
        }

        if (!is_array($userAllow)) {
            $userAllow = explode(',', $userAllow);;
        }

        if (!is_array($listPermissionId)) {
            $listPermissionId = explode(',', $listPermissionId);;
        }

        $listUserDisallow         = $this->permissionDisallowRepo->getDataByListUserIdAndListPermissionId($userDisallow, $listPermissionId)->groupBy(PermissionDisallow::_PERMISSION_ID)->toArray();
        $checkExists              = $this->permissionRoleRepo->getByListPermissionIdAndRoleId($listPermissionId, $roleId)->keyBy(PermissionRole::_PERMISSION_ID)->toArray();
        $dataInsertPermissionRole = [];

        foreach ($listPermissionId as $permissionId) {
            if (isset($listUserDisallow[$permissionId])) {
                $listUser = array_column($listUserDisallow[$permissionId], PermissionDisallow::_USER_ID);
                foreach ($userDisallow as $userId) {
                    if (!in_array($userId, $listUser)) {
                        $dataInsertDisallow[$permissionId][] = [
                            PermissionDisallow::_USER_ID       => $userId,
                            PermissionDisallow::_PERMISSION_ID => $permissionId,
                        ];
                    }
                }
            }

            $dataInsertPermissionRole[$permissionId] = [
                PermissionRole::_PERMISSION_ID => $permissionId,
                PermissionRole::_ROLE_ID       => $roleId,
                PermissionRole::_RULE          => $rule ? json_encode($rule) : '',
                PermissionRole::_TIME_UPDATED  => time(),
            ];

        }
        $exception = DB::transaction(
            function () use ($checkExists, $dataInsertPermissionRole, $dataInsertDisallow, $listPermissionId, $roleId,  $userAllow) {
                foreach ($listPermissionId as $permissionId) {
                    if (isset($checkExists[$permissionId])) {
                        $this->permissionRoleRepo->updateByPermissionIdAndRoleId($permissionId, $roleId, $dataInsertPermissionRole[$permissionId]);
                    } else {
                        $dataInsertPermissionRole[$permissionId][PermissionRole::_TIME_CREATED] = time();
                        $this->permissionRoleRepo->insert($dataInsertPermissionRole[$permissionId]);
                    }

                    if (isset($dataInsertDisallow[$permissionId]) && $dataInsertDisallow[$permissionId]) {
                        $this->permissionDisallowRepo->insert($dataInsertDisallow[$permissionId]);
                    }


                }
            }
        );

        if ($exception) {
            $this->message = 'Cập nhật quyền vào nhóm quyền thất bại';
            goto next;
        }
        $dataUpdate = [
            'role_id'       => $roleId,
            'permission_id' => $permissionId,
            'rule'          => $rule,
            'userDisallow'  => $userDisallow
        ];

        $this->message = 'Cập nhật quyền vào nhóm quyền thành công';
        $this->status  = 'success';
        next:
        return $this->responseData();
    }

    public function getDataInputGetListRole()
    {
        $params['limit']     = $this->request->input('limit', 10);
        $params['page']      = $this->request->input('page',1);
        $params['role']      = $this->request->input('role');
        $params['role_name'] = $this->request->input('role_name');
        $params['status']    = $this->request->input('status');
        $params['offset']    = ($params['page'] - 1) * $params['limit'];

        return $params;
    }


}
