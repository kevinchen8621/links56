define(function (require, exports, module) {
	'use strict';
	module.exports = function(app){
		app.register.controller('ShipownerNeworderCtl', ['$scope', '$rootScope', '$routeParams', '$location', '$rest', function($scope, $rootScope, $routeParams, $location, $rest){
			$scope.custs = [
				{_id:123123, name:"邻客物流有限公司"}
			];
		}]);
	}
});