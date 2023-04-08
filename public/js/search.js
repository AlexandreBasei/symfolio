// public/js/controllers/search.js

angular.module('myApp').factory('SearchService', function($http) {
    var urlBase = '/search';

    var SearchService = {};

    SearchService.search = function(searchTerm) {
        return $http.get(urlBase, {params: {q: searchTerm}});
    };

    return SearchService;
});

angular.module('myApp').controller('SearchController', function($scope, SearchService) {
    $scope.results = [];

    $scope.search = function() {
        SearchService.search($scope.searchTerm)
            .then(function(response) {
                $scope.results = response.data;
            });
    };
});