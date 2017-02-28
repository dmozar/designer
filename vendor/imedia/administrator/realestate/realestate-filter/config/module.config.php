<?php


return [
    
    'routes' => [
        
        'index' => [
            'route' => '/backside/filters',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Filter\Controller\FilterController',
            'method' => 'index',
            'action' => 'http',
            
        ],
        'add' => [
            'route' => '/backside/filter/add',
            'constraints' => [
                'id'        => null,
                'submit'    => false,
                'type_id'   => null
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Filter\Controller\FilterController',
            'method' => 'add',
            'action' => 'http',
            'children' => [
                'type_select' => [
                    'route' => '/(?P<type_id>\d+)',
                    'constraints' => [
                        'id'        => null,
                        'submit'    => false,
                        'type_id'   => null
                    ],
                    'priority'  => 1,
                    'action' => 'http'
                ],
                'submit' => [
                    'route' => '/submit',
                    'constraints' => [
                        'id'     => null,
                        'submit' => true
                    ],
                    'priority'  => 2,
                    'action' => 'ajax'
                ]
            ],
        ],
        'edit' => [
            'route' => '/backside/filter/edit/(?P<id>\d+)',
            'constraints' => [
                'id' => null,
                'submit' => false,
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Filter\Controller\FilterController',
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
            'route' => '/backside/filter_source',
            'constraints' => [
                'type_id' => null
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Filter\Controller\FilterController',
            'method' => 'grid',
            'action' => 'ajax',
            'children' => [
                'grid2' => [
                    'route' => '/(?P<type_id>\d+)',
                    'constraints' => [
                        'type_id' => null,
                    ],
                    'priority'  => 1,
                    'action' => 'ajax'
                ]
            ],
        ],
        'status' => [
            'route' => '/backside/filter_status/(?P<id>\w+)/(?P<status>\d+)',
            'constraints' => [
                'id'        => null,
                'status'    => 1
            ],
            'priority' => 1,
            'controller' => 'Administrator\RealEstate\Filter\Controller\FilterController',
            'method' => 'status',
            'action' => 'http',
        ],
        
    ],
    'services' => [
        'SpecificationFilterService' => 'Administrator\RealEstate\Filter\Service\FilterService',
    ],
    
    'views' => [
        'index'                     => __DIR__ . '/../view/master/index.phtml',
        'filter_add'                => __DIR__ . '/../view/master/add.phtml',
        'filter_edit'               => __DIR__ . '/../view/master/edit.phtml',
        'filter_add_form'           => __DIR__ . '/../view/partial/add_form.phtml',
        'filter_edit_form'          => __DIR__ . '/../view/partial/edit_form.phtml',
        'type_select'               => __DIR__ . '/../view/master/type_select.phtml',
        'type_form'                 => __DIR__ . '/../view/partial/type_form.phtml',
    ],
    'view_helpers' => [
        'FilterHelper'              => 'Administrator\RealEstate\Filter\View\Helper',
        'FilterAddHelper'           => 'Administrator\RealEstate\Filter\View\AddHelper',
        'FilterEditHelper'          => 'Administrator\RealEstate\Filter\View\EditHelper'
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
        'name'              => ['string|required',              'ErrorName',                'Name'],
        'slug'              => ['string|required',              'ErrorSlug',                'Slug'],
        'position'          => ['number|required',              'ErrorPosition',            'Position'],
        'status'            => ['number|required',              'ErrorStatus',              'Status'],
        'specification'     => ['number|required',              'ErrorSpecification',       'Specification'],
        'typeselect'        => ['number|required',              'ErrorTypeSelect',          'TypeSelect']
    ],
    
];