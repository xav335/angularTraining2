<?php
namespace CAP\Controller;

use CAP\Model\Mapper\EntrantMapper;
use CAP\Model\Entrant;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntrantController
 *
 * @author Eric
 */
class EntrantsController extends AbstractController{
    private $entrantMapper;
    
    private function getEntrantMapper(){
        if(! $this->entrantMapper){
            $this->entrantMapper = new EntrantMapper();            
        }
        
        return $this->entrantMapper;
    }
    
    public function getAllAction(){
        $res = $this->getEntrantMapper()->fetchAllEntrants();
        echo json_encode($res);
    }

    public function getFromRunnerAction($runnerId){
        $res = $this->getEntrantMapper()->fetchRunnerEntrants($runnerId);
        echo json_encode($res);
    }
    
    public function getAction(){
        $id = (int)$_GET['id'];
        try{
            $res = $this->getEntrantMapper()->getEntrant($id);
        }catch(\Exception $e){
            if($e->getMessage() == "Row $id not found"){
                header('HTTP/1.0 404 Not Found');
                exit;
            }
        }
        echo json_encode($res);
    }
    
    public function deleteAction(){
        $params = $this->getParams();
        
        if(isset($params['id'])){
            if( $this->getEntrantMapper()->deleteEntrant((int)$params['id']) ){
                echo json_encode(array('data' => 'deleted'));
            }else{
                echo json_encode(array('data' => 'undeleted', errors => 'Wrong id provided'));
                return;
            }
        }else{
            echo json_encode(array('data' => 'undeleted', errors => 'No id provided'));
            return;
        }
    }
    
    public function addAction(){
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oEnt = json_decode($json);
            $ent = new Entrant((array)$oEnt);
        }else{
            $params = $this->getParams();
            $ent = new Entrant($params);
        }
        
        $map = $this->getEntrantMapper();
        $id = $map->saveEntrant($ent);
        $rtrvEnt = $map->getEntrant($id);

        echo json_encode($rtrvEnt);
    }
    
    public function updateAction(){
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oEnt = json_decode($json);
            $ent = new Entrant((array)$oEnt);
        }else{
            $params = $this->getParams();
            $ent = new Entrant($params);
        }
        
        $map = $this->getEntrantMapper();
        $map->saveEntrant($ent);
        $rtrvEnt = $map->getEntrant($ent->id);

        echo json_encode($rtrvEnt);        
    }
}
