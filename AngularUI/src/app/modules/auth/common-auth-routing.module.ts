import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';
import {WelcomeLayoutComponent} from "./welcome-layout/welcome-layout.component";
import {NotFoundComponent} from "./not-found/not-found.component";


const routes: Routes = [
      {
        path: 'login',
        component: LoginComponent
      },
      {
        path: 'register',
        component: RegisterComponent
      },
      {
        path: 'forgot-password',
        component: ForgotPasswordComponent
      },
      {
        path: 'welcome',
        component: WelcomeLayoutComponent
      },
      {
        path      : '404',
        component : NotFoundComponent,
      },

];

@NgModule ({
  imports : [
    RouterModule.forChild (routes)
  ],
  exports : [RouterModule],
})

export class CommonAuthRoutingModule {
  static components = [
    LoginComponent,
    RegisterComponent,
    ForgotPasswordComponent,
    WelcomeLayoutComponent,
    NotFoundComponent

  ];
}
