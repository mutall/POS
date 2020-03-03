<?php
require __DIR__."/../BaseModel.php";
require_once 'session.php';
require_once 'stocking.php';


class Quantity extends BaseModel{
    private int $value;
    private Session $session;
    private Stocking $stocking;


    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg):Quantity
    {
        return new Quantity();
    }
}