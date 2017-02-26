<?php namespace Minty\MySql\ORM;

use Minty\MySql\Exception\ExceptionConnect;
use Minty\Cache;
use PDO;

class Connect {
    
    
     
    protected $connection_string = "mysql:host=%s;dbname=%s;charset=%s";
    
    
    protected $connection;
    
    
    protected $conf;
    
    
    public function __construct() {
        
        $Cache = new Cache;
        $this->conf = $Cache->get_cached_application_configs('database');
    }
    
    
    public function open(){
        
        if( $dbh = new PDO(sprintf($this->connection_string, $this->conf['host'], $this->conf['db'], $this->conf['charset']),  $this->conf['user'], $this->conf['pass'])){
            $dbh->exec( sprintf("set names %s", $this->conf['charset']));
            $this->connection = $dbh;
        }
        
        if( ! $this->connection )
            throw new ExceptionConnect('Cannot connect to MySQl database!');
        
        return $this->connection;
    }
    
}