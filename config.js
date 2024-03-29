define(function(require, exports, module) {
	"use strict";
	module.exports = function(app) {
		app.config(['$routeProvider', function($routeProvider) {
			$routeProvider
				.when('/dashboard_demo', {controller: 'DashboardDemoCtl',controllerUrl: 'modules/dashboard/demo.js',templateUrl: 'modules/dashboard/demo.html'})
				.when('/dicshipowner_authentication', {controller: 'ShipownerAuthenticationCtl',controllerUrl: 'modules/shipowner/authentication.js',templateUrl: 'modules/shipowner/authentication.html'})
				.when('/dicshipowner_authdetail', {controller: 'ShipownerAuthdetailCtl',controllerUrl: 'modules/shipowner/authdetail.js',templateUrl: 'modules/shipowner/authdetail.html'})
				.when('/shipowner_dispatch', {controller: 'ShipownerDispatchCtl',controllerUrl: 'modules/shipowner/dispatch.js',templateUrl: 'modules/shipowner/dispatch.html'})
				.when('/shipowner_plan', {controller: 'ShipownerPlanCtl',controllerUrl: 'modules/shipowner/plan.js',templateUrl: 'modules/shipowner/plan.html'})
				.when('/shipowner_neworder', {controller: 'ShipownerNeworderCtl',controllerUrl: 'modules/shipowner/neworder.js',templateUrl: 'modules/shipowner/neworder.html'})
				.when('/shipowner_receive', {controller: 'ShipownerReceiveCtl',controllerUrl: 'modules/shipowner/receive.js',templateUrl: 'modules/shipowner/receive.html'})
				.when('/shipowner_track', {controller: 'ShipownerTrackCtl',controllerUrl: 'modules/shipowner/track.js',templateUrl: 'modules/shipowner/track.html'})
				.when('/shipowner_list', {controller: 'ShipownerListCtl',controllerUrl: 'modules/shipowner/list.js',templateUrl: 'modules/shipowner/list.html'})
				.when('/shipowner_edit/:id', {controller: 'ShipownerEditCtl',controllerUrl: 'modules/shipowner/edit.js',templateUrl: 'modules/shipowner/edit.html'})
				.when('/shipowner_edit', {controller: 'ShipownerEditCtl',controllerUrl: 'modules/shipowner/edit.js',templateUrl: 'modules/shipowner/edit.html'})
				.when('/dicdriver_authentication', {controller: 'DriverAuthenticationCtl',controllerUrl: 'modules/driver/authentication.js',templateUrl: 'modules/driver/authentication.html'})
				.when('/dicdriver_authdetail', {controller: 'DriverAuthdetailCtl',controllerUrl: 'modules/driver/authdetail.js',templateUrl: 'modules/driver/authdetail.html'})
				.when('/driver_check', {controller: 'DriverCheckCtl',controllerUrl: 'modules/driver/check.js',templateUrl: 'modules/driver/check.html'})
				.when('/driver_exception', {controller: 'DriverExceptionCtl',controllerUrl: 'modules/driver/exception.js',templateUrl: 'modules/driver/exception.html'})
				.when('/driver_pay', {controller: 'DriverPayCtl',controllerUrl: 'modules/driver/pay.js',templateUrl: 'modules/driver/pay.html'})
				.when('/driver_track', {controller: 'DriverTrackCtl',controllerUrl: 'modules/driver/track.js',templateUrl: 'modules/driver/track.html'})
				.when('/driver_list', {controller: 'DriverListCtl',controllerUrl: 'modules/driver/list.js',templateUrl: 'modules/driver/list.html'})
				.when('/driver_edit/:id', {controller: 'DriverEditCtl',controllerUrl: 'modules/driver/edit.js',templateUrl: 'modules/driver/edit.html'})
				.when('/driver_edit', {controller: 'DriverEditCtl',controllerUrl: 'modules/driver/edit.js',templateUrl: 'modules/driver/edit.html'})
				.when('/order_grub', {controller: 'OrderGrubCtl',controllerUrl: 'modules/order/grub.js',templateUrl: 'modules/order/grub.html'})
				.when('/order_list', {controller: 'OrderListCtl',controllerUrl: 'modules/order/list.js',templateUrl: 'modules/order/list.html'})
				.when('/order_edit/:id', {controller: 'OrderEditCtl',controllerUrl: 'modules/order/edit.js',templateUrl: 'modules/order/edit.html'})
				.when('/order_edit', {controller: 'OrderEditCtl',controllerUrl: 'modules/order/edit.js',templateUrl: 'modules/order/edit.html'})
				.when('/order_dispatch/:id', {controller: 'OrderDispatchCtl',controllerUrl: 'modules/order/dispatch.js',templateUrl: 'modules/order/dispatch.html'})
				.when('/order_track/:id', {controller: 'OrderTrackCtl',controllerUrl: 'modules/order/track.js',templateUrl: 'modules/order/track.html'})
				.when('/order_receive/:id', {controller: 'OrderReceiveCtl',controllerUrl: 'modules/order/receive.js',templateUrl: 'modules/order/receive.html'})
				.when('/waybill_list', {controller: 'WaybillListCtl',controllerUrl: 'modules/waybill/list.js',templateUrl: 'modules/waybill/list.html'})
				.when('/waybill_edit/:id', {controller: 'WaybillEditCtl',controllerUrl: 'modules/waybill/edit.js',templateUrl: 'modules/waybill/edit.html'})
				.when('/waybill_edit', {controller: 'WaybillEditCtl',controllerUrl: 'modules/waybill/edit.js',templateUrl: 'modules/waybill/edit.html'})
				.when('/waybill_exception', {controller: 'WaybillExceptionCtl',controllerUrl: 'modules/waybill/exception.js',templateUrl: 'modules/waybill/exception.html'})
				.when('/waybill_deal', {controller: 'WaybillDealCtl',controllerUrl: 'modules/waybill/deal.js',templateUrl: 'modules/waybill/deal.html'})
				.when('/waybill_track', {controller: 'WaybillTrackCtl',controllerUrl: 'modules/waybill/track.js',templateUrl: 'modules/waybill/track.html'})
				.when('/waybill_book', {controller: 'WaybillBookCtl',controllerUrl: 'modules/waybill/book.js',templateUrl: 'modules/waybill/book.html'})
				.when('/waybill_pay', {controller: 'WaybillPayCtl',controllerUrl: 'modules/waybill/pay.js',templateUrl: 'modules/waybill/pay.html'})
				.otherwise({
					redirectTo: '/dashboard_demo'
				});
		}]);		
	}
});