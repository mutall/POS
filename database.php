
<?php
require_once 'config.php';
  /*
   * PDO Database Class
   * Connect to database
   * Create prepared statements
   * Bind values
   * Return rows and results
   */
  class Database extends PDO{
    private $host = HOST;
    private $user = USERNAME;
    private $pass = PASSWORD;
    private $dbname = DBNAME;
    
    private $error;

    public function __construct(){
      // Set DSN
      $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      );

      // Create PDO instance
      try{
        parent::__construct($dsn, $this->user, $this->pass, $options);
      } catch(PDOException $e){
        $this->error = $e->getMessage();
        die($this->error);
      }
    }
  }