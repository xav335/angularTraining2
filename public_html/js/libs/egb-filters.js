(function(){
   var app = angular.module('egb.filters', []);
   
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

    app.filter('genre', ['$filter', function($filter){
        return function(val){
            switch($filter('lowercase')(val)){
                case 'm':
                    return 'Masculin';
                case 'f':
                    return 'Feminin';
                default:
                    throw 'Sexe inconnu' ;
            }
        };
    }]);
    
})();
