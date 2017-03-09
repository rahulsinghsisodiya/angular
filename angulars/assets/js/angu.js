    var base_url = 'http://localhost/work/angulars/angulars/';
    var app = angular.module('myApp', []);
    app.controller('myCtrl', function($scope) {
        $scope.firstName = "John";
        $scope.lastName = "Doe";
    });
    app.controller('hov', function($scope) {
        $scope.count = 0;
    });
    app.controller('addproduct', ['$scope', '$http', function($scope, $http) {

            //$scope.products = ["Milk", "Bread", "Cheese"];
            var re = $http.get(base_url + 'getclient_json');
            re.success(function(data) {
                $scope.data = data;
                angular.forEach($scope.data, function(value) {
                    $scope.productsxcx = value;
                });
                $scope.products = data;
            });
            $scope.addItem = function() {

                var dataObj = {
                    name: $scope.addMe,
                };
                var res = $http.post(base_url + 'savecompany_json', dataObj);
                res.success(function(data, status, headers, config) {
                    $scope.message = data;
                });

            }
        }]);


    