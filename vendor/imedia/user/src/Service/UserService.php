<?php namespace Imedia\User\Service;

use Minty\Service\ServiceInterface;
use Minty\Validation;
use Imedia\User\Entity\User;
use Imedia\User\Service\SessionUserService;
use \Minty\Doctrine\DoctrineManager;

class UserService implements ServiceInterface {

    /**
     *
     * @var Minty\Service\ServiceLocator
     */
    public $ServiceLocator;

    /**
     *
     * @var type 
     */
    private $Http;

    /**
     *
     * @var type 
     */
    private $options;

    /**
     *
     * @var type 
     */
    private $post;

    /**
     *
     * @var type 
     */
    private $module_configs;

    /**
     *
     * @var type 
     */
    private $message;
    
    
    /**
     *
     * @var type 
     */
    private $error_filed = null;
    
    
    /**
     *
     * @var type 
     */
    private $error_value = null;
    
    

    /**
     * 
     * @param type $options
     * @return \Imedia\RealEstate\Home\Service\ControllerService
     */
    public function create($options = array()) {

        $this->options = $options;
        
        return $this;
    }

    /**
     * 
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator(\Minty\Service\ServiceLocator $ServiceLocator) {
        $this->ServiceLocator = $ServiceLocator;
    }

    /**
     * 
     * @return Minty\Mysql\ORM\Paginator
     */
    public function getUser(){
        $Repository = $em = DoctrineManager::get()->EntityManager()->getRepository('Imedia\User\Entity\User');
        return $Repository->getUser(/* options */);
    }

    /**
     * 
     * @return type
     */
    public function getHttp() { return $this->Http; }

    /**
     * 
     * @return type
     */
    public function getServiceLocator() { return $this->ServiceLocator; }

    /**
     * 
     * @param \Minty\Route\Http $Http
     */
    public function setHttp(\Minty\Route\Http $Http) { $this->Http = $Http; }

    /**
     * 
     * @param type $post
     */
    public function setPost( $post ){ $this->post = $post; }

    /**
     * 
     * @param type $configs
     */
    public function setModuleConfigs( $configs ){ $this->module_configs = $configs; }

    /**
     * 
     * @return type
     */
    public function getModuleConfigs(){ return $this->module_configs; }
    
    /**
     * 
     * @return type
     */
    public function GetMessage(){ return $this->message; }
    
    
    /**
     * 
     * @return type
     */
    public function getErrorField(){
        return $this->error_filed;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getErrorValue(){
        return $this->error_value;
    }
    
    
    
    /**
     * 
     * @param type $values
     * @return boolean
     */
    public function make_user( $values ){
        
        $user = new User;
        
        foreach ($values as $key => $value){
            
            switch ($key){
                case 'first_name': $user->setFirstName( $value ); break;
                case 'last_name': $user->setLastName( $value ); break;
                case 'email': $user->setEmail( $value ); break;
                case 'password': $user->setPassword( $value ); break;
                case 'phone' : $user->setPhone( $value ); break; 
            }
        }
        
        $em = DoctrineManager::get()->EntityManager();
        
        $em->persist( $user );
        
        if( ! $em->flush()){
            return false;
        }
        
        $this->message = language('UserRegisterSuccessfully');
        
        return true;
    }
    
    
    
    /**
     * 
     * @param type $values
     * @return boolean
     */
    public function login_user( $values ){
        
        $user = new User;
        $user->setPassword( $values['password'] );
        $password = $user->getPassword();
        
        unset($user);
        $em = DoctrineManager::get()->EntityManager();
        $UserRepository = $em->getRepository('Imedia\User\Entity\User');
        $user = $UserRepository->login( $values['email'], $password);
        
        if( $user ){
            
            $options = [
                'user' => $user
            ];
            
            $SessionUserService = $this->getServiceLocator()->get('SessionUserService', $options);
            $SessionUserService->store();
            
            $this->message = language('UserLoginSuccessfully');
            return true;
        }
        
        $this->message = language('UserLoginError');
        
        return false;
    }
    
    
    
    
    /**
     * 
     * @return boolean
     */
    public function Register(){

        $Validation 
                = Validation::get()
                ->setInputs( $this->module_configs['inputs'])
                ->setValues( $this->post )
                ->validate( array( 'first_name', 'last_name', 'email', 'password', 'rpassword', 'phone' ));
        
        if( $error = $Validation->error){
            $this->message      = $error;
            $this->error_filed  = $Validation->field;
            $this->error_value  = $Validation->getValue();
            return false;
        }
        
        return $this->make_user( $Validation->getValues() );
    }
    
    
    
    /**
     * 
     * @return boolean
     */
    public function Login(){
        
        $Validation 
                = Validation::get()
                ->setInputs( $this->module_configs['inputs'])
                ->setValues( $this->post )
                ->validate( array(  'email','password' ));
        
        if( $error = $Validation->error){
            $this->message      = $error;
            $this->error_filed  = $Validation->field;
            $this->error_value  = $Validation->getValue();
            return false;
        }
        
        return $this->login_user( $Validation->getValues() );
    }
    
    
    /**
     * 
     * @return type
     */
    public function getSessionUser(){
        
         $SessionUserService = $this->getServiceLocator()->get('SessionUserService');
         
         return $SessionUserService->getUser();
        
    }
    
    
}