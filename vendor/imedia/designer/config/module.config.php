<?php


return [
    
    'routes' => [
        
        'index' => [
            'route' => '/designer',
            'constraints' => [
                'id' => null
            ],
            'priority' => 1,
            'controller' => 'Imedia\Designer\Controller\Controller',
            'method' => 'index',
            'action' => 'http',
            'children' => [
                'item' => [
                    'route' => '/(?P<id>\d+)',
                    'constraints' => [
                        'id' => null,
                    ],
                    'priority'  => 1,
                    'action' => 'http'
                ],
            ]
        ],
        'save' => [
            'route' => '/designer/save',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Imedia\Designer\Controller\Controller',
            'method' => 'save',
            'action' => 'ajax'
        ],
        'load' => [
            'route' => '/designer/load',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Imedia\Designer\Controller\Controller',
            'method' => 'load',
            'action' => 'ajax'
        ],
        'history' => [
            'route' => '/designer/history',
            'constraints' => [
                'page' => 1
            ],
            'priority' => 1,
            'controller' => 'Imedia\Designer\Controller\Controller',
            'method' => 'history',
            'action' => 'http',
            'children' => [
                'submit' => [
                    'route' => '/(?P<page>\d+)',
                    'constraints' => [
                        'page' => 1,
                    ],
                    'priority'  => 1,
                    'action' => 'http'
                ],
            ]
        ],
        'remove' => [
            'route' => '/designer/remove/(?P<id>\d+)',
            'constraints' => [
                'id'        => null,
                'confirmed' => false
            ],
            'priority' => 1,
            'controller' => 'Imedia\Designer\Controller\Controller',
            'method' => 'remove',
            'action' => 'http',
            'children' => [
                'confirmed' => [
                    'route' => '/confirmed',
                    'constraints' => [
                        'id'        => null,
                        'confirmed' => true,
                    ],
                    'priority'  => 1,
                    'action' => 'http'
                ],
            ]
        ],
    ],
    
    
    'services' => [
        'DesignerService' => 'Imedia\Designer\Service\Service',
    ],
    
    
    'views' => [
        'index'             => __DIR__ . '/../view/master/index.phtml',
        'editor'            => __DIR__ . '/../view/partial/editor.phtml',
        'list_designes'     => __DIR__ . '/../view/master/list.phtml',
        'list_design_item'  => __DIR__ . '/../view/partial/list_item.phtml',
        'remove_design_item'=> __DIR__ . '/../view/master/remove.phtml',
    ],
    
    
    'view_helpers' => [
        'DesignerHelper' => 'Imedia\Designer\View\Helper',
        'HistoryDesignHelper' => 'Imedia\Designer\View\HistoryHelper',
        'ViewHelper' => 'Imedia\Designer\View\ViewHelper'
    ],
    
    
    'assets' => [
        'css_collection'    => 'css/design.css',
        'js_collection'     => 'js/design.js',
        'css_name'          => 'design.css',
        'js_name'           => 'design.js',
        'css' => [
            'designer.css'      => __DIR__ . '/../public/css/designer.css'
        ],
        'js' => [
            
        ],
    ]
    
];