import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { ToastrModule } from 'ngx-toastr';
import { AppComponent } from './app.component';

import { NavbarComponent } from './core/layout/common/navbar/navbar.component';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { BrowserModule } from '@angular/platform-browser';
import { ReactiveFormsModule} from '@angular/forms';
import {CoreModule} from "./core/core.module";
import {ToasterModule} from "angular2-toaster";
import {AppRoutingModule} from "./app-routing.module";
import {ThemeModule} from "./core/theme.module";
import {AdminModule} from "./modules/admin/admin.module";
import {CommonModule} from "@angular/common";


@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    CoreModule,
    HttpClientModule,
    BrowserAnimationsModule,
    ReactiveFormsModule,
    AppRoutingModule,
    AdminModule,
    CommonModule,
    ThemeModule.forRoot (),
    ToastrModule.forRoot(),
    ToasterModule.forRoot (),
  ],

  bootstrap: [AppComponent]
})
export class AppModule { }
