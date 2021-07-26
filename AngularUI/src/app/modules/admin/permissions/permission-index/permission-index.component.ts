import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {PermissionRestService} from "../../../../core/services/permission-rest.service";

@Component({
  selector: 'app-permission-index',
  templateUrl: './permission-index.component.html',
  styleUrls: ['./permission-index.component.scss']
})
export class PermissionIndexComponent implements OnInit {
  permissionList : any;
  constructor(
    private route: ActivatedRoute,
    private permissionRest :PermissionRestService,
    private router: Router
    ) { }

  ngOnInit() {
  }

  getPermission()

}
