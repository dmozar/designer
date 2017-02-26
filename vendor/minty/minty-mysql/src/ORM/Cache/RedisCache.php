<?php namespace Minty\MySql\ORM\Cache;

use Redis;

class RedisCache {
    
    
    /**
     *
     * @var type 
     */
    private $Cache;
    
    
    /**
     *
     * @var type 
     */
    private $provider;
    
    
    /**
     *
     * @var type 
     */
    private $options;
    
    
    
    /**
     * 
     * @param \Minty\MySql\ORM\Cache $Cache
     * @param type $options
     */
    public function __construct( \Minty\MySql\ORM\Cache $Cache, $options = [] ) {
        
        $this->Cache = $Cache;
        
        $this->options = $options;
    }
    
    
    /**
     * 
     */
    public function connect(){
        
        $this->provider = new Redis;
        
        $this->provider->connect( $this->options['host'], $this->options['port'] );
    }
    
    
    /**
     * 
     * @param type $key
     * @param type $data
     * @param type $lifeTime
     * @return type
     */
    public function set($key, $data, $lifeTime = 300){
        
        if( ! $this->provider ){
            $this->connect();
        }
        
        $this->provider->set($key, serialize($data));
        
        if ($lifeTime > 0) {
            return $this->provider->setex($key, $lifeTime, serialize($data));
        }

        return $this->provider->set($key, $data);
        
    }
    
    
    
    /**
     * 
     * @param type $key
     * @return type
     */
    public function get($key){
        
        if( ! $this->provider ){
            $this->connect();
        }
        
        $data = $this->provider->get($key);
        
        if( $data ) return unserialize ( $data );
        
        return null;
    }
    
    
    
    /**
     * 
     * @param type $key
     */
    public function deleteCache( $key ){
        
        if( ! $this->provider ){
            $this->connect();
        }
        
        $arList = $this->provider->keys("*");
        
        foreach ($arList as $index => $name ){
            
            if(strpos($name, $key) === 0){
                $this->provider->del($name);
            }
        }
    }
    
    
    
    public function flush(){
        
        if( ! $this->provider ){
            $this->connect();
        }
        
        $this->provider->flushall();
        
    }
    
    
}