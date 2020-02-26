<?php
require_once 'owner.php';
require_once "../Model.php";

class Business extends Model{
    private $name;
    private $address;
    private $location;
    private $telephone;
    private Owner $owner;

    public function __construct()
    {
        parent::__construct();
    }

    public static function create($arg):Business
    {
        $business = new Business;
        $business->name = $arg->name;
        $business->address = $arg->address;
        $business->location = $arg->location;
        $business->telephone = $arg->location;
        $business->owner = $arg->owner;

        $sql = "INSERT INTO ".self::$tableName." (name, address, location, telephone, owner) VALUES('')" ;

        return $business;
    }
}