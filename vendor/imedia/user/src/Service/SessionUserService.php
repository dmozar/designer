<?php namespace Imedia\User\Service;

use Exception;
use Minty\Service\ServiceInterface;
use Minty\Validation;
use Imedia\User\Entity\User;
use Minty\Session\SessionManager;
use \Minty\Doctrine\DoctrineManager;

class SessionUserService implements ServiceInterface { 
    
    
    /**
     *
     * @var type 
     */
    private $ServiceLocator;
    
    
    /**
     *
     * @var type 
     */
    private $options;
    
    
    
    /**
     *
     * @var type 
     */
    private $key = null;
    
    
    /**
     * 
     * @param type $options
     * @return \Imedia\User\Service\SessionUserService
     */
    public function create($options = array()) {
        $this->options = $options;
        
        global $session_key;
        
        if( ! $session_key )
            $session_key = 'ci_credential';

        $this->key = $session_key;
        
        return $this;
    }

    
    /**
     * 
     */
    public function getHttp() {
        
    }

    
    /**
     * 
     * @return type
     */
    public function getServiceLocator() {
        return $this->ServiceLocator;
    }

    
    /**
     * 
     * @param \Minty\Route\Http $Http
     */
    public function setHttp(\Minty\Route\Http $Http) {
        
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
     * @return type
     * @throws Exception
     */
    public function generate_token( $id ){
        
        if( ! $userIp = user_ip()){
            throw new Exception('Session user can`t read client data. Application is aborted.');
        }
        
        if( ! $userAgent = ($_SERVER['HTTP_USER_AGENT']) )
            throw new Exception('Session user can`t read client data. Application is ended.');

        $token = md5( sprintf( 'Token %s IP %s Agent %s Key %s', $id, $userIp, $userAgent, APP_SECRET_KEY ));
        
        return $token;
    }
    
    
    
    public function store(){
        
        $token = $this->generate_token( $this->options['user']->getID() );
        
        $id = $this->options['user']->getID();
        
        $this->options['user']->setToken( $token );
        
        $em = DoctrineManager::get()->EntityManager();
        $em->persist( $this->options['user'] );
        $em->flush();
        
        $SessionManager = SessionManager::get();
        
        $storeData = array(
            'userId' => $id
        );
        
        $SessionManager->Store($this->key, $storeData, 36000000);
        
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getUser(){
        
        $SessionManager = SessionManager::get();
        
        global $session_key;
        
        if( ! $session_key )
            $session_key = 'ci_credential';
        
        $credential = $SessionManager->Read( $session_key );
        
        if($credential)
        {
            $token = $this->generate_token( $credential['userId'] );
            
            if( $token ){
                
                $UserRepository = DoctrineManager::get()->EntityManager()->getRepository('Imedia\User\Entity\User');
                
                $validationUser = $UserRepository->validateUser( $credential['userId'], $token );
                
                if($validationUser){
                    
                    $storeData = array(
                        'userId' => $validationUser->getID()
                    );
                    
                    $SessionManager->Store($this->key, $storeData, 360000000);
                    
                    return $validationUser;
                }
            }
        }
        
        $SessionManager->delete($session_key);
        
        return null;
    }
    

}