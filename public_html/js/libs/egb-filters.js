(function(){
   var app = angular.module('CollectiveTodos.filters', []);
   
   app.filter('importance', ['$sce', function($sce){
        $sce.trustAsHtml();
        return function(input,icones){
            icones = icones || false;
            if(!icones){
                switch(input){
                    case 1:
                        return 'Haute';
                    case 2:
                        return 'Moyenne';
                    case 3:
                        return 'Basse';
                    default:
                        return input;
                }
            }else{
                switch(input){
                    case 1:
                        return 'glyphicon glyphicon-thumbs-up text-danger';
                    case 2:
                        return 'glyphicon glyphicon-hand-right';
                    case 3:
                        return 'glyphicon glyphicon-thumbs-down text-success';
                    default:
                        return input;
                }
            }
        };
    }]);

    
})();
