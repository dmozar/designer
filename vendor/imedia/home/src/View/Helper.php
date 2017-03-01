<?php namespace Imedia\Home\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;


/**
 * 
 */
class Helper extends AbstractView implements ViewInterface {
    
    
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
        
        $ViewModel = $this->getView();
        
        $options = [
            
        ];
        
        $ViewModel =  $this->getView( $options );
        
        $ViewModel->get('index');
        
        return $ViewModel;
        
    }
    
    
    
    /**
     * 
     * @param type $request
     * @param type $options
     * @return type
     */
    public function create($options = array() ) { return $this($options); }
    
}
