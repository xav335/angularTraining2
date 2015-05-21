
(function(){
    var app = angular.module('CapApp', ['ngRoute','Cap.coureurs.controller','Cap.evenements.controller']);

    app.config(['$routeProvider', function($routeProvider){
        $routeProvider
        .when('/', {
            templateUrl:'js/app/partials/coureurs.html',
            controller:'CoureursController'
        })
        .when('/route2', {
            templateUrl:'js/app/partials/evenements.html',
            controller:'EvenementsController'
        }) 
        .otherwise('/'); 

    }]);
    
})();

