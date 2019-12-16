<?php 
require_once "database.php";
new BarcodeController;

class BarcodeController{
    public $db;
    public $barcode;
    public $data;
    
    public function __construct()
    {
        $this->db = new Database;
        if (sizeof($_POST)>0) {
            if(isset($_POST['type'])){
                $this->update_quantity();
            }else{
                //insert new record
                $this->insertNewBarcode();
            }
        }else{
            //scan a barcode
            $postData = file_get_contents('php://input');
            $this->data = json_decode($postData);
            $this->barcode = $this->data->barcode;
            $this->checkBarcodeInDb();
        }
    }
    

    function checkBarcodeInDb(){
        $sql = "SELECT barcode FROM product WHERE barcode = '$this->barcode' ";
        $result = $this->db->query($sql);

        if($result->rowCount() == 0){
            http_response_code(404);
            return;
        }elseif($result->rowCount() > 0){
                $this->incrementCount();
                http_response_code(202);
            return;
        }else{
            http_response_code(500);
            die("Invalid output");
        }
    }
    
    function incrementCount(){
        $sql = "SELECT quantity FROM product WHERE barcode = '$this->barcode'";
        $result = $this->db->query($sql);
        $quantity = $result->fetchObject()->quantity;
        $quantity ++;
        $update = "UPDATE product SET quantity = $quantity WHERE barcode = '$this->barcode'";
        $this->db->exec($update);
       
    }
    function update_quantity(){
        $barcode = $_POST['scanned'];
        $sql = "select quantity from product where barcode = '$barcode'";
        $result = $this->db->query ($sql);
        $quantity = $result-> fetchobject()->quantity;
        $quantity += $_POST['quantity'];
        $update = "update product set quantity = $quantity where barcode = '$barcode'";
        $this->db->exec($update);
        http_response_code(202);
       }
            
    function insertNewBarcode(){
        $product_name = $_POST['name'];
        $this->barcode = $_POST['unique'];
        $quantity = 0;
        
        if(isset($_POST['quantity'])){
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

