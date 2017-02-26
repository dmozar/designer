<?php namespace Minty\MySql\ORM;

use Minty\MySql\ORM\Connect;
use Minty\MySql\ORM\Entity;
use Minty\MySql\ORM\QueryBuilder;
use Minty\MySql\ORM\Annotation;

class Query {
    
    
    /**
     *
     * @var type 
     */
    protected $conn;
    
    
    
    /**
     *
     * @var type 
     */
    protected $select_query_mixed;
    
    
    
    /**
     *
     * @var type 
     */
    protected $select_from_entity_namespace_string;
    
    
    /**
     *
     * @var type 
     */
    protected $select_alias;
    
    
    /**
     *
     * @var type 
     */
    protected $queryType;
    
    
    /**
     *
     * @var type 
     */
    protected $leftJoins = [];
    
    
    /**
     *
     * @var type 
     */
    protected $params = [];
    
    
    /**
     *
     * @var type 
     */
    protected $where = [];
    
    
    /**
     *
     * @var type 
     */
    protected $order = [];
    
    
    /**
     *
     * @var type 
     */
    protected $group = [];
    
    
    /**
     *
     * @var type 
     */
    protected $offset = 0;
    
    
    
    /**
     *
     * @var type 
     */
    protected $limit = 500;
    
    
    
    /**
     *
     * @var type 
     */
    protected $adSelect = [];
    
    
    
    /**
     * 
     */
    public function __construct() {}
    
    
    
    /**
     * 
     * @return \Minty\MySql\ORM\Query
     */
    public function QueryConnect(){
        
        $connect = new Connect;
        $this->conn = $connect->open();
        
        return $this;
    }
    
    
    public function getConnect(){
        return $this->conn;
    }
    
    
    /**
     * 
     * @param array $query
     * @return \Minty\MySql\ORM\Query
     */
    public function Select( array $query ){
        
        $this->leftJoins = [];
        
        $this->queryType = 'Select';
        $this->select_query_mixed = $query;
        return $this;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getSelectQueryMixed(){
        return $this->select_query_mixed;
    }
    
    
    
    /**
     * 
     * @param type $entity
     * @return \Minty\MySql\ORM\Query
     */
    public function From( $entity , $alias ){
        
        $this->select_from_entity_namespace_string = $entity;
        $this->select_alias = $alias;
        return $this;
    }
    
    
    
    
    
    /**
     * 
     * @param type $select
     */
    public function addSelect( $select, $key ){
        $this->adSelect[] = ['query'=>$select,'key'=>$key];
        return $this;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getAddSelect(){
        return $this->adSelect;
    }
    
    
    
    /**
     * 
     * @param type $namespace
     * @param type $alias
     * @param type $on
     * @param type $master
     * @return \Minty\MySql\ORM\Query
     */
    public function leftJoin($name, $alias, $with = null){
        
        $this->leftJoins[] = [
            'name'    => $name,
            'alias'   => $alias,
            'with'   => $with
        ];
        return $this;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getLeftJoin(){
        return $this->leftJoins;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getFromEntityNamespaceString(){
        return $this->select_from_entity_namespace_string;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getMySqlConnect(){
        return $this->conn;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getSelectAlias(){
        return $this->select_alias;
    }
    
    

    /**
     * 
     * @return type
     */
    public function getQueryType(){
        return $this->queryType;
    }
    
    
    /**
     * 
     * @return QueryBuilder
     */
    public function QueryBuilder( $EventManager = null, $called, $keyName ){
        return new QueryBuilder( $this, $EventManager, $called, $keyName );
    }
    
    
    /**
     * 
     * @param type $where
     * @return \Minty\MySql\ORM\Query
     */
    public function where( $where ){
        $this->where = [];
        $this->where[] = $where;
        return $this;
    }
    
    
    
    /**
     * 
     * @param type $where
     */
    public function addWhere($where){
        $this->where[] = $where;
        return $this;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getWhere(){
        return $this->where;
    }
    
    
    
    /**
     * 
     * @param type $offset
     * @return \Minty\MySql\ORM\Query
     * @throws \Minty\MySql\Exception\ExceptionQuery
     */
    public function setFirstResult($offset){
        if(is_numeric($offset))
            $this->offset = $offset;
        else 
            throw new \Minty\MySql\Exception\ExceptionQuery('Offset must be a integer');
        
        return $this;
    }
    
    
    /**
     * 
     * @param type $limit
     * @return \Minty\MySql\ORM\Query
     * @throws \Minty\MySql\Exception\ExceptionQuery
     */
    public function setMaxResult($limit){
        if(is_numeric($limit))
            $this->limit = $limit;
        else 
            throw new \Minty\MySql\Exception\ExceptionQuery('Max result must be a integer');
        
        return $this;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getOffset(){
        return $this->offset;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getLimit(){
        return $this->limit;
    }
    
    
    
    /**
     * 
     * @param type $where
     * @return \Minty\MySql\ORM\Query
     */
    public function groupBy( $group_by ){
        $this->group = [];
        $this->group[] = $group_by;
        return $this;
    }
    
    
    
    /**
     * 
     * @param type $where
     */
    public function addGroupBy($group_by){
        $this->group[] = $group_by;
        return $this;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getGroup(){
        return $this->group;
    }
    
    
    
    
    
    
    
    
    /**
     * 
     * @param type $key
     * @param type $value
     * @return \Minty\MySql\ORM\Query
     */
    public function addParameter($key, $value){
        $this->params[] = [
            'key' => ':'.$key,
            'value' => $value
        ];
        return $this;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getParams(){
        return $this->params;
    }
    
    
    
    /**
     * 
     * @param type $cell
     * @param type $sort
     * @return \Minty\MySql\ORM\Query
     */
    public function orderBy($cell, $sort = 'ASC' ){
        
        $this->order = [];
        
        $this->order[] = ' '.$cell.' '. ($sort ? $sort : 'ASC ');
        
        return $this;
    }
    
    
    /**
     * 
     * @param type $cell
     * @param type $sort
     * @return \Minty\MySql\ORM\Query
     */
    public function addOrderBy($cell, $sort = 'ASC'){
        
        $this->order[] = ' '.$cell.' '. ($sort ? $sort : 'ASC ');
        
        return $this;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getOrderBy(){
        return $this->order;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getConnection(){
        return $this->conn;
    }
    
    
    
    public function getMasterEntity(){
        return $this->select_from_entity_namespace_string;
    }
    
}
