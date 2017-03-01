<?php namespace Imedia\User\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;
use Minty\Output\OutputManager;
use Minty\Session\SessionManager;
use Minty\Router;

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
        
        $Service = $this->getServiceLocator()->get('UserService');
        
        $post = $this->getHttp()->getPost();
        
        $Service->setPost( $post );
        $Service->setModuleConfigs( $configs );

        $RouteService   = $this->getServiceLocator()->Route;
        $Route          = $RouteService->Route();
        
        $params = $Route->params_from_route();

        $r = SessionManager::get()->Read('redirect');
        
        if( $params['status'] == "success" ){
            $opt = [
                'redirect' => $r ? $r : Router::FromRoute('Imedia\Home', 'index')
            ];
            $ViewModel =  $this->getView( $opt );
            $ViewModel->get('success');
            return $ViewModel;
        }
        
        if( $params['status'] == "error" ){
            $ViewModel =  $this->getView();
            $ViewModel->get('error');
            return $ViewModel;
        }

        $options = [
            'form'          => $params,
            'submit'        => $params['submit'],
            'message'       => null,
            'status'        => true,
            'error_filed'   => null,
            'error_value'   => null,
        ];

        if($params['submit'] == true){
            $options['status'] = $Service->Login();
            
        }
            
        
        if($options['status'] == 1 && $options['submit'] == true){
            redirect(Router::FromChilde('Imedia\User', 'login', 'success'));
        }
        
        if( $options['status'] != 1 && $options['submit'] == true){
            redirect(Router::FromChilde('Imedia\User', 'login', 'error'));
        }
        
        $ViewModel =  $this->getView($options);
        $ViewModel->get('login');
        
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