<?php
namespace CAP\Controller;

use CAP\Model\Mapper\RunMapper;
use CAP\Model\Run;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RunController
 *
 * @author Eric
 */
class RunsController extends AbstractController{
    private $runMapper;
    
    private function getRunMapper(){
        if(! $this->runMapper){
            $this->runMapper = new RunMapper();
        }
        return $this->runMapper;
    }
    
    public function getAllAction(){
        $res = $this->getRunMapper()->fetchAllRuns();
        echo json_encode($res);
    }
    
    public function getSomeFromIdsAction($ids){
        $res = $this->getRunMapper()->fetchRunsFromIds($ids);
        echo json_encode($res);
    }
    
    public function getAction($id){
        $id = (int)$id;
        try{
            $res = $this->getRunMapper()->getRun($id);
        }catch(\Exception $e){
            if($e->getMessage() == "Row $id not found"){
                header("HTTP/1.0 404 Not Found"); 
                exit;
            }
        }
        echo json_encode($res);
    }
    
    public function getFromEventAction($eventId){
        $res = $this->getRunMapper()->fetchEventsRuns($eventId);
        echo json_encode($res);
    }

    public function deleteAction(){
        $params = $this->getParams();
        if(isset($params['id'])){
            if($this->getRunMapper()->delete((int)$params['id'])){
                echo json_encode('deleted');
                return;
            }else{
                echo json_encode(array('undeleted','errors' => 'Wrong id provided'));
                return;
            }
        }else{
            echo json_encode(array('undeleted','errors' => 'No id provided'));
            return;
        }
    }
    
    public function addAction(){
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oRun = json_decode($json);
            $run = new Run((array)$oRun);
        }else{
            $params = $this->getParams();
            $run = new Run($params);
        }

        $map = $this->getRunMapper();
        $id = $map->saveRun($run);
        $rtrvRun = $map->getRun($id);
        
        echo json_encode($rtrvRun);
    }
    
    public function updateAction(){
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oRun = json_decode($json);
            $run = new Run((array)$oRun);
        }else{
            $params = $this->getParams();
            $run = new Run($params);
        }
        $map = $this->getRunMapper();
        $map->saveRun($run);
        $rtrvRun = $map->getRun($run->id);
        
        echo json_encode($rtrvRun);
    }
}
