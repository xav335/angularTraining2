
(function(){
    var app = angular.module('CapApp', ['ngRoute','ngResource','Cap.coureurs.controller','Cap.evenements.controller','Cap.coureurs.services','egb.filters']);

    app.config(['$routeProvider', function($routeProvider){
        $routeProvider
        .when('/', {
            templateUrl:'js/app/partials/coureurs.html',
            controller:'CoureursController',
             resolve:{
                coureurs:['getTousCoureurs', function(getTousCoureurs){
                    return getTousCoureurs;    
                }]
            }
        })
        .when('/evenements', {
            templateUrl:'js/app/partials/evenements.html',
            controller:'EvenementsController'
        }) 
        .when('/coureur/:id', {
            templateUrl:'js/app/partials/fiche.html',
            controller:'CoureurController',
            resolve:{
                unCoureur:['$route','getUnCoureur', function($route,getUnCoureur){
                    return getUnCoureur($route.current.params.id);    
                }]
            }
        }) 
        .otherwise('/'); 

    }]);
    
})();

