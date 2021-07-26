import { Component, OnInit } from '@angular/core';


export interface RouteInfo {
    path: string;
    title: string;
    icon: string;
    class: string;
    childMenu:Array<object>;
}

export const ROUTES: RouteInfo[] = [

    { path: '', title: 'Manage Users', icon:'nc-support-17', class: '',
        childMenu: [
            {path: '/admin/user', title: 'Users', class: ''},
            {path: '/admin/role', title: 'Roles', class: ''},
            {path: '/admin/permission', title: 'Permission', class: ''},
        ]
    }
];



@Component({
    moduleId: module.id,
    selector: 'sidebar-cmp',
    templateUrl: 'sidebar.component.html',
})

export class SidebarComponent implements OnInit {
    public menuItems: any[];
    ngOnInit() {
        this.menuItems = ROUTES.filter(menuItem => menuItem);
    }
}
