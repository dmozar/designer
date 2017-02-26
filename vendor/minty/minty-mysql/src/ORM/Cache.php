<?php namespace Minty\MySql\ORM;

use Exception;

class Cache {
    
    /**
     *
     * @var type 
     */
    private $driver;
    
    
    /**
     *
     * @var type 
     */
    private $qb;
    
    
    
    /**
     *
     * @var type 
     */
    private $key;
    
    
    
    /**
     *
     * @var type 
     */
    private $lifetime = 0;
    
    
    /**
     * 
     * @param \Minty\MySql\ORM\QueryBuilder $qb
     */
    public function __construct(  $key) {
        
        $this->key = $key;
    }
    
    
    
    /**
     * 
     * @return \Minty\MySql\ORM\Cache
     */
    public static function get( \Minty\MySql\ORM\QueryBuilder $qb, $key ){
        return new Cache($qb, $key);
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getKey(){
        return $this->key;
    }
    
    
    
    
    /**
     * 
     * @param type $lifetime
     * @return \Minty\MySql\ORM\Cache
     * @throws Exception
     */
    public function useRedisCache( $lifetime = 0 ){
        
        $this->lifetime = $lifetime;
        
        $Cache = new \Minty\Cache();
        
        $config = $Cache->get_cached_application_configs('redis');
        
        if( ! $config )
            throw new Exception('Redis cache configuration does not exist!');
            
        $this->driver = new \Minty\MySql\ORM\Cache\RedisCache( $this, $config );
        
        
        $this->qb->setCache( $this );
        
        return $this;
        
    }
    
    
    
    /**
     * 
     * @param type $data
     */
    public function setResult( $data ){
        
        $hash = md5(serialize($this->qb->getSQL()) . serialize($this->qb->getParams()));
        
        $this->driver->connect();
        
        $this->driver->set($this->key.'_'.$hash, $data, $this->lifetime);
        
    }
    
    
    /**
     * 
     * @return type
     */
    public function getResult( ){
        
        $hash = md5(serialize($this->qb->getSQL()) . serialize($this->qb->getParams()));
        
        $this->driver->connect();
        
        return  $this->driver->get( $this->key.'_'.$hash );
    }
    
    
    
    /**
     * 
     * @param type $key
     * @return \Minty\MySql\ORM\Cache
     */
    public static function instance( $key = null ){
        
        return new Cache($key);
        
    }
    
    
    
    /**
     * 
     */
    public function delete(){
        
        $this->driver->connect();
        
        if($this->key)
        $this->driver->deleteCache( $this->key );
        
    }
    
    
    
    public function flush(){
        
        $this->driver->connect();
        
        $this->driver->flush();
        
    }
    
    
    
    public function useMemchachedcache(){
        
    }
    
    
    
    public function useMemcacheCache(){
        
        
    }
    
    
    public function useArrayCache(){
        
    }
    
    
    
}