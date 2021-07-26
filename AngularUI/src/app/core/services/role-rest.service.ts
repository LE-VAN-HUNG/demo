import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class RoleRestService {
  roleList: Array<{id: number, role: string}> = [];
  constructor(private http: HttpClient) { }

  getRoles() {
    return this.http.get('http://localhost:8000/api/get-list-role');
  }

}
