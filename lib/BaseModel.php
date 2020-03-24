<?php

/**
 * @todo create sql generator for child clasees
 * @todo
 */
require_once 'Database.php';

/**
 * Create an abstract base model that other models will extend.
 * The base model will create an instance of the database, this one instance will be used by all database operations
 * The base model will provide two methods for accessing data from enitiy tables
 * What is a model? a model is a class that represents a datatbase entity
 * Models have entity columns as class properties, 
 * This type of design pattern is called the data access object pattern. 
 * It is used to separate low level data accessing API or operations from high level business services.
 * The idea was gotten from Njuguna's Real estate system
 */
abstract class BaseModel
{
    //save the table name
    protected static String $tableName;
    //save the instance of a database
    protected static $db;

    public function __construct()
    {
        //get the instance of a db
        self::$db = Database::getInstance();

        //set the table name
        self::$tableName = strtolower(get_class($this));
    }

    /**
     * @method create()
     * This method will involve creating a new record in an entity table 
     * We set it as abstract because each entity will have a different implementation of create
     * It should return the inserted row which should be a model representation
     * @param array of arguments for object creation
     * @return object Object representation of the model
     */
    abstract static function create(object $args): BaseModel;

    /**
     * @method records
     * This method will be used to fetch a list/ array of rows from an entity
     * We establish a general implementation because fetching rows from a table is more or less the same procedure
     * The method can be ovveriden in the child clasees for a more fine tuned fetch method or when fetching from 
     * related tables.
     * The return type is an array because we use the pdo method of fetchAll()
     */
    public function records(): array
    {
        //sql for fetching items
        $sql = "SELECT * FROM " . self::$tableName;

        //store the results in a cursor variable
        $result = self::$db->query($sql);

        //error checking
        if ($result === false) throw new Exception("Failed to fetch items " . self::$db->errorInfo());

        //return a class representation of the resultset
        return $result->fetchAll(PDO::FETCH_CLASS, ucfirst(self::$tableName));
    }
    /**
     * @method record
     * Method Used to fetch for a single row in an entity. 
     * @param $args
     * It takes an array of key/value pairs as $args which will be used to build the where clause in the sql
     */
    public function record($args)
    {
        //get the size of arguments intended to build sql;
        $size = sizeof($args);
        $whereArgs = "WHERE ";

        if ($size < 1) {
            //no arguments were passed. throw exception
            throw new LengthException("Number of arguments cannot be zero");
        } else if ($size == 1) {
            foreach ($args as $key => $value) :
                $whereArgs = $whereArgs . " $key = '$value'";
                break;
            endforeach;
        } else {
            foreach ($args as $key => $value) :
                $whereArgs = $whereArgs . " $key = '$value' AND";
            endforeach;
            //remove the trailing "AND"
            $whereArgs = explode(" ", trim($whereArgs));
            array_pop($whereArgs);
            $whereArgs = implode(" ", $whereArgs);
        }
        //sql containing the where  parameters
        $sql = "SELECT * FROM " . self::$tableName . " " . $whereArgs;

        //svae thee result in a cursor variable
        $result = self::$db->query($sql);

        //error checking
        if ($result === false) {
            throw new Exception("Failed to fetch item " . self::$db->error);
        }
        //return a class object of the result
        return $result->fetchObject(ucfirst(self::$tableName));
    }

    public function __toString()
    {
        return json_encode($this);
    }
    /**
     * @method getPrimary
     * Gets the primary key of the the instance model. 
     * 
     */
    abstract function getPrimary(): int;
}

/**
 * Create a class ModelController thet will be used to interact with child classes.
 * The class will be primarily made up of static methods 
 */
class ModelController
{
    /**
     * @method createModel
     * This method will be used to call a method then invoke the create function on it
     * The reason we i use this intermediate way rather than just calling the model::create()
     * is because i want to do some error checking to see if the actual model exists 
     * @param string $modelName The name of model
     * @param array $data What is to be inserted in the model
     * @return object Return an object of type BaseModel
     */
    public static function createModel(string $modelName, stdClass $data): BaseModel
    {
        //convert to Sentence case 
        $modelName = ucfirst($modelName);

        if (class_exists($modelName)) {
            return $modelName::create($data);
        } else {
            throw new BadMethodCallException("class " . $modelName . " not found");
        }
    }

    /**
     * @method getModelRecords
     * Used to get the records for that particular model 
     * @param string modelName The name of the model
     * @return array This will return an array of model objects
     */
    public static function getModelRecords(string $modelName): array
    {
        $modelName = ucfirst($modelName);
        if (class_exists($modelName)) {
            $model = new $modelName;
            return $model->records();
        } else {
            throw new BadMethodCallException("class " . $modelName . " not found");
        }
    }

    /**
     * Create function getSingleRecord
     * modelname represents the name of the model to access
     * @param  string modelName
     * args is an array o associative key value pairs used for the where clause
     * @param array args
     * 
     * @return object a single object representation of the record
     */
    public static function getSingleRecord(string $modelName, array $args)
    {
        $modelName = ucfirst($modelName);
        if (class_exists($modelName)) {
            $model = new $modelName;
            return ($model->record($args));
        } else {
            throw new BadMethodCallException("class " . $modelName . " not found");
        }
    }
}

class Product extends BaseModel
{
    public string $product, $name, $image, $category;

    //set a nullable parameter barcode because it can either have a value or return null
    public ?string $barcode;
    public function __construct()
    {
        parent::__construct();
    }

    public static function create($args): Product
    {
        $product = new Product;
        $product->name = $args->name;
        $product->image = $args->image;
        $product->category = $args->category;
        $product->barcode = $args->barcode;


        $sql = "INSERT INTO " . self::$tableName . "(name, barcode, image, category) 
                VALUES (
                        :name, 
                        :barcode, 
                        (SELECT image FROM image WHERE name = :image), 
                        (SELECT category FROM category WHERE name = :category)
                        )";
        $stmt = self::$db->prepare($sql);

        $stmt->bindParam(':name', $product->name);
        $stmt->bindParam(':barcode', $product->barcode);
        $stmt->bindParam(':category', $product->category);
        $stmt->bindParam(':image', $product->image);

        //check if it has inserted
        if ($stmt->execute()) {
            $product->product = self::$db->lastInsertId();
        }

        return $product;
    }

    //we override the default records from the basemodel because we retirve data from related entities
    public function records(): array
    {
        //sql for retrieving products from database
        $sql = "SELECT 
                    product.name, product, product.barcode, image.name as image, category.name as category
                FROM 
                    product
                        INNER JOIN image on product.image = image.image 
                        INNER JOIN category on product.category = category.category 
                ORDER BY 
                    product.name ASC";

        $result = self::$db->query($sql);
        return $result->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }

    public function getPrimary(): int
    {
        if (!isset($this->product)) {
            throw new InvalidArgumentException();
        }
        return intval($this->product);
    }
}

class Quantity extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function create($args): Quantity
    {

        $quantity = new Quantity;
        $quantity->session = $args->session;

        
        $sql = "INSERT INTO quantity(stocking, `session`, `value`) 
                VALUES(
                    (SELECT stocking FROM stocking WHERE stocking.product = :product), 
                    :session, 
                    :value
                    )";

        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':product', $quantity->product);
        $stmt->bindValue(':session', $quantity->session);
        $stmt->bindParam(':value', $quantity->value);

        foreach($args->data as $key => $value):
            $quantity->product = $value->product;
            $quantity->value = $value->quantity;

            try {
                $stmt->execute();
            } catch (PDOException $e) {
                //handle the exception
                die($e->getMessage());
            }
        endforeach;

        //return an empty model;
        return new Quantity;
    }
    //given a session id, get all the products and related quantites
    public function getItems($session)
    {
        $sql = "SELECT 
                    name, barcode, sell_price, value
                FROM 
                    quantity
                        INNER JOIN stocking ON stocking.stocking = quantity.stocking
                        INNER JOIN product ON stocking.product = product.product
                WHERE 
                    quantity.session= '$session'";
        $result = self::$db->query($sql);
        return $result->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPrimary(): int
    {
        return 0;
    }
}


class Session extends BaseModel
{
    public String $date, $direction, $station, $staff, $session;
   
    public function __construct()
    {
        parent::__construct();
    }


    public static function create($args): Session
    {
        $session = new Session;
        $session->station = $args->station;
        $session->staff = $args->staff;
        $session->direction = $args->direction;
        if(isset($args->date)){
            $session->date = $args->date;
            $date = new DateTime();
            $parsed = $date->format('Y-m-d H:i:s');
        }else{
            $date = new DateTime();
            $session->date = $date->format('Y-m-d H:i:s');
        }

        $sql = "INSERT INTO " . self::$tableName .
            "(staff, station, direction, date) 
            VALUES
            ('$session->staff', '$session->station', '$session->direction', '$session->date')";

            try {
            if (self::$db->exec($sql)) {
                $session->session = self::$db->lastInsertId();
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $session;
    }

    //given a session get the details about staff and station
    public static function getSessionDetails($sessionId)
    {
        $sql = "SELECT 
                    staff.name, station.name, session.direction, session.date 
                FROM
                    session 
                        INNER JOIN staff ON session.staff = staff.staff
                        INNER JOIN station ON session.station = station.station
                WHERE session = '$sessionId'";

        $result = self::$db->query($sql);
        return $result->fetchObject();
    }
    
    public function getPrimary(): int
    {
        if (!isset($this->session)) {
            throw new InvalidArgumentException();
        }
        return intval($this->session);
    }
}

class Staff extends BaseModel
{
    public string $staff, $name, $image;
    public function __construct()
    {
        parent::__construct();
    }

    public static function create($args): Staff
    {
        $staff = new Staff;
        $staff->name = $args->name;
        $staff->image = $args->image;

        //check if the staff exists. return if true otherwise insert new 
        $sql = "INSERT into " . self::$tableName . "(name, image) 
                VALUES(
                    :name,
                    (SELECT image FROM image WHERE name = :image)
                )";
        $stmt = self::$db->prepare($sql);

        $stmt->bindParam(':name', $staff->name);
        $stmt->bindParam(':image', $staff->image);

        //check if it has inserted
        if ($stmt->execute()) {
            $staff->staff = self::$db->lastInsertId();
        }

        return $staff;
    }


    public function getPrimary(): int
    {
        if (!isset($this->staff)) {
            throw new InvalidArgumentException();
        }
        return intval($this->staff);
    }
}

class Station extends BaseModel
{
    public string $station, $name;
    // public ?array $layout;


    public function __construct()
    {
        parent::__construct();
    }

    public static function create($args): Station
    {
        $station = new Station;
        $station->name = $args->name;

        $sql = "INSERT into ". self::$tableName. "(name)VALUES('$station->name')";

        if (self::$db->exec($sql) >0){
            $station->station = self::$db->lastInsertId();
        } 

        return $station;
    }

    public function getPrimary(): int
    {
        if (!isset($this->station)) {
            throw new InvalidArgumentException();
        }
        return intval($this->station);
    }
}
