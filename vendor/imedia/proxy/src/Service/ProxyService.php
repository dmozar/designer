<?php namespace Imedia\Proxy\Service;

use Minty\Service\ServiceInterface;
use Minty\Cache;

class ProxyService implements ServiceInterface {
    
    
    /**
     *
     * @var Minty\Service\ServiceLocator
     */
    public $ServiceLocator;
    
    
    /**
     *
     * @var type 
     */
    private $keyService;
    
    
    
    /**
     *
     * @var type 
     */
    private $keyword;
    
    
    
    /**
     *
     * @var type 
     */
    private $deppend;
    
    
    
    /**
     *
     * @var type 
     */
    private $options;
    
    
    /**
     *
     * @var type 
     */
    private $Http;
    
    
    /**
     * 
     * @param type $request
     * @param type $options
     * @return type
     */
    public function __invoke( $request, $options) {
        
        $this->options = $options;
        
        return $this->{$request}();
    }
    
    
    
    
    /**
     * 
     * @param type $options
     * @return \Imedia\RealEstate\Home\Service\ControllerService
     */
    public function create($options = array()) {

        $this->options = $options;

        return $this;
    }

    
    /**
     * 
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator(\Minty\Service\ServiceLocator $ServiceLocator) {
        $this->ServiceLocator = $ServiceLocator;
    }
    
    
    
    public function getServiceLocator(){
        return $this->ServiceLocator;
    }
    
    
    
    /**
     * 
     * @param type $keyService
     */
    public function setKeyService( $keyService){
        $this->keyService = $keyService;
    }
    
    
    
    /**
     * 
     * @return Minty\Mysql\ORM\Paginator
     */
    public function getKeyService(){
        
        return $this->keyService;
    }
    

    /**
     * 
     * @param type $keyword
     */
    public function setKeyword( $keyword ){
        
        $this->keyword = $keyword;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getDeppend(){
        return $this->deppend;
    }
    
    
    
    /**
     * 
     * @param type $keyword
     */
    public function setDeppend( $deppend ){
        
        $this->deppend = $deppend;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getKeyword(){
        return $this->keyword;
    }
    
    
    
    /**
     * 
     */
    public function AjaxManager(){
        
        $Cache = new Cache;
        
        if( ! $class = $Cache->get_cached_ajax_configs( $this->getKeyService() )){
            die('Request Key Service is not valid');
        }
        
        $e = explode('::', $class);
        
        $objectNamespace = reset( $e );
        $objectMethod = end( $e );
        
        $options = [
            'keyword' => $this->getKeyword(),
            'deppend' => $this->getDeppend()
        ];
        
        $Object = $this->getServiceLocator()->get($objectNamespace, $options);
        
        return $Object->{$objectMethod}();
        
    }

    
    /**
     * 
     * @return type
     */
    public function getHttp() {
        return $this->Http;
    }

    
    /**
     * 
     * @param \Minty\Route\Http $Http
     */
    public function setHttp(\Minty\Route\Http $Http) {
        $this->Http = $Http; 
    }

}