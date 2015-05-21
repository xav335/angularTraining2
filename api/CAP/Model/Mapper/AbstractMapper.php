<?php
namespace CAP\Model\Mapper;

/**
 * Description of Abstract
 *
 * @author Eric
 */
abstract class AbstractMapper {
    const DBNAME = 'cap';
    const DBUSER = 'root';
    const DBPASS = '';
    const DBHOST = 'localhost';
    
    public $tableName;
    public $idField;
    protected $pdo;
    
    public function __construct()
    {
        $dsn = 'mysql:host=' . self::DBHOST . ';dbname=' . self::DBNAME;
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );
        $this->pdo = new \PDO($dsn,  self::DBUSER,  self::DBPASS, $options);
    }
            
    public function __destruct()
    {
        $this->pdo = null;
    }
    
    public function fetchAll()
    {
        $sql = "SELECT * FROM $this->tableName" ;
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $rows;                
    }
    
    public function get($id)
    {
        $sql = "SELECT * FROM $this->tableName WHERE " . $this->idField . ' = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;                
    }
    
    public function delete($id)
    {
        $sql = "DELETE FROM $this->tableName WHERE $this->idField = :id" ;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
    
    
}
?>
