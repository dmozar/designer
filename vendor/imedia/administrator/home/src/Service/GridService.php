<?php namespace Administrator\Home\Service;

use Minty\Service\ServiceInterface;
use Minty\Helper\HelperManager;

class GridService implements ServiceInterface {
    
    
    /**
     *
     * @var Minty\Service\ServiceLocator
     */
    public $ServiceLocator;

    /**
     *
     * @var int
     */
    private $offset = 0;

    /**
     *
     * @var int 
     */
    private $limit = 25;

    /**
     *
     * @var type 
     */
    private $page = 1;

    /**
     *
     * @var type 
     */
    private $Http;
    
    
    /**
     *
     * @var type 
     */
    private $keywords;
    
    
    /**
     *
     * @var type 
     */
    private $pag = 1;
    
    
    
    /**
     *
     * @var type 
     */
    private $options = [];
    

    /**
     * create
     * -------------------------------------------------------------------------
     * @param type $params
     * @return \Administrator\Home\Service\GridService
     */
    public function create($params = array()) {
        
        $this->options = $params;
        
        if(isset($params['post']['offset']))
            $this->offset = $params['post']['offset'];
        
        if(isset($params['post']['limit']))
            $this->limit = $params['post']['limit'];
        
        if(isset($params['post']['keywords']))
            $this->keywords = $params['post']['keywords'];
        
        $this->page = $this->offset ?  ( ($this->pag = round(ceil(($this->offset+$this->limit) / $this->limit))) > 0 ? $this->pag : 1 ) : 1;
        
        unset($this->options['post']);
        
        return $this;
    }

    
    /**
     * setServiceLocator
     * -------------------------------------------------------------------------
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator(\Minty\Service\ServiceLocator $ServiceLocator) {  $this->ServiceLocator = $ServiceLocator; }
    
    
    /**
     * getServiceLocator
     * -------------------------------------------------------------------------
     * @return type
     */
    public function getServiceLocator() { return $this->ServiceLocator; }

    
    /**
     * getSource
     * -------------------------------------------------------------------------
     * @return json
     */
    public function getSource( ){
        
        HelperManager::get()->load('table');
        
        $options = [
            'page'      => $this->page,
            'offset'    => $this->offset,
            'limit'     => $this->limit,
            'keywords'  => $this->keywords
        ];
        
        if(isset($this->options['params']))
            $options['params'] = $this->options['params'];
        
        $Repository = $this->ServiceLocator->get($this->options['repository'], $options);

        $result = $Repository->{$this->options['method']}();
        
        $cells = $this->options['cells'];
        $key   = $this->options['key'];
        $plus  = isset($this->options['plus']) ? $this->options['plus'] : [];
        
        $data = minty_table_data($result, $cells, $key, $this->page, $this->pag, $plus );
        
        \Minty\Output\OutputManager::get()->json($data);
    }
    
    
    /**
     * getHttp
     * ------------------------------------------------------------------------
     * @return type
     */
    public function getHttp() { return $this->Http;}

    
    /**
     * setHttp
     * -------------------------------------------------------------------------
     * @param \Minty\Route\Http $Http
     */
    public function setHttp(\Minty\Route\Http $Http) { $this->Http = $Http; }

}