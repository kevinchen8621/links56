define(function(require, exports, module) {
	"use strict";
	module.exports = function(app) {
		app.config(['$routeProvider', function($routeProvider) {
			$routeProvider
			.when('/dashboard_demo', {controller: 'DashboardDemoCtl',controllerUrl: 'modules/dashboard/demo.js',templateUrl: 'modules/dashboard/demo.html'})
			.when('/shipowner_authentication', {controller: 'ShipownerAuthenticationCtl',controllerUrl: 'modules/shipowner/authentication.js',templateUrl: 'modules/shipowner/authentication.html'})
			.when('/shipowner_dispatch', {controller: 'ShipownerDispatchCtl',controllerUrl: 'modules/shipowner/dispatch.js',templateUrl: 'modules/shipowner/dispatch.html'})
			.when('/shipowner_plan', {controller: 'ShipownerPlanCtl',controllerUrl: 'modules/shipowner/plan.js',templateUrl: 'modules/shipowner/plan.html'})
			.when('/shipowner_receive', {controller: 'ShipownerReceiveCtl',controllerUrl: 'modules/shipowner/receive.js',templateUrl: 'modules/shipowner/receive.html'})
			.when('/shipowner_track', {controller: 'ShipownerTrackCtl',controllerUrl: 'modules/shipowner/track.js',templateUrl: 'modules/shipowner/track.html'})
			.when('/driver_authentication', {controller: 'DriverAuthenticationCtl',controllerUrl: 'modules/driver/authentication.js',templateUrl: 'modules/driver/authentication.html'})
			.when('/driver_check', {controller: 'DriverCheckCtl',controllerUrl: 'modules/driver/check.js',templateUrl: 'modules/driver/check.html'})
			.when('/driver_exception', {controller: 'DriverExceptionCtl',controllerUrl: 'modules/driver/exception.js',templateUrl: 'modules/driver/exception.html'})
			.when('/driver_pay', {controller: 'DriverPayCtl',controllerUrl: 'modules/driver/pay.js',templateUrl: 'modules/driver/pay.html'})
			.when('/driver_track', {controller: 'DriverTrackCtl',controllerUrl: 'modules/driver/track.js',templateUrl: 'modules/driver/track.html'})
			.otherwise({
				redirectTo: '/dashboard_demo'
			});
		}]);		
	}
});