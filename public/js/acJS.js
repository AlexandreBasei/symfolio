var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope, $http) {

    $scope.envoyerFormulaire = function() {
        var data = {
            nom: $scope.nom,
            competence: $scope.competence,
            niveau: $scope.niveau
        };
        $http.post('/symfolio/public/index.php/api/ac/add', data).then(function(response) {
            console.log(response.data);
            console.log(data);
        }, function(response) {
            console.log('erreur');
        });
      }
});