import { RouterModule, Routes, PreloadAllModules } from '@angular/router';
import { NgModule } from '@angular/core';

const app_routes: Routes =[
  {path : 'auth', loadChildren : './modules/auth/common-auth.module#CommonAuthModule'},
  {path : 'admin', loadChildren : './modules/admin/admin.module#AdminModule'},
  {path : '', pathMatch : 'full', redirectTo : 'auth/welcome'},
  {path : '**', redirectTo : '/auth/404'},
];
@NgModule ({
  imports : [RouterModule.forRoot (app_routes, {preloadingStrategy : PreloadAllModules})],
  exports : [RouterModule]
})
export class AppRoutingModule {
}
