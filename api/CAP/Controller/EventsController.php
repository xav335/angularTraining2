<?php

namespace CAP\Controller;

use CAP\Model\Mapper\EventMapper;
use CAP\Model\Mapper\RunMapper;

use CAP\Model\Event;
//use CAP\View\View;

/**
 * Description of Event
 *
 * @author Eric
 */
class EventsController extends AbstractController{
    private $eventMapper;
    
    private function getEventMapper(){
        if( ! $this->eventMapper )
            $this->eventMapper = new EventMapper();
        return $this->eventMapper;
    }
    
    public function getAllAction(){
        $map = $this->getEventMapper();
        $res = $map->fetchAllEvents();
        echo json_encode($res);
    }
    
    public function getSomeFromIdsAction($ids){
        $map = $this->getEventMapper();
        $res = $map->fetchEventsFromIds($ids);
        echo json_encode($res);
    }

    public function getAction(){
        $map = $this->getEventMapper();
        $id = (int)$_GET['id']; 
        try{
            $evt = $map->getEvent($id);
        }catch(\Exception $e){
            if($e->getMessage() == "Row $id not found"){
                header("HTTP/1.0 404 Not Found"); 
                exit;
            }
        }
        echo json_encode($evt);
    }
    
    public function deleteAction(){
        $params = $this->getParams();
        $map = $this->getEventMapper();
        if(isset($params['id'])){
            $id = (int)$params['id'];
            if($map->deleteEvent($id)){
                echo json_encode('deleted');
                return;
            }else{
                $err = 'Wrong id provided';
            }
        }else{
            $err = 'No id provided';
        }
        $res = array(
            'data' => 'undeleted',
            'errors' => $err,
        );
        echo json_encode($res);
    }
    
    public function addAction(){
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oEvt = json_decode($json);
            $evt = new Event((array)$oEvt);
        }else{
            $params = $this->getParams();
            $evt = new Event($params);
        }
        
        $map = new EventMapper();
        $id = $map->saveEvent($evt);
        $rtrvEvt = $map->getEvent($id);
        echo json_encode($rtrvEvt);
    }
    
    public function updateAction(){
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oEvt = json_decode($json);
            $evt = new Event((array)$oEvt);
        }else{
            $params = $this->getParams();
            $evt = new Event($params);
        }
        $map = new EventMapper();
        $id = $map->saveEvent($evt);
        
        $rtrvEvt = $map->getEvent($id);
        echo json_encode($rtrvEvt);
    }
}
