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
     * @var type 
     */
    private $Limit = 30;
    
    /**
     *
     * @var type 
     */
    private $Offset = 0;
    
    /**
     *
     * @var type 
     */
    private $Page = 1;
    
    /**
     *
     * @var type 
     */
    private $Id;
    
    /**
     * 
     * @param type $options
     * @return \Imedia\Shop\Service\Service
     */
    public function create($options = array()) {
        
        $RouteService   = $this->getServiceLocator()->Route;
        $Route          = $RouteService->Route();
        $params         = $Route->params_from_route();
        
        if( isset($params['page']) ) { $this->setPage ( $params['page'] ); $this->setOffset(); }
        if(isset($options['page'])) { $this->setPage ( $options['page'] ); $this->setOffset(); }
        if(isset($options['limit'])) $this->setLimit ( $options['limit'] );
        if(isset($options['id'])) $this->setID ( $options['id'] );
        if( isset($params['id']) ) { $this->setID ( $params['id'] ); }
        
        $this->setEntityManager( DoctrineManager::get()->EntityManager() );
        $this->setRepository( $this->getEntityManager()->getRepository('Imedia\Designer\Entity\Design') );

        return $this;
    }
    
    /**
     * 
     * @param type $id
     */
    public function setID( $id ){ $this->Id = $id; }
    
    /**
     * 
     * @return type
     */
    public function getID(){ return $this->id; }
    
    
    /**
     * 
     * @param type $page
     */
    public function setPage( $page ){ $this->Page = $page; $this->setOffset(); }
    
    /**
     * 
     * @return type
     */
    public function getPage(){ return $this->Page; }
    
    /**
     * 
     * @param type $limit
     */
    public function setLimit( $limit ){ $this->Limit = $limit; return $this; }
    
    /**
     * 
     * @return type
     */
    public function getLimit(){ return $this->Limit; }
    
    
    /**
     * 
     * @return $this
     */
    public function setOffset(){ $this->Offset = ($this->Page * $this->Limit) - $this->Limit; return $this;}
    
    /**
     * 
     * @return type
     */
    public function getOffset(){ return $this->Offset; }
    
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
        
        if( ! $design_id && $this->Id ) $design_id = $this->Id;
        
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
            
            DoctrineManager::RemoveCache('designer_single');
            DoctrineManager::RemoveCache('designer_list');
            
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

    /**
     * 
     * @return type
     */
    public function getListItems(){ return $this->getRepository()->setOptions(['offset'=>$this->getOffset(),'limit'=>$this->getLimit()])->getListItems(); }

}