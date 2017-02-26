<?php namespace Minty\Service;

use Exception;
use Minty\Cache;
use Minty\Service\ServiceInterface;
use Minty\Factory\FactoryInterface;
use Minty\View\Interfaces\ViewInterface;

class ServiceLocator {
    
    
    private static $serviceList = [];
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    /**
     * 
     * @param type $service_name
     * @param type $options
     * @return type
     * @throws Exception
     */
    public function get( $service_name, $options = [] ){
        
        
        $views      = [];
        $service    = null;
        
        if(isset(self::$serviceList[$service_name])) {     
            $service = & self::$serviceList[$service_name];
        }
        
        
        
        $this->locate_service($service, $views, $service_name);
        $this->locate_current_helper($service, $views, $service_name);
        $this->locate_helper($service, $views, $service_name);
        
        if($service instanceof ViewInterface){
            $service->setViews( $views );
        }

        if($service){
            
            $service->setServiceLocator( $this );
            $service->setHttp( $this->Route->Route()->Http() );

            self::$serviceList[$service_name] = & $service;
            
            return $service->create($options);
        
        } else {
            throw new Exception('Service locator could not find ' . $service_name);
        }
    }
    
    
    
    /**
     * 
     * @param type $service_name
     * @param type $views
     * @param type $service_name
     * @throws Exception
     */
    private function locate_service( & $service, & $views, $service_name ){
        
        if( $service ) return;
        
        $Cache      = new Cache;
        $map        = $Cache->get_cached_service_configs();
        
        // Look for service
        if(array_key_exists($service_name, $map)){
            $service = new $map[$service_name];
            $service->setServiceLocator($this);
            if( ! $service instanceof ServiceInterface )
                throw new Exception($service_name . ' must be a instance of Minty\Service\ServiceInterface');
        }
    }
    
    
    
    /**
     * 
     * @param type $service
     * @param type $views
     * @param type $service_name
     * @throws Exception
     */
    private function locate_current_helper( & $service, & $views, $service_name ){
        
        if($service) return;
        
        $Cache      = new Cache;
        
        $map = $Cache->get_cached_view_configs();
        
        // Look in current module
        if( $modulePath =  $this->getModulePath() ){
            $confPath = $modulePath . '/config/module.config.php';
            if(file_exists($confPath)){
                $config = include $confPath;
                if(array_key_exists('view_helpers', $config)){
                    $viewHelpersArray = $config['view_helpers'];
                    if(array_key_exists($service_name, $viewHelpersArray)){
                        $viewHelper = $viewHelpersArray[$service_name];
                        $service    = new $viewHelper;
                        $views = @$config['views'] ? $config['views'] : [];
                        if( ! $service instanceof ViewInterface )
                            throw new Exception($service_name . ' must be a instance of Minty\View\Interfaces\ViewInterface');
                    }
                }
            }
        }
    }
    
    
    
    /**
     * 
     * @param \Minty\Service\list $service
     * @param type $views
     * @param type $service_name
     */
    public function locate_helper( & $service, & $views, $service_name ){
        
        if( $service ) return;
        
        $Cache      = new Cache;
        
        // Look in all modules 
        $viewMap = $Cache->get_cached_view_configs();

        foreach ($viewMap as $module => $list){
            if(array_key_exists('helpers', $list)){
                if(array_key_exists($service_name, $list['helpers'])){
                    $service = new $list['helpers'][$service_name];
                    $views = @$list['views'] ? $list['views'] : [];
                    break;
                }
            }
        }
        
    }
    
    
    
    
    /**
     * 
     * @param type $factory_name
     * @param type $request
     * @param type $options
     * @param ServiceInterface $service
     * @return type
     * @throws Exception
     */
    public function factory( $factory_name, $request = null, $options = [], ServiceInterface $service = null ){
        
        $Cache = new Cache;
        $map = $Cache->get_cached_service_configs();
        $factory = new $map[$factory_name];
        $factory->ServiceLocator = $this;
        
        if( ! $factory instanceof FactoryInterface )
            throw new Exception($factory_name . ' must be a instance of Minty\Factory\FactoryInterface');
        
        return $factory->create($request, $options, $service);
    }
    
    
    
}