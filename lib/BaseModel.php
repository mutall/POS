<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * @todo create sql generator for child clasees
 * @todo
 */
require_once 'Database.php';

abstract class BaseModel
{
    //if the model should have timestamps
    protected $timestamps = true;

    //database instance 
    protected static $db;
    //save the table name
    protected String $tableName;

    //save data relating to an enetiti
    protected array $tableData;

    public function __construct(){
        //get the instance of a db
        self::$db = Database::getInstance();
        //set the table name
        $this->tableName = strtolower(get_class($this));
    
    }

    
    abstract function create($arg):object;

    //create a method for fetching records from a database 
    public function records():array{
        //sql for fetching items
        $sql = "SELECT * FROM ".$this->tableName;

        //store the results in a cursor variable
        $result = self::$db->query($sql);

        //error checking
        if($result===false) throw new Exception("Failed to fetch items ".self::$db->errorInfo());

        //return a class representation of the resultset
       return $result->fetchAll(PDO::FETCH_CLASS, $this->tableName);
    }

    // create a method for fetching one item from table
    public function record($args, $value):object{
        //sql containing the where  parameters
        $sql = "SELECT * FROM ".$this->tableName. " WHERE $args = '$value'";

        //svae thee result in a cursor variable
        $result = self::$db->query($sql);

        //error checking
        if($result===false){
            throw new Exception("Failed to fetch item ".self::$db->error);
        }
        //return a class object of the result
        return $result->fetch(PDO::FETCH_CLASS, $this->tableName);
    }

    public function getId($args, $value){
         //sql containing the where  parameters
         $sql = "SELECT ".$this->tableName. " FROM ".$this->tableName. " WHERE $args = '$value'";

         //svae thee result in a cursor variable
         $result = self::$db->query($sql);
 
         //error checking
         if($result===false){
             throw new Exception("Failed to fetch item ".self::$db->error);
         }
         //return a class object of the result
         return $result->fetch(PDO::FETCH_CLASS, $this->tableName);
    }

    public function __toString()
    {
        return json_encode($this);
    }
    
}

class Business extends BaseModel{
    private String $name, $address, $location, $telephone;
    private Owner $owner;

    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg):Business
    {
        $business = new Business;
        $business->name = $arg->name;
        $business->address = $arg->address;
        $business->location = $arg->location;
        $business->telephone = $arg->location;
        $business->owner = $arg->owner;

        $sql = "INSERT INTO ".$this->tableName." (name, address, location, telephone, owner) VALUES('')" ;

        return $business;
    }
}

class Owner extends BaseModel{
    private String $username;

    public function __construct()
    {
        parent::__construct();
    }


    public function create($arg):Owner
    {
        $owner = new Owner;
        $owner->username = $arg->username;

        $sql =  "INSERT INTO ".$this->tableName."(username) VALUES('$arg->username')";
        
        
        // echo $sql;
         if(self::$db->exec($sql) <1) throw new Exception("Failed to insert product ". self::$db->errorInfo());;
         
         return $owner;
    }
}

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

class Quantity extends BaseModel{
    private int $value;
    private Session $session;
    private Stocking $stocking;
    private String $sql;
    private $stmt;

    public function __construct()
    {
        parent::__construct();
        $sql = "INSERT INTO ".$this->tableName." (value, stocking, session) VALUES(:value, :stocking, :session)";
        $this->stmt = self::$db->prepare($sql);
    }

    public function create($arg):Quantity
    {
        $quantity = new Quantity();
        $quantity->session = $arg->session;
        $quantity->stocking = $arg->stocking;
        $quantity->value = $arg->value;

        $this->stmt->bindParam(':value', $quantity->value);
        $this->stmt->bindParam(':stocking', $quantity->stocking->id);
        $this->stmt->bindParam(':session', $quantity->session->id);
        try {
            $this->stmt->execute();
        }catch (Exception $e){
            die($e->getMessage());
        }

        return $quantity;
    }
}


class Session extends BaseModel
{
    private String $date;
    private String $direction;
    private Station $station;
    private Staff $staff;


    public function __construct()
    {
        parent::__construct();

        // create a log channel
        $log = new Logger('POS');
        $log->pushHandler(new StreamHandler(__DIR__.'/../../app.log', Logger::INFO));
        // $log->info(json_encode($this->tableData));
        // var_dump($this->tableData);
    }

    public function create($arg): Session
    {
        $session = new Session();
        $session->station = $arg->station;
        $session->staff = $arg->staff;
        $session->direction = $arg->direction;
        $session->date = $arg->date;

        $sql = "INSERT INTO " . $this->tableName .
            "(staff, station, direction, date) 
            VALUES
            ('$this->staff->staff', '$this->station->station', '$this->direction', '$this.date')";
        try {
            $res = self::$db->exec($sql);
            if ($res != false) {
                $session->id = self::$db->lastInsertId();
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return new Session();
    }
}

class Staff extends BaseModel{
    public string $name;
    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg):Staff
    {
        $staff = new Staff();
        $staff->name = $arg;


        $sql = "INSERT into "
            . $this->tableName
            . "(name) 
                    VALUES('$staff->name')";

        // echo $sql;
        if (self::$db->exec($sql) < 1) throw new Exception("Failed to insert product " . self::$db->errorInfo());

        return $staff;
    }
}

class Station extends BaseModel{
    public String $name;


    public function __construct()
    {
        parent::__construct();
    }

    public function create($arg):Station
    {
        $station = new Station();
        $station->name = $arg->name;

        $sql = "INSERT into "
            . $this->tableName
            . "(name) 
                    VALUES('$station->name')";

        // echo $sql;
        if (self::$db->exec($sql) < 1) throw new Exception("Failed to insert product " . self::$db->errorInfo());

        return $station;
    }
}

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
