var app = angular.module('myApp', [])
    .config(function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    });

app.controller("myController", function($scope, $http, $q) {
    var urlBase = 'http://localhost/symfolio/public/index.php/search/';

    $scope.searchTerm = "";
    $scope.showResults = false;
    $scope.results = [];
    

    $scope.getResults = function() {
      console.log("RESULTSSSSS", $scope.results);
        console.log("search = ", $scope.searchTerm); // affiche la valeur saisie dans le champ de recherche
        if ($scope.searchTerm.length >= 2) {
          $http.get(urlBase + $scope.searchTerm)
          .then(function(response) {
              var results = response.data.slice(0, 3);
              $scope.results = results;
              $scope.showResults = true;
          })
          .catch(function(error) {
              console.log("Error:", error);
          });
        }
      };
    });
