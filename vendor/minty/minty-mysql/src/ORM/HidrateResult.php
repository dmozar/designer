<?php namespace Minty\MySql\ORM;

use Minty\MySql\ORM\Annotation;
use Minty\Cache as MintyCache;
use Minty\File\Directory;
use Minty\File\File;

class HidrateResult {

    /**
     *
     * @var type 
     */
   private $queryBuilder;
 
   /**
    *
    * @var type 
    */
   private $rows = [];

   /**
    *
    * @var type 
    */
   private $queryList = [];

   /**
    *
    * @var type 
    */
   private $joins = [];

   /**
    *
    * @var type 
    */
   private $MasterEntity;

   /**
    *
    * @var type 
    */
   private $MasterAlias;

   /**
    *
    * @var type 
    */
   private $asArray = false;

   /**
    * 
    */
   public function __construct( $return_as_array = false) { $this->asArray = $return_as_array; }

   /**
    * 
    * @param \Minty\MySql\ORM\QueryBuilder $queryBuilder
    * @return \Minty\MySql\ORM\HidrateResult
    */
   public function setQueryBuilder( \Minty\MySql\ORM\QueryBuilder $queryBuilder ){ $this->queryBuilder = $queryBuilder; return $this; }

   /**
    * 
    * @param array $rows
    * @return \Minty\MySql\ORM\HidrateResult
    */
   public function setRows( array $rows = []){ $this->rows = $rows; return $this; }

   /**
    * 
    * @param array $queryList
    * @return \Minty\MySql\ORM\HidrateResult
    */
   public function setQueryList( array $queryList ){ $this->queryList = $queryList; return $this; }

   /**
    * 
    * @param type $masterEntity
    * @return \Minty\MySql\ORM\HidrateResult
    */
   public function setMasterEntity( $masterEntity ){ $this->MasterEntity = $masterEntity; return $this; }

   /**
    * 
    * @param type $alias
    * @return \Minty\MySql\ORM\HidrateResult
    */
   public function setMasterAlias( $alias ){ $this->MasterAlias = $alias; return $this; }

   /**
    * 
    * @param type $joins
    * @return \Minty\MySql\ORM\HidrateResult
    */
   public function setJoins( $joins ){ $this->joins = $joins; return $this; }

   
   /**
    * 
    * @param \Minty\MySql\ORM\Entity $Entity
    * @return type
    */
   private function getPrimaryKey( Entity $Entity ){ return Annotation::get()->getPrimaryKey( $Entity );}
   
   
   
   
   
  

    
    private function alternate(  ){
        
        $serialized = serialize( $this->entities );
        
        foreach ($this->entities as $k => & $data){
            
            $proxies = $this->getProxy( get_class($data) );
            
            $pk = Annotation::get()->getPrimarykey( $data );
            
            foreach ($proxies as $name => $proxy ){
                
                if($proxy['target']){
                    
                    $regex = '/('. str_replace('\\','\\\\',$proxy['target']).'-\d+)(.*?)('.$data->{$pk}.')/';
                    
                    //preg_match($regex, $serialized, $match);
                    
                    preg_match_all($regex, $serialized, $matches);
                    
                   
                    foreach ($matches as $matched){
                        foreach ($matched as $k => $match){
                            
                                // (Imedia\\RealEstate\\Ad\\Entity\\Ad\\Image-\d+)$
                            
                                preg_match('/('. str_replace('\\','\\\\',$proxy['target']).'-\d+)$/', $match, $m);
                        
                                if(isset($m[1])){

                                    $m = $m[1];
                                    $e = explode('-',$m);
                                    $indx = $proxy['target'].'-'. end($e);

                                    if(isset($this->entities[$indx])){

                                        if( $proxy['type'] == 'OneToOne'){
                                            $data->{$name} = $this->entities[$indx];
                                        } else {

                                            if( !is_array($data->{$name}))
                                            $data->{$name} = array();

                                            $data->{$name}[] = $this->entities[$indx];
                                        }

                                    }
                                } else {



                                    $rid = $data->{$proxy['cell']};

                                    $indx = $proxy['target'].'-'.$rid;

                                    if(isset($this->entities[$indx])){

                                        if( $proxy['type'] == 'OneToOne'){
                                            $data->{$name} = $this->entities[$indx];
                                        } else {

                                            if(  !is_array($data->{$name}))
                                            $data->{$name} = array();

                                            $data->{$name}[] = $this->entities[$indx];
                                        }
                                    }

                                }
                        
                        }
                    
                    }
                }
                
            }
        }
        
    }
    
   
   
   /**
    *
    * @var type 
    */
    private $map, $map_fields;
   
   /**
    * 
    * @return \Minty\MySql\ORM\MasterEntity
    */
   public function hidrate(){
       
       $this->map_result();
       $this->rows_to_array();
       
       $result = [];
       
       return $result;
   }
   
   
   private function rows_to_array(){
       
       print_r($this->map);
       print_r($this->map_fields);
       
       $list = [];
       
       foreach ($this->rows as $row){
           
           $a = [];
           
           foreach ($row as $cell_name => $cell_value){
               if(array_key_exists($cell_name, $this->map_fields))
                    $a[ $this->map_fields[$cell_name] ] = $cell_value;
           }
           
           $this->joins_to_array($row, $a, $this->MasterEntity );
           
           $list[] = $a;
       }
       
       print_r($list);
   }
   
   
   private function joins_to_array($row, & $a, $entity_name){
       
       print_r($a);
       
       if(is_array( $this->map_fields[ $entity_name] ))
            foreach ($this->map_fields[ $entity_name ] as $property => $rules){
                
                if($rules['tip'] == 'OneToOne')
                    if( ! isset($a[$property] )) $a[$property] = null;
                if($rules['tip'] == 'OneToMany')
                    if( ! isset($a[$property] )) $a[$property] = [];
                if($rules['tip'] == 'ManyToOne')
                    if( ! isset($a[$property] )) $a[$property] = null;
                if($rules['tip'] == 'ManyToMany')
                    if( ! isset($a[$property] )) $a[$property] = [];
                    
                print_r($rules);
                foreach ($row as $cell_name => $data){
                    
                }
                
           
            }
   }
   
   
   private function map_result(){
       
        $map = [];

        $map[$this->MasterAlias.'_'] = $this->MasterEntity;

        $joins = $this->queryBuilder->getJoins();

        foreach ($joins as $join){
            $map[$join->alias . '_'] = $join->entity;
        }

        $this->map = $map;
        
        unset($map);

        $map_fields = [];
       
       
        $proxies = $this->getProxy( $this->MasterEntity );
       
        foreach ($proxies as $name => $proxy){
            if( ! $proxy['target'] ){
                $map_fields[$this->MasterAlias.'_'.$name] = $name;
            } else {
                $map_fields[$this->MasterEntity][$name] = [
                    'name' => $name,
                    'rel'  => $proxy['relation_key'],
                    'cel'  => $proxy['cell'],
                    'tar'  => $proxy['target'],
                    'tip'  => $proxy['type']
                ];
            }
        }
       
        foreach ($joins as $join){
            $entity = $join->entity;
            $alias  = $join->alias;
            $proxies = $this->getProxy( $entity );
            foreach ($proxies as $name => $proxy){ 
                if( ! $proxy['target'] ){
                    $map_fields[$this->MasterAlias.'_'.$name] = $name;
                }
            }
        }

        $this->map_fields = $map_fields;
        unset($map_fields);
        
        
   }
   

   
   /**
    *
    * @var type 
    */
   public static 
           $isGenerated = false, 
           $proxyPath, 
           $proxies = [];
   
   
   /**
    * 
    */
   public function ProxyGenerate(){
       $proxies = MintyCache::$cached_app['database']['proxies'];
        foreach ($proxies as $entity )
             $this->generate_proxy( $entity );
   }
   
   
   /**
    * 
    * @param type $entity
    */
   private function generate_proxy( $entity ){
       
       self::$isGenerated = true;
       
       $proxyName = $this->getProxyName( $entity );
       $proxyPath = MintyCache::$cached_app['database']['proxy_path']. $proxyName;
       
       if(!file_exists($proxyPath) || ENV == 'development'){
           
            $e = new $entity; $vars = [];

            foreach (get_object_vars( $e ) as $key => $value){
                    $vars[$key] = [
                        'type'           => Annotation::get()->readProperty($e, $key, Annotation::Relation),
                        'cell'           => Annotation::get()->readProperty($e, $key, Annotation::Cell),
                        'target'         => Annotation::get()->readProperty($e, $key, Annotation::Target),
                        'relation_key'   => Annotation::get()->readProperty($e, $key, Annotation::RelationKey),
                    ];
            }
            File::get()->write('<?php ' . PHP_EOL . 'return '.var_export($vars, true) . ';', $proxyPath);
       }
   }
   
   
   /**
    * 
    * @param type $entity
    * @return type
    */
   public function getProxyName( $entity ){  return 'CG__' . str_replace('\\','_',$entity) . '.inc.php'; }
   
   
   /**
    * 
    * @param type $entity
    * @return type
    * @throws Exception
    */
   public function getProxy( $entity ){
       
       if(array_key_exists($entity, self::$proxies)) return self::$proxies[$entity];
       if(!self::$proxyPath) self::$proxyPath = MintyCache::$cached_app['database']['proxy_path'];
       
       $proxyPath = self::$proxyPath.  $this->getProxyName( $entity );
       
       if(file_exists($proxyPath)) self::$proxies[$entity] = include( $proxyPath );
       else  throw new Exception('Proxy for ' . $entity . ' is not found!');
       
       return self::$proxies[$entity];
   }
}