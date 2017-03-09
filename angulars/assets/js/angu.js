    var base_url = 'http://localhost/work/paint/';
    var app = angular.module('myApp', []);
    app.controller('myCtrl', function($scope) {
        $scope.firstName = "John";
        $scope.lastName = "Doe";
    });
    app.controller('hov', function($scope) {
        $scope.count = 0;
    });
    app.controller('addproduct', ['$scope', '$http', function($scope, $http) {
            $scope.products = ["Milk", "Bread", "Cheese"];
            $scope.addItem = function() {
                 $scope.products.push($scope.addMe);
                var dataObj = {
                    name: $scope.addMe,
                };
                var res = $http.post(base_url + 'savecompany_json', dataObj);
                res.success(function(data, status, headers, config) {
                    $scope.message = data;
                });
            }
        }]);


    