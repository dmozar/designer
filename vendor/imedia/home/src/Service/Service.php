<?php namespace Imedia\Home\Service;

use Minty\Service\ServiceInterface;
use Minty\Doctrine\DoctrineManager;
use Minty\Service\ServiceLocator;
use Doctrine\ORM\EntityManager;
use Minty\MVC;
use Imedia\Designer\Entity\Design;
use Imedia\Designer\Repository\DesignRepository;
use Exception;

class Service implements ServiceInterface {
    
    
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
    private $EntityManager;
    
    /**
     *
     * @var type 
     */
    private $Repository;
    
    /**
     * 
     * @param type $options
     * @return \Imedia\Shop\Service\Service
     */
    public function create($options = array()) {
        
        $this->setEntityManager( DoctrineManager::get()->EntityManager() );
        $this->setRepository( $this->getEntityManager()->getRepository('Imedia\Designer\Entity\Design') );

        return $this;
    }
    
    public function setRepository( DesignRepository $Repository ){  $this->Repository = $Repository; }
    
    public function getRepository(){ return $this->Repository; }
    
    
    /**
     * 
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator( ServiceLocator $ServiceLocator) { $this->ServiceLocator = $ServiceLocator; }

    /**
     * 
     * @return type
     */
    public function getServiceLocator() { return $this->ServiceLocator; }

    /**
     * 
     * @return type
     */
    public function getEntityManager(){ return $this->EntityManager;  }
    
    
    public function getDesign( $design_id = null ){
        if(! $User = MVC::getUserData() ) return null;
        if(! $design_id ) return null;
        
        $Record = new Design;
        $Record->setUser( $User );
        if( ! $Record = $this->getRepository()->SingleDesign($design_id, $User) ) 
                $Record = new Design;
            
        return $Record;
    }
    
    
    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager( EntityManager $em ){ $this->EntityManager = $em; }

    /**
     * 
     * @return type
     */
    public function getHttp() { return $this->Http; }

    /**
     * 
     * @param \Minty\Route\Http $Http
     */
    public function setHttp(\Minty\Route\Http $Http) { $this->Http = $Http; }
    
    
    public function Save( $output, $json, $id = null ){
        
        if( ! $Design = $this->getDesign( $id ) ) return $output;
        
        $Design->setjson( $json );
        
        $em = $this->getEntityManager();
        $em->persist( $Design );
        
        try {
            $em->flush();
            
            $output['id']       = $Design->getID();
            $output['status']   = true;
            $output['title']    = 'Info';
            $output['message']  = language('DesignSaved');
            
        } catch( Exception $e ){
            $output['message']  = $e->getMessage();
        }
        
        return $output;
    }
    

}