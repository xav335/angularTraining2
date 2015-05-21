(function(){
    var app = angular.module('Cap.coureurs.controller', ['Cap.coureurs.services']);
    
    //le ['$scope', est ajouter au cas ou on minififirait le script
    // afin que scope ne soit pas écrasé mais inutile si gardé en clair.
    app.controller('CoureursController', ['$scope', 'getTousCoureurs',function($scope,getTousCoureurs){
        
        $scope.coureurs = getTousCoureurs;
    }]);

    
    
})();


