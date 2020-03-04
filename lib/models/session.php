<?php
require __DIR__ . "/../BaseModel.php";
require_once 'station.php';
require_once 'staff.php';


class Session extends BaseModel
{
    private String $date;
    private String $direction;
    private Station $station;
    private Staff $staff;


    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg): Session
    {
        $session = new Session();
        $session->station = $arg->station;
        $session->staff = $arg->staff;
        $session->direction = $arg->direction;
        $session->date = $arg->date;

        $sql = "INSERT INTO " . $this->tableName .
            "(staff, station, direction, date) 
            VALUES
            ('$this->staff->staff', '$this->station->station', '$this->direction', '$this.date')";
        try {
            $res = self::$db->exec($sql);
            if($res!= false){
                $session->id = self::$db->lastInsertId();
            }
        }catch (PDOException $e){
            die($e->getMessage());
        }
        return new Session();
    }
}