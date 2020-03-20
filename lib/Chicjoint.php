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
 * We use monolog for debugging
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('POS');
$log->pushHandler(new StreamHandler(__DIR__ . '/../app.log', Logger::INFO));

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


        $arr['products'] = ModelFactory::getModelRecords('product');
        $arr['staff'] = ModelFactory::getModelRecords('staff');
        $arr['station'] = ModelFactory::getModelRecords('station');
        echo json_encode($arr);
    }

}

/**
 * Class Session
 * This class will be for controlling session details, either inserting a session or retrieving pprevious sessions
 */
class StockSession
{
    private $session_id;
    private $product_id;
    private $quantity_value;
    private $stmt;
    private $db;
    public $log;
    public function __construct()
    {
        // $this->init();
        //create a prepared statement
        $sql = "INSERT INTO quantity(stocking, `session`, `value`) VALUES((
            SELECT stocking FROM stocking WHERE stocking.product = :product
        ), :session, :value)";
        $this->db = Database::getInstance();
        $this->stmt = $this->db->prepare($sql);
    }

    public function init()
    {
        //get post items
        global $data;
        //insert session details
        $session_details = $data->data;

        try {

            $sessionData = [
                'date' => $session_details->session->date,
                'direction' => $session_details->session->direction,
                'staff' => $session_details->staff,
                'station' => $session_details->station
            ];
            //session

            $session = ModelFactory::createModel('session', $sessionData);
            $stocking = ModelFactory::createModel('stocking', [
                'session_id' => $session->_id,
                'table' => $session_details->table,
            ]);
        } catch (Exception $e) {
            $x = $e->getMessage();
            die($x);
        }
    }

    public function commitSession()
    {
        global $data, $log;
        $session_details = $data->data->session;
        $direction = $session_details->direction;
        $staff = $session_details->staff;
        $station = $session_details->station;
        $dateTime = new DateTime($session_details->date);
        $date = $dateTime->format('Y-m-d H:i:s');

        $sql = "INSERT INTO session(date, direction, station, staff) VALUES('$date', '$direction', '$station', '$staff')";

        if ($this->db->exec($sql) > 0) {
            $this->session_id = $this->db->lastInsertId();
        }

        $this->stmt->bindParam(':session', $this->session_id);
        $this->stmt->bindParam(':product', $this->product_id);
        $this->stmt->bindParam(':value', $this->quantity_value);

        try {
            foreach ($data->data->table as $key) :
                $this->product_id = $key->product;
                $this->quantity_value = $key->quantity;

                $this->stmt->execute();
            endforeach;
            http_response_code(201);
            $response = new stdClass;
            $response->session = $this->session_id;
            echo json_encode($response);
        } catch (PDOException $e) {
            $log->info($e->getMessage());
            die("Something went terribly wrong");
        }
    }

    public static function getSessionList()
    {
        $session = ModelFactory::getModelRecords('session');
        echo json_encode($session);
    }

    public static function getSessionDetails()
    {
        global $data, $log;
        $id = $data->data->id;
        $session_details = ModelFactory::getSingleRecord('session', ['session' => $id]);

        //write a custom  sql for retrieving products and quantities
        $sql = "SELECT 
                    product.name, quantity.value
                FROM 
                    quantity 
                        INNER JOIN session ON quantity.session = session.session
                        INNER JOIN stocking ON quantity.stocking = stocking.stocking
                        INNER JOIN product on product.product = stocking.product
                WHERE
                    session.session = '$id'";

        $result = Database::getInstance()->query($sql);
        $table = $result->fetchAll(PDO::FETCH_OBJ);
        $final = new stdClass;
        $final->session_details = $session_details;
        $final->table = $table;


        echo json_encode($final);
    }

    public static function getRecords()
    {
        global $data, $log;
        $direction = $data->data->direction;
        $date = $data->data->date;
        $station = $data->data->station;

        $arr = [
            'direction' => $direction,
            'date' => $date,
            'station' => $station
        ];
        $session_details = ModelFactory::getSingleRecord('session', $arr);

        //write a custom  sql for retrieving products and quantities
        $sql = "SELECT 
                    product.name, quantity.value
                FROM 
                    quantity 
                        INNER JOIN session ON quantity.session = session.session
                        INNER JOIN stocking ON quantity.stocking = stocking.stocking
                        INNER JOIN product on product.product = stocking.product
                WHERE
                    session.session = '$session_details->session'";

        $result = Database::getInstance()->query($sql);
        $table = $result->fetchAll(PDO::FETCH_OBJ);
        $final = new stdClass;
        $final->session_details = $session_details;
        $final->table = $table;

        $log->info($final);
        echo json_encode($final);
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
        $date = $data->data->date;

        $session = ModelFactory::getSingleRecord('Session', ['date' => $date, 'direction' => 'out']);

        if (!$session) {
            return http_response_code(404);
        }
        $sql = "SELECT 
                    name, barcode, sell_price, value 
                FROM product 
                    INNER JOIN stocking on stocking.product = product.product 
                    INNER JOIN quantity on stocking.stocking = quantity.stocking 
                WHERE quantity.session = $session->getPrimary()";

        $result = Database::getInstance()->query($sql);

        $log->info(json_encode($result->fetchAll()));

        echo json_encode($result->fetchAll());
    }
    //get report for all stock taken at a particular day and station
    public function stockReport()
    {
        global $data, $log;
        $date = $data->data->date;
        $station = $data->data->station;

        /**
         * We start by calculating the opening stock. 
         * Whats is opening stock??
         * This is and *last* stock taken prior to the date provided
         * We first calculate the maximum date less than the date provided.
         * Then we get the stock related to that date
         * We then get the added stock.
         * what is added stock??
         * This is stock whose session direction is inward to the counter.
         * If we cannot get opening stock for the date provided means that we are either far ahead of time and prompt 
         * the user to select a different date meaning the report for the provided date cannot be produced
         * 
         */
        
        //create an array to hold the end result
         $arr = array();
         $previous_dates = "SELECT 
                                MAX(date) as max_date 
                            FROM 
                                session 
                            WHERE date < '$date' AND station = '$station' AND direction ='stock' ";
        
        $session_sql = "SELECT 
                        session.*
                    FROM 
                        session 
                            INNER JOIN ($previous_dates) as prev_date ON prev_date.max_date = session.date";
        $result = Database::getInstance()->query($session_sql);

        $session = $result->fetch(PDO::FETCH_OBJ);
        if(!$session){
            return http_response_code(404);
        }
        //get items from session
        $opening = ModelFactory::executeModel('quantity', 'getItems', false, $session->session);
        $arr['opening'] = $opening;
        
        echo json_encode($arr);
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