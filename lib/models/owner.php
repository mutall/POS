<?php

require __DIR__."/../BaseModel.php";

class Owner extends BaseModel{
    private String $username;

    public function __construct()
    {
        parent::__construct();
    }


    public function create($arg):Owner
    {
        $owner = new Owner;
        $owner->username = $arg->username;

        $sql =  "INSERT INTO ".$this->tableName."(username) VALUES('$arg->username')";
        
        
        // echo $sql;
         if(self::$db->exec($sql) <1) throw new Exception("Failed to insert product ". self::$db->errorInfo());;
         
         return $owner;
    }
}