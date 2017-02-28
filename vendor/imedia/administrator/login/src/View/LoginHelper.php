<?php namespace Administrator\Login\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;


/**
 * 
 */
class LoginHelper extends AbstractView implements ViewInterface {
    
    
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
    public function __invoke( $configs = array()) {
        
        $RouteService   = $this->getServiceLocator()->Route;
        $Route          = $RouteService->Route();
        $params         = $Route->params_from_route();
        
        $options = [
            'submit'    => $params['submit'],
            'configs'   => $configs
        ];
      
        $Service = $this->getServiceLocator()->get('LoginService', $options );
        
        $ViewModel =  $this->getView([
            'message'   => $Service->getMessage(),
            'status'    => $Service->getStatus(),
            
        ]);
        
        $ViewModel->get('index');
        
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
