import { Injectable } from '@angular/core';
import {
  ActivatedRoute, ActivatedRouteSnapshot,
  CanActivate,
  CanActivateChild,
  Router, RouterStateSnapshot
} from '@angular/router';
import {ToasterService} from "angular2-toaster";
import {AuthenticationService} from "../authentication/authentication.service";



@Injectable({
  providedIn: 'root'
})
export class ClientAuthGuard implements CanActivate , CanActivateChild {

  constructor (
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private toasterService: ToasterService,
    private authenticationService: AuthenticationService) {
  }

  canActivate (route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
    return this.checkUser (route, state);

  }
  canActivateChild (route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
    return this.checkUser (route, state);
  }
  private checkUser (route, state): boolean {
    const userType = this.authenticationService.getUserType ();
    const isLogin  = this.authenticationService.isLogin ();


    if (userType === 'user' && isLogin) {
      let check = this.checkRouter (route);
      if (check) {
        return true;
      }
      this.router.navigate (['/auth/welcome'], {queryParams : {returnUrl : state.url}});
      return false;
    }
  }

  private checkRouter (route: any) {
    const routerView = this.authenticationService.getRouterView ();
    let check           = false;
    Object.keys (routerView).forEach ((key) => {
      Object.keys (route.url).forEach ((keyRoute) => {
        let listKey = key.split("/");
        Object.keys (listKey).forEach ((data) => {
          if (listKey[data] === route.url[keyRoute]['path']) {
            console.log(listKey[data]);
            check = true;
            console.log('you have permission')
          }else{
            console.log('permission denied');
          }
        });
      });
    });

    return check;
  }


}
