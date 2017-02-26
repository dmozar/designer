<?php


return [
    
    'routes' => [
        
        'index' => [
            'route' => '/backside/specification',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Specification\Controller\SpecificationController',
            'method' => 'index',
            'action' => 'http',
            
        ],
        'add' => [
            'route' => '/backside/specification/add',
            'constraints' => [
                'id' => null,
                'submit' => false,
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Specification\Controller\SpecificationController',
            'method' => 'add',
            'action' => 'http',
            'children' => [
                'submit' => [
                    'route' => '/submit',
                    'constraints' => [
                        'id'     => null,
                        'submit' => true
                    ],
                    'priority'  => 1,
                    'action' => 'ajax'
                ]
            ],
        ],
        'edit' => [
            'route' => '/backside/specification/edit/(?P<id>\d+)',
            'constraints' => [
                'id' => null,
                'submit' => false,
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Specification\Controller\SpecificationController',
            'method' => 'edit',
            'action' => 'http',
            'children' => [
                'submit' => [
                    'route' => '/submit',
                    'constraints' => [
                        'id'     => null,
                        'submit' => true
                    ],
                    'priority'  => 1,
                    'action' => 'ajax'
                ]
            ],
        ],
        'grid' => [
            'route' => '/backside/specification_source',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Specification\Controller\SpecificationController',
            'method' => 'grid',
            'action' => 'http',
        ],
        'status' => [
            'route' => '/backside/specification_status/(?P<id>\w+)/(?P<status>\d+)',
            'constraints' => [
                'id'        => null,
                'status'    => 1
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Specification\Controller\SpecificationController',
            'method' => 'status',
            'action' => 'http',
        ],
        
    ],
    'services' => [
        'SpecificationService' => 'Administrator\RealEstate\Specification\Service\SpecificationService',
    ],
    
    'views' => [
        'index'                     => __DIR__ . '/../view/master/index.phtml',
        'specification_add'          => __DIR__ . '/../view/master/add.phtml',
        'specification_edit'         => __DIR__ . '/../view/master/edit.phtml',
        'specification_add_form'     => __DIR__ . '/../view/partial/add_form.phtml',
        'specification_edit_form'    => __DIR__ . '/../view/partial/edit_form.phtml',
    ],
    'view_helpers' => [
        'SpecificationHelper'        => 'Administrator\RealEstate\Specification\View\Helper',
        'SpecificationAddHelper'     => 'Administrator\RealEstate\Specification\View\AddHelper',
        'SpecificationEditHelper'    => 'Administrator\RealEstate\Specification\View\EditHelper'
    ],
    
    'master'                => PATH_ROOT . 'vendor/imedia/administrator/config/master.config.php',
    
    /**
     * Assets
     */
    'assets' => [
        'remove' => [
            'realestate.css'
        ],
        'css' => [
            
        ],
        'js' => [

        ],
    ],
    
    
    /* Inputs */
    'inputs' => [
        'name'              => ['string|required',              'ErrorName',        'Name'],
        'slug'              => ['string|required',              'ErrorSlug',        'Slug'],
        'position'          => ['number|required',              'ErrorPosition',    'Position'],
        'status'            => ['number|required',              'ErrorStatus',      'Status'],
        'type'              => ['number|required',              'ErrorType',        'Type']
    ],
    
];