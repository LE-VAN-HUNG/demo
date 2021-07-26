import { NgModule } from '@angular/core';
import {RouterModule, Routes} from "@angular/router";

import {UserIndexComponent} from "./users/user-index/user-index.component";
import {UserCreateComponent} from "./users/user-create/user-create.component";
import {UserEditComponent} from "./users/user-edit/user-edit.component";
import {RoleIndexComponent} from "./roles/role-index/role-index.component";
import {RoleCreateComponent} from "./roles/role-create/role-create.component";
import {ClientAuthGuard} from "../../core/guards/client-auth.guard";
import {DashboardComponent} from "./dashboard/dashboard.component";
import {ContextualLifecycleRule} from "codelyzer";
import {PermissionIndexComponent} from "./permissions/permission-index/permission-index.component";
import {PermissionCreateComponent} from "./permissions/permission-create/permission-create.component";


const routes:Routes =[

  {
    path: 'user',
    component: UserIndexComponent,
    canActivate : [ClientAuthGuard]
  },
  {
    path: 'create-user',
    component: UserCreateComponent,
    // canActivate : [ClientAuthGuard]
  },
  {
    path: 'edit-user/:id',
    component: UserEditComponent,
    // canActivate : [ClientAuthGuard]
  },
  {
    path: 'role',
    component: RoleIndexComponent,
    canActivate : [ClientAuthGuard]
  },
  {
    path: 'role-create',
    component: RoleCreateComponent,
    // canActivate : [ClientAuthGuard]
  },
  {
    path:'dashboard',
    component:DashboardComponent,
    // canActivate:[ClientAuthGuard]
  },
  {
    path: 'permission-create',
    component: PermissionCreateComponent,
    // canActivate : [ClientAuthGuard]
  },
  {
    path: 'permission',
    component: PermissionIndexComponent,
    // canActivate : [ClientAuthGuard]
  },

];


@NgModule ({
  imports : [RouterModule.forChild (routes)],
  exports : [RouterModule],
})
export class AdminRoutingModule {
  static components = [
    UserIndexComponent,
    UserCreateComponent,
    UserEditComponent,
    RoleIndexComponent,
    RoleCreateComponent
  ];
}
