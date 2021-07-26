import {Component, Injectable, OnInit} from '@angular/core';
import { CommonAuthService } from '../../../core/services/common-auth.service';
import { NgForm, FormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import { HttpResponse } from '@angular/common/http';
import { RouterModule, Router } from '@angular/router';
import {Observable} from "rxjs/internal/Observable";
import {environment} from "../../../../environments/environment";
import {tap} from "rxjs/operators";
import {AuthenticationService} from "../../../core/authentication/authentication.service";



@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
@Injectable({
  providedIn: 'root'}
)
export class LoginComponent implements OnInit {
  loginForm: FormGroup;
  serverErrors = [];

  constructor(
    // private auth: CommonAuthService,
    private router: Router,
    private authenticationService: AuthenticationService,
  ) { }

  ngOnInit() {
    this.loginForm = new FormGroup({
      'email' : new FormControl(null, [Validators.required, Validators.email]),
      'password' : new FormControl(null, [Validators.required, Validators.minLength(5)])
    });
  }

  get email(){ return this.loginForm.get('email'); }
  get password(){ return this.loginForm.get('password'); }


  onSubmit(){
    this.authenticationService.login(this.loginForm.value)
      .subscribe((res)=>{
        if(res.status === 'fail'){
          console.log('fail');
        }
        else{
          console.log('success');
        }
      });

  }

}
