<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionDisallow;
use App\Repositories\User\PermissionDisallowRepository;
use App\Repositories\User\PermissionRepository;
use App\Repositories\User\PermissionRoleRepository;
use App\Repositories\User\RoleRepository;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private $permissionRepo;
    private $permissionRoleRepo;
    private $roleRepo;
    private $permissionDisallowRepo;
    private $request;

    public function __construct(
        PermissionRepository $permissionRepo,
        PermissionRoleRepository $permissionRoleRepo,
        RoleRepository $roleRepo,
        PermissionDisallowRepository $permissionDisallowRepo,
        Request $request
    ) {
        $this->permissionRepo         = $permissionRepo;
        $this->permissionRoleRepo     = $permissionRoleRepo;
        $this->roleRepo               = $roleRepo;
        $this->permissionDisallowRepo = $permissionDisallowRepo;
        $this->request                = $request;
    }

public function createPermission()
{
    $rules = [
        'name'      => 'required',
        'type'      => 'required',
        'router'    => 'required',
        'is_public' => 'required',
        'note'      => 'required',
    ];
    $this->validateBase($this->request, $rules);

    $name     = $this->request->input('name');
    $type     = $this->request->input('type');
    $router   = $this->request->input('router');
    $isPublic = $this->request->input('is_public');
    $note     = $this->request->input('note');
    $id       = $this->request->input('id');
    $permission = $this->permissionRepo->getPermissionByRouter($router);

    if ($permission) {
        if ((isset($id) && $permission[Permission::_ID] != $id) || !$id) {
            $this->message = 'Router đã tồn tại.';
            goto next;
        }
    }

    $dataInsertPermission = [
        Permission::_NAME         => $name,
        Permission::_ROUTER       => $router,
        Permission::_TYPE         => $type,
        Permission::_NOTE         => $note,
        Permission::_IS_PUBLIC    => $isPublic,
        Permission::_TIME_UPDATED => time(),
    ];

    if ($id) {
        $check   = $this->permissionRepo->update($id, $dataInsertPermission);
        $message = 'Cập nhật quyền ';
    } else {
        $dataInsertPermission[Permission::_STATUS]       = Permission::STATUS_ACTIVE;
        $dataInsertPermission[Permission::_TIME_CREATED] = time();

        $check   = $this->permissionRepo->insertGetId($dataInsertPermission);
        $id      = $check;
        $message = 'Thêm mới quyền ';
    }

    if (!$check) {
        $this->message = $message . 'thất bại.';
        goto next;
    }
    $this->message = $message . 'thành công.';
    $this->status  = 'success';
    next:
    return $this->responseData();
}
    public function getListPermissionByRoleId()
    {
        $rules = [
            'role_id' => 'required',
        ];
        $this->validateBase($this->request, $rules);

        $roleId                 = $this->request->input('role_id');
        $userId                 = $this->request->input('user_id');


        $listRouter        = $this->permissionRepo->getPermissionByRoleId($roleId, Permission::TYPE_ROUTER, Permission::STATUS_ACTIVE)->toArray();
        $listRouterId      = array_column($listRouter, Permission::_ID);
        foreach ($listRouter as &$router) {
            if (isset($listPermissionDisallow[$router[Permission::_ID]])) {
                $router['allow'] = false;
            } else {
                $router['allow'] = true;
            }
        }

        $listApiId     = [];

        $listApiInfo = $this->permissionRepo->getPermissionByRoleId($roleId, Permission::TYPE_API, Permission::STATUS_ACTIVE, $listApiId)->keyBy(Permission::_ID)->toArray();
//        foreach ($listScreenLinkApi as $key => $infoApi) {
//            foreach ($infoApi as $value) {
//                if (isset($listApiInfo[$value[ScreenLinkApi::_API_ID]])) {
//                    if (isset($listPermissionDisallow[$value[ScreenLinkApi::_API_ID]])) {
//                        $listApiInfo[$value[ScreenLinkApi::_API_ID]]['allow'] = false;
//                    } else {
//                        $listApiInfo[$value[ScreenLinkApi::_API_ID]]['allow'] = true;
//                    }
//                    $screenLinkApi[$key][] = $listApiInfo[$value[ScreenLinkApi::_API_ID]];
//                }
//            }
//        }
        $data = [
            'listRouter'    => $listRouter,
        ];

        $this->message = 'Lấy danh sách quyền thành công';
        $this->status  = 'success';
        next:
        return $this->responseData($data);
    }

    public function getListPermission(){

    }

}
