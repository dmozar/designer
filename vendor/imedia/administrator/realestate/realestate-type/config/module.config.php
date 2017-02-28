<?php


return [
    
    'routes' => [
        
        'type' => [
            'route' => '/backside/type',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Type\Controller\TypeController',
            'method' => 'index',
            'action' => 'http',
            
        ],
        'type_add' => [
            'route' => '/backside/type/add',
            'constraints' => [
                'id' => null,
                'submit' => false,
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Type\Controller\TypeController',
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
        'type_edit' => [
            'route' => '/backside/type/edit/(?P<id>\d+)',
            'constraints' => [
                'id' => null,
                'submit' => false,
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Type\Controller\TypeController',
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
            'route' => '/backside/type_source',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Type\Controller\TypeController',
            'method' => 'grid',
            'action' => 'http',
        ],
        'status' => [
            'route' => '/backside/type_status/(?P<id>\w+)/(?P<status>\d+)',
            'constraints' => [
                'id'        => null,
                'status'    => 1
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Type\Controller\TypeController',
            'method' => 'type_status',
            'action' => 'http',
        ],
        
    ],
    'services' => [
        'TypeService' => 'Administrator\RealEstate\Type\Service\TypeService',
    ],
    
    'views' => [
        'index'             => __DIR__ . '/../view/master/index.phtml',
        'type_add'          => __DIR__ . '/../view/master/type_add.phtml',
        'type_edit'         => __DIR__ . '/../view/master/type_edit.phtml',
        'type_add_form'     => __DIR__ . '/../view/partial/add_type_form.phtml',
        'type_edit_form'    => __DIR__ . '/../view/partial/edit_type_form.phtml',
    ],
    'view_helpers' => [
        'TypeHelper'        => 'Administrator\RealEstate\Type\View\TypeHelper',
        'TypeAddHelper'     => 'Administrator\RealEstate\Type\View\TypeAddHelper',
        'TypeEditHelper'    => 'Administrator\RealEstate\Type\View\TypeEditHelper'
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
        'search_name'       => ['string|required',              'ErrorSearchName',  'SearchName'],
        'slug'              => ['string|required',              'ErrorSlug',        'Slug'],
        'tags'              => ['string|required',              'ErrorTags',        'Tags'],
        'position'          => ['number|required',              'ErrorPosition',    'Position'],
        'status'            => ['number|required',              'ErrorStatus',      'Status']
    ],
    
];