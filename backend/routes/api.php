<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


$router->get('/', function () use ($router) {
    return "Service auth !";
});
$router->post('auth/login', 'AuthController@login');
$router->post('auth/register','AuthController@register');
$router->post('remove-user', 'UsersController@removeUserbyId');

$router->group(
    ['middleware'=>'api.auth'],
    function () use ($router){
        //UsersController
        $router->get('get-list-user','UsersController@getListUser');
        $router->post('create-user', 'UsersController@createUser');
        $router->post('get-user-by-role', 'UsersController@getUserByRole');
        $router->post('add-role-user', 'UsersController@addRoleForUser');
        $router->get('get-info-user-by-user-id', 'UsersController@getInfoUserByUserId');
        $router->post('remove-role-user', 'UsersController@removeRoleUser');

        //RoleController
        $router->post('create-role','RoleController@createRole');
        $router->get('get-list-role','RoleController@getListRole');
        $router->post('add-permission-into-role','RoleController@addPermissionIntoRole');
        //PermissionController
        $router->post('create-permission','PermissionController@createPermission');
        $router->get('get-list-permission-by-role-id','PermissionController@getListPermissionByRoleId');

        // Email

        $router->get('send-email','EmailController@sendEmail');

    }
);

