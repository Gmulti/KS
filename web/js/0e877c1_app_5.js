(function () {

    'use strict';

    angular

        .module('app', ['http-auth-interceptor'])

        .factory('AuthenticationService', function ($rootScope, $http, authService, $httpBackend) {

            return {
                login:          function (credentials) {

                    $http
                        .post('/login_check', credentials, { 
                            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                            transformRequest: function(obj) {
                                var str = [];
                                for(var p in obj)
                                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                                return str.join("&");
                            },
                        	ignoreAuthModule: true,

                        })
                        .success(function (data, status, headers, config) {
                            $http.defaults.headers.common.Authorization = 'Bearer ' + data.token;  // Step 1
                            authService.loginConfirmed(data, function (config) {  // Step 2 & 3
                                config.headers.Authorization = 'Bearer ' + data.token;
                                $rootScope.$broadcast('event:auth-login-complete');
                                return config;
                            });
                        })
                        .error(function (data, status, headers, config) {
                            $rootScope.$broadcast('event:auth-login-failed', status);
                        });
                },
                logout:         function (user) {
                    delete $http.defaults.headers.common.Authorization;
                    $rootScope.$broadcast('event:auth-logout-complete');
                }
            };
        })

        .controller('loginCtrl', function ($scope, AuthenticationService) {

            $scope.credentials = {
				_csrf_token: $("#_csrf_token").val()
            };


            $scope.submit = function (credentials) {
                AuthenticationService.login(credentials);
            };

        })

    ;

})();
