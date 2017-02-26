# minty

This is still a development of guidelines AND NOT FOR PRODUCTION

Powerful but easy php pattern designs framework. Minty framework has ORM framework inspired by the Doctrine 2 but is a lot easier and faster.

# ORM Query Sample


<pre>public function getSpecifications( $type_id ){
        
        $query = new Query;
        
        $query-&gt;QueryConnect()
            -&gt;Select(array(
                's.id, s.name, s.slug, s.status, s.position',
                't.id, t.name, t.slug, t.status, t.position'
            ))-&gt;From( self::AdSpecifications, 's')
            -&gt;leftJoin( 's.type', 't' )
            -&gt;orderBy('s.position', 'ASC');
        
        if($type_id)
            $query-&gt;addWhere ('t.id = :type_id')-&gt;addParameter ('type_id', $type_id);
        
        $QueryBuilder = $query-&gt;QueryBuilder( QueryBuilder::UseEventManager, $this, 'getSpecifications' );
        
        Cache::get($QueryBuilder, 'imedia_get_ad_specifications')-&gt;useRedisCache(3600);
        
        return $QueryBuilder-&gt;getResult( );
}</pre>

# ORM Entity Sample

<pre>&lt;?php namespace Imedia\RealEstate\Ad\Entity\Ad;

use Minty\MySql\ORM\Entity;
use Imedia\RealEstate\Ad\Entity\Ad\Type;

/**
 * @table="ad_specification"
 */
class Specification extends Entity {
    
    
    /**
     * @primary
     * @type="primary"
     * @cell="id"
     */
    public $id;

    /**
     *
     * @type="name"
     * @cell="name"
     */
    public $name;
    
    /**
     *
     * @type="string"
     * @cell="slug"
     */
    public $slug;
    
    /**
     *
     * @type="integer"
     * @cell="position"
     */
    public $position;
    
    /**
     *
     * @type="string"
     * @cell="status"
     */
    public $status;

    /**
     * @type="OneToOne"
     * @cell="type_id"
     * @target="Imedia\RealEstate\Ad\Entity\Ad\Type"
     * @relation="id"
     */
    public $type;
    
    /**
     * @type="OneToMany"
     * @target="Imedia\RealEstate\Ad\Entity\Ad\Filter"
     * @cell="id"
     * @relation="specification_id"
     */
    public $filters;
    
    
    public function __construct() {

    }

    public function getID(){ return $this-&gt;id; }
    public function getName(){ return $this-&gt;name; }
    public function setName( $name ){ $this-&gt;name = $name; }
    public function getSlug(){ return $this-&gt;slug; }
    public function setSlug( $slug ){ $this-&gt;slug = $slug; }
    public function getPosition(){ return $this-&gt;position; }
    public function setPosition( $position ){ $this-&gt;position = $position; }
    public function getStatus(){ return $this-&gt;status; }
    public function setStatus( $status ){ $this-&gt;status = $status ? 1 : 0; }
    public function getType(){ return $this-&gt;type; }
    public function setType( $type = null ){ $this-&gt;type = $type; }
    public function getFilters(){ return $this-&gt;filters; }
    
}</pre>

#Controller Sample

<pre>&lt;?php namespace Imedia\RealEstate\Home\Controller;

use Imedia\RealEstate\Home\Module;
use Minty\View\ViewModel;


class HomeController extends Module {
    
    
    
    public function __construct() {
        parent::__construct();
        
        $this-&gt;register(__FILE__);
        
    }
    
    
    public function index(){
        
        $ServiceLocator = $this-&gt;getServiceLocator();
        
        $ViewModel =  $ServiceLocator-&gt;get('IndexHelper');
        
        return $ViewModel;
        
    }
    
}</pre>

#Configuration Sample

<pre>&lt;?php


return [
    
    'routes' =&gt; [
        
        'home' =&gt; [
            'route' =&gt; '/',
            'constraints' =&gt; [
                
            ],
            'priority' =&gt; 1,
            'controller' =&gt; 'Imedia\RealEstate\Home\Controller\HomeController',
            'method' =&gt; 'index',
            'action' =&gt; 'http'
        ]
        
    ],
    'services' =&gt; [
        'RealEstateHomeService' =&gt; 'Imedia\RealEstate\Home\Service\RealEstateHomeService',
    ],
    
    'views' =&gt; [
        'index'     =&gt; __DIR__ . '/../view/master/index.phtml',
    ],
    'view_helpers' =&gt; [
        'IndexHelper' =&gt; 'Imedia\RealEstate\Home\View\IndexHelper'
    ],
    
    /**
     * Assets
     */
    'assets' =&gt; [
        'css' =&gt; [
            'real_estate_home.css'      =&gt; __DIR__ . '/../public/css/real_estate_home.css'
        ],
        'js' =&gt; [

        ],
    ]
    
];</pre>
