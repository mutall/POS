<?php
require __DIR__."/../BaseModel.php";

class Stocking extends BaseModel{
    private int $buy_price, $sell_price;
    private Business $business;
    private Product $product;
    
    
    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg):Stocking
    {
        return new Stocking();
    }
}