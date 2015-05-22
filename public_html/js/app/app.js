
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
                unCoureur:['$route','getUnCoureur','getParticipationsUnCoureur','getSomeRuns','getSomeEvents','$q', 
                        function($route,getUnCoureur,getParticipationsUnCoureur,getSomeRuns,getSomeEvents,$q){
                    var id = $route.current.params.id;
                        //return getUnCoureur($route.current.params.id);    
                    var differe = $q.defer();
                    var toutesPromesses = $q.all([getUnCoureur(id), getParticipationsUnCoureur(id)]);
                    var coureurComplet = {};
                    
                    toutesPromesses.then(function(tousResultats){
                        coureurComplet = tousResultats[0];
                        coureurComplet.results = tousResultats[1];
                        //return coureurComplet;
                    }).then(function(){
                        var ids ='';
                        angular.forEach(coureurComplet.results, function(participation){
                            ids = ids + participation.runId +',';
                        });
                        return getSomeRuns(ids);
                       
                    }).then(function(courses){
                        var eventIds = [];
                        angular.forEach(coureurComplet.results, function(participation,index){
                            participation.runDistance = courses[index].distance;
                            eventIds.push(courses[index].eventId);
                        });
                        
                        //console.log(eventIds);
                        return getSomeEvents(eventIds.join(','));
                        
                    }).then(function(evenements){
                        angular.forEach(coureurComplet.results, function(participation,index){
                            participation.eventName = evenements[index].name;
                            participation.eventDate = evenements[index].date.date.substr(0,10);
                        });
                        
                        
                        differe.resolve(coureurComplet);
                    }).catch(function(error){
                        differe.reject('Impossible de réassembler le coureur '+ error);
                    });
                    return differe.promise;
                }]
            }
        })
        .otherwise('/'); 

    }]);
    
})();

