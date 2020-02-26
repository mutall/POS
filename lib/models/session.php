<?php
require_once '../Model.php';
require_once 'station.php';
require_once 'staff.php';


class Session extends Model{
    private DateTime $date;
    private string $direction;
    private Station $station;
    private Staff $staff;


    public function __construct()
    {
        parent::__construct();
    }

    public static function create($arg)
    {
        
    }
}