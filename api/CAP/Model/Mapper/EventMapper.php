<?php
namespace CAP\Model\Mapper;

use CAP\Model\Event;
use CAP\Model\Mapper\AbstractMapper;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventMapper
 *
 * @author Eric
 */
class EventMapper extends AbstractMapper{
    public function __construct() 
    {
        $this->tableName = 'events';
        $this->idField = 'id';
        parent::__construct();
    }
    
    public function fetchAllEvents()
    {
        $events = parent::fetchAll();
        $res = array();
        
        foreach($events as $evt){
            $res[] = $this->mapEvent($evt);
        }
        
        return $res;
    }
    
    public function fetchEventsFromIds($ids){
        $in = implode(',', array_fill(0,count($ids), '?'));
        $sql = "SELECT * FROM events WHERE id IN ($in)";
        $stmt = $this->pdo->prepare($sql);
        foreach($ids as $k => $id){
            $stmt->bindValue(($k+1),$id);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = array();
        foreach($rows as $evt){
            $res[] = $this->mapEvent($evt);
        }
        return $res;
    }
    
    
    public function getEvent($id)
    {
        $evt = parent::get($id);
        if(is_array($evt)){
            $res = $this->mapEvent($evt);
            return $res;
        }else {
            throw new \Exception("Row $id not found");
        }
        
        
    }
    
    public function deleteEvent($id)
    {
        return parent::delete($id);
    }
    
    public function saveEvent(Event $evt)
    {
        $id = (int)$evt->id;
        
        if($id === 0){
            // INSERT 
            $sql = "INSERT INTO $this->tableName(name,location,rundate,description) VALUES(:name,:location,:date,:description)";
            $stmt = $this->pdo->prepare($sql);
            $params = $this->bindParams($evt);
            $stmt->execute($params);
            return $this->pdo->lastInsertId();
        }else{
            //UPDATE
            $sql = "UPDATE $this->tableName SET name = :name,location = :location,rundate = :date,description = :description 
                    WHERE $this->idField = :id";
            $stmt = $this->pdo->prepare($sql);
            $params = $this->bindParams($evt);
            $params[':id'] = $evt->id;
            $stmt->execute($params);
            return $id;
        }
    }
    
    private function mapEvent($tabEvent)
    {
        
        $tabEvent['date'] = new \DateTime($tabEvent['rundate']);
        unset($tabEvent['rundate']);
        
        $res = new Event($tabEvent);
        
        return $res;
    }
    
    private function bindParams(Event $evt)
    {
        $res = array();
        $res[':name'] = $evt->name;
        $res[':location'] = $evt->location;
        $res[':date'] = $evt->date->format('Y-m-d');
        $res[':description'] = $evt->description;
        
        return $res;
    }
}

?>
