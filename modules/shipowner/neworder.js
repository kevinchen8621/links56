define(function (require, exports, module) {
	'use strict';
	module.exports = function(app){
		app.register.controller('ShipownerNeworderCtl', ['$scope', '$rootScope', '$routeParams', '$location', '$rest', function($scope, $rootScope, $routeParams, $location, $rest){
			$scope.custs = [
				{_id:123123, name:"邻客物流有限公司",province:"上海", city:"上海", district:"浦东", address:" xxxxxssssssssssssssss", contact:"姓名", tel:"13123443344"},
				{_id:123124, name:"邻客物流有限公司2",province:"上海", city:"上海", district:"浦东", address:" xxxxxssssssssssssssss", contact:"姓名", tel:"13123443344"},
				{_id:123125, name:"邻客物流有限公司3",province:"上海", city:"上海", district:"浦东", address:" xxxxxssssssssssssssss", contact:"姓名", tel:"13123443344"},
				{_id:123126, name:"邻客物流有限公司4",province:"上海", city:"上海", district:"浦东", address:" xxxxxssssssssssssssss", contact:"姓名", tel:"13123443344"},
			];
		}]);
	}
});