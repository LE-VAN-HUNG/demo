import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {AdminRoutingModule} from "./admin-routing.module";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";

import {UserCreateComponent} from "./users/user-create/user-create.component";
import {UserEditComponent} from "./users/user-edit/user-edit.component";
import {UserIndexComponent} from "./users/user-index/user-index.component";
import {ThemeModule} from "../../core/theme.module";
import {RoleCreateComponent} from "./roles/role-create/role-create.component";
import {RoleIndexComponent} from "./roles/role-index/role-index.component";
import { PermissionIndexComponent } from './permissions/permission-index/permission-index.component';
import { PermissionCreateComponent } from './permissions/permission-create/permission-create.component';
import { DashboardComponent } from './dashboard/dashboard.component';

@NgModule({
  imports: [
    CommonModule,
    AdminRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    ThemeModule,

  ],
  declarations: [
    AdminRoutingModule.components,
      UserCreateComponent,
      UserEditComponent,
      UserIndexComponent,
      RoleCreateComponent,
      RoleIndexComponent,
      PermissionIndexComponent,
      PermissionCreateComponent,
      DashboardComponent
  ],

})
export class AdminModule { }
