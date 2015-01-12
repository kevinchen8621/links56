define(function(require, exports, module) {
	"use strict";
	module.exports = function(app) {
		app.directive("baiduMap", function(){
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
		app.directive('sideNavigation', function() {
			return {
				restrict: "A",
				link: function(scope, element) {
                			var toggle = true;
                			element.find("li.active").has("ul").children("ul").addClass("collapse in");
                			element.find("li").not(".active").has("ul").children("ul").addClass("collapse");
                			element.find("li").has("ul").children("a").on("click", function(e) {
                				e.preventDefault();
                				$(this).parent("li").toggleClass("active").children("ul").collapse("toggle");
                				if (toggle) {
                					$(this).parent("li").siblings().removeClass("active").children("ul.in").collapse("hide");
                				}
                			});
				}
			}
		})
		.directive("responsiveVideo", function() {
			return {
				restrict: 'A',
				link: function(scope, element) {
					var figure = element;
					var video = element.children();
					video.attr('data-aspectRatio', video.height() / video.width()).removeAttr('height').removeAttr('width')
					//We can use $watch on $window.innerWidth also.
					$(window).resize(function() {
						var newWidth = figure.width();
						video.width(newWidth).height(newWidth * video.attr('data-aspectRatio'));
					}).resize();
				}
			}
		})
		.directive("iboxTools", function($timeout) {
			return {
				restrict: 'A',
				link: function(scope, element) {
					var ibox = element.closest('div.ibox');
					var icon = element.find('i:first');
					var content = ibox.find('div.ibox-content');
					icon.on("click", function(event){
						content.slideToggle(200);
						icon.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
						ibox.toggleClass('').toggleClass('border-bottom');
						$timeout(function() {
							ibox.resize();
							ibox.find('[id^=map-]').resize();
						},50);
					});
				}
			};
		})
		.directive("minimalizaSidebar", function($timeout) {
			return {
				restrict: 'A',
				template: '<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="" ng-click="minimalize()"><i class="fa fa-bars"></i></a>',
				controller: function($scope, $element) {
					$scope.minimalize = function() {
						$("body").toggleClass("mini-navbar");
						if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
							// Hide menu in order to smoothly turn on when maximize menu
							$('#side-menu').hide();
							// For smoothly turn on menu
							$timeout(function() {
								$('#side-menu').fadeIn(500);
							},
							100);
						} else {
							// Remove all inline style from jquery fadeIn function to reset menu state
							$('#side-menu').removeAttr('style');
						}
					}
				}
			};
		})
		.directive("vectorMap", function() {
			return {
				restrict: 'A',
				scope: {
					myMapData: '=',
				},
				link: function(scope, element, attrs) {
					element.vectorMap({
						map: 'world_mill_en',
						backgroundColor: "transparent",
						regionStyle: {
							initial: {
								fill: '#e4e4e4',
								"fill-opacity": 0.9,
								stroke: 'none',
								"stroke-width": 0,
								"stroke-opacity": 0
							}
						},
						series: {
							regions: [{
								values: scope.myMapData,
								scale: ["#1ab394", "#22d6b1"],
								normalizeFunction: 'polynomial'
							}]
						},
					});
				}
			}
		})
		.directive("morrisArea", function() {
			return {
				restrict: 'A',
				scope: {
					chartOptions: '='
				},
				link: function(scope, element, attrs) {
					var chartDetail = scope.chartOptions;
					chartDetail.element = attrs.id;
					var chart = new Morris.Area(chartDetail);
					return chart;
				}
			}
		})
		.directive("morrisBar", function() {
			return {
				restrict: 'A',
				scope: {
					chartOptions: '='
				},
				link: function(scope, element, attrs) {
					var chartDetail = scope.chartOptions;
					chartDetail.element = attrs.id;
					var chart = new Morris.Bar(chartDetail);
					return chart;
				}
			}
		})
		.directive("morrisLine", function() {
			return {
				restrict: 'A',
				scope: {
					chartOptions: '='
				},
				link: function(scope, element, attrs) {
					var chartDetail = scope.chartOptions;
					chartDetail.element = attrs.id;
					var chart = new Morris.Line(chartDetail);
					return chart;
				}
			}
		})
		.directive("morrisDonut", function() {
			return {
				restrict: 'A',
				scope: {
					chartOptions: '='
				},
				link: function(scope, element, attrs) {
					var chartDetail = scope.chartOptions;
					chartDetail.element = attrs.id;
					var chart = new Morris.Donut(chartDetail);
					return chart;
				}
			}
		})
		.directive("sparkline", function() {
			return {
				restrict: 'A',
				scope: {
					sparkData: '=',
					sparkOptions: '=',
				},
				link: function(scope, element, attrs) {
					scope.$watch(scope.sparkData,
					function() {
						render();
					});
					scope.$watch(scope.sparkOptions,
					function() {
						render();
					});
					var render = function() {
						$(element).sparkline(scope.sparkData, scope.sparkOptions);
					};
				}
			}
		})
		.directive("icheck", function($timeout) {
			return {
				restrict: 'A',
				require: 'ngModel',
				link: function($scope, element, $attrs, ngModel) {
					return $timeout(function() {
						var value;
						value = $attrs['value'];

						$scope.$watch($attrs['ngModel'],
						function(newValue) {
							$(element).iCheck('update');
						})

						return $(element).iCheck({
							checkboxClass: 'icheckbox_square-green',
							radioClass: 'iradio_square-green'

						}).on('ifChanged',
						function(event) {
							if ($(element).attr('type') === 'checkbox' && $attrs['ngModel']) {
								$scope.$apply(function() {
									return ngModel.$setViewValue(event.target.checked);
								});
							}
							if ($(element).attr('type') === 'radio' && $attrs['ngModel']) {
								return $scope.$apply(function() {
									return ngModel.$setViewValue(value);
								});
							}
						});
					});
				}
			};
		})
		.directive("ionRangeSlider", function() {
			return {
				restrict: 'A',
				scope: {
					rangeOptions: '='
				},
				link: function(scope, elem, attrs) {
					elem.ionRangeSlider(scope.rangeOptions);
				}
			}
		})
		.directive("dropZone", function() {
			return function(scope, element, attrs) {
				element.dropzone({
					url: "/upload",
					maxFilesize: 100,
					paramName: "uploadfile",
					maxThumbnailFilesize: 5,
					init: function() {
						scope.files.push({
							file: 'added'
						});
						this.on('success',
						function(file, json) {});
						this.on('addedfile',
						function(file) {
							scope.$apply(function() {
								alert(file);
								scope.files.push({
									file: 'added'
								});
							});
						});
						this.on('drop',
						function(file) {
							alert('file');
						});
					}
				});
			}
		})
		.directive("fancyBox", function() {
			return {
				restrict: 'A',
				link: function(scope, element) {
					element.fancybox({
						openEffect: 'none',
						closeEffect: 'none'
					});
				}
			}
		})
	}
});