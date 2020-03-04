<?php
require __DIR__."/../BaseModel.php";
require_once 'session.php';
require_once 'stocking.php';


class Quantity extends BaseModel{
    private int $value;
    private Session $session;
    private Stocking $stocking;
    private String $sql;
    private $stmt;

    public function __construct()
    {
        parent::__construct();
        $sql = "INSERT INTO ".$this->tableName." (value, stocking, session) VALUES(:value, :stocking, :session)";
        $this->stmt = self::$db->prepare($sql);
    }

    public function create($arg):Quantity
    {
        $quantity = new Quantity();
        $quantity->session = $arg->session;
        $quantity->stocking = $arg->stocking;
        $quantity->value = $arg->value;

        $this->stmt->bindParam(':value', $quantity->value);
        $this->stmt->bindParam(':stocking', $quantity->stocking->id);
        $this->stmt->bindParam(':session', $quantity->session->id);
        try {
            $this->stmt->execute();
        }catch (Exception $e){
            die($e->getMessage());
        }

        return $quantity;
    }
}