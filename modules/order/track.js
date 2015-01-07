define(function (require, exports, module) {
	'use strict';
	require('baidumap');
	module.exports = function(app){
		app.register.directive("baiduMap", function(){
			var checkMandatory = function(prop, desc) {
				if (!prop) {throw new Error(desc);}
			};

			var defaults = function(dest, src) {
				for (var key in src) {
					if (typeof dest[key] === 'undefined') {dest[key] = src[key];}
				}
			};	
				
			return {
				restrict: 'E',
				scope: {
					'options': '='
				},
				link: function($scope, element, attrs) {

					var defaultOpts = {
						navCtrl: true,
						scaleCtrl: true,
						overviewCtrl: true,
						enableScrollWheelZoom: true,
						zoom: 10
					};

					var opts = $scope.options;
					defaults(opts, defaultOpts);

					checkMandatory(opts.center, 'options.center must be set');
					checkMandatory(opts.center.longitude, 'options.center.longitude must be set');
					checkMandatory(opts.center.latitude, 'options.center.latitude must be set');
					checkMandatory(opts.city, 'options.city must be set');

					// create map instance
					var map = new BMap.Map(element.find('div')[0]);

					// init map, set central location and zoom level
					map.centerAndZoom(new BMap.Point(opts.center.longitude, opts.center.latitude), opts.zoom);
					if (opts.navCtrl) {
						// add navigation control
						map.addControl(new BMap.NavigationControl());
					}
					if (opts.scaleCtrl) {
						// add scale control
						map.addControl(new BMap.ScaleControl());
					}
					if (opts.overviewCtrl) {
						//add overview map control
						map.addControl(new BMap.OverviewMapControl());
					}
					if (opts.enableScrollWheelZoom) {
						//enable scroll wheel zoom
						map.enableScrollWheelZoom();
					}
					// set the city name
					map.setCurrentCity(opts.city);

					if (!opts.markers) {
						return;
					}
					//create markers
					var openInfoWindow = function(infoWin) {
						return function() {
							this.openInfoWindow(infoWin);
						};
					};
					for (var i in opts.markers) {
						var marker = opts.markers[i];
						var pt = new BMap.Point(marker.longitude, marker.latitude);
						var marker2;
						if (marker.icon) {
							var icon = new BMap.Icon(marker.icon, new BMap.Size(marker.width, marker.height));
							marker2 = new BMap.Marker(pt, {
								icon: icon
							});
						} else {
							marker2 = new BMap.Marker(pt);
						}

						// add marker to the map
						map.addOverlay(marker2); // 将标注添加到地图中
						if (!marker.title && !marker.content) {
							return;
						}
						var infoWindow2 = new BMap.InfoWindow("<p>" + (marker.title ? marker.title: '') + "</p><p>" + (marker.content ? marker.content: '') + "</p>", {
							enableMessage: !!marker.enableMessage
						});
						marker2.addEventListener("click", openInfoWindow(infoWindow2));
					}

				},
				template: '<div style="width: 100%; height: 100%;"></div>'
			};
		});
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
		                icon: 'assets/img/car.png',
		                width: 49,
		                height: 60,
		                title: '王五 13042345607',
		                content: '运单号123456'
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