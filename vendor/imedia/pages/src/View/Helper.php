<?php namespace Imedia\Pages\View;

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
        
        $Service = $this->getServiceLocator()->get('PagesService');
        
        $ViewModel = $this->getView();
        
        $RouteService   = $this->getServiceLocator()->Route;
        $Route          = $RouteService->Route();
        $params         = $Route->params_from_route();
        
        $view = 'errorpage';
        
        if(isset($params['view'])) $view = $params['view'];
        
        $ViewModel->get($view);
        
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
