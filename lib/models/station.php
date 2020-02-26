<?php 
require_once '../Model.php';

class Station extends Model{
    private string $name;


    public function __construct()
    {
        parent::__construct();
    }

    public static function create($arg)
    {
        
    }
}