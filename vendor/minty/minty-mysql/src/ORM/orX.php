<?php namespace Minty\MySql\ORM;

use Minty\MySql\Exception\ExceptionConnect;
use Minty\Cache;
use PDO;

class orX {
    
    private $list = [];
    

    public function __construct() {  }
    
    
    public function add( $query ){
        $this->list[] = $query;
    }
    
    public function getQuery(){
        
        $sql = " (";
        
        $sql.= implode(' OR ', $this->list);
        
        $sql .= ") ";
        
        return $sql;
    }
    
}