<?php namespace Minty\Route;

use Minty\Factory\FactoryInterface;
use Minty\Service\ServiceInterface;

class RouteFactory implements FactoryInterface {
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    
    /**
     * 
     * @param type $request
     * @param type $options
     * @param ServiceInterface $service
     * @return type
     */
    public function __invoke( $request, $options = array(), ServiceInterface $service = null ) {
        
        if( ! $service ){
            $service = new \Minty\Route\RouteService;
            $service = $service->create($options);
        }
        
        return $service($request,$options);
        
    }
    

    
    /**
     * 
     * @param type $request
     * @param type $options
     * @param ServiceInterface $service
     * @return type
     */
    public function create($request, $options = array(), ServiceInterface $service = null ) {
        
        return $this($request,$options,$service);
        
    }

}
