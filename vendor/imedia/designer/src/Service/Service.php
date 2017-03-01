<?php namespace Imedia\Designer\Service;

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
    
    /**
     * 
     * @param DesignRepository $Repository
     */
    public function setRepository( DesignRepository $Repository ){  $this->Repository = $Repository; }
    
    /**
     * 
     * @return type
     */
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
    
    /**
     * 
     * @param type $design_id
     * @return Design
     */
    public function getDesign( $design_id = null ){
        if(! $User = MVC::getUserData() ) return null;
        
        $Record = new Design;
        $Record->setUser( $User );
        
        if($design_id)
        if( ! $Record = $this->getRepository()->SingleDesign($design_id, $User) ) 
        return null;
            
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
    
    
    /**
     * 
     * @param type $output
     * @param type $json
     * @param type $title
     * @param type $id
     * @return type
     */
    public function Save( $output, $json, $title, $id = null ){
        
        if( ! $Design = $this->getDesign( $id ) ) return $output;
        
        if(! $title ) return $output;
        
        $Design->setjson( $json );
        $Design->setTitle( $title );
        
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
    
    

    /**
     * 
     * @param type $output
     * @return type
     */
    public function Load( $output = [] ){

        if( ! $Design = $this->getDesign( $this->getHttp()->getFromPost('id') ) ) return $output;
        
        $output['json']     = $Design->getJson();
        $output['title']    = $Design->getTitle();
        $output['message']  = null;
        $output['status']   = true;
        $output['id']       = $Design->getID();
        
        return $output;
    }

}