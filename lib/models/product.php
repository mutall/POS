<?php


require __DIR__."/../BaseModel.php";

class Product extends BaseModel
{
    public String $name, $barcode, $image;

    public function __construct()
    {
        parent::__construct();
    }

    public function create($obj): Product
    {
        $product = new Product;
        $product->name = $obj->name;
        $product->barcode = $obj->barcode;
        $product->image = $obj->image;

        $sql = "INSERT into "
            . $this->tableName
            . "(name, barcode, image) 
                    VALUES('$product->name', '$product->barcode', '$product->image')";

        // echo $sql;
        if (self::$db->exec($sql) < 1) throw new Exception("Failed to insert product " . self::$db->errorInfo());

        return $product;
    }
}