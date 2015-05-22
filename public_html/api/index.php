<?php
spl_autoload_register(function ($class)
    {
        include '../../api/' . str_replace('\\','/',$class) . '.php';
        //echo '../../api/' . str_replace($class,'\\','/') . '.php';
    });
    
$route = str_replace('/api/','',$_SERVER['REQUEST_URI']);
$route = explode('/',$route);
$params = '';

// Déterminer le contrôleur à appeler et son action
for($i = count($route) - 1; $i>=0;$i--){
    if(!is_numeric($route[$i]) && strPos($route[$i],',') === FALSE){
        $ctl = ucfirst(strtolower($route[$i]));
        $ctlIndex = $i;
        break;
    }else{
        $id = $route[$i];
    }
}
//print_r($ctl);


//
//if(isset($_GET['ctl'])){
//    $ctl = $_GET['ctl'];
//}else{
//    $ctl = 'Err404';
//}

// Vérifier existence contrôleur demandé ou retourner le contrôleur Err404
$className = 'CAP\Controller\\' . $ctl . 'Controller';
if(@class_exists($className)){
    $oCtl = new $className();
}else{
    $oCtl = new CAP\Controller\Err404Controller();
}
//echo get_class($oCtl);
//exit;

// Déterminer l'action du contrôleur selon la méthode utilisée pour l'appel
if(get_class($oCtl) !== 'Err404Controller'){
    switch(strtoupper($_SERVER['REQUEST_METHOD'])){
        case 'GET':
            if(isset($id) && strPos($id,',') !== FALSE){
                $id = explode(',',$id);
            }
            if(isset($id) && !is_array($id)){
                $action = 'getAction';
                $params = $id;
            }else{
                // Déterminer le reste du chemin selon la route (se baser sur le chunk textuel précédent)
                for($i = $ctlIndex - 1; $i>=0; $i--){
                    if(!is_numeric($route[$i])){
                        $ctlPrec = ucfirst(strtolower($route[$i]));
                        break;
                    }else{
                        $idPrec = (int)$route[$i];
                    }
                }
                if( !isset($ctlPrec) && !isset($id) ){
                    $action = 'getAllAction';
                    $params = null;
                }elseif( !isset($ctlPrec) && is_array($id) ){
                    $action = 'getSomeFromIdsAction';
                    $params = $id;
                }else{
                    $action = 'getFrom' . ucfirst(strtolower(substr($ctlPrec,0,-1))) . 'Action';
                    $params = $idPrec;
//                    echo "$action<br />";
//                    echo $params;
//                    exit;
                }
            }
            
            break;
        case 'POST':
            $action = 'addAction';
            break;
        case 'DELETE':
            $action = 'deleteAction';
            break;
        case 'PUT':
            $action = 'updateAction';
            break;
        default:
            $oCtl = new CAP\Controller\Err404Controller();
            $action = 'indexAction';
    }
}

// Vérifiation de l'existence de la méthode
if(!method_exists($oCtl,$action)){
    die('Pas bon : ' . $oCtl ."->" . $action);
    $oCtl = new CAP\Controller\Err404Controller();
    $action = 'indexAction';
}

sleep(rand(0,3));

// Appel action du contrôleur
$oCtl->$action($params);