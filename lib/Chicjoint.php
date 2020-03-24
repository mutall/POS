<?php

/**
 * @todo insert session data gotten from javascript
 * @todo retrieve list of sessions created throughout the lifecycle
 * @todo given a session id retrieve relevant session data associated with it
 * @todo write a query that retirves the last closing balance for a particular product
 * 
 */
require __DIR__ . '/BaseModel.php';
require __DIR__ . '/../vendor/autoload.php';
/**
 * Set up logging library for debugging
 * We use monolog for debugging.
 * Monolog sends your logs to files, sockets, inboxes, databases and various web services. 
 * Official documentation can bee found in https://github.com/Seldaek/monolog
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('POS');
$log->pushHandler(new StreamHandler(__DIR__ . '/../app.log', Logger::INFO));

/**
 * Since the point of sale heavily relies on ajax requests we get the post requests from javascript 
 * The javascript request needs to have atleast 3parameters-:
 * Class: This represents the class that will be instatiated
 * Method: this represents the method that will be invoked
 * State: Whether the method is static or not
 */
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
     * @method init
     * This method will be used to pull out system wide values that wont change when the system is operational
     * i.e products, staff, and stations
     * We get this info and save them to javascri local storage and this will atleast speed up call that i have to make 
     * to the server.  
     */
    public function init()
    {
        //create an empty array to store the result
        $arr = [];

        //fetch the products, staff and stations and save them in an associative array
        $arr['products'] = ModelController::getModelRecords('product');
        $arr['staff'] = ModelController::getModelRecords('staff');
        $arr['station'] = ModelController::getModelRecords('station');
        //return data to the client
        echo json_encode($arr);
    }
}

/**
 * Class Session
 * This class will be for controlling session details, either inserting a session or retrieving pprevious sessions
 */
class StockSession
{
    /**
     * @method insertSessionData 
     * This method will be used to insert data taken from a session
     * It will conprise of two parts
     * The first will be to insert the session details. i.e staff, station, date and direction
     * This will help us get the session primary key which is essential in inserting the quantities
     * The next part willl be inserting the quantites, we get them from the table posted from the client
     */
    public function insertSessionData()
    {
        //get post items
        global $data, $log;
        //insert session details
        $session_details = $data->data;

        $sessionData = new stdClass;
        $sessionData->date = $session_details->session->date;
        $sessionData->direction = $session_details->session->direction;
        $sessionData->staff = $session_details->staff;
        $sessionData->station = $session_details->station;

        $session = ModelController::createModel('session', $sessionData);
        $log->info($session);
        $quantityData = new stdClass;
        $quantityData->session = $session->session;
        $quantityData->data = $session_details->table; 
        // $log->info($quantityData);
        //insert quantities
        ModelController::createModel('quantity', $quantityData);

        //send a http response code for inserted items
        http_response_code(201);
        $response = new stdClass;
        $response->session = $session->session;
        echo json_encode($response);
    }
    /**
     * @method getSessionList 
     * This method will be used to get all sessions in the database regardless of date direction staff or station
     * This will be displayed in a page called sessions where the user can query the particulars of a session */    
    public static function getSessionList()
    {
        $session = ModelController::getModelRecords('session');
        echo json_encode($session);
    }

    /**
     * @method getSessionDetails
     * Given a session id, get the particulars i.e quantities products, staff, stations for that particuular session
     */
    public static function getSessionDetails()
    {
        global $data, $log;
        $id = $data->data->id;
        $session = ModelController::getSingleRecord('session', ['session' => $id]);
        $quantity = new Quantity;
        $table = $quantity->getItems($session->session); 
        
        //create an std class for returning the data to javascropt
        $response = new stdClass;
        $response->session_details = $session;
        $response->table = $table;

        //debug
        $log->info(json_encode($response));

        echo json_encode($response);
    }
}

/**
 * class reports 
 * This class will be associated in retrieving all reports requested by the system
 */
class Reports
{

    public function salesReport()
    {
        global $data, $log;
        }
    //get report for all stock taken at a particular day and station
    public function stockReport()
    {
        global $data, $log;
       
        /**
         * Stocks report is a report of stock taken at bhat particular day vs last stock taken on previous date
         * We get the last closing stock, calculate the new stock
         * We also calculate the added stock for that date to get the full calculated stock
         * Descrepancices are also calculated here
         *  
         */
        
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
