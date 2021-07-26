<?php

use App\Models\Permission;
use App\Repositories\User\PermissionRepository;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
      private $listApi = [
//            'api/auth/login',
//            'api/auth/register',
//            'api/get-list-user',
//            'api/create-user',
//            'api/get-user-by-role',
//            'api/get-info-user-by-user-id',
//            'api/add-role-user',
//            'api/remove-role-user',
               'auth/welcome',
                'auth/login',
                'auth/register',
                'admin/user',
                'admin/role',
                'admin/404',

];

    private $permissionRepo;

    public function  __construct(
        PermissionRepository $permissionRepo
){
        $this->permissionRepo = $permissionRepo;
}

    public function run()
    {
        $listRouterExists = $this->permissionRepo->getListPermissionByListRouter($this->listApi)->pluck(Permission::_ROUTER,Permission::_ROUTER)->toArray();
        $dataInsertPermission = [];
        foreach($this->listApi as $api){
            if(!isset($listRouterExists[$api])){
                $dataInsertPermission = [
                    Permission::_NAME => $api,
                    Permission::_ROUTER =>$api,
                    Permission::_TYPE =>Permission::TYPE_API,
                    Permission::_STATUS =>Permission::STATUS_ACTIVE,
                    Permission::_NOTE =>$api,
                    Permission::_IS_PUBLIC =>Permission::PUBLIC,
                    Permission::_TIME_CREATED=>time(),
                    Permission::_TIME_UPDATED =>time(),

                ];
            }
            $this->permissionRepo->insert($dataInsertPermission);

        }


    }
}
