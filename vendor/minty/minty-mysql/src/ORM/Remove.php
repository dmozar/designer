<?php namespace Minty\MySql\ORM;

use PDO;
use Exception;
use Minty\MySql\ORM\Query;

class Remove {
    
    /**
     * @var type 
     */
    private $persists = [];
    
    /**
     * @var type 
     */
    private $conn;

    /**
     * 
     */
    public function __construct() { $this->error = null; }


    /**
     * 
     * @param type $persists
     */
    public function setPersists( $persists = []){ $this->persists = & $persists; }

   
    
    /**
     * 
     * @return boolean
     */
    public function execute(){

        foreach ($this->persists as $i => $entity){
            
            $table      = Annotation::get()->getTable( $entity );
            $primary    = Annotation::get()->getPrimaryKey( $entity );
            $id         = $entity->{$primary};
            if($primary && $table && $primary){
                
                $sql        = 'DELETE FROM `'.$table.'` WHERE `'.$primary.'` = ? ';
                $query      = new Query;
                $this->conn = $query->QueryConnect()->getConnect();
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmth      = $this->conn->prepare( $sql );
                
                $stmth->execute( array($id) );
            }
            
        }
        
    }

}