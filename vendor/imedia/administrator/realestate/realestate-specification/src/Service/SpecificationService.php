<?php namespace Administrator\RealEstate\Specification\Service;

use Minty\Service\ServiceInterface;
use Minty\Validation;
use Minty\MySql\ORM\Cache;

class SpecificationService implements ServiceInterface {
    
    
    /**
     *
     * @var Minty\Service\ServiceLocator
     */
    public $ServiceLocator;

    /**
     *
     * @var int
     */
    private $offset = 0;

    /**
     *
     * @var int 
     */
    private $limit = 5;

    /**
     *
     * @var type 
     */
    private $Http;

    /**
     *
     * @var type 
     */
    private $options = [];

     /**
     *
     * @var type 
     */
    private $message;

    /**
     *
     * @var type 
     */
    private $error_filed = null;

    /**
     *
     * @var type 
     */
    private $error_value = null;
    
    /**
     *
     * @var type 
     */
    private $Specification;

    /**
     * 
     * @param type $options
     * @return \Imedia\RealEstate\Home\Service\ControllerService
     */
    public function create($options = array()) {
        
        $this->options  = $options;
        $RouteService   = $this->ServiceLocator->Route;
        $Route          = $RouteService->Route();
        $params         = $Route->params_from_route();
        
        if(isset($params['page']))
            $this->offset = ($params['page'] * $this->limit) - $this->limit;

        return $this;
    }

    /**
     * 
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator(\Minty\Service\ServiceLocator $ServiceLocator) { $this->ServiceLocator = $ServiceLocator; }

    /**
     * 
     * @return type
     */
    public function getServiceLocator() { return $this->ServiceLocator;}
    
    /**
     * 
     * @return Minty\Mysql\ORM\Paginator
     */
    public function getAds(){
        $Repository = $this->ServiceLocator->get('AdRepository');
        return $Repository->getAds($this->offset, $this->limit);
    }
    
    /**
     * 
     * @return type
     */
    public function getHttp() { return $this->Http; }

    
    /**
     * 
     * @param \Minty\Route\Http $Http
     */
    public function setHttp(\Minty\Route\Http $Http) { $this->Http = $Http; }

    /**
     * 
     * @return type
     */
    public function GetMessage(){ return $this->message; }
    
    /**
     * 
     * @return type
     */
    public function getErrorField(){ return $this->error_filed; }

    /**
     * 
     * @return type
     */
    public function getErrorValue(){ return $this->error_value; }
    
    
    /**
     * 
     * @param \Imedia\RealEstate\Ad\Entity\Ad\Specification $Specification
     * @param type $values
     * @return boolean
     */
    public function Store(\Imedia\RealEstate\Ad\Entity\Ad\Specification $Specification, $values = [] ){
        
        Cache::instance('imedia_get_specificationgrid')->useRedisCache()->delete();
        Cache::instance('imedia_get_ad_specification')->useRedisCache()->delete();
        Cache::instance('imedia_get_ad_specfilters')->useRedisCache()->delete();
        
        $type = $this->ServiceLocator->get('AdRepository')->getType( $values['type'] );
        
        $Specification->setName( $values['name'] );
        $Specification->setSlug( $values['slug'] );
        $Specification->setType( $type );
        $Specification->setStatus( $values['status'] ? $values['status'] : 0 );
        $Specification->setPosition( $values['position'] );
        
        $em = \Minty\MySql\ORM\EventManager::get();
        $em->persist( $Specification );
        
        if( ! $em->flush()){  return false; }
        
        $this->message      = language('SuccessSaved');
        $this->Specification = $Specification;
        
        return true;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getSpecification(){ return $this->Specification; }
    
    
    /**
     * 
     * @return boolean
     */
    public function submit(){
        
        $Validation 
                = Validation::get()
                ->setInputs( $this->options['inputs'] )
                ->setValues( $this->options['post'] )
                ->validate( array( 'name', 'slug', 'position', 'status', 'type' ));
        
        if( $error = $Validation->error){
            $this->message      = $error;
            $this->error_filed  = $Validation->field;
            $this->error_value  = $Validation->getValue();
            return false;
        } 
        
        return $this->Store( $this->options['specification'], $Validation->getValues() );
    }
    

}