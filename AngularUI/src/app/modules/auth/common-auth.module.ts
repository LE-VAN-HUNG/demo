import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CommonAuthRoutingModule } from './common-auth-routing.module';
import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';
import { CommonAuthService } from '../../core/services/common-auth.service';
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from './auth-interceptor.interceptor';
import {WelcomeLayoutComponent} from "./welcome-layout/welcome-layout.component";
import {ThemeModule} from "../../core/theme.module";
import { NotFoundComponent } from './not-found/not-found.component';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {AppComponent} from "../../app.component";

@NgModule({
  declarations: [
    LoginComponent,
    RegisterComponent,
    ForgotPasswordComponent,
    WelcomeLayoutComponent,
    NotFoundComponent

  ],
  imports: [
    CommonModule,
    CommonAuthRoutingModule,
    ThemeModule,
    FormsModule,
    ReactiveFormsModule
  ],
  providers: [
    CommonAuthService,
    WelcomeLayoutComponent,
    {provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true}
  ],
  bootstrap: [WelcomeLayoutComponent]
})


export class CommonAuthModule { }
