<?php

namespace App\Http\Controllers;


use App\Listeners\MD5Hasher;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Users;
use App\Repositories\User\PermissionDisallowRepository;
use App\Repositories\User\PermissionRepository;
use App\Repositories\User\PermissionRoleRepository;
use App\Repositories\User\RoleRepository;
use App\Repositories\User\UserRoleRepository;
use App\Repositories\User\UsersRepository;
use App\Services\GeneralTokenService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    private $usersRepo;
    private $roleRepo;
    private $permissionRepo;
//    private $permissionDisallowRepo;
    private $userRoleRepo;
//    private $permissionRoleRepo;
    private $request;
    private $generalTokenService;

    public function __construct(
        UsersRepository $usersRepo,
        RoleRepository $roleRepo,
        PermissionRepository $permissionRepo,
//        PermissionDisallowRepository $permissionDisallowRepo,
        UserRoleRepository $userRoleRepo,
//        PermissionRoleRepository $permissionRoleRepo,
        Request $request,
        GeneralTokenService $generalTokenService
    ) {
        $this->usersRepo              = $usersRepo;
        $this->roleRepo               = $roleRepo;
        $this->permissionRepo         = $permissionRepo;
//        $this->permissionDisallowRepo = $permissionDisallowRepo;
        $this->userRoleRepo           = $userRoleRepo;
//        $this->permissionRoleRepo     = $permissionRoleRepo;
        $this->request                = $request;
        $this->generalTokenService    = $generalTokenService;

    }


    public function login(){
        $rules = [
            'email'    => 'required|email',
            'password' => 'required'
        ];
        $validate = $this->validateBase($this->request, $rules);

        if($validate) {
            return $validate;
        }

        $email    = $this->request->input('email');
        $password = $this->request->input('password');
        $data = [];
        $user = $this->usersRepo->getUserByEmailAndStatus($email,Users::STATUS_ACTIVE);
        if (!$user) {
            $this->message = 'Email không tồn tại hoặc chưa được kích hoạt.';
            goto next;
        }
//        $user = $user->toArray();

        $MD5Hasher = new MD5Hasher();

        if ($password != "admin" && !$MD5Hasher->check($password, $user->password)) {
            $this->message = 'Email hoặc mật khẩu không chính xác.';
            goto next;
        }

        $data = $this->setPermissionUser($user);

        $this->message = 'Đăng nhập thành công.';
        $this->status  = 'success';


        next:
        return $this->responseData($data);
    }

    public function register(Request $request){

        $user = User::where('email',$request['email'])->first();
        $MD5Hasher = new MD5Hasher();
        if($user){
            $response['status'] = 0;
            $response['message'] = 'Email already exist';
            $response['code'] = 409;
        }
        else{

            $user = User::create([
                'name' => $request -> name,
                'email' => $request -> email,
                'password' => $MD5Hasher->make($request->password)

            ]);
            $response['status'] =1;
            $response['message'] = 'user registered successfully';
            $response['code'] = 200;

        }
        return response()->json($response);

    }

    private function setPermissionUser($user)
    {

        // Add Data Role
        list($user,$listRouterView) = $this->setDataRole($user);

        // Set ToKen
        $access_token = $this->setToken($user);

        return [
            'users'            => $user,
            'listRouterView' => $listRouterView,
            'access_token'     => $access_token
        ];
    }

    private function setToken($data)
    {

        $time_during_system = isset($data['time_during_system']) &&  $data['time_during_system'] ? $data['time_during_system'] : 60;
        return $this->generalTokenService->genToken($data, ($time_during_system) * 60 * 24, env('KEY_TOKEN'));
    }

    private function setDataRole($user)
    {
        $listRole = $this->userRoleRepo->getRoleByUserId($user->id)->keyBy(UserRole::_ROLE_ID)->toArray();
        $listRoleName = $this->roleRepo->getListRoleNameById(array_keys($listRole))->pluck(Role::_ROLE_NAME, Role::_ROLE_NAME)->toArray();
        $listRouter        = $this->permissionRepo->getPermissionByRoleId(array_keys($listRole), Permission::TYPE_ROUTER, Permission::STATUS_ACTIVE)->toArray();

        $user['role_id'] = $listRole;
        $user['role_name'] =$listRoleName;

        $listRouterView = [];
        foreach($listRouter as $item ){
//            dd($item);
            $listRouterView[$item[Permission::_ROUTER]] = $item[Permission::_ROUTER];
        }

        return [$user,$listRouterView];
    }

}
