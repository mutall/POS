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
    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $pass = DB_PASSWORD;
    private string $dbname = DB_NAME;
    public string $error;
    
    //save the instance of a db
    private static ?PDO $instance;
    
    
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

    public static function getInstance():PDO{
      if(self::$instance == null){
        new Database();
      }

      return self::$instance;
    }
  }