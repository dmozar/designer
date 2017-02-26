<?php namespace Administrator\RealEstate\Filter\Service;

use Minty\Service\ServiceInterface;
use Minty\Validation;
use Minty\MySql\ORM\Cache;

class FilterService implements ServiceInterface {
    
    
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
    private $Filter;

    /**
     * 
     * @param type $options
     * @return \Imedia\RealEstate\Home\Service\ControllerService
     */
    public function create($options = array()) {
        
        $this->options = $options;
        
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
     * @param \Imedia\RealEstate\Ad\Entity\Ad\Filter $Filter
     * @param type $values
     * @return boolean
     */
    public function Store(\Imedia\RealEstate\Ad\Entity\Ad\Filter $Filter, $values = [] ){
        
        $specification = $this->ServiceLocator->get('AdRepository')->getSpecification( $values['specification'] );
        
        
        Cache::instance('imedia_get_ad_specfilters')->useRedisCache()->delete();
        
        $Filter->setName( $values['name'] );
        $Filter->setSlug( $values['slug'] );
        $Filter->setSpecification( $specification );
        $Filter->setStatus( $values['status'] ? $values['status'] : 0 );
        $Filter->setPosition( $values['position'] );
        $Filter->setType( $values['typeselect'] );
        
        $em = \Minty\MySql\ORM\EventManager::get();
        $em->persist( $Filter );
        
        if( ! $em->flush()){  return false; }
        
        $this->message      = language('SuccessSaved');
        $this->Filter = $Filter;
        
        return true;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getFilter(){ return $this->Filter; }
    
    
    /**
     * 
     * @return boolean
     */
    public function submit(){
        
        $Validation 
                = Validation::get()
                ->setInputs( $this->options['inputs'] )
                ->setValues( $this->options['post'] )
                ->validate( array( 'name', 'slug', 'position', 'status', 'specification','typeselect' ));
        
        if( $error = $Validation->error){
            $this->message      = $error;
            $this->error_filed  = $Validation->field;
            $this->error_value  = $Validation->getValue();
            return false;
        } 
        
        return $this->Store( $this->options['filter'], $Validation->getValues() );
    }
    

}