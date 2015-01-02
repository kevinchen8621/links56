define(function (require, exports, module) {
	'use strict';
	module.exports = function(app){
		app.register.controller('DashboardDemoCtl', ['$scope', '$rootScope', '$routeParams', '$location', '$http', '$timeout', function($scope, $rootScope, $routeParams, $location, $http, $timeout){
			$scope.states = [
				{title:'Alabama', description:"asdfasdfasdfa"},
				{title:'sdfabama', description:"asdfasdfasdfa"},
				{title:'fgdfbama', description:"asdfasdfasdfa"},
			];
		}]);
	}
});