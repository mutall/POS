<?php
/**
 * @todo insert session data gotten from javascript
 * @todo retrieve list of sessions created throughout the lifecycle
 * @todo given a session id retrieve relevant session data associated with it
 *
 */
require __DIR__ . '/models/product.php';
require __DIR__ . '/models/staff.php';
require __DIR__ . '/models/station.php';
require __DIR__ . '/models/session.php';

//TODO write a query that retirves the last closing balance for a particular product


//get post data from javascript
$data = json_decode(file_get_contents('php://input'));


//check for class and method variables
if (isset($data->class) && isset($data->method)) {
    run($data->class, $data->method, $data->state);
}

//create a  function that takes in a class name and method and runs the class and method
function run($class, $method, $static = false)
{
    if (class_exists($class)) {
        //check if method exists
        if ($static) {
            return (method_exists($class, $method)) ? $class::$method() : die("Class $class doesnt contain method $method");
        } else {
            $obj = new $class;
            return (method_exists($obj, $method)) ? $obj->$method() : die("Class $class doesnt contain method $method");
        }

    } else {
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
     * why??
     * The querys all pull values that are always constant in the system i.e product names, staff members stations
     */
    public function init()
    {
        $arr = [];

        $staff = new Staff;
        $station = new Station;
        $product = new Product;

        try {
            $arr['products'] = $product->records();
            $arr['staff'] = $staff->records();
            $arr['station'] = $station->records();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        echo json_encode($arr);
    }

    //get stok of a counter for  particular day
    static public function getStock()
    {
        global $data;
        $arr = array();


        echo json_encode($arr);
    }

    /**
     * function for getting the closing stock of a partricular product
     * pass the station as a parameter
     */
    static public function getClosingStock()
    {

        $arr = [];

        echo json_encode($arr);
    }

}

/**
 * Class Session
 * This class will be for controlling session details, either inserting a session or retrieving pprevious sessions
 */
class StockSession
{

    private Session $session;

    public function __construct()
    {
        $this->init();
        //create a prepared statement


    }

    public function init()
    {
        //get post items
        global $data;
        //insert session details
        $session_details = $data->data;

        try {
            //insert staff
            $staff = new Staff();
            $staff->create($session_details->staff);

            //insert station
            $station = new Station();
            $station->create($session_details->station);

            //create a new session object
            $obj = new stdClass();
            $obj->date = $session_details->session->date;
            $obj->direction = $session_details->session->direction;
            $obj->staff = $staff;
            $obj->station = $station;
            //session
            $session = new Session();
            $this->session = $session->create($obj);
        } catch (Exception $e) {
            die($e->getMessage());
        }

    }

    public function commitSession()
    {

    }
}

/**
 * Class BarcodeController
 */
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