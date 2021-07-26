import {ModuleWithProviders, NgModule} from '@angular/core';
import { CommonModule } from '@angular/common';
import {NavbarComponent} from "./layout/common/navbar/navbar.component";
import {RouterModule} from "@angular/router";
import {NgbModule} from "@ng-bootstrap/ng-bootstrap";
import {FooterComponent} from "./layout/footer/footer.component";
import {UtilsComponent} from "./layout/utils/utils.component";
import {SidebarComponent} from "./layout/sidebar/sidebar.component";

@NgModule({
  imports: [
    RouterModule,
    CommonModule,
    NgbModule

  ],
  declarations: [
    NavbarComponent,
    FooterComponent,
    UtilsComponent,
    SidebarComponent
  ],
  exports:[
    NavbarComponent,
    FooterComponent,
    UtilsComponent,
    SidebarComponent
  ]
})
export class ThemeModule {
  static forRoot (): ModuleWithProviders {
    return <ModuleWithProviders>{
      ngModule  : ThemeModule,
    };
  }
}
