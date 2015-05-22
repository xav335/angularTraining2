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

    app.factory('getTousCoureurs',['Coureurs','$q','CreeNomComplet', function(Coureurs,$q,CreeNomComplet){
        var differe = $q.defer();
        
        Coureurs.query(function(runners){
            differe.resolve(runners);
            angular.forEach(runners, function(runner){
                runner.fullName = CreeNomComplet(runner.firstName,runner.lastName);
            });
        },function(error){
            differe.reject('Erreur : '+ error);
        }); 
        return differe.promise;
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