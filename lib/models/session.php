<?php
require __DIR__."/../BaseModel.php";
require_once 'station.php';
require_once 'staff.php';


class Session extends BaseModel{
    private DateTime $date;
    private String $direction;
    private Station $station;
    private Staff $staff;


    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg):Session
    {
        return new Session();
    }
}