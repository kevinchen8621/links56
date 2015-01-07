define(function (require, exports, module) {
	'use strict';
	module.exports = function(app){
		app.register.controller('OrderEditCtl', ['$scope', '$rootScope', '$routeParams', '$location', '$window', function($scope, $rootScope, $routeParams, $location, $window){
			$scope.new_order = function(){
				$scope.order = {
					cust: {},
					from: {},
					to: {},
					goods:[],
					fees:[],
					dispatchs:[],
					status: "待做运输计划",
					create_at: Date.now(),
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
				$scope.order._id = $scope.order._id || Date.now();
				$scope.orders = JSON.parse($window.localStorage.orders || "[]");
				var idx = _.findIndex($scope.orders, function(o){return o._id == $scope.order._id;});
				if(idx > -1){
					$scope.orders.splice(idx,1,$scope.order);
				}else{
					$scope.orders.push($scope.order);	
				}
				$window.localStorage.orders =  JSON.stringify($scope.orders);
				$location.path("/order_dispatch/"+$scope.order._id);
			}
			$scope.drop = function(){
				$scope.order.status = "不受理";
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
			};
			$scope.del_goods = function(name){
				$scope.order.goods = _.reject($scope.order.goods, function(item){return item.name == name});
			};
			$scope.new_fee = function(){
				console.log($scope.order);
				$scope.order.fees.push({
					name:"",
					fee:0,
					description:"",
				});
			};
			$scope.del_fee = function(name){
				$scope.order.fees = _.reject($scope.order.fees, function(item){return item.name == name});
			};
			$scope.new_dispatch = function(){
				$scope.order.dispatchs.push({
					car:"",
					num:1,
					fee:0,
					isinsurance:false,
					insurance:0,
					ispublish:false,
					description:"",
					goods: [],
				});
			};
			$scope.del_dispatch = function(car){
				$scope.order.dispatchs = _.reject($scope.order.dispatchs, function(item){return item.car == car});
			};
			$scope.new_sub_goods = function(car){
				var dispatch =  _.find($scope.order.dispatchs, function(item){return item.car == car;});
				if(dispatch){
					dispatch.goods.push({
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
			};
			$scope.del_sub_goods = function(car, name){
				var dispatch =  _.find($scope.order.dispatchs, function(item){return item.car == car;});
				if(dispatch){
					dispatch.goods = _.reject(dispatch.goods, function(item){return item.name == name});
				}
			};
		}]);
	}
});