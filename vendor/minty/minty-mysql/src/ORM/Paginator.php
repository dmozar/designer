<?php namespace Minty\MySql\ORM;

use Minty\MySql\ORM\Connect;

class Paginator {
    
    
    /**
     *
     * @var type 
     */
    private $QueryBuilder;
    
    /**
     *
     * @var type 
     */
    public $total = 0;
    
    
    /**
     *
     * @var type 
     */
    public $offset = 0;
    
    
    /**
     *
     * @var type 
     */
    public $limit = 0;
    
    
    /**
     *
     * @var type 
     */
    public $result = null;
    
    
    /**
     *
     * @var type 
     */
    private $sql;
    
    
    /**
     *
     * @var type 
     */
    private $sql2;
    
    
    /**
     *
     * @var type 
     */
    private $query;
    
    
    /**
     *
     * @var type 
     */
    private $alias;
    
    
    /**
     *
     * @var type 
     */
    private $primaryKey;
    
    
    
    /**
     * 
     * @param \Minty\MySql\ORM\QueryBuilder $QueryBuilder
     * @return \Minty\MySql\ORM\Paginator
     */
    public function __construct( \Minty\MySql\ORM\QueryBuilder & $QueryBuilder ) {
        
        $this->QueryBuilder = & $QueryBuilder;
        
        $this->QueryBuilder->pagination = true;
        
        return $this;
    }
    
    
    /**
     * 
     */
    private function sql(){
        
        $this->sql = $this->QueryBuilder->getSQL();
    }
    
    
    /**
     * 
     * @return type
     */
    private function sql2(){
        
        return $this->sql2;
    }
    
    
    
    /**
     * 
     * @return type
     */
    private function getPrimaryKey(){
        return $this->QueryBuilder->getPrimaryKey();
    }
    
    
    /**
     * 
     */
    private function query(){
        $this->query = $this->QueryBuilder->getQuery();
    }
    
    
    
    /**
     * 
     */
    private function alias(){
        $this->alias = $this->QueryBuilder->getAlias();
    }
    
    
    
    /**
     * 
     */
    private function create_sql(){
        
        $this->alias();
        
        $this->primaryKey = $this->getPrimaryKey();
        
        $sql = preg_replace('/SELECT[(.*)]+?FROM/', $this->alias.'.'.$this->primaryKey, $this->QueryBuilder->getSQL() );
        
        $e = explode('FROM', $sql);  array_shift($e);
        
        $sql  = 'SELECT COUNT(DISTINCT(' . $this->alias . '.' . $this->primaryKey . ')) as total FROM ' . (implode(' ', $e));
        $sql2 = 'SELECT DISTINCT(' . $this->alias . '.' . $this->primaryKey . ') as '.$this->primaryKey.' FROM ' . (implode(' ', $e));
        
        $a = explode('LIMIT', $sql); array_pop($a);
        $a = implode(' ', $a);
        
        if(strpos($a,'ORDER BY') !== false){
            $a = explode('ORDER BY', $sql); array_pop($a);
            $a = implode(' ', $a);
        }
        
        $this->sql  = $a;
        $this->sql2 = $sql2;
        
    }
    
    
    
    /**
     * 
     * @param type $result
     */
    private function add_list( $result ){
        
        $r = [];
        
        foreach ($result as $i => $d)
            $r[$d[$this->primaryKey]] = $d[$this->primaryKey];
        
        if($this->total){
            $where = $this->alias.'.'.$this->primaryKey.' in ('.(implode(',',$r)).') ';
            $this->QueryBuilder->injectPagineClausule($where);
        }
    }
    
    
    
    
    /**
     * 
     */
    public function getCount(){
        
        $connect    = new Connect; 
        $conn       = $connect->open();
        $params     = $this->QueryBuilder->getQuery()->getParams();
        $stmth      = $conn->prepare( $this->sql );
        
        if($params)
            foreach ($params as $index => $param )
                $stmth->bindValue($param['key'],$param['value']);
        
        $stmth->execute();
        
        $result = $stmth->fetch();
        
        $this->total = $result['total'];
        
    }
    
    
    
    /**
     * 
     * @return \Minty\MySql\ORM\Paginator
     */
    public function getResult(){
        
        $this->sql();
        $this->query();
        $this->alias();
        $this->create_sql();
        
        $this->getCount();

        $connect    = new Connect; 
        $conn       = $connect->open();
        $params     = $this->QueryBuilder->getQuery()->getParams();
        $stmth      = $conn->prepare( $this->sql2 );
        
        $this->offset = $this->QueryBuilder->getQuery()->getOffset();
        $this->limit = $this->QueryBuilder->getQuery()->getLimit();
        
        if($params)
            foreach ($params as $index => $param )
                $stmth->bindValue($param['key'],$param['value']);
        
        $stmth->execute();
        $this->add_list( $stmth->fetchAll(\PDO::FETCH_ASSOC) );
        $this->result = $this->QueryBuilder->getResult();
        
        $stmth = null;
        
        unset($stmth);
        unset($connect);
        unset($conn);
        unset($param);
        unset($params);
        unset($index);
        unset($this->QueryBuilder);
        unset($this->alias);
        unset($this->primaryKey);
        unset($this->query);
        unset($this->sql);
        unset($this->sql2);

        return $this;
    }
}

