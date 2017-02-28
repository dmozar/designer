<?php namespace Minty\MySql\ORM;

use ReflectionClass;

class Annotation {
    
    
    const OneToMany     = '/@type="OneToMany"/';
    const OneToOne      = '/@type="OneToOne"/';
    const Cell          = '/@cell="(.*)"/';
    const Relation      = '/@type="(.*)"/';
    const Target        = '/@target="(.*)"/';
    const RelationKey   = '/@relation="(.*)"/';
    const PrimaryKey    = '/@primary/';
    const Primary       = '/@type="primary"/';
    const Tablename     = '/@table="(.*)"/';
    const Uniq          = '/@uniq="(.*)"/';
    
    /**
     *
     * @var array 
     */
    private static $reglectionList = [];
    
    
    /**
     *
     * @var array
     */
    private static $varList = [];
    
    
    
    /**
     *
     * @var array
     */
    private static $propertyList = [];
    
    
    /**
     *
     * @var array
     */
    private static $docsList = [];
    
    
    
    /**
     *
     * @var Minty\MySql\ORM\Annotation; 
     */
    private static $an;
    
    
    /**
     * 
     */
    public function __construct() { }
    
    
    
    /**
     * 
     * @return type
     */
    public static function get(){
        
        if(self::$an)
            return self::$an;
        
        self::$an = new Annotation; 
        
        return self::$an;
    }
    
    
    /**
     * 
     * @param type $Entity
     * @return type
     */
    public function getEntityDocs($Entity){
        
        $r = $r = new ReflectionClass( $Entity );
        return  $r->getDocComment();
    }
    
    
    /**
     * 
     * @param type $object_string
     * @return ReflectionClass
     */
    public function getReflectionClass( $object_string ){

        if($object_string instanceof ReflectionClass)
            return $object_string;
        
        if(is_object( $object_string ))
            $object_string = get_class ( $object_string );
        
        if(array_key_exists($object_string, self::$reglectionList))
                return self::$reglectionList[$object_string];
        
        self::$reglectionList[$object_string] = new \ReflectionClass( $object_string );
        
        return self::$reglectionList[$object_string];
    }
    
    
    /**
     * 
     * @param object $object
     * @return type
     */
    public function getObjectVars( $object ){
        
        $key = null;
        
        if( !is_object( $object ) ){
            $key = $object;
            $object = new $object;
        } else {
            $key = get_class( $object );
        }
        
        if(array_key_exists($key, self::$varList))
                return self::$varList[$key];
        
        self::$varList[$key] = get_object_vars($object);
        
        return self::$varList[$key];
    }
    
    
    /**
     * 
     * @param ReflectionClass $object
     * @param type $property
     * @return type
     */
    public function getClassProperty( $object, $property ){
        
        $key = null;
        
        if(is_object( $object )) 
            $key = get_class ( $object );
        else 
            $key = $object;
        
        if(array_key_exists($key, self::$propertyList))
                if(array_key_exists($property, self::$propertyList[$key]))
                        return self::$propertyList[$key][$property];
        
        if( ! $object instanceof ReflectionClass)
            $ReflectionClass = $this->getReflectionClass($key);
        else 
            $ReflectionClass = $object;
        
        $docs = $ReflectionClass->getProperty($property)->getdoccomment();

        if( ! isset(self::$propertyList[$key]))
            self::$propertyList[$key] = array();
        
        self::$propertyList[$key][$property] = $docs;
        
        return $docs;
    }
    
    
    /**
     * 
     * @param type $object_reflection
     * @param type $var
     * @param type $regex
     * @return boolean
     */
    public function is_defined_property( $object_reflection, $var, $regex){
        
        $key = null; 
        
        if(is_object($object_reflection))
            $key = get_class ( $object_reflection);
        else 
            $key = $object_reflection;

        if(isset(self::$docsList[$key]))
            if(isset(self::$docsList[$key][$var])) {
                return self::$docsList[$key][$var] == $regex ? true : false;
            }
        
        if( ! $object_reflection instanceof ReflectionClass ){
            $object_reflection = $this->getReflectionClass($object_reflection);
        }
        
        $docs = $this->getClassProperty($object_reflection, $var);
        
        preg_match($regex, $docs, $match);
        
        if(!isset(self::$docsList[$key]))
                self::$docsList[$key] = array();

        if(@$match[0]){
            self::$docsList[$key][$var] = $regex;
            return true;
        }
        return false;
    }
    
    
    /**
     * 
     * @param type $Entity
     * @param type $var
     * @param type $regex
     * @return boolean
     */
    public function readProperty( $Entity, $var, $regex ){

        $vars = $this->getObjectVars( $Entity );
        $propertyDoc = $this->getClassProperty($Entity, $var);

        preg_match($regex, $propertyDoc, $match);

        if($match){
            if(isset($match[1]))
                return $match[1];
            
            if(isset($match[0]))
                return true;
        }

        return null;
    }
    
    
    /**
     * 
     * @param type $classDoc
     * @param type $regex
     * @return type
     */
    public function readDocsProperty( $classDoc, $regex ){
        
         preg_match($regex, $classDoc, $match);
            
            if($match){
                if(isset($match[1]))
                    return $match[1];
            }
    }
    
    
    /**
     * 
     * @param type $Entity
     * @return type
     */
    public function getPrimaryKey($Entity){
        
         $class = get_class( $Entity );
         $vars = get_object_vars( new $class );

         foreach ($vars as $key => $value){
             
             if( $this->readProperty($Entity, $key, self::Primary) ){
                 return $key;
             }
         }
         return null;
    }
    
    
    /**
     * 
     * @param \Minty\MySql\ORM\Entity $Entity
     * @return type
     */
    public function getTable( $Entity ){
        
        if( !is_object( $Entity ))
            $Entity = new $Entity;
        
        $docs = $this->getEntityDocs($Entity);
        
        preg_match('/@table="(.*)"/', $docs, $match);
        
        if(isset($match[1]))
            return $match[1];
    }
    
    
    /**
     * 
     * @param \Minty\MySql\ORM\Entity $Entity
     * @return type
     */
    public function getUniq( $Entity ){
        
        if( !is_object( $Entity ))
            $Entity = new $Entity;
        
        $docs = $this->getEntityDocs($Entity);
        
        preg_match(self::Uniq, $docs, $match);
        
        if(isset($match[1]))
            return $match[1];
    }
    
}