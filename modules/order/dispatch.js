define(function (require, exports, module) {
	'use strict';
	module.exports = function(app){
		app.register.controller('OrderDispatchCtl', ['$scope', '$rootScope', '$routeParams', '$location', '$window', function($scope, $rootScope, $routeParams, $location, $window){
			$scope.new_order = function(){
				$scope.order = {
					cust: {},
					from: {},
					to: {},
					goods:[],
					pickup_at: new Date(),
					send_at: new Date(),
				};
				$scope.isDisable = false;
				$scope.lbl_order = "新建订单";
				$scope.lbl_drop = "不受理";
			}
			if($routeParams.id){
				$scope.orders = JSON.parse($window.localStorage.orders || "[]");
				$scope.order = _.find($scope.orders, function(item){ return item._id == $routeParams.id;});
				if(!$scope.order) $scope.new_order();
				$scope.isDisable = true;
				$scope.lbl_order = "订单详情";
				$scope.lbl_drop = $scope.order.status == "受理" ? "强行关闭": "不受理";
			}else{
				$scope.new_order();
			}
			$scope.custs = JSON.parse($window.localStorage.custs || "[]");
			$scope.froms = JSON.parse($window.localStorage.froms || "[]");
			$scope.tos = JSON.parse($window.localStorage.tos || "[]");

			$scope.edit = function(){
				$scope.isDisable = false;
				$scope.lbl_order = "修改订单";
			}

			$scope.save = function(){
				$scope.order._id = Date.now();
				$scope.orders = JSON.parse($window.localStorage.orders || "[]");
				$scope.orders.push($scope.order);
				$window.localStorage.orders =  JSON.stringify($scope.orders);
				$location.path("/order_edit/"+$scope.order._id);
			}
			$scope.drop = function(){
				$scope.order.status = "不受理";
				$scope.save();
				$scope.new_order();
			}

			$scope.accept = function(){
				$scope.order.status = "受理";
				$scope.save();
				$scope.new_order();
			}

			$scope.new_goods = function(){
				$scope.order.goods.push({
					name:"",
					num:1,
					uweight: 0,
					weight:0,
					l:0,
					w:0,
					h:0,
					volumn:0,
					description:"",
				});
			}
			$scope.del_goods = function(name){
				$scope.order.goods = _.reject($scope.order.goods, function(item){return item.name == name});
			}
		}]);
	}
});