<?php namespace Minty;

use Minty\Cache;

class Autoload {
    
    
    /**
     *
     * @var type 
     */
    private $paths = [];
    
    
    /**
     *
     * @var type 
     */
    private $exits = [];
    
    /**
     * 
     */
    public function __construct( $_cache ) {
        
        if(! $this->paths){
            $json = json_decode(file_get_contents(PATH_ROOT . 'composer.json'),true);
            
            $list = $json['autoload']['psr-4'];
            
            $this->paths = $list;
            
            $cache = new Cache();

            $map = $cache->getMap( $_cache );
            
            $this->addPath ( $map );
        }
        
        spl_autoload_register(array($this, 'loader'));
    }
    
    
    
    /**
     * 
     * @param type $path
     */
    public function addPath( $path = [] ){
        
        $this->paths = array_merge ($path, $this->paths);
        
    }
    
    
    
    /**
     * 
     * @param type $className
     * @return type
     */
    private function loader($className) {
        
        if(class_exists($className)) return;
        
        if(array_key_exists($className, $this->exits)) return;
        
        foreach ($this->paths as $alias => $path){
            
            $cls = str_replace('\\', '/', $className);
            $als = str_replace('\\', '/', $alias);
            
            if( stripos ($cls, $als) !== false ){
                
                $name = str_replace($als, '', $cls);
                
                $file_path = str_replace('//', '/',$path . $name) . '.php';
                
                if(file_exists($file_path)){
                    include_once $file_path;
                    $this->exits[$className] = $className;
                    
                    return true;
                }
            }
        }
        
        throw new \Exception('Objec/class ' . $className . ' not found!');
        
    }
    
}