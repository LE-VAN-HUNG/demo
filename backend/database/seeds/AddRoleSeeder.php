<?php

use App\Models\Role;
use App\Models\UserRole;
use App\Models\Users;
use App\Repositories\User\RoleRepository;
use App\Repositories\User\UserRoleRepository;
use App\Repositories\User\UsersRepository;
use Illuminate\Database\Seeder;

class AddRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
        private $roleRepo;
        private $usersRepo;
        private $userRoleRepo;

        public function __construct(
        RoleRepository $roleRepo,
        UsersRepository $usersRepo,
        UserRoleRepository $userRoleRepo

    ) {
        $this->roleRepo           = $roleRepo;
        $this->usersRepo          = $usersRepo;
        $this->userRoleRepo       = $userRoleRepo;
    }
        public function run()
    {

        $listEmailUser = [
            'admin@admin.com'
        ];
        $listUser      = $this->usersRepo->getUserByListEmail($listEmailUser)->keyBy(Users::_ID)->toArray();
        $dataInsertRoleAdmin = [
            Role::_ROLE      => 'admin',
            Role::_STATUS    => Role::STATUS_ACTIVE,
            Role::_ROLE_NAME => 'ADMIN',
        ];
        $dataInsertRoleUser = [
            Role::_ROLE      => 'user',
            Role::_STATUS    => Role::STATUS_ACTIVE,
            Role::_ROLE_NAME => 'USER',
        ];
        $adminId         = $this->roleRepo->insertGetId($dataInsertRoleAdmin);
        $roleId         = $this->roleRepo->insertGetId($dataInsertRoleUser);

        foreach ($listUser as $userId => $user) {
            $dataInsertUserRole    = [
                UserRole::_USER_ID => $userId,
                UserRole::_ROLE_ID => $adminId,
            ];
            $this->userRoleRepo->insertGetId($dataInsertUserRole);
        }
    }
}
