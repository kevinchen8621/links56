define(function (require, exports, module) {
	"use strict";
	var app = angular.module("app", [
		"angular-lazyload", 
		"ngRoute",
		"ngAnimate",
	]);
	require('./services.js')(app);
	require('./directives.js')(app);
	require('./config.js')(app);
	app.run(['$lazyload', "$rootScope", "$window", "$location", "$rest", function($lazyload, $rootScope, $window, $location, $rest){
		$lazyload.init(app);
		app.register = $lazyload.register;
		$rootScope.isActive = function (viewLocation) { 
			return $location.path().substr(0, viewLocation.length) === viewLocation;
		};
		$rootScope.templates = [
			{_id: "111122223333444455551001", domain:"open163.xinziji.com"},
		];


		$rootScope.user = JSON.parse($window.localStorage.user || "{}");
		//没有之前赋值一个假的
		$rootScope.user = {_id:1, username:"admin", email:"11@11.com", mobile:"13041666292"};
		/*
		if(_.isEmpty($rootScope.user)){
			return $window.location.href = "/landing_page";
		}else{
			$rest.get_site(function(site){
				$rootScope.site = site;
				if(site.owner_id == $rootScope.user._id){
					$rootScope.is_admin = true;
				}else{
					$rest.get_member_by_refid($rootScope.user._id, function(member){
						console.log(member);
						$rootScope.member = member;
						$rootScope.is_admin = _.contains($rootScope.member.roles, "admin");
						$rootScope.is_editor = _.contains($rootScope.member.roles, "editor") || _.contains($rootScope.member.roles, "admin");
					});
				}
			});
		}
		*/
		$rootScope.signout= function(){
			delete $rootScope.user;
			delete $window.sessionStorage.token;
			delete $window.sessionStorage.user;
			delete $window.localStorage.token;
			delete $window.localStorage.user;
			$window.location.href = "/";
		}

	}]);
	module.exports = app;
});