<?php namespace Minty\Doctrine;

use Minty\Cache as MintyCache;
use Redis;
use \Doctrine\Common\ClassLoader,
    \Doctrine\ORM\Configuration,
    \Doctrine\ORM\EntityManager,
    \Doctrine\Common\Cache\ArrayCache,
    \Doctrine\ORM\Tools\Setup;  


class DoctrineManager {
    
    
    /**
     *
     * @var type 
     */
    public 
            $em;
    
    /**
     *
     * @var type 
     */
    private 
            $conf,
            $doctrineConfig;
    
    
    private static $self;
    
    
    
    /**
     * 
     * @return type
     */
    public static function get(){
        
        if( self::$self ) return self::$self;
        
        self::$self = new DoctrineManager();
        
        return self::$self;
        
    }
    
    
    /**
     * 
     */
    public function __construct() {
        
        if($this->em) return $this->em;
        
        $Cache = new MintyCache;
        $this->conf = $Cache->get_cached_application_configs('database');
        
        $this->register_Doctrine();
        
        return $this;
    }
    
    
    /**
     * 
     * @return type
     */
    public function EntityManager(){
        return $this->em;
    }
    
    
    /**
     * 
     */
    private function register_Doctrine(){
        
        $this->register_proxi(PATH_ROOT. 'data/proxies'); 
        
        $this->register_model('Entity');
        
        $this->registerClassLoader();
        
        $this->register_configuration();
        
        $this->register_cache();
        
        $this->register_entities('Entity');
        
        $this->set_proxy(PATH_ROOT. 'data/proxies');
        
        $this->register_logger();
        
        $this->register_connection( );

    }
    
    
    /**
     * 
     * @param type $proxy
     */
    public function register_proxi( $proxy ){
        $proxiesClassLoader = new \Doctrine\Common\ClassLoader('proxies',$proxy);
        $proxiesClassLoader->register();  
    }
    
    
    /**
     * 
     * @param type $models
     * @param type $name
     */
    public function register_model( $models, $name = '' ){
        
        $entitiesClassLoader = new \Doctrine\Common\ClassLoader($name, $models );
        $entitiesClassLoader->register();
    }
    
    
    /**
     * 
     */
    public function registerClassLoader(){
        $classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', PATH_ROOT.'vendor/beberlei/DoctrineExtensions/src/');
        $classLoader->register();
    }
    
    
    /**
     * 
     */
    public function register_configuration(){
        
        $this->doctrineConfig = new Configuration;
    }
    
    
    private static $cacheDriver;
    
    
    /**
     * 
     */
    public function register_cache(){
        
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);

        $cacheDriver = new \Doctrine\Common\Cache\RedisCache();
        $cacheDriver->setRedis($redis);
        
        self::$cacheDriver = $cacheDriver;
        
        $this->doctrineCache = new \Doctrine\Common\Cache\ArrayCache;
        $this->doctrineConfig->setMetadataCacheImpl( $this->doctrineCache );
        
        $this->doctrineConfig->setQueryCacheImpl( $cacheDriver );
        $this->doctrineConfig->setResultCacheImpl( $cacheDriver  );
        
    }
    
    
    public static function RemoveCache($name){

        self::$cacheDriver->delete($name);
        
    }
    
    
    /**
     * 
     * @param type $entities
     */
    public function register_entities( $entities ){
        
        $this->doctrineDriverImpl = $this->doctrineConfig->newDefaultAnnotationDriver( array($entities), false );
        $this->doctrineConfig->setMetadataDriverImpl( $this->doctrineDriverImpl );
        $this->doctrineConfig->setQueryCacheImpl( $this->doctrineCache );
        
    }
    
    
    /**
     * 
     * @param type $proxy
     */
    public function set_proxy($proxy){
        
        $this->doctrineConfig->setProxyDir($proxy);
        $this->doctrineConfig->setProxyNamespace('proxy');
        
        $this->doctrineConfig->setAutoGenerateProxyClasses( ENV == "development" ? true : false );
        
    }
    
    
    /**
     * 
     */
    public function register_logger(){
        
        //if(PROFFILER)
        //    $this->doctrineConfig->setSQLLogger(new \extension\Profiler() );
        
    }
    
    
    /**
     * 
     * @param type $connection
     */
    public function register_connection( $connection = array()){
        
        $db = [
            'driver'    => 'pdo_mysql',
            'user'      => $this->conf['user'],
            'password'  => $this->conf['pass'],
            'host'      => $this->conf['host'],
            'dbname'    => $this->conf['db'],
            'charset'  => 'utf8',
                'driverOptions' => array(
                    1002 => 'SET NAMES utf8'
            )
        ];

        $this->em = EntityManager::create( $db , $this->doctrineConfig); 
        
        $config = $this->em->getConfiguration();
        $config->addCustomStringFunction('RAND', 'DoctrineExtensions\Query\Mysql\Rand');
        $config->addCustomStringFunction('STRTODATE', 'DoctrineExtensions\Query\Mysql\StrToDate');
        $config->addCustomStringFunction('DATE_FORMAT', 'DoctrineExtensions\Query\Mysql\DateFormat');
    }
}
