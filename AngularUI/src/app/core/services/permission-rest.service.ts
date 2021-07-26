import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class PermissionRestService {
  permissionList: any;

  constructor(private http: HttpClient) {
  }

  getPermissions() {
    return this.http.get('http://localhost:8000/get-list-permission');
  }
}


