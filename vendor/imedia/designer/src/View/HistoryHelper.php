<?php namespace Imedia\Designer\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;


/**
 * 
 */
class HistoryHelper extends AbstractView implements ViewInterface {
    
    
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
        
        $ViewModel = $this->getView();
        
        $options = [
            'items' => $Service->getListItems(),
        ];
        
        $jokerUrl = \Minty\Router::FromChilde('Imedia\Designer', 'index', 'item', [], ['%s']);
        
        $options = [
            'items' => $options['items'] ? $options['items'] : [],
            'pagine'=> pagination (
                    $Service->getPage(), 
                    $Service->getOffset(), 
                    $Service->getLimit(), 
                    count($options['items']), 
                    true, 
                    $jokerUrl 
            )
        ];
        
        $ViewModel =  $this->getView( $options );
        
        $ViewModel->get('list_designes');
        
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
