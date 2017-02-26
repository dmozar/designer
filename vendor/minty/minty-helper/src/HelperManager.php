<?php namespace Minty\Helper;

class HelperManager {
    
    /**
     *
     * @var type 
     */
    private static $helpers = [];
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    /**
     * 
     * @return \Minty\Helper\HelperManager
     */
    public static function get(){
        return new HelperManager; 
    }
    
    
    /**
     * 
     * @param type $name
     * @return type
     */
    public function load( $name ){
        
        if(array_key_exists($name, self::$helpers))
                return;
        
        $path = __DIR__ . '/helpers/' . $name . '.php';
        
        if(file_exists($path)) {
            include_once $path;
            self::$helpers[$name] = true;
        } else {
            throw new \Exception('Helper ('.$name.') does not exists in ' . $path);
        }
    }
    
    
    
}