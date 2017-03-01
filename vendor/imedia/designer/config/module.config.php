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
                'submit' => [
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
        ]
        
    ],
    
    
    'services' => [
        'DesignerService' => 'Imedia\Designer\Service\Service',
    ],
    
    
    'views' => [
        'index'      => __DIR__ . '/../view/master/index.phtml',
        'editor'     => __DIR__ . '/../view/partial/editor.phtml',
    ],
    
    
    'view_helpers' => [
        'DesignerHelper' => 'Imedia\Designer\View\Helper'
    ],
    
    
    'assets' => [
        'css' => [
            'designer.css'      => __DIR__ . '/../public/css/designer.css'
        ],
        'js' => [
            
        ],
    ]
    
];