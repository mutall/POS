<?php 

class Stocking extends Model{
    private int $buy_price;
    private int $sell_price;
    private Business $business;
    private Product $product;
    
    
    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg)
    {
        
    }
}