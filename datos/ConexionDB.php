<?php
require_once 'vendor/autoload.php';

class ConexionDB {
    

private static $instance = null;
    private $conn;
    
    private function __construct(){
        //para utilizacion del archivo .env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();
        
        $host = $_ENV['DB_HOST'];
        $db_name = $_ENV['DB_NAME'];
        $dsn = "pgsql:host=$host;dbname=$db_name";
        $db_username  = $_ENV['DB_USERNAME'];
        $db_password = $_ENV['DB_PASSWORD'];
        $this->conn = new \PDO($dsn, $db_username, $db_password);
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new ConexionDB();
        }
        return self::$instance;
    }

    public function getConexion(){
        return $this->conn;
    }
}