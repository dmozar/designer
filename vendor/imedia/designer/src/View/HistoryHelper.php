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
        
        $opt = $this->Search();
        
        $options = [
            'items' => $Service->getListItems( $opt ),
        ];
        
        $jokerUrl = \Minty\Router::FromChilde('Imedia\Designer', 'history', 'page', [], ['00000000']);
        
        $options = [
            'items' => $options['items'] ? $options['items'] : [],
            'pagine'=> pagination (
                    $Service->getPage(), 
                    $Service->getOffset(), 
                    $Service->getLimit(), 
                    count($options['items']), 
                    true, 
                    $jokerUrl 
            ),
            'search' => $opt
        ];
        
        $ViewModel =  $this->getView( $options );
        
        $ViewModel->get('list_designes');
        
        return $ViewModel;
        
    }
    
    
    /**
     * 
     * @return \stdClass
     */
    private function Search(){
        if(isset($_COOKIE['search'])){
            $opt = json_decode( $_COOKIE['search']);
        } else {
            $opt = null;
        }
        
        if( ! $opt ){
            $opt = new \stdClass();
            $opt->keywords = null;
            $opt->date     = null;
        } else {
            $opt->keywords = isset($opt->keywords) ? $opt->keywords : null;
            $opt->date     = isset($opt->date) ? $opt->date : null;
        }
        return $opt;
    }
    
    
    
    /**
     * 
     * @param type $request
     * @param type $options
     * @return type
     */
    public function create($options = array() ) { return $this($options); }
    
}
