
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
                unCoureur:['$route','getUnCoureur','getParticipationsUnCoureur','$q', function($route,getUnCoureur,getParticipationsUnCoureur,$q){
                    var id = $route.current.params.id;
                        //return getUnCoureur($route.current.params.id);    
                    var differe = $q.defer();
                    var toutesPromesses = $q.all([getUnCoureur(id), getParticipationsUnCoureur(id)]);
                    
                    toutesPromesses.then(function(tousResultats){
                        var coureurComplet = tousResultats[0];
                        coureurComplet.results = tousResultats[1];
                        differe.resolve(coureurComplet);
                    });
                    return differe.promise;
                }]
            }
        })
        
        .otherwise('/'); 

    }]);
    
})();

