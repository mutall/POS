<?php
require_once 'Database.php';

abstract class BaseModel
{
    //if the model should have timestamps
    protected $timestamps = true;

    //database instance 
    protected static $db;
    //save the table name
    protected String $tableName;

    //save data relating to an enetiti
    protected array $tableData;

    public function __construct(){
        //get the instance of a db
        self::$db = Database::getInstance();
        //set the table name
        $this->tableName = strtolower(get_class($this));

        //get the column names
        $this->init();
    }

    private function init()
    {
        //get the table propeerties
        $sql = "DESCRIBE " . $this->tableName;
        
        //query the taable 
        $stmt = self::$db->prepare($sql);

        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $this->tableData = array_filter($arr, function ($k) {
            return $k->Key != 'PRI';
        });
    }

    abstract function create($arg):object;

    //create a method for fetching records from a database 
    public function records():array{
        //sql for fetching items
        $sql = "SELECT * FROM ".$this->tableName;

        //store the results in a cursor variable
        $result = self::$db->query($sql);

        //error checking
        if($result===false) throw new Exception("Failed to fetch items ".self::$db->errorInfo());

        //return a class representation of the resultset
       return $result->fetchAll(PDO::FETCH_CLASS, $this->tableName);
    }

    // create a method for fetching one item from table
    public function record($args, $value):object{
        //sql containing the where  parameters
        $sql = "SELECT * FROM ".$this->tableName. " WHERE $args = '$value'";

        //svae thee result in a cursor variable
        $result = self::$db->query($sql);

        //error checking
        if($result===false){
            throw new Exception("Failed to fetch item ".self::$db->error);
        }
        //return a class object of the result
        return $result->fetch(PDO::FETCH_CLASS, $this->tableName);
    }

    public function __toString()
    {
        return json_encode($this);
    }
    
}