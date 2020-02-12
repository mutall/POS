<?php
require_once("database.php");

$data = json_decode(file_get_contents('php://input'));

if(isset($data->class) && isset($data->method)){
    run($data->class, $data->method, $data->state);
}

function run($class, $method, $static = false){
    if(class_exists($class)){
        //check if method exists
        if($static){
            return (method_exists($class, $method)) ? $class::$method() : die("Class $class doesnt contain method $method");
        }else{
            $obj = new $class;
            return (method_exists($obj, $method))? $obj->$method() : die("Class $class doesnt contain method $method");
        }
        
    }else{
        die("No class by the name $class");
    }
}


/**
 * This will be the main php entry file 
 * Most database operations will be handled by this file 
 */

class ChicJoint 
{
    
    /**
     * This method will be called before the system initialises.
        why?? 
        The querys all pull values that are always constant in the system i.e product names, staff members stations
     */
    public function init()
    {
        $arr = [];
        $sql = "SELECT 
                    product.*, image.name as image 
                FROM 
                    product INNER JOIN image ON product.product = image.product";

        $products = Database::getInstance()->query($sql);

        $arr['products'] = $products->fetchAll(PDO::FETCH_OBJ);
        //fetch all users

        $staff = Database::getInstance()->query("SELECT * FROM staff");
        $arr['staff'] = $staff->fetchAll(PDO::FETCH_OBJ);

        //fetch stations 
        $stations = Database::getInstance()->query("SELECT * FROM location");
        $arr['station'] = $stations->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($arr);
    }

    
    //get stok of a counter for  particular day
    static public function getStock()
    {
        global $data;
        $arr = array();

        $arr['date'] = $data->date;
        $station = intval($data->station);
        $sales = "SELECT * FROM sale WHERE date = '$data->date'";
        $stock = "SELECT * FROM stock WHERE date = '$data->date' AND location = $station";

        $sql = "SELECT 
                    product.name, 
                    product.amount, 
                    sale.quantity as sale, 
                    stock.quantity as stock, 
                    stock.added,
                    stock.staff  
                FROM 
                    product 
                        INNER JOIN ($sales) as sale ON product.product = sale.product 
                        INNER JOIN ($stock) as stock ON product.product = stock.product";
                

        $result = Database::getInstance()->query($sql);

        $arr['table'] = $result->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($arr);
    }

    static public function test(){
        $sql = "SELECT * FROM product";
        $result = Database::getInstance()->query($sql);
        echo json_encode($result->fetchAll());
    }

    /**
     * function for getting the closing stock of a partricular product
     * pass the station as a parameter
     */
    static public function getClosingStock()
    {

        $arr = [];
        // create an sql querry to calculate the maximum date
        $max_date = "SELECT 
                        product, 
                        max(stock.date) as max 
                    FROM 
                        stock 
                    GROUP BY
                        product";

        //get the staff for that particular date
        $staff = Database::getInstance()->query("SELECT DISTINCT stock.staff FROM stock WHERE date = '$max_date.max'");
        $arr['staff'] = $staff->fetch(PDO::FETCH_OBJ);

        // create an sql querry to get product
        $product_details = "SELECT 
                       product.product, stock.date, stock.quantity
                    FROM 
                        product 
                            INNER JOIN stock ON product.product = stock.product
                            INNER JOIN ($max_date) as max_date ON product.product = max_date.product
                    WHERE 
                        stock.date = max_date.max";

        // Querry the sql
        $result = Database::getInstance()->query($product_details);
        $arr['details'] = $result->fetchAll(PDO::FETCH_OBJ);
        //echo the results to javascript
        echo json_encode($arr);
    }

}
//this code is for inserting barcode readings 
class BarcodeController
{
    public $db;
    public $barcode;
    public $data;

    public function __construct()
    {
        $this->db = Database::getInstance();
        if (sizeof($_POST) > 0) {
            if (isset($_POST['type'])) {
                $this->update_quantity();
            } else {
                //insert new record
                $this->insertNewBarcode();
            }
        } else {
            //scan a barcode
            $postData = file_get_contents('php://input');
            $this->data = json_decode($postData);
            $this->barcode = $this->data->barcode;
            $this->checkBarcodeInDb();
        }
    }


    function checkBarcodeInDb()
    {
        $sql = "SELECT barcode FROM product WHERE barcode = '$this->barcode' ";
        $result = $this->db->query($sql);

        if ($result->rowCount() == 0) {
            http_response_code(404);
            return;
        } elseif ($result->rowCount() > 0) {
            $this->incrementCount();
            http_response_code(202);
            return;
        } else {
            http_response_code(500);
            die("Invalid output");
        }
    }

    function incrementCount()
    {
        $sql = "SELECT quantity FROM product WHERE barcode = '$this->barcode'";
        $result = $this->db->query($sql);
        $quantity = $result->fetchObject()->quantity;
        $quantity++;
        $update = "UPDATE product SET quantity = $quantity WHERE barcode = '$this->barcode'";
        $this->db->exec($update);
    }
    function update_quantity()
    {
        $barcode = $_POST['scanned'];
        $sql = "select quantity from product where barcode = '$barcode'";
        $result = $this->db->query($sql);
        $quantity = $result->fetchobject()->quantity;
        $quantity += $_POST['quantity'];
        $update = "update product set quantity = $quantity where barcode = '$barcode'";
        $this->db->exec($update);
        http_response_code(202);
    }

    function insertNewBarcode()
    {
        $product_name = $_POST['name'];
        $this->barcode = $_POST['unique'];
        $quantity = 0;

        if (isset($_POST['quantity'])) {
            $quantity = intval($_POST['quantity']);
        }
        $amount = $_POST['amount'];


        $sql = "INSERT INTO product
                    (name, barcode, quantity, amount, client) 
                VALUES
                    ('$product_name', '$this->barcode', $quantity, '$amount', 1)";

        $this->db->exec($sql);

        http_response_code(201);
    }
}