<?php 
require_once __DIR__.'/../BaseModel.php';

class Staff extends BaseModel{
    public string $name;
    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg):Staff
    {
        $staff = new Staff();
        $staff->name = $arg->name;


        $sql = "INSERT into "
            . $this->tableName
            . "(name) 
                    VALUES('$staff->name')";

        // echo $sql;
        if (self::$db->exec($sql) < 1) throw new Exception("Failed to insert product " . self::$db->errorInfo());

        return $staff;
    }
}