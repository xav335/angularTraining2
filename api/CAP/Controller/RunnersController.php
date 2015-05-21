<?php
namespace CAP\Controller;
use CAP\Model\Mapper\RunnerMapper;
use CAP\Model\Mapper\CategoryMapper;
use CAP\Model\Runner;

/**
 * Description of RunnerController
 *
 * @author Eric
 */
class RunnersController extends AbstractController{
    private $runnerMapper;
    private $categorieMapper;
    
    public function getRunnerMapper(){
        if(! $this->runnerMapper ){
            $this->runnerMapper = new RunnerMapper();
        }
        return $this->runnerMapper;
    }
    
    public function getCategorieMapper(){
        if(! $this->categorieMapper ){
            $this->categorieMapper = new CategoryMapper();
        }
        return $this->categorieMapper;
    }
    
    
    public function getAllAction(){
        $res = $this->getRunnerMapper()->fetchAllRunners();
        echo json_encode($res);
    }
    
    public function getAction(){
        $params = $this->getParams();
        $id = $params['id'];
        
        try{
            $res = $this->getRunnerMapper()->getRunner($id);
        }catch(\Exception $e){
            if($e->getMessage() == "Row $id not found"){
                header('HTTP/1.0 404 Not Found');
                exit;
            }
        }
        $catMap = $this->getCategorieMapper();
        $cat = $catMap->getCategoryFromBirthDate($res->birthDate);
        $res->categorie = $cat->name;
        echo json_encode($res);
        
    }
    
    public function deleteAction(){
        $params = $this->getParams();
        if(isset($params['id'])){
            $id = $params['id'];
        }else{
            echo json_encode(array(
                'data' => 'undeleted',
                'errors' => 'No id provided',
            ));
            return;
        }
        
        if($this->getRunnerMapper()->deleteRunner($id)){
            echo json_encode(array(
                'data' => 'deleted'
            ));
                
        }else{
            echo json_encode(array(
                'data' => 'undeleted',
                'errors' => 'Wrong id provided',
            ));
        }
        
    }
    
    public function addAction(){
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oRnr = json_decode($json);
            $rnr = new Runner((array)$oRnr);
        }else{
            $params = $this->getParams();
            $rnr = new Runner($params);
        }
        
        $map = $this->getRunnerMapper();
        $id = $map->saveRunner($rnr);
        $rtrvRnr = $map->getRunner($id);
        echo json_encode( $rtrvRnr );
    }
    
    public function updateAction(){
        
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oRnr = json_decode($json);
            $rnr = new Runner((array)$oRnr);
        }else{
            $params = $this->getParams();
            $rnr = new Runner($params);
        }
        
        $map = $this->getRunnerMapper();
        $map->saveRunner($rnr);
        $rtrvRnr = $map->getRunner($rnr->id);
        echo json_encode( $rtrvRnr );
    }
}
