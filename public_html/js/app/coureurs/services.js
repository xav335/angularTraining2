(function(){
    var app = angular.module('Cap.coureurs.services', ['ngResource']);

    app.factory ('CreeNomComplet', function(){
        return function(prenom, nom){
            return prenom+' '+nom;
        };
    });

    app.service('Coureurs',['$resource', function($resource){
        return $resource('/api/runners/:id',{id:'@id'});    
    }]);

    app.service('ParticipationsCoureur',['$resource', function($resource){
        return $resource('/api/runners/:id/entrants',{id:'@runnerId'});    
    }]);

    app.service('Courses',['$resource', function($resource){
        return $resource('/api/runs/:id',{id:'@id'});    
    }]);

    app.service('Evenements',['$resource', function($resource){
        return $resource('/api/events/:id',{id:'@id'});    
    }]);

    app.factory('getTousCoureurs',['Coureurs','$q','CreeNomComplet', function(Coureurs,$q,CreeNomComplet){
        var differe = $q.defer();
        
        Coureurs.query(function(runners){
            angular.forEach(runners, function(runner){
                runner.fullName = CreeNomComplet(runner.firstName,runner.lastName);
            });
            differe.resolve(runners);
        },function(error){
            differe.reject('Erreur : '+ error);
        }); 
        return differe.promise;
    }]);

    app.factory('getUnCoureur', ['Coureurs','$q','CreeNomComplet',function(Coureurs,$q,CreeNomComplet){
        return function (id){
            var differe = $q.defer();
            Coureurs.get({id:id}, function(runner){
                runner.fullName = CreeNomComplet(runner.firstName,runner.lastName);
                differe.resolve(runner);
            },function(error){
                differe.reject('Erreur : '+ error);
            }); 
            
            return differe.promise;
        };
    }]);

    app.factory('getParticipationsUnCoureur', ['ParticipationsCoureur','$q',function(ParticipationsCoureur,$q){
        return function (id){
            //si transformation du runTime passer par $q
            //var differe = $q.defer();
             return ParticipationsCoureur.query({id:id}).$promise;
        };
    }]);

    app.factory('getSomeRuns', ['Courses','$q',function(Courses,$q){
        return function (ids){
             return Courses.query({id:ids}).$promise;
        };
    }]);   

    app.factory('getSomeEvents', ['Evenements','$q',function(Evenements,$q){
        return function (ids){
             return Evenements.query({id:ids}).$promise;
        };
    }]); 
/*
    app.factory ('getTousCoureurs', ['$http','CreeNomComplet', function($http,CreeNomComplet){
        //var toto = [{"id":"1","firstName":"Jean-Marc","lastName":"Martin","gender":"M","birthDate":{"date":"1974-03-02 00:00:00.000000","timezone_type":3,"timezone":"Europe\/Berlin"},"ffaId":"PR123654","picture":null},{"id":"2","firstName":"Ir\u00e8ne","lastName":"Cazaux","gender":"F","birthDate":{"date":"1975-06-15 00:00:00.000000","timezone_type":3,"timezone":"Europe\/Berlin"},"ffaId":"L987654","picture":null},{"id":"4","firstName":"Annie","lastName":"Petit","gender":"J","birthDate":{"date":"1972-06-11 00:00:00.000000","timezone_type":3,"timezone":"Europe\/Berlin"},"ffaId":"L000000","picture":null},{"id":"103","firstName":"John","lastName":"Smith","gender":"M","birthDate":{"date":"1977-07-15 00:00:00.000000","timezone_type":3,"timezone":"Europe\/Berlin"},"ffaId":"","picture":null},{"id":"154","firstName":"Benjamin","lastName":"Button","gender":"M","birthDate":{"date":"1978-05-06 00:00:00.000000","timezone_type":3,"timezone":"Europe\/Berlin"},"ffaId":null,"picture":null},{"id":"155","firstName":"Milo","lastName":"Gentile","gender":"M","birthDate":{"date":"1974-03-23 00:00:00.000000","timezone_type":3,"timezone":"Europe\/Berlin"},"ffaId":null,"picture":null}];
         //console.log(toto);
         //return toto;
            var promisedRunners = $http.get('/api/runners');
            promisedRunners.success(function(data){
                angular.forEach(data, function(runner){
                    runner.fullName = CreeNomComplet(runner.firstName,runner.lastName);
                });
            });
            return promisedRunners;
       
    }]);
    
     app.factory ('getUnCoureur', ['$http', function($http){
        return function(id){     
            var promisedRunners = $http.get('/api/runners/'+id);
            return promisedRunners;
        };    
         
     }]);
     
     */
})();