<?php

require_once '../Model.php';

class Owner extends Model{
    private $username;

    public function __construct()
    {
        parent::__construct();
    }


    public static function create($arg):Owner
    {
        $owner = new Owner;
        $owner->username = $arg->username;

        $sql =  "INSERT INTO ".self::$tableName."(username) VALUES('$arg->username')";
        
        
        // echo $sql;
         if(self::$db->exec($sql) <1){
            throw new Exception("Failed to insert product ". self::$db->error);
         };
         
         return $owner;
    }
}