import { Injectable } from '@angular/core';
import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest } from '@angular/common/http';
import { Observable } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Router } from '@angular/router';
import { UNAUTHORIZED, BAD_REQUEST, FORBIDDEN, UNPROCESSABLE_ENTITY } from 'http-status-codes';

import { LoggerService } from '../services/logger.service';
import { environment } from '../../../environments/environment';
import { ToasterService } from 'angular2-toaster';
import { appToaster } from '../../configs/app-toaster.config';

/**
 * Adds a default error handler to all requests.
 */
@Injectable ()
export class ErrorHandlerInterceptor implements HttpInterceptor {
	constructor (
		private logger: LoggerService,
		private toasterService: ToasterService,
		private router: Router
	) {
	}
	
	intercept (request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
		return next.handle (request).pipe (catchError (error => this.errorHandler (error)));
	}
	
	private errorHandler (response: HttpEvent<any>): Observable<HttpEvent<any>> {
		if (!environment.production) {
			this.logger.logError ('Request error ' + JSON.stringify (response));
		}
		
		const httpErrorCode = response['status'];
		switch (httpErrorCode) {
			case UNAUTHORIZED:
				this.router.navigateByUrl ('/auth/login');
				break;
			case FORBIDDEN:
				this.router.navigateByUrl ('/auth/403');
				break;
			case UNPROCESSABLE_ENTITY:
				let message = '';
				Object.keys (response['error']).forEach ((key) => {
					message += response['error'][key];
				});
				this.toasterService.pop ('error', appToaster.errorHead, message);
				break;
			default:
				this.toasterService.pop ('error', appToaster.errorHead, response['message']);
		}
		
		throw response;
	}
	
}

