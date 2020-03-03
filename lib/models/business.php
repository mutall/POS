<?php
require_once 'owner.php';
require_once "../BaseModel.php";

class Business extends BaseModel{
    private String $name, $address, $location, $telephone;
    private Owner $owner;

    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg):Business
    {
        $business = new Business;
        $business->name = $arg->name;
        $business->address = $arg->address;
        $business->location = $arg->location;
        $business->telephone = $arg->location;
        $business->owner = $arg->owner;

        $sql = "INSERT INTO ".$this->tableName." (name, address, location, telephone, owner) VALUES('')" ;

        return $business;
    }
}