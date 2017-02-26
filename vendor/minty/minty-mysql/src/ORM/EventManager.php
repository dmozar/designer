<?php namespace Minty\MySql\ORM;


class EventManager {
    
    /**
     *
     * @var type 
     */
    private static $events = [];
    
    
    /**
     *
     * @var type 
     */
    private $sql;
    
    
    /**
     *
     * @var type 
     */
    private $time;
    
    
    
    /**
     *
     * @var type 
     */
    private $persist = [];
    
    
    
    /**
     *
     * @var type 
     */
    private $error = null;
    
    
    
    
    /**
     * 
     */
    public function __construct() {

    }
    
    
    
    /**
     * 
     * @return \Minty\Type\NullType
     */
    public function NullType(){
        return new \Minty\Type\NullType;
    }
    
    
    
    /**
     * 
     * @return \Minty\MySql\ORM\EventManager
     */
    public static function get(){
        return new EventManager;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getError(){
        return $this->error;
    }
    
    
    
    /**
     * 
     * @param \Minty\MySql\ORM\Entity $Entity
     */
    public function persist(\Minty\MySql\ORM\Entity & $Entity ){
        
        $this->persist[] = $Entity;
        return $this;
    }
    
    
    
    /**
     * 
     * @return boolean
     */
    public function flush(){
        
        $flush = new \Minty\MySql\ORM\Flush();
        $flush->setPersists( $this->persist );
        
        if( ! $flush->execute() ){
            $this->error = $flush->getError();
            return false;
        } else {
            $this->error = null;
            return true;
        }
        
    }
    
    
    /**
     * 
     */
    public function remove(){
        $remove = new \Minty\MySql\ORM\Remove();
        $remove->setPersists( $this->persist );
        $remove->execute();
    }
    
    
    
    
    /**
     * 
     * @param type $ql
     */
    public function setSql( $sql ){
        $this->sql = $sql;
    }
    
    
    /**
     * 
     * @param type $time
     */
    public function setExecutionTime( $time ){
        $this->time = $time;
    }
    
    
    /**
     * 
     * @param type $name
     * @param type $obj
     */
    public function register($name, $obj){
        
        self::$events[] = [
            'key' => $name,
            'sql' => $this->sql,
            'execution_time' => $this->time,
            'file' => get_class($obj)
        ];
        
        $this->sql = null;
        $this->time = null;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getEvents(){
        return self::$events;
    }
    
    
    public static function getLastEvent(){
        return end( self::$events );
    }
    
}