<?php namespace Imedia\Designer\View;

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
        
        $Service = $this->getServiceLocator()->get('DesignerService');
        
        $RouteService   = $this->getServiceLocator()->Route;
        $Route          = $RouteService->Route();
        $params         = $Route->params_from_route();
        
        $ViewModel = $this->getView();
        
        $options = [
            'editor' => $ViewModel->setOptions(['id' => $params['id']])->get('editor')->html(),
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
