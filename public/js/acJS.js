var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope, $http) {

    $scope.showDiv = false;
    $scope.showDiv2 = false;
    $scope.showDiv3 = false;

    $scope.envoyerAC = function() {
        var data = {
            nom: $scope.nom,
            competence: $scope.competence,
            niveau: $scope.niveau
        };
        $http.post('/symfolio/public/index.php/api/ac/add', data).then(function(response) {
            alert("L'AC a bien été créé");
        }, function(response) {
            console.log("erreur d'ajout");
        });
    }

    $scope.supprAC = function() {
        var data2 = {
            id: $scope.id,
        };
        $http.post('/symfolio/public/index.php/api/ac/del', data2).then(function(response) {
            alert("L'AC a bien été supprimé");
        }, function(response) {
            console.log('erreur de suppression');
        });
    }

    $scope.envoyerIut = function() {
        var data3 = {
            nom: $scope.nom,
        };
        $http.post('/symfolio/public/index.php/api/iut/add', data3).then(function(response) {
            alert("L'IUT a bien été créé");
        }, function(response) {
            console.log("erreur d'ajout");
        });
    }

    $scope.supprIut = function() {
        var data4 = {
            id: $scope.id,
        };
        $http.post('/symfolio/public/index.php/api/iut/del', data4).then(function(response) {
            alert("L'IUT a bien été supprimé");
        }, function(response) {
            console.log('erreur de suppression');
        });
    }
});