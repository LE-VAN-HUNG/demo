import { Injectable } from '@angular/core';
import {Router} from "@angular/router";
import {ToastrService} from "ngx-toastr";
import {HttpClient} from "@angular/common/http";
import {LocationStrategy} from "@angular/common";
import {Login} from "../../shared/models/auth/login.model";
import {Observable} from "rxjs/internal/Observable";
import {environment} from "../../../environments/environment";
import {tap} from "rxjs/operators";
import { of as observableOf } from 'rxjs';


const credentialsKey = 'currentUser';
const accessTokenKey = 'accessToken';
const listViewKey = 'listView';
const viewnKey = 'view';
const listRouterViewKey = 'listRouterView';
const listRouterAndViewKey = 'listRouterAndView';
const popupKey = 'popup';

@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {

  constructor(
    private router:Router,
    private toasterService:ToastrService,
    private http:HttpClient,
    private locationStrategy: LocationStrategy
  ) { }

  login(loginData: Login): Observable<any>{
    const href = environment.host_local + 'auth/login';
    return this.http.post<any>(href,loginData).pipe(
      tap( (item)=> {
        if (item.status === 'success') {
          this.dataLogin(item.data);

        }
        return item;
      })
    );
  }


  private dataLogin(data) {
    this.setData(credentialsKey,JSON.stringify(data.users));
    this.setData(listRouterViewKey, JSON.stringify(data.listRouterView));
    this.setData(accessTokenKey,data.access_token);
    return this.router.navigate(['/admin/dashboard']);


  }

  setData(key, data) {
    sessionStorage.setItem(key, data);
    localStorage.setItem(key, data);
  }

  getToken() {
    return this.getData(accessTokenKey);
  }

  getData(key) {
    return sessionStorage.getItem(key) || localStorage.getItem(key);
  }

  logout(): Observable<boolean> {
    this.removeAll();
    return observableOf(true);
  }
  removeAll(): void {
    sessionStorage.clear();
    localStorage.clear();
  }

  isLogin() {
    if (this.getData(credentialsKey)) {
      return true;
    }
    return false;
  }
  getUser() {
    const savedCredentials = this.getData(credentialsKey);
    return JSON.parse(savedCredentials);
  }
  getRouterView() {
    let listRouterView = this.getData(listRouterViewKey);
    return JSON.parse(listRouterView);
  }

  getUserType() {
    const savedCredentials = this.getUser();
    if (this.isLogin()) {
      // return savedCredentials['role'];
      return 'user';
    } else {
      return false;
    }
  }
}
