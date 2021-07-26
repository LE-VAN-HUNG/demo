import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import { RoleRestService } from '../../../../core/services/role-rest.service';

@Component({
  selector: 'app-role-index',
  templateUrl: './role-index.component.html',
  styleUrls: ['./role-index.component.scss']
})
export class RoleIndexComponent implements OnInit {
  roleList: any;
  constructor(private route: ActivatedRoute, private roleRest: RoleRestService, private router:Router) { }

  ngOnInit() {
    this.getRole();
  }

  getRole(){
    this.roleRest.getRoles().subscribe(
      (response) => {this.roleList = response},
      (error) => { console.log(error) }

    );
  }

}
