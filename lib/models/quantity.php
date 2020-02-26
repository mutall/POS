<?php 
require_once '../Model.php';
require_once 'session.php';
require_once 'stoocking.php';


class Quantity extends Model{
    private int $value;
    private Session $session;
    private Stocking $stocking;


    public function __construct()
    {
        parent::__construct();
    }

    public static function create($arg)
    {
        
    }
}