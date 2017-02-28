<?php namespace Administrator\RealEstate\Filter\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;
use Minty\Output\OutputManager;
use Imedia\RealEstate\Ad\Entity\Ad\Filter;

/**
 * 
 */
class AddHelper extends AbstractView implements ViewInterface {
    
    
    
    /**
     * 
     */
    public function __construct() { }
    
    
    private $options = [];
    
    
    private function merge_options( & $options ){
        
        $options['message']         = null;
        $options['status']          = true;
        $options['error_filed']     = null;
        $options['error_value']     = null;
        $options['callback']        = 'Display.Message';
        
        $RouteService       = $this->getServiceLocator()->Route;
        $Route              = $RouteService->Route();
        $params             = $Route->params_from_route();
        
        $options['type_id'] = isset($params['type_id']) ? $params['type_id'] : null;
    }
    
    
    /**
     * 
     * @param type $request
     * @param type $options
     * @return type
     */
    public function __invoke( $options = array()) {
        
        $this->merge_options($options);

        $Filter                      = new Filter;
        $options['filter']           = $Filter;
        $options['type']             = null;
        $options['types']            = $this->getServiceLocator()->get('AdRepository')->getTypes( );
        
        if($options['type_id']) {
            $options['type']            = $this->getServiceLocator()->get('AdRepository')->getType( $options['type_id'] );
            $options['specifications']  = $this->getServiceLocator()->get('AdRepository')->getSpecifications( $options['type_id'] );
        }
        else 
            $options['specifications']  = null;
        
        if($options['submit']){
            
            $Service = $this->getServiceLocator()->get('SpecificationFilterService', $options);
            
            $options['status']          = $Service->submit();
            $options['message']         = $Service->GetMessage();
            $options['error_filed']     = $Service->getErrorField(); 
            $options['error_value']     = $Service->getErrorValue(); 
            $options['filter']          = $Service->getFilter();
            $options['title']           = $options['status'] ? language('Info') : language('Warning');
            
            
            unset($options['type']);
            unset($options['post']);
            unset($options['params']);
            unset($options['inputs']);
            
            $options['clear'] = true;
            
            OutputManager::get()->json( $options );
        }
        
        
        $ViewModel          =  $this->getView( $options );
        
        if($options['specifications'])
            $ViewModel->get('filter_add');
        else 
            $ViewModel->get('type_select');
        
        return $ViewModel;
        
    }
    
    
    
    /**
     * 
     * @param type $request
     * @param type $options
     * @return type
     */
    public function create($options = array() ) {
        return $this($options);
    }
    
}
