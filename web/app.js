// (function () {

//     'use strict';

//     var services = angular.module('services', []); 
//     var controllers = angular.module('controllers', []);
    
//     var app = angular.module('app', ['http-auth-interceptor','controllers', 'services','ui.router']);

//     controllers.controller('loginCtrl', loginCtrl);
//     loginCtrl.$inject = ['$scope','$window', 'AuthenticationService'];

//     angular.module('services')
//         .factory('AuthenticationService',['$rootScope','$http','authService','$window','$httpBackend', function ($rootScope, $http, authService, $window, $httpBackend) {
//             return {
//                 login:  function (credentials) {
//                     $http
//                         .post('/login_check', credentials, { 
//                             headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
//                             transformRequest: function(obj) {
//                                 var str = [];
//                                 for(var p in obj)
//                                 str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
//                                 return str.join("&");
//                             },
//                             ignoreAuthModule: true,

//                         })
//                         .success(function (data, status, headers, config) {
//                             $http.defaults.headers.common.Authorization = 'Bearer ' + data.token;

//                             authService.loginConfirmed(data, function (config) { 
//                                 config.headers.Authorization = 'Bearer ' + data.token;
//                                 return config;
//                             });
                            
//                             $window.localStorage.setItem('jwt', data.token);

//                             $rootScope.$broadcast('event:auth-login-complete');
//                         })
//                         .error(function (data, status, headers, config) {
//                             $rootScope.$broadcast('event:auth-login-failed', status);
//                         });
//                 },
//                 logout:         function (user) {
//                     delete $http.defaults.headers.common.Authorization;
//                     $rootScope.$broadcast('event:auth-logout-complete');
//                 }
//             };
//         }]);


//     function loginCtrl($scope, $window, AuthenticationService){

//         // View Model
//         var vm = this;

//         vm.credentials = {
//             _csrf_token: $("#_csrf_token").val()
//         };

//         $scope.$on('event:auth-login-failed', function () {
//             $scope.errorMessage = 'Identifiants incorrect';
//         });

//         $scope.$on('event:auth-login-complete', function () {
//             $window.location.reload();
//         });

//         vm.submit = function (credentials) {
//             AuthenticationService.login(credentials);
//         };

//     }


// })();
