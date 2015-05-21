<?php
namespace CAP\Model\Mapper;

use CAP\Model\Category;

/**
 * Description of Category
 *
 * @author Eric
 */
class CategoryMapper extends AbstractMapper{
    public function __construct()
    {
        $this->tableName = 'categories';
        $this->idField = 'id';
        parent::__construct();
    }
    
    public function fetchAllCategories()
    {
        $all = parent::fetchAll();
        $res = array();
        
        foreach($all as $enr){
            $res[]=$this->mapCategory($enr);
        }
        
        return $res;
    }
    
    public function getCategory($id)
    {
        $enr = parent::get($id);
        
        if(is_array($enr))
            return $this->mapCategory($enr);
        
        throw new \Exception("Row $id not found");
        
    }
    
    public function getCategoryFromBirthDate(\DateTime $date){
        $sql = "SELECT * FROM categories WHERE minimumAge < YEAR(CURRENT_DATE()) - YEAR(:birthDate) ORDER BY minimumAge DESC LIMIT 0,1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':birthDate',$date->format('Y-m-d'));
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $res = $this->mapCategory($row);
        return $res;
    }
    
    public function deleteCategory($id)
    {
        return parent::delete($id);
    }
    
    public function saveCategory(Category $cat)
    {
        $id = (int)$cat->id;
        if(0 === $id){
            // Insert
            $sql = "INSERT INTO " . $this->tableName . "(name,minimumAge) VALUES (:name,:ageLimit)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $cat->name);
            $stmt->bindParam(':ageLimit', $cat->ageLimit);
            $stmt->execute();
            $res = $this->pdo->lastInsertId();
            return $res;

        }else{
            // Update
            $sql = "UPDATE $this->tableName SET name=:name, minimumAge=:ageLimit WHERE $this->idField = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $cat->name);
            $stmt->bindParam(':ageLimit', $cat->ageLimit);
            $stmt->bindParam(':id', $cat->id);
            $stmt->execute();
            return $cat->id;
        }
    }
    
    /**
     * 
     * @param array $enreg
     * @return \CAP\Model\Category
     */
    private function mapCategory($enreg)
    {
        $enreg['ageLimit'] = $enreg['minimumAge'];
        unset($enreg['minimumAge']);
        
        $cat = new Category();
        $cat->exchangeArray($enreg);
        return $cat;
    }
}

?>
