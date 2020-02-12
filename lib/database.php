<?php
// since our system will be making multiple calls to the db a single global instance of a db is needed
// we use the singleton pattern to access the database 

require_once 'config.php';
  /*
   * PDO Database Class
   * Connect to database
   * Create prepared statements
   * Bind values
   * Return rows and results
   */
  class Database{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASSWORD;
    private $dbname = DB_NAME;
    public $error;
    
    //save the instance of a db
    private static $instance = null;
    
    
    private function __construct(){
      // Set DSN
      $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      );

      // Create PDO instance
      try{
        self::$instance = new PDO($dsn, $this->user, $this->pass, $options);
      } catch(PDOException $e){
        $this->error = $e->getMessage();
        die($this->error);
      }
    }

    public static function getInstance(){
      if(self::$instance == null){
        new Database();
      }

      return self::$instance;
    }
  }