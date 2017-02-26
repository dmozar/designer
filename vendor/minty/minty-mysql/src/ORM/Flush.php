<?php namespace Minty\MySql\ORM;

use PDO;
use Exception;
use Minty\MySql\ORM\Query;

class Flush {
    
    /**
     * @var type 
     */
    private $persists = [];
    
    /**
     * @var type 
     */
    private $conn;
    
    /**
     * @var type 
     */
    private $error;

    /**
     * 
     */
    public function __construct() { $this->error = null; }

    /**
     * 
     * @return type
     */
    public function getError(){ return $this->error;  }

    /**
     * 
     * @param type $persists
     */
    public function setPersists( $persists = []){ $this->persists = & $persists; }
    
    /**
     * 
     */
    private function begin(){  $this->conn->beginTransaction(); }

    /**
     * 
     */
    private function commit(){ $this->conn->commit(); }

    /**
     * 
     */
    private function rollback(){ $this->conn->rollBack(); }
    
    /**
     * 
     * @return boolean
     */
    public function execute(){
        
        $query = new Query;
        
        $this->conn = $query->QueryConnect()->getConnect();
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->begin();
        $this->TreeWalkPersistents();
            
        if( ! $this->error ){ $this->commit(); return true;
        } else { $this->rollback(); return false; }
    }
    
    
    /**
     * 
     * @return type
     */
    private function get_connect(){
        $query = new Query;
        $conn  = $query->QueryConnect()->getConnect();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $conn;
    }
    

    /**
     * 
     * @param type $entity
     * @param array $data
     */
    private function entity_primary_key($entity, & $data){
        
        $EntityPrimaryKey   = Annotation::get()->getPrimaryKey( $entity ); 
        $primary_data       = null;
        $a                  = $entity->{$EntityPrimaryKey};
            
        if(is_string($a) || is_integer($a) || is_numeric($a))
            $primary_data = $a;

        $data[$EntityPrimaryKey] = $primary_data;
    }
    
    /**
     * 
     */
    private function TreeWalkPersistents(){
        
        $fileds         = [];
        $this->prepared = [];
        
        foreach ( $this->persists as $u => & $entity ) {

            $rc     = new \ReflectionClass( $entity );
            $vars   = get_object_vars( new $rc->name );
            $data   = [];
            
            $this->entity_primary_key($entity, $data);
            
            foreach ($vars as $key => $value){
                
                $v      = $entity->$key;
                $target = Annotation::get()->readProperty($entity, $key, Annotation::Target);

                if($target === null){

                    if( $v instanceof \DateTime ){
                        $v = $v->format('Y-m-d H:i:s');
                    }
                    if($v !== NULL){
                        if($v instanceof \Minty\Type\NullType) 
                           $v = NULL;
                        $data[$key] = $v;
                    }
                } else {

                    $cell           = Annotation::get()->readProperty($entity, $key, Annotation::Cell);
                    $relation_cell  = Annotation::get()->readProperty($entity, $key, Annotation::RelationKey);

                    if( $v instanceof Entity )
                        if( isset($v->{$relation_cell} ) ){
                            $data[$cell] = $v->{$relation_cell};
                        } else {
                            echo 'Coomit data has some error. Entity cell ' . $relation_cell . ' has not found in ' . @get_class($v);
                        }
                }
            }
            $this->store_data($entity, $data);
        }
    }
    
    
    /**
     * 
     * @param type $data
     * @param type $entity
     * @return boolean
     */
    private function check_unique_record( $data, $entity, $table ){
        
        $uniq = Annotation::get()->getUniq( $entity );
        
        if($uniq){
            $fields = explode(',', $uniq); 
            $f = [];
            $y = [];
            foreach ($fields as $i => $fld){
                $f[$fld] = $fld.'=\''.$data[$fld].'\'';
                $y[$fld] = $fld;
            }
            $lst = implode(',',$y);
            $wrs = implode(' AND ', $f);
            $conn = $this->get_connect();
            $stringSql = 'SELECT COUNT(*) as total FROM `'.$table.'` WHERE ' . $wrs;
            $_stmht = $conn->prepare( $stringSql );
            $_stmht->execute();
            $res = $_stmht->fetch();
            return ($res['total'] > 0) ? false : true;
        }
        
        return true;
    }
    
    
    /**
     * 
     * @param type $entity
     * @param type $data
     */
    public function store_data( & $entity, $data){
        
        $eDosc = Annotation::get()->getEntityDocs( $entity );
        $table = Annotation::get()->readDocsProperty( $eDosc, Annotation::Tablename);

        $c = [];
        $u = [];
        $s = [];
        $p = [];

        foreach ($data as $k=>$v){
            
                $c[$k] = '`'.$k.'`';
                $s[':'.$k] = ':'.$k;
                $p[':'.$k] = $v;
                $u[':u_'.$k] = '`'.$k.'`=:u_'.$k;
                $p[':u_'.$k] = $v;
        }
        
        $cells      = implode(',',$c);
        $inserts    = implode(',',$s);
        $values     = implode(',',$u);
        $continue   = $this->check_unique_record($data, $entity, $table);

        
        $sql        = 'INSERT INTO `'.$table.'` ('.$cells.') VALUES( '.$inserts.') ON DUPLICATE KEY UPDATE ' . $values;
        
        if($continue){
            $stmht = $this->conn->prepare( $sql );

            foreach ($p as $key => $v){

                if(empty($v) || $v === NULL)
                    $stmht->bindValue( $key, NULL,  PDO::PARAM_NULL);

                if(is_numeric($v) || $v === 0)
                    $stmht->bindValue( $key, $v,  PDO::PARAM_INT );

                if(is_string($v))
                    $stmht->bindValue( $key, $v, PDO::PARAM_STR );

                if(is_bool($v))
                    $stmht->bindValue( $key, $v, PDO::PARAM_BOOL );
            }

            try{ 
                $stmht->execute();
                $lastId     = $this->conn->lastInsertId();
                $primaryKey = Annotation::get()->getPrimaryKey( $entity );

                if($lastId){
                    $entity->{$primaryKey} = $lastId;
                }
            } catch(Exception $e){
                $this->error = $e->getMessage();
            }
        }
    }
    
}