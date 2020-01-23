<?php

require_once "database.php";

$db = new Database; 

// $data = file_get_contents('php://input');

// $identifier = json_decode($data)->identifier;

$sql = "SELECT 
            product.name, product.amount, sale.quantity as sale, stock.quantity as stock, stock.added  
        FROM 
            product 
                INNER JOIN sale ON product.product = sale.product 
                INNER JOIN stock ON product.product = stock.product
        WHERE
            stock.date = '2020-01-01' AND sale.date = '2020-01-01'";


$result = $db->query($sql);

$arr = array();

foreach($result->fetchAll(PDO::FETCH_OBJ) as $item):
    $table_item = new TableItem($item->name, $item->amount, $item->sale, $item->stock, $item->added);
    array_push($arr, $table_item);

endforeach;

echo json_encode($arr);

class TableItem{
    public $product;
    public $stock;
    public $added;
    public $total_stock;
    public $closing_stock;
    public $sale;
    public $selling;
    
    public function __construct($product, $selling, $sale = null, $stock = null, $added = null)
    {
        $this->product = $product;
        $this->selling = intval($selling);
        if(is_null($sale)){
            $this->sale = 0;
        }else{
            $this->sale = intval($sale);
        };
        if(is_null($stock)) {
            $this->stock = 0;
        }else{
            $this->stock = intval($stock);
        }

        if(is_null($added)){
            $this->added = 0;
        }else{
            $this->added = intval($added);
        }  
        
        $this->calculateTotalStock();
        $this->calculateClosingStock();
        $this->calculateAmount();

    }
    public function calculateTotalStock(){
        $this->total_stock = $this->stock + $this->added; 
    }
    public function calculateClosingStock(){
        $this->closing_stock = $this->total_stock - $this->sale;
    }
    public function calculateAmount(){
        $this->amount = $this->selling * $this->sale;
       
    }

    public function toJson(){
        return json_encode($this);
    }
    
}