<?php namespace Administrator\Home\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;


/**
 * 
 */
class HomeHelper extends AbstractView implements ViewInterface {
    
    
    /**
     *
     * @var array 
     */
    private $options = [];
    
    
    /**
     * 
     */
    public function __construct() { }
    
    
    
    /**
     * 
     * @param type $request
     * @param type $options
     * @return type
     */
    public function __invoke( $options = array()) {
        
        $this->options = $options;
        
        $Service = $this->getServiceLocator()->get('HomeService');
        
        $ViewModel =  $this->getView([
            
        ]);
        
        $ViewModel->get('home');
        
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
