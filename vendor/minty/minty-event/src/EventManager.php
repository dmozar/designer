<?php namespace Minty\Event;

use Exception;

class EventManager {
    
    
    /**
     *
     * @var type 
     */
    private static $event;
    
    
    
    /**
     *
     * @var type 
     */
    private static $registerList = [];


    
    /**
     *
     * @var type 
     */
    private static $listeners = [];
    
    
    
    /**
     *
     * @var type 
     */
    private static $seo = [];
    
    

    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    
    /**
     * 
     * @return type
     */
    public static function get(){
        
        if(self::$event) return self::$event;
        
        self::$event = new EventManager;
        
        return self::$event;
    }
    
    
    
    /**
     * 
     * @param type $name
     * @param type $object
     */
    public function register($name, $object){
        
        self::$registerList[$name] = $object;
        
    }
    
    
    
    /**
     * 
     * @param type $name
     * @return type
     */
    public function getRegistered($name){
        
        return self::$registerList[$name];
        
    }
    
    
    
    /**
     * 
     * @param type $trigger_class
     * @param type $key
     */
    public function CreateEventListener($trigger_class, $key){
        
        self::$listeners[$key] = $trigger_class;
        
    }
    
    
    
    /**
     * 
     * @param type $request
     * @param type $args
     */
    public function __invoke($request, $args) {
        
    }
    
    
    
    /**
     * 
     * @param type $e
     * @throws Exception
     */
    public function ListenerEvent( $e ){
        
        foreach (self::$listeners as $key => $value){
            
            $a = explode('::', $value);
            
            if(count( $a ) !== 2)
                throw new Exception('Listener event request call static object string in format namespace::method ');
            
            $namespace = @$a[0];
            $method    = @$a[1];
            
            $namespace::$method($e);
            
        }
        
    }
    
    
    public static function SEO($name, $value){
        
        self::$seo[$name] = $value;
        
    }
    
    
    public static function fromSEO($name){
        
        return isset(self::$seo[$name]) ? self::$seo[$name] : 'undefined seo key ' . $name;
        
    }
    
    
    
}
