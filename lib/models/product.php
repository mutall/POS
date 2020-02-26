<?php 
require_once '../Model.php';

class Product extends Model{
    private $name;
    private $barcode;
    private $image;


    public function __construct(){
        parent::__construct();
        

    }

   public static function create($obj){
        $product = new Product;
        $product->name = $obj->name;
        $product->barcode = $obj->barcode;
        $product->image = $obj->image;

        $sql = "INSERT into "
                    .self::$tableName 
                ."(name, barcode, image) 
                    VALUES('$product->name', '$product->barcode', '$product->image')";
        
        // echo $sql;
         if(self::$db->exec($sql) <1){
            throw new Exception("Failed to insert product ". self::$db->error);
         };           

         return $product;
    }
}