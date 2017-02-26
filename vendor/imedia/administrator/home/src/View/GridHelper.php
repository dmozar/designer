<?php namespace Administrator\Home\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;


/**
 * 
 */
class GridHelper extends AbstractView implements ViewInterface {
    
    
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
        
        $post = $this->getHttp()->getPost();
        
        $options['post'] = $post;
        
        $Service = $this->getServiceLocator()->get('GridService', $options);
        
        $Service->getSource();
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