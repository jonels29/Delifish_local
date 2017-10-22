<?php
 error_reporting(E_ALL ^ E_WARNING);

class Controller
{
    /**
     * @var null Database Connection
     */
    public $db = null;
    public $dbname = null;

    /**
     * @var null Model
     */
    public $model = null;
   

    /**
     * Whenever controller is created, open a database connection too and load "the model".
     */
    function __construct()
    {
        $this->openDatabaseConnection();
        $this->loadModel();
    }

    /**
     * Open the database connection with the credentials from application/config/config.php
     */
    private function openDatabaseConnection()
    {
      
        // generate a database connection, using the PDO connector
        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
        $this->db =    mysqli_connect( DB_HOST,
                                        DB_USER,
                                        DB_PASS,
                                        DB_NAME); 
        $this->dbname = DB_NAME;

    }

    /**
     * Loads the "model".
     * @return object model
     */
    public function loadModel()
    {
        require_once APP . 'model/model.php';
        // create new "model" (and pass the database connection)
        $this->model = new Model($this->db,$this->dbname);


    }
}
