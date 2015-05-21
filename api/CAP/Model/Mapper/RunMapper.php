<?php
namespace CAP\Model\Mapper;

use CAP\Model\Run;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RunMapper
 *
 * @author Eric
 */
class RunMapper extends AbstractMapper {
    public function __construct() {
        $this->tableName = 'runs';
        $this->idField = 'id';
        parent::__construct();
    }
    
    public function fetchAllRuns()
    {
        $runs = parent::fetchAll();
        $res = array();
        foreach($runs as $run){
            $res[] = $this->mapRun($run);
        }
        
        return $res;
    }
    
    public function getRun($id)
    {
        $run = parent::get($id);
        
        if(is_array($run))
            return $this->mapRun($run);
        
        throw new \Exception("Row $id not found");
    }
    
    public function fetchEventsRuns($eventId){
        $sql = "SELECT * FROM runs WHERE eventId = :evtId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':evtId',$eventId);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = array();
        
        foreach($rows as $run){
            $res[] = $this->mapRun($run);
        }
        
        return $res;
        
    }
    
    public function fetchRunsFromIds($ids){
        $in = implode(',', array_fill(0,count($ids), '?'));
        $sql = "SELECT * FROM runs WHERE id IN ($in)";
        $stmt = $this->pdo->prepare($sql);
        foreach($ids as $k => $id){
            $stmt->bindValue(($k+1),$id);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = array();
        foreach($rows as $run){
            $res[] = $this->mapRun($run);
        }
        return $res;
    }
    
    public function deleteRun($id)
    {
        return parent::delete($id);
    }
    
    public function saveRun(Run $run)
    {
        $id = (int)$run->id;
        
        if( $id > 0 ){
            // UPDATE
            $sql = "UPDATE $this->tableName SET eventId = :evtId,start=:start,name=:name,distance=:distance WHERE $this->idField = :id";
            $stmt = $this->pdo->prepare($sql);
            $params = $this->bindParams($run);
            $params['id'] = $id;
            $stmt->execute($params);
            return $id;
        }else{
            // INSERT
            $sql = "INSERT INTO $this->tableName (eventId,start,name,distance) VALUES (:evtId,:start,:name,:distance)";
            $stmt = $this->pdo->prepare($sql);
            $params = $this->bindParams($run);
            $stmt->execute($params);
            return $this->pdo->lastInsertId();
        }
    }
    
    private function mapRun($tab)
    {
        $tab['startTime'] = new \DateTime($tab['start']);
        unset($tab['start']);
        $res = new Run($tab);
        return $res;
    }
    
    private function bindParams(Run $run)
    {
        $res = array();
        $res[':evtId'] = $run->eventId;
        $res[':name'] = $run->name;
        $res[':distance'] = $run->distance;
        $res[':start'] = $run->startTime->format('H:i:s');
        return $res;
    }
}

?>
