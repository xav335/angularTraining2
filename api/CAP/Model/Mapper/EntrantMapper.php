<?php
namespace CAP\Model\Mapper;

use CAP\Model\Entrant;
use CAP\Model\Mapper\AbstractMapper;
/**
 * Description of EntrantMapper
 *
 * @author Eric
 */
class EntrantMapper extends AbstractMapper{
    public function __construct() 
    {
        $this->tableName = 'entrants';
        $this->idField = 'id';
        parent::__construct();
    }
    
    public function fetchAllEntrants(){
        $ents = parent::fetchAll();
        $res = array();
        
        foreach($ents as $ent){
            $res[] = $this->mapEntrant($ent);
        }
        
        return $res;
    }

    public function fetchRunnerEntrants($runnerId){
        $sql = "SELECT * FROM $this->tableName WHERE runnerId = :runnerId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':runnerId',$runnerId);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = array();
        
        foreach($rows as $ent){
            $res[] = $this->mapEntrant($ent);
        }
        
        return $res;
    }

    
    public function getAllRunsForRunner($runnerId)
    {
        $sql = "SELECT * FROM $this->tableName WHERE runnerId = :runnerId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':runnerId',$runnerId);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = array();
        
        foreach($rows as $ent){
            $res[] = $this->mapEntrant($ent);
        }
        
        return $res;
    }
    
    public function getAllRunnersForRun($runId)
    {
        $sql = "SELECT * FROM $this->tableName WHERE runId = :runId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':runId',$runId);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $res = array();
        
        foreach($rows as $ent){
            $res[] = $this->mapEntrant($ent);
        }
        
        return $res;
    }
    
    public function getEntrant($id)
    {
        $tabEnt = parent::get($id);
        if(is_array($tabEnt)){
            $res = $this->mapEntrant($tabEnt);
        }else{
            throw new \Exception("Row $id not found");
        }
        return $res;
    }
    
    public function deleteEntrant($id)
    {
        return parent::delete($id);
    }
    
    public function saveEntrant(Entrant $ent)
    {
        $id = (int)$ent->id;
        
        if (0 === $id){
            //INSERT
            $sql = "INSERT INTO $this->tableName (runnerId,runId,bibNumber,runTime,didFinish) 
                    VALUES (:runnerId,:runId,:bibNumber,:runTime,:didFinish)";
            $stmt = $this->pdo->prepare($sql);
            $params = $this->bindParams($ent);
            $stmt->execute($params);
            return $this->pdo->lastInsertId();
        }else{
            //UPDATE
            $sql = "UPDATE $this->tableName SET runnerId = :runnerId,runId = :runId,bibNumber = :bibNumber,
                    runTime = :runTime,didFinish = :didFinish 
                    WHERE $this->idField = :id";
            $stmt = $this->pdo->prepare($sql);
            $params = $this->bindParams($ent);
            $params[':id'] = $ent->id;
            $stmt->execute($params);
            return $id;
        }
    }
    
    private function mapEntrant($tabEnt)
    {
        if(null !== $tabEnt['runTime']){
            $duration = explode(':',$tabEnt['runTime']);
            $tabEnt['runTime'] = new \DateInterval("PT$duration[0]H$duration[1]M$duration[2]S");
        }
        $tabEnt['didFinish'] = (bool)$tabEnt['didFinish'];
        $ent = new Entrant($tabEnt);
        return $ent;
    }
    
    private function bindParams(Entrant $ent)
    {
        $res = array();
        $res[':runnerId'] = $ent->runnerId;
        $res[':runId'] = $ent->runId;
        $res[':bibNumber'] = $ent->bibNumber;
        $res[':didFinish'] = (bool)$ent->didFinish;
        if(null !== $ent->runTime){
            $res[':runTime'] = $ent->runTime->format('%H:%I:%S');
        }else{
            $res[':runTime'] = null;
        }
            
        return $res;
    }
    
}

?>
