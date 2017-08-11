<?php namespace Imedia\User\View;

use Minty\View\Interfaces\ViewInterface;
use Minty\View\AbstractView;
use Minty\Output\OutputManager;

/**
 * 
 */
class RegistrationHelper extends AbstractView implements ViewInterface {
    
    
    
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
        
        $Service->setPost( $this->getHttp()->getPost() );
        $Service->setModuleConfigs( $configs );

        $RouteService   = $this->getServiceLocator()->Route;
        $Route          = $RouteService->Route();
        
        $params = $Route->params_from_route();

        $options = [
            'form'          => $params,
            'submit'        => $params['submit'],
            'message'       => null,
            'status'        => true,
            'error_filed'   => null,
            'error_value'   => null,
        ];
        
         if($params['submit']){
            $options['status']      = $Service->Register();
            $options['message']     = $Service->GetMessage();
            $options['error_filed'] = $Service->getErrorField(); 
            $options['error_value'] = $Service->getErrorValue(); 
        }
        
        $ViewModel =  $this->getView($options);
        $ViewModel->get('registration');
        
        if( $options['submit'] ) {
            if( $Service->getRegUser()){
                $ViewModel =  $this->getView($options);
                $ViewModel->get('successreg');
            }
            OutputManager::get()->view( $ViewModel );
        }
        
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