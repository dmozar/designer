<?php namespace Administrator\RealEstate\Specification\Controller;

use Administrator\RealEstate\Specification\Module;
use Minty\View\ViewModel;
use Minty\Router;
use Minty\MySql\ORM\Query;
use Minty\MySql\ORM\QueryBuilder;
use Minty\MySql\ORM\Cache;
use Minty\Event\EventManager;


class SpecificationController extends Module {
    
    
    /**
     * 
     */
    public function __construct() {
        parent::__construct();
        
        $this->register(__FILE__);
        
    }
    
    
    /**
     * 
     * @return type
     */
    public function index(){
        
        EventManager::SEO('title','Specification Listing');

        $ServiceLocator = $this->getServiceLocator();
        
        $ViewModel =  $ServiceLocator->get('SpecificationHelper');
        
        return $ViewModel;
        
    }
    
    
    
    /**
     * 
     */
    public function grid(){
        
        $ServiceLocator = $this->getServiceLocator();  
        
        $options = [
            'repository'    => 'AdRepository',
            'method'        => 'getSpecificationGrid',
            'cells'         => [
                        'Category'  => 'type->name',
                        'Name'      => 'name',
                        'Status'    => 'status',
                        'Edit'      => null,
            ],
            'key'           => 'id',
            'plus'          => [
                'Status' => [
                    '0' => '<i class="fa fa-circle-o text-color-red cursor-pointer" aria-hidden="true" data-ajax="'.(Router::FromRoute('Administrator\RealEstate\Specification', 'status', array('_key_',1))).'"></i>',
                    '1' => '<i class="fa fa-check text-color-green cursor-pointer" aria-hidden="true" data-ajax="'.(Router::FromRoute('Administrator\RealEstate\Specification', 'status', array('_key_',0))).'"></i>'
                ],
                'Edit' => '<a href="'.Router::FromRoute('Administrator\RealEstate\Specification', 'edit', array('_key_')).'" class="cursor-pointer"><i class="fa fa-wrench" aria-hidden="true"></i</a>'
            ]
        ];
        
        $ServiceLocator->get('GridHelper', $options);
        
    }
    
    
    
    /**
     * 
     */
    public function status(){

        Cache::instance('imedia_get_specificationgrid')->useRedisCache()->delete();
        Cache::instance('imedia_get_ad_specification')->useRedisCache()->delete();
        Cache::instance('imedia_get_ad_specfilters')->useRedisCache()->delete();
        
        $ServiceLocator = $this->getServiceLocator();
        
        $RouteService   = $ServiceLocator->Route;
        $Route          = $RouteService->Route();
        
        $params = $Route->params_from_route();
        
        $status = $params['status'];
        $id     = $params['id'];
        
        $AdRepository = $ServiceLocator->get('AdRepository');
        
        $Type = $AdRepository->getSpecification( $id, false );
        $Type->setStatus( $status );
        
        $em = \Minty\MySql\ORM\EventManager::get();
        $em->persist( $Type );
        $em->flush();
        
        if($status){
            $str = '<i class="fa fa-check text-color-green cursor-pointer" aria-hidden="true" data-ajax="'.(Router::FromRoute('Administrator\RealEstate\Specification', 'status', [$id,0])).'"></i>';
        } else {
            $str = '<i class="fa fa-circle-o text-color-red cursor-pointer" aria-hidden="true" data-ajax="'.(Router::FromRoute('Administrator\RealEstate\Specification', 'status', [$id,1])).'"></i>';
        }
        
        echo $str;
        exit(0);
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function edit(){
        
        EventManager::SEO('title','Edit Real Estate Specification');
        
        $ServiceLocator = $this->getServiceLocator();
        
        $RouteService   = $ServiceLocator->Route;
        $Route          = $RouteService->Route();
        $params         = $Route->params_from_route();
        $post           = $this->getHttp()->getPost();
        
        $options = [
            'submit' => $params['submit'],
            'id'     => $params['id'],
            'post'   => $post,
            'inputs' => $this->getModuleConfigs()['inputs']
        ];
        
        $ViewModel =  $ServiceLocator->get('SpecificationEditHelper', $options );
        
        return $ViewModel;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function add(){
        
        EventManager::SEO('title','Add Real Estate Specification');
        
        $ServiceLocator = $this->getServiceLocator();
        
        $RouteService   = $ServiceLocator->Route;
        $Route          = $RouteService->Route();
        $params         = $Route->params_from_route();
        $post           = $this->getHttp()->getPost();
        
        $options = [
            'submit' => $params['submit'],
            'id'     => $params['id'],
            'post'   => $post,
            'inputs' => $this->getModuleConfigs()['inputs']
        ];
        
        $ViewModel =  $ServiceLocator->get('SpecificationAddHelper', $options );
        
        return $ViewModel;
    }
    
    
    
}