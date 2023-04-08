// public/js/controllers/search.js

var app = angular.module('myApp', [])
    .config(function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    });

    app.controller("myController", function($scope, $http, $q) {
        $scope.search = "";
        $scope.showResults = false;
        $scope.results = [];
      
        $scope.getResults = function() {
          console.log("search = ", $scope.search); // affiche la valeur saisie dans le champ de recherche
          if ($scope.search.length >= 2) {
            var api1 = $http.get("http://localhost/symfolio/public/index.php/search" + $scope.search);
            var api2 = $http.get("http://127.0.0.1:8000/search/" + $scope.search);
      
            $http.get("http://localhost/symfolio/public/index.php/search/").then(function(response) {
              var results = [];
              for (var i = 0; i < response.length; i++) {
                results = results.concat(response[i].data.slice(0, 3));
              }
              console.log("results = ", results); // affiche les résultats de la recherche
              $scope.results = results;
              $scope.showResults = true;
              console.log(results); 
            }, function(error) {
                console.log("erreur");  
            });
          } else {
            $scope.showResults = false;
          }
        };
      });   