(function(){
    var app = angular.module('Cap.coureurs.controller', []);
    
    //le ['$scope', est ajouter au cas ou on minififirait le script
    // afin que scope ne soit pas écrasé mais inutile si gardé en clair.
    app.controller('CoureursController', ['$scope', 'coureurs',function($scope,coureurs){
        $scope.coureurs = coureurs; //.data;
        
    }]);

    app.controller('CoureurController', ['$scope', 'unCoureur',function($scope,unCoureur){
        $scope.coureur = unCoureur; //.data;
        $scope.infoActif = true;
        $scope.selectionPanneau = function(infoActif){
            if (infoActif){
                $scope.infoActif = false;
                $scope.commActif = true;
            } else {
                $scope.infoActif = true;
                $scope.commActif = false;
            }
        };
    }]);
    
})();


