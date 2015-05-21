(function(){
    var app = angular.module('Cap.evenements.controller', ['Cap.evenements.services']);
    
    //le ['$scope', est ajouter au cas ou on minififirait le script
    // afin que scope ne soit pas écrasé mais inutile si gardé en clair.
    app.controller('EvenementsController', ['$scope', 'getTousEvenements',function($scope,getTousEvenements){
        
        $scope.evenements = getTousEvenements;
    }]);

    
    
})();
