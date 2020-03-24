<?php
// use Monolog\Logger;
// use Monolog\Handler\StreamHandler;

//  // create a log channel
//  $log = new Logger('POS');
//  $log->pushHandler(new StreamHandler(__DIR__ . '/../app.log', Logger::INFO));


/**
 * @todo create sql generator for child clasees
 * @todo
 */
require_once 'Database.php';

abstract class BaseModel
{
    //save the table name
    protected static String $tableName;
    protected static $db;

    //save data relating to an enetiti
    // protected array $tableData;


    //create an array to store class variables 
    protected array $attrs;
    public function __construct($data = null)
    {
        //get the instance of a db
        self::$db = Database::getInstance();
        // $x = get_class($this);
        //set the table name
        self::$tableName = strtolower(get_class($this));


        if (!is_null($data)) {
            $this->attrs = $data;
            $data = null;
            $this->create();
        }
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->attrs)) {
            throw new Exception("The requested key " . $name . " doesnt exist");
        } else return $this->attrs[$name];
    }

    public function __set($name, $value)
    {
        if (!isset($this->attrs)) {
            $this->attrs = array();
        }
        $this->attrs[$name] = $value;
    }

    abstract function exists(): bool;


    abstract function create(): object;

    //create a method for fetching records from a database 
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

    // create a method for fetching one item from table
    public function record($args)
    {
        //get the size of arguments intended to build sql;
        $size = sizeof($args);
        $whereArgs = "WHERE ";


        if ($size < 1) {
            //no arguments were passed. throw exception
            throw new \LengthException("Number of arguments cannot be zero");
        } else if ($size == 1) {
            foreach ($args as $key => $value) :
                $whereArgs = $whereArgs ." $key = '$value'";
                break;
            endforeach;
        }else{
            foreach ($args as $key => $value) :
                $whereArgs = $whereArgs." $key = '$value' AND";
            endforeach;
            //remove the trailing "AND"
            $whereArgs = explode(" ", trim($whereArgs));
            array_pop($whereArgs);
            $whereArgs = implode(" ", $whereArgs);
        }
        //sql containing the where  parameters
        $sql = "SELECT * FROM " . self::$tableName ." ". $whereArgs;

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

    abstract function getPrimary(): int;
}

class ModelFactory
{
    public static function createModel(string $modelName, array $data): BaseModel
    {
        $modelName = ucfirst($modelName);
        if (class_exists($modelName)) {
            return new $modelName($data);
        } else {
            throw new BadMethodCallException("class " . $modelName . " not found");
        }
    }

    public static function getModelRecords(string $modelName): array
    {
        $modelName = ucfirst($modelName);
        if (class_exists($modelName)) {
            $model = new $modelName(null);
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

    public static function executeModel(string $class, string $method, bool $state = false, $args = null){
        if (class_exists($class)) {
            //check if method exists
            if ($state) {
                return (method_exists($class, $method)) ? $class::$method($args) : die("Class $class doesnt contain method $method");
            } else {
                $obj = new $class;
                return (method_exists($obj, $method)) ? $obj->$method($args) : die("Class $class doesnt contain method $method");
            }
        } else {
            die("No class by the name $class");
        }
    }
}

class Product extends BaseModel
{
    public string $product, $name, $image, $category;
    public $barcode;
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
    }

    public function create(): Product
    {

        if (!$this->exists()) {
            $sql = "INSERT INTO " . self::$tableName . "(name, barcode, image) VALUES('$this->name', '$this->barcode', '$this->image')";
            if (self::$db->exec($sql) < 1) throw new Exception("Failed to insert product " . self::$db->errorInfo());
        }
        return self::record(['barcode'=> $this->barcode]);
    }
    public function exists(): bool
    {
        $sql = "SELECT COUNT(*) FROM " . self::$tableName . " WHERE barcode = '$this->barcode'";
        $result = self::$db->query($sql);
        return (($result->fetchColumn() > 0) ? true : false);
    }

    public function records(): array
    {
        $sql = "SELECT 
                    product.name, product.product, product.barcode, image.name as image, category.name as category
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
    public int $value, $session, $stocking;
    public String $sql;
    public $stmt;

    public function __construct(?array $data = null)
    {
        parent::__construct($data);
    }

    public function create(): Quantity
    {
        return $this;
    }
    //given a session id, get all the products and related quantites
    public function getItems($session){
        $sql = "SELECT 
                    name, barcode, sell_price, value
                FROM 
                    product 
                        INNER JOIN stocking ON stocking.product = product.product
                        INNER JOIN quantity ON stocking.stocking = quantity.stocking
                WHERE 
                    quantity.session= '$session'";
        
        if(!$result = self::$db->query($sql)){
            return $result;
        }else{
            return $result->fetchAll(PDO::FETCH_OBJ);
        }
    }

    public function exists(): bool
    {
        return false;
    }

    public function getPrimary(): int
    {
        return 0;
    }
}


class Session extends BaseModel
{
    public String $date, $direction;
    public int $station, $staff, $session, $_id;
    

    public function __construct(?array $data = null)
    {
        parent::__construct($data);
    }


    public function create(): Session
    {
        $this->station = intval(parent::__get('station'));
        $this->staff = intval(parent::__get('staff'));
        $this->direction = parent::__get('direction');
        $this->date = parent::__get('date');

        $sql = "INSERT INTO " . self::$tableName .
            "(staff, station, direction, date) 
            VALUES
            ('$this->staff', '$this->station', '$this->direction', NOW())";

        try {
            $res = self::$db->exec($sql);
            if ($res != false) {
                $this->_id = self::$db->lastInsertId();
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $this;
    }

    public function records(): array
    {
        $sql = "SELECT 
                    *
                FROM 
                    session
                ORDER BY 
                    date ASC";
        
        $result = self::$db->query($sql);
        return $result->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }
    //given a session get the details about staff and station
    public static function getSessionDetails($sessionId){
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
    public function exists(): bool
    {
        return false;
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
    public string $staff, $name;
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
    }

    public function create(): Staff
    {
        //get the name of tthe staff member saved in parent class
        $this->name = parent::__get('name');

        //check if the staff exists. return if true otherwise insert new 
        if (!$this->exists()) {
            $sql = "INSERT into "
                . self::$tableName
                . "(name) 
                    VALUES('$this->name')";

            // echo $sql;
            if (self::$db->exec($sql) < 1) throw new Exception("Failed to insert product " . self::$db->errorInfo());
        }

        return $this;
    }



    public function exists(): bool
    {
        $sql = "SELECT COUNT(*) FROM " . self::$tableName . " WHERE name = '$this->name'";
        $result = self::$db->query($sql);
        return (($result->fetchColumn() > 0) ? true : false);
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


    public function __construct(?array $data = null)
    {
        parent::__construct($data);
    }

    public function exists(): bool
    {
        $nm = self::$name;
        $sql = "SELECT COUNT(*) FROM " . self::$tableName . " WHERE name = '$nm'";
        $result = self::$db->query($sql);
        return (($result->fetchColumn() > 0) ? true : false);
    }

    public function create(): Station
    {

        if (!self::exists()) {
            $sql = "INSERT into "
                . self::$tableName
                . "(name) 
                    VALUES('$this->name')";

            // echo $sql;
            if (self::$db->exec($sql) < 1) throw new Exception("Failed to insert product " . self::$db->errorInfo());
        }


        return $this;
    }

    public function getPrimary(): int
    {
        if (!isset($this->station)) {
            throw new InvalidArgumentException();
        }
        return intval($this->station);
    }
}
