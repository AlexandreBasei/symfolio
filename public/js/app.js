var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope) {
  $scope.formVisible = false;

  $scope.showForm = function() {
    if ($scope.formVisible == false) {
        $scope.formVisible = true;
    }
    else{
        $scope.formVisible = false;
    }
    
  };
});