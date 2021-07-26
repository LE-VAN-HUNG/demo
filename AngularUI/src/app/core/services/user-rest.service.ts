import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs/internal/Observable';
import {environment} from "../../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class UserRestService {
  users: Array<{id: number, name: string, email: string}> = [];
  constructor(private http: HttpClient) { }

  getUsers(): Observable<any> {
    return this.http.get('http://localhost:8000/api/get-list-user');
  }

  editUser(id): Observable<any> {
    return this.http.get('http://localhost:8000/api/user-list/' + id);
  }

  updateUser(form,id): Observable<any> {
    return this.http.put('http://localhost:8000/api/user-list/' + id, form.value);
  }

  storeUser(form): Observable<any> {
    return this.http.post('http://localhost:8000/api/create-user',form.value);
  }

  deleteUser(id): Observable<any> {

    const href = environment.host_local + 'remove-user';
    return this.http.post<any>(href, {id:id})
  }
}
