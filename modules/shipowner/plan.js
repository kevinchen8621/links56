	define(function (require, exports, module) {
	'use strict';
	module.exports = function(app){
		app.register.controller('ShipownerPlanCtl', ['$scope', '$rootScope', '$routeParams', '$location', '$window', function($scope, $rootScope, $routeParams, $location, $window){
			$scope.orders = JSON.parse($window.localStorage.orders || "[]");
		}]);
	}
});