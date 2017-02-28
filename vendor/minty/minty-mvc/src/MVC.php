<?php namespace Minty;

use Minty\Event\EventManager;

class MVC {
    
    /**
     *
     * @var type 
     */
    private $RouteService;
    
    /**
     *
     * @var type 
     */
    private $Route;
    
    /**
     *
     * @var type 
     */
    private $Http;
    
    /**
     *
     * @var type 
     */
    private $options;
    
    /**
     *
     * @var type 
     */
    private $Cache;
    
    /**
     *
     * @var type 
     */
    private $ServiceLocator;
    
    
    /**
     *
     * @var type 
     */
    private $EventManager;
    
    
    /**
     *
     * @var type 
     */
    private $path = null;
    
    
    
    /**
     *
     * @var type 
     */
    public static $modulePath;
    
    
    /**
     *
     * @var type 
     */
    protected static $user;
    
    
    
    /**
     * 
     * @param \Minty\Route\RouteService $RouteService
     */
    public function setRoute(\Minty\Route\RouteService $RouteService ){
        
        $this->RouteService = $RouteService;
        
        $this->Http = ($this->RouteService->get()->Http());
        
        $this->Route = $this->RouteService->Route();
        
    }
    
    
    /**
     * 
     * @return type
     */
    public function getRoute(){
        
        return $this->Route;
        
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getHttp(){
        
        return $this->Http;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getRouteService(){
        return $this->RouteService;
    }
    
    
    
    
    /**
     * 
     * @param type $options
     */
    public function setOptions( $options ){
        
        $this->options = $options;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getOptions(){
        return $this->options;
    }
    
    
    /**
     * 
     * @param \Minty\Cache $Cache
     */
    public function setCache( \Minty\Cache $Cache ){
        
        $this->Cache = $Cache;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getCache(){
        return $this->Cache; 
    }
    
    
    /**
     * 
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator( \Minty\Service\ServiceLocator & $ServiceLocator ){

        $this->ServiceLocator = $ServiceLocator;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getServiceLocator(){
        return $this->ServiceLocator;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getEventManager(){
        return $this->EventManager;
    }
    
    
    /**
     * 
     * @param type $path
     */
    public function register( $path ){
        
        $e = explode('/', $path);
        
        array_pop($e);
        
        $path = implode('/', $e);
        
        if(is_dir($path.'/config')){
            $this->path = self::$modulePath = $path;
        } else {
            $e = explode('/', $path);
            if(count($e) > 1) $this->register ($path);
        }
    }
    
    
    /**
     * 
     * @return type
     */
    public function getModulePath(){
        return $this->path;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getUser(){
        
        if(self::$user )
            return self::$user;
        
        self::$user = $this->getServiceLocator()->get('UserService')->getSessionUser();
        
        return self::$user;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public static function isLoged(){

        return self::$user ? true : false;
        
    }
    
    
    /**
     * 
     * @return type
     */
    public static function getUserData(){
        
        return self::$user;
        
    }
    
}