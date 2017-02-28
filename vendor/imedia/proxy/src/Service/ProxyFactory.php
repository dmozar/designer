<?php namespace Imedia\Proxy\Service;

use Minty\Factory\FactoryInterface;
use Minty\Service\ServiceInterface;

class ProxyFactory implements FactoryInterface {
    
    
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
        
        $Http = $this->ServiceLocator->Route->Route()->Http();
        
        if( ! $keyService = $Http->getFromPost('keyService') ) die('Page not found');
        
        if( ! $service ){
            $service = new \Imedia\Proxy\Service\ProxyService;
            $service = $service->create($options);
        }
        
        
        $service->setDeppend( $Http->getFromPost('deppend') );
        $service->setKeyService($keyService);
        $service->setKeyword( $Http->getFromPost('keyword') );
        $service->setServiceLocator( $this->ServiceLocator );
        
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