define(function (require, exports, module) {
	'use strict';
	module.exports = function(app){
		app.register.controller('OrderTrackCtl', ['$scope', '$rootScope', '$routeParams', '$location', '$window', function($scope, $rootScope, $routeParams, $location, $window){
			if($routeParams.id){
				$scope.orders = JSON.parse($window.localStorage.orders || "[]");
				$scope.order = _.find($scope.orders, function(item){ return item._id == $routeParams.id;});
				if(!$scope.order) return $location.path("/shipowner_track");
				$scope.isDisable = true;
				$scope.lbl_order = "订单详情";
			}else{
				return $location.path("/shipowner_track");
			}
        var longitude = 121.506191;
        var latitude = 31.245554;
        $scope.mapOptions = {
            center: {
                longitude: longitude,
                latitude: latitude
            },
            zoom: 17,
            city: 'ShangHai',
            markers: [{
                longitude: longitude,
                latitude: latitude,
                icon: 'img/mappiont.png',
                width: 49,
                height: 60,
                title: 'Where',
                content: 'Put description here'
            }]
        };

			$scope.save = function(){
				$scope.order._id = $scope.order._id || Date.now();
				$scope.orders = JSON.parse($window.localStorage.orders || "[]");
				var idx = _.findIndex($scope.orders, function(o){return o._id == $scope.order._id;});
				if(idx > -1){
					$scope.orders.splice(idx,1,$scope.order);
				}else{
					$scope.orders.push($scope.order);	
				}
				$window.localStorage.orders =  JSON.stringify($scope.orders);
			}

			$scope.drop = function(){
				$scope.order.status = "强行关闭";
				$scope.save();
				$location.path("/shipowner_track");
			}

		}]);
	}
});