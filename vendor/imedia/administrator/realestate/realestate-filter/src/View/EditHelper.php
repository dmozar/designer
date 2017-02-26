<?php namespace Administrator\RealEstate\Filter\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;
use Minty\Output\OutputManager;

/**
 * 
 */
class EditHelper extends AbstractView implements ViewInterface {
    
    
    
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
        
    }
    
    
    /**
     * 
     * @param type $request
     * @param type $options
     * @return type
     */
    public function __invoke( $options = array()) {
        
        $this->merge_options($options);

        $Filter                     = $this->getServiceLocator()->get('AdRepository')->getFilter( $options['id'],false);
        $options['filter']          = $Filter;
        $options['type']            = $Filter->getSpecification()->getType();
        $options['specifications']  = $this->getServiceLocator()->get('AdRepository')->getSpecifications( $Filter->getSpecification()->getType()->getID() );
        
        if($options['submit']){
            
            $Service = $this->getServiceLocator()->get('SpecificationFilterService', $options);
            
            $options['status']          = $Service->submit();
            $options['message']         = $Service->GetMessage();
            $options['error_filed']     = $Service->getErrorField(); 
            $options['error_value']     = $Service->getErrorValue(); 
            $options['filter']          = $Service->getFilter();
            $options['title']           = $options['status'] ? language('Info') : language('Warning');
            
            unset($options['filter']);
            unset($options['post']);
            unset($options['params']);
            unset($options['inputs']);
            
            OutputManager::get()->json( $options );
        }
        
        
        $ViewModel          =  $this->getView( $options );
        
        $ViewModel->get('filter_edit');
        
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
