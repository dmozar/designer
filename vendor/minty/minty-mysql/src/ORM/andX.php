<?php namespace Minty\MySql\ORM;

use Minty\MySql\Exception\ExceptionConnect;
use Minty\Cache;
use PDO;

class andX {
    
    private $list = [];
    

    public function __construct() {  }
    
    
    public function add( $query ){
        $this->list[] = $query;
    }
    
    public function getQuery(){
        
        $sql = " (";
        
        $sql.= implode(' AND ', $this->list);
        
        $sql .= ") ";
        
        return $sql;
    }
    
    
}