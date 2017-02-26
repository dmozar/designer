<?php namespace Administrator\RealEstate\Specification\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;
use Minty\Output\OutputManager;
use Imedia\RealEstate\Ad\Entity\Ad\Specification;

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
        
    }
    
    
    /**
     * 
     * @param type $request
     * @param type $options
     * @return type
     */
    public function __invoke( $options = array()) {
        
        $this->merge_options($options);

        $Specification               = new Specification;
        $options['specification']    = $Specification;
        $options['types']   = $this->getServiceLocator()->get('AdRepository')->getTypes();
        
        if($options['submit']){
            
            $Service = $this->getServiceLocator()->get('SpecificationService', $options);
            
            $options['status']          = $Service->submit();
            $options['message']         = $Service->GetMessage();
            $options['error_filed']     = $Service->getErrorField(); 
            $options['error_value']     = $Service->getErrorValue(); 
            $options['specification']   = $Service->getSpecification();
            $options['title']           = $options['status'] ? language('Info') : language('Warning');
            
            
            unset($options['type']);
            unset($options['post']);
            unset($options['params']);
            unset($options['inputs']);
            
            $options['clear'] = true;
            
            OutputManager::get()->json( $options );
        }
        
        
        $ViewModel          =  $this->getView( $options );
        
        $ViewModel->get('specification_add');
        
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
