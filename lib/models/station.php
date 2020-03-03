<?php 
require_once __DIR__.'/../BaseModel.php';

class Station extends BaseModel{
    public String $name;


    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg):Station
    {
        $station = new Station();
        $station->name = $arg->name;

        $sql = "INSERT into "
            . $this->tableName
            . "(name) 
                    VALUES('$station->name')";

        // echo $sql;
        if (self::$db->exec($sql) < 1) throw new Exception("Failed to insert product " . self::$db->errorInfo());

        return $station;
    }
}