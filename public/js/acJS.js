var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope, $http) {

    $scope.showDiv = false;
    $scope.showDiv2 = false;
    $scope.showDiv3 = false;

    $scope.envoyerAC = function() {
        var data= {
            nom: $scope.nom,
            competence: $scope.competence,
            niveau: $scope.niveau,
        };
        $http.post('/symfolio/public/index.php/api/ac/add', data).then(function(response) {
            alert("L'AC a bien été créé");
        }, function(response) {
            alert("Erreur d'ajout");
        });
    }

    $scope.supprAC = function() {
        var data = {
            id: $scope.id,
        };
        $http.post('/symfolio/public/index.php/api/ac/del', data).then(function(response) {
            alert("L'AC a bien été supprimé");
        }, function(response) {
            alert('erreur de suppression');
        });
    }

    $scope.envoyerIut = function() {
        var data = {
            nom: $scope.nom,
        };
        $http.post('/symfolio/public/index.php/api/iut/add', data).then(function(response) {
            alert("L'IUT a bien été créé");
        }, function(response) {
            alert("erreur d'ajout");
        });
    }

    $scope.supprIut = function() {
        var data = {
            id: $scope.id,
        };
        $http.post('/symfolio/public/index.php/api/iut/del', data).then(function(response) {
            alert("L'IUT a bien été supprimé");
        }, function(response) {
            alert('erreur de suppression');
        });
    }

    $scope.envoyerUser = function() {
        var data = {
            email: $scope.email,
            pwd: $scope.pwd,
            iut: $scope.iut,
            niveau: $scope.niveau,
            desc: $scope.desc,
            role: $scope.role
        };
        $http.post('/symfolio/public/index.php/api/user/add', data).then(function(response) {
            alert("L'utilisateur a bien été créé");
        }, function(response) {
            alert("Erreur d'ajout");
        });
    }

    $scope.supprUser = function() {
        var data = {
            id: $scope.id,
        };
        $http.post('/symfolio/public/index.php/api/user/del', data).then(function(response) {
            alert("L'utilisateur a bien été supprimé");
        }, function(response) {
            alert('Erreur lors de la suppression');
        });
    }
});