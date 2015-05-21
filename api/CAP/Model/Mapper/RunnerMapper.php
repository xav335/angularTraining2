<?php
namespace CAP\Model\Mapper;

use CAP\Model\Runner;
use CAP\Model\Mapper\AbstractMapper;

/**
 * Description of RunnerMapper
 *
 * @author Eric
 */
class RunnerMapper extends AbstractMapper{
    public function __construct() 
    {
        $this->tableName = 'runners';
        $this->idField = 'id';
        parent::__construct();
    }
    
    public function fetchAllRunners()
    {
        $runners = parent::fetchAll();
        $res = array();
        
        foreach($runners as $rnr){
            $res[]=$this->mapRunner($rnr);
        }
        return $res;
    }
    
    public function getRunner($id)
    {
        $tabRnr = parent::get($id);
        
        if(is_array($tabRnr)){
            return $this->mapRunner($tabRnr);
        }else{
            throw new \Exception("Row $id not found");
        }
    }
    
    public function deleteRunner($id)
    {
        return parent::delete($id);
    }
    
    public function saveRunner(Runner $rnr)
    {
        $id = (int)$rnr->id;
        if(0 === $id){
            //INSERT
            $sql = "INSERT INTO $this->tableName(firstName,lastName,gender,ffaId,picture,birthDate) 
                    VALUES(:firstName,:lastName,:gender,:ffaId,:picture,:birthDate)";
            $stmt = $this->pdo->prepare($sql);
            $params = $this->bindParams($rnr);
            $stmt->execute($params);
            return $this->pdo->lastInsertId();
        }else{
            //UPDATE
            $sql = "UPDATE $this->tableName SET firstName = :firstName, lastName = :lastName, gender = :gender, 
                    ffaId = :ffaId, picture = :picture, birthDate = :birthDate 
                    WHERE $this->idField = :id";
            $stmt = $this->pdo->prepare($sql);
            $params = $this->bindParams($rnr);
            $params[':id'] = $id;
            $stmt->execute($params);
            return $id;
        }
    }
    
    private function mapRunner($tabRnr)
    {
        $tabRnr['birthDate'] = new \DateTime($tabRnr['birthDate']);
        $res = new Runner($tabRnr);
        return $res;
    }
    
    private function bindParams(Runner $rnr)
    {
        $res = array();
        $res[':firstName'] = $rnr->firstName;
        $res[':lastName'] = $rnr->lastName;
        $res[':gender'] = $rnr->gender;
        $res[':ffaId'] = $rnr->ffaId;
        $res[':picture'] = $rnr->picture;
        $res[':birthDate'] = $rnr->birthDate->format('Y-m-d');
        return $res;
    }
}

?>
