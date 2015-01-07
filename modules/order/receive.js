define(function (require, exports, module) {
	'use strict';
	module.exports = function(app){
		app.register.controller('OrderReceiveCtl', ['$scope', '$rootScope', '$routeParams', '$location', '$window', function($scope, $rootScope, $routeParams, $location, $window){
			if($routeParams.id){
				$scope.orders = JSON.parse($window.localStorage.orders || "[]");
				$scope.order = _.find($scope.orders, function(item){ return item._id == $routeParams.id;});
				if(!$scope.order) return $location.path("/shipowner_track");
				$scope.isDisable = true;
				$scope.lbl_order = "订单详情";
			}else{
				return $location.path("/shipowner_receive");
			}

			$scope.save = function(){
				$scope.order.receives = $scope.order.receives || [];
				$scope.order.receives.push($scope.receive);
				$scope.orders = JSON.parse($window.localStorage.orders || "[]");
				var idx = _.findIndex($scope.orders, function(o){return o._id == $scope.order._id;});
				if(idx > -1){
					$scope.orders.splice(idx,1,$scope.order);
				}else{
					$scope.orders.push($scope.order);	
				}
				$window.localStorage.orders =  JSON.stringify($scope.orders);
			}
		}]);
	}
});