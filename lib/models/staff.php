<?php 
require_once '../Model.php';

class Staff extends Model{
    private string $name;


    public function __construct()
    {
        parent::__construct();
    }

    public static function create($arg)
    {
        
    }
}