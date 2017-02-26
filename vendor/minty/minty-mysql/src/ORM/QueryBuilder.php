<?php namespace Minty\MySql\ORM;

use ReflectionClass;
use PDO;
use Minty\MySql\ORM\Query;
use Minty\MySql\ORM\Entity;
use Minty\MySql\Exception\ExceptionFromEntity;
use Minty\MySql\Exception\ExceptionQuery;
use Minty\MySql\ORM\HidrateResult;
use Minty\MySql\ORM\EventManager;

class QueryBuilder {
    
    
    const UseEventManager = 'EventManager';
    
    
    /**
     *
     * @var type 
     */
    private $query;
    
    
    
    /**
     *
     * @var type 
     */
    private $fromEntity;
    
    
    
    /**
     *
     * @var type 
     */
    private $fromTable;
    
    
    
    /**
     *
     * @var type 
     */
    private $fromAlias;
    
    
    
    /**
     *
     * @var type 
     */
    private $queryType = null;
    
    
    
    /**
     *
     * @var type 
     */
    private $sql;
    
    
    
    /**
     *
     * @var type 
     */
    private $queryList = [];
    
    
    
    /**
     *
     * @var type 
     */
    private $stmth;
    
    
    
    /**
     *
     * @var type 
     */
    private $where_string;
    
    
    
    /**
     *
     * @var type 
     */
    private $result;

    
    /**
     *
     * @var type 
     */
    public $pagination = false;
    
    
    /**
     *
     * @var type 
     */
    private $called;
    
    
    /**
     *
     * @var type 
     */
    private $keyName;
    
    
    /**
     *
     * @var type 
     */
    private $singleResult = false;
    
    
    
    /**
     *
     * @var type 
     */
    private $EventManagerArgs = null;
    
    
    
    private $cache = null;
    
    
    
    private $joins = [];
    
    
    
    /**
     * 
     * @param Query $query
     */
    public function __construct( Query & $query = null, $EventManagerArgs = null, $called = null, $keyName = null ) {
        
        if(! $query ) return $this;
        
        $this->EventManagerArgs = $EventManagerArgs;
        $this->called           = $called;
        $this->keyName          = $keyName;
        
        $this->query =& $query;
        
        $this->queryType = $query->getQueryType(); 
        
        $this->parseQuery();
        $this->getDocs();
        $this->createQuery();
        $this->prepareQuery(); 
    }
    
    
    /**
     * 
     * @return type
     */
    public function getQuery(){
        return $this->query;
    }
    
    
    
    /**
     * 
     * @param \Minty\MySql\ORM\Cache $cache
     */
    public function setCache(\Minty\MySql\ORM\Cache $cache ){
        $this->cache = $cache;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getCache(){
        return $this->cache;
    }
    
    
    
    
    /**
     * 
     * @throws ExceptionFromEntity
     */
    private function parseQuery(){
        
        $from = $this->query->getFromEntityNamespaceString();
       
        if(is_string( $from ))
            $this->fromEntity = new $from();

        if($from instanceof Entity )
            $this->fromEntity = $from;
        
        if( ! $this->fromEntity )
            throw new ExceptionFromEntity('Query does not have defined FROM entity!');
        
        $this->fromAlias = $this->query->getSelectAlias();
    }
    
    
    
    
    /**
     * 
     */
    private function getDocs(){
        
        $rc = new ReflectionClass(get_class( $this->fromEntity ) );
        $docs = ($rc->getDocComment());
        
        if( $docs ){
            preg_match('/@table="(.*)"/', $docs, $match);
            if(count($match) >= 2) if($match[1]) $this->fromTable = $match[1];
        }
    }
    
    
    /**
     * 
     * @return type
     */
    public function getPrimaryKey(){
        
        return (Annotation::get()->getPrimaryKey( $this->fromEntity  ));
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getAlias(){
        return $this->fromAlias;
    }
    
    
    
    /**
     * 
     * @throws ExceptionQuery
     */
    private function createQuery(){
        
        $this->sql = null;
        $this->queryList = []; 
        $this->joins = [];
        
        switch ($this->queryType){
            case 'Select':
                $this->querySelect();
            break;
            default :
                throw new ExceptionQuery('Query builder dont have query type.');
        }
        
    }
    
    
    /**
     * 
     * @return type
     */
    public function getSQL(){
        return $this->sql;
    }
    
    
    
    /**
     * 
     * @param type $query_string
     */
    private function parse_mixed_query( & $query_string ){
        if(is_array($this->query->getSelectQueryMixed())){
            $query_string = implode(',', $this->query->getSelectQueryMixed());
        } else {
            $query_string = $this->query->getSelectQueryMixed();
        }
        
        $query_string   = preg_replace('/\s+/', '', $query_string);
    }
    
    
    /**
     * 
     * @param type $query_string
     */
    private function parse_query_list( & $query_string ){
        
        $query_array    = explode(',', $query_string);
        
        $a              = [];
        foreach ($query_array as $cell){
            $d          = explode('.', $cell);
            if(isset($d[1]))
                if($d[1])
                    $a[$cell]   = @$d[0].'_'.@$d[1];
        }
        $this->queryList= $a;
        
        $query_array    = [];
        
        if(is_array($this->queryList))
        foreach ($this->queryList as $cell => $alias) {
            if($cell && $alias)
            $query_array[] = $cell . ' as ' . $alias;
        }
        
        $query_string   = implode(',', $query_array);
    }
    
    
    /**
     * 
     */
    private function querySelect( $limit = true){
        
        // Initial Query String
        $query_string = "";
        
        $this->leftJoins();
        $this->parse_mixed_query( $query_string );
        $this->parse_query_list( $query_string );
        
        $this->addSelect( $query_string );

        if($this->joins){
            $qs = [];
            foreach ($this->joins as $join){
                $qs[] = $join->parent_alias.'.'.$join->parent_cell . ' as ' . $join->query_parent;
                
                
                if(strpos($query_string, $join->alias.'.'.$join->key.' as ') === false){
                    $s = $join->alias.'.'.$join->key.' as ' . $join->query_join;
                    
                    $qs[] = $s;
                }
            }
            $query_string .= ', '. implode(',', $qs);
        }
        
        $this->sql      = "SELECT {$query_string} FROM `{$this->fromTable}` {$this->fromAlias} ";
        $this->sql     .= $this->join_sql;
        
        $this->where();
        $this->group();
        $this->order();
        
        if($limit)
        $this->limit();
    }
    
    
    
    /**
     * 
     * @param type $query_string
     */
    private function addSelect( & $query_string ){
        
        if($addSelect = $this->query->getAddSelect() ){
            $query_string .= (trim($query_string) ? ',':'');
            
            $t = [];
            foreach ( $addSelect as $index => $query ){
                
                $_cell = $query['query'];
                $_alias = (str_replace('.','_',$query['key']));
                if( $_cell && $_alias)
                $t[] = $_cell . ' as ' . $_alias;
                
            }
            $query_string .= implode(',', $t);
        }
    }
    
    
    private $join_sql = null;
    
    /**
     * 
     */
    private function leftJoins(){
        
        $this->join_sql = "";
        
        $Hidrator = new \Minty\MySql\ORM\HidrateResult;
        $Hidrator->setJoins( $leftJoins = $this->query->getLeftJoin()   );
        $Hidrator->setMasterAlias( $MasterAlias = $this->query->getSelectAlias() );
        $Hidrator->setMasterEntity( $MasterEntity = $this->query->getMasterEntity() );
        $Hidrator->ProxyGenerate();
        
        $proxy = null;
        $joins = array();
        
        if( $leftJoins ){
            foreach ($leftJoins as $index => $join){
                
                if( ! $proxy ) { 
                    $joinObj    = $this->get_joined_evolution($MasterEntity, $MasterAlias, $join, $Hidrator);
                    $joins[]    = $joinObj;
                    $proxy      = true;
                } else {
                    $e          = explode('.',$join['name']);
                    $_a         = $e[0];
                    $_c         = $e[1];
                    
                    if($MasterAlias == $_a){
                        $joinObj = $this->get_joined_evolution($MasterEntity, $MasterAlias, $join, $Hidrator);
                        $joins[] = $joinObj;
                    }
                    else {
                        foreach ($joins as $obj){
                            if($obj->alias == $_a){
                                $joinObj = $this->get_joined_evolution($obj->entity, $obj->alias, $join, $Hidrator); 
                                $joins[$obj->alias] = $joinObj;
                            }
                        }
                    }
                }
            }
        }
        
        if($joins){
            foreach ($joins as $single_join)
                $this->join_sql .= $single_join->string;
            $Hidrator->setJoins($joins);
            $this->joins = $joins;
        }
    }
    
    public function getJoins(){ return $this->joins; }
    
    
    private function get_joined_evolution($master, $master_alias, $join, \Minty\MySql\ORM\HidrateResult $Hidrator){
       
        $joinArray  =  explode('.',$join['name']);
        $alias      = $joinArray[0];
        $cell       = $joinArray[1];

        $proxy      = $Hidrator->getProxy( $master );
        
        $string = ' LEFT JOIN `%s` %s on %s.`%s`=%s.`%s` ';
        
        if($master_alias == $alias){
            
            if(! isset( $proxy[$cell] )){
                echo 'Mising proxy key ('.$cell.') master alias ('.$master_alias.')  target alias ('.$alias.')' . PHP_EOL;
                print_r($proxy);
            }
            
            $target         = $proxy[$cell]['target'];
            $joinedTable    = Annotation::get()->getTable( $target );
            $join_str       = sprintf( $string, 
                    $joinedTable, 
                    $join['alias'], 
                    $alias, 
                    $proxy[$cell]['cell'], 
                    $join['alias'], 
                    $proxy[$cell]['relation_key']
            );
            
            $obj                = new \stdClass();
            $obj->string        = $join_str . ( $join['with'] ? (' and ' . $join['with']) : '' );
            $obj->alias         = $join['alias'];
            $obj->key           = $proxy[$cell]['relation_key'];
            $obj->parent_alias  = $alias;
            $obj->entity        = $target;
            $obj->parent_entity = $master;
            $obj->parent_cell   = $proxy[$cell]['cell'];
            $obj->query_parent  = $alias.'_'.$obj->parent_cell;
            $obj->query_join    = $obj->alias . '_' . $obj->key;
            $obj->property      = $cell;
            $obj->with          = $join['with'];

            $obj->relation      = Annotation::get()->readProperty( $master, $cell, Annotation::Relation);
            
            return $obj;
        }
    }

    /**
     * 
     */
    private function where(){
        if( $where = $this->query->getWhere() ){
            $this->where_string = "WHERE ";
            $a = [];
            
            foreach ( $where as $index => $w ){
                if(is_string($w)) $a[] = $w;
                if($w instanceof \Minty\MySql\ORM\andX) $a[] = $w->getQuery();
                if($w instanceof \Minty\MySql\ORM\orX)  $a[] = $w->getQuery();
            }
            
            $this->where_string .= implode(' AND ', $a);
            $this->sql .= $this->where_string . ' ';
        }
    }
    
    /**
     * 
     * @param type $where
     */
    public function injectPagineClausule($where){
        
        if( $this->query->getWhere() )
            $this->query->addWhere($where);
        else 
            $this->query->where($where);
        
        $this->sql = "";
        $this->querySelect();
        $this->prepareQuery();
    }

    /**
     * 
     */
    private function order(){
        if( $orders = $this->query->getOrderBy() ){
            $str = ' ORDER BY ';
            $str.= implode(',',$orders);
            $this->sql .= $str.' ';
        }
    }

    /**
     * 
     */
    private function group(){
        if( $groups = $this->query->getGroup()){
            $str = ' GROUP BY ';
            $str.= implode(',',$groups);
            $this->sql .= $str . ' ';
        }
    }

    /**
     * 
     */
    private function limit(){ if( ! $this->pagination ) $this->sql .= ' LIMIT ' . $this->query->getOffset() . ',' . $this->query->getLimit().' ';}
    
    /**
     * 
     */
    private function prepareQuery(){ $this->stmth = $this->query->getConnection()->prepare( $this->sql ); }

    /**
     * 
     * @return type
     */
    public function getParams(){ return $this->query->getParams(); }
    
    /**
     * 
     */
    private function execute(){
        
        $this->result = null;

        if( $params = $this->getParams() ){
            
            foreach ($params as $index => $param){
                
                $p = $param['key'];
                $v = $param['value'];
                
                if(is_numeric($v))
                    $this->stmth->bindValue( $p, $v,  PDO::PARAM_INT );
                
                if(is_string($v))
                    $this->stmth->bindValue( $p, $v, PDO::PARAM_STR );
                
                if(is_bool($v))
                    $this->stmth->bindValue( $p, $v, PDO::PARAM_BOOL );
                
            }
        }
        
        try {
            $this->stmth->execute();
        } catch ( PDOException $e ){
            echo $e->getMessage();
        }
    }
    
    
    /**
     * 
     * @return type
     */
    public function isSingleResult(){
        return $this->singleResult ? true : false;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getResult( $return_as_array = false ){

        $cacheResult = null; 
        
        if($this->cache){
            $cacheResult = $this->cache->getResult();
        }
        
        if(! $cacheResult ) {
            
                if($this->EventManagerArgs == 'EventManager'){
                    $start = microtime( true );
                }
            
                $this->execute();
        

                if($this->stmth->rowCount() > 0){
                    $rows = $this->stmth->fetchAll(PDO::FETCH_ASSOC);

                    if($this->cache){
                        $this->cache->setResult( $rows );
                    }

                    $HidateResult = new HidrateResult( $return_as_array );
                    $HidateResult->setQueryBuilder( $this )
                    ->setRows( $rows )
                    ->setQueryList( $this->queryList )
                    ->setMasterEntity( $this->query->getFromEntityNamespaceString() )
                    ->setMasterAlias( $this->query->getSelectAlias() )
                    ->setJoins( $this->query->getLeftJoin() );

                    $this->result = $HidateResult->hidrate();

                }
                
                
                if($this->EventManagerArgs == 'EventManager'){
                    $EventManager = EventManager::get();
                    $EventManager->setSql( $this->sql );
                    $EventManager->setExecutionTime( microtime( true ) - $start );
                    $EventManager->register($this->keyName, $this->called);
                }
                
                $this->stmth = null;
        
        } else {
            
            $HidateResult = new HidrateResult( $return_as_array );
                    $HidateResult->setQueryBuilder( $this )
                    ->setRows( $cacheResult )
                    ->setQueryList( $this->queryList )
                    ->setMasterEntity( $this->query->getFromEntityNamespaceString() )
                    ->setMasterAlias( $this->query->getSelectAlias() )
                    ->setJoins( $this->query->getLeftJoin() );

                    $this->result = $HidateResult->hidrate();
            
        }
        
        $this->joins = [];
        
        return $this->result;
    }
    
    
    /**
     * 
     * @param type $return_as_array
     * @return type
     */
    public function getSingleResult($return_as_array = false){
        $this->singleResult = true;
        return $this->getResult($return_as_array);
    }
    
}