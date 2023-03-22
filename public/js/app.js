// public/app.js

angular.module('symfolio', [])
.controller('UserController', ['$http', function($http) {
    var vm = this;

    // Récupérez les données de l'API REST
    $http.get('/profil/noter').then(function(response) {
        vm.projets = response.data;
    });
}]);
