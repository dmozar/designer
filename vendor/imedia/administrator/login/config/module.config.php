<?php


return [
    
    'routes' => [
        
        'login' => [
            'route' => '/backside/login',
            'constraints' => [
                'submit' => false
            ],
            'priority' => 1,
            'controller' => 'Administrator\Login\Controller\LoginController',
            'method' => 'index',
            'action' => 'http',
            'children' => [
                'submit' => [
                    'route' => '/submit',
                    'constraints' => [
                        'submit' => true
                    ],
                    'priority'  => 1,
                    'action' => 'ajax'
                ]
            ]
        ]
        
    ],
    'services' => [
        'LoginService' => 'Administrator\Login\Service\LoginService',
    ],
    
    'views' => [
        'index'     => __DIR__ . '/../view/master/index.phtml',
    ],
    'view_helpers' => [
        'LoginHelper' => 'Administrator\Login\View\LoginHelper'
    ],
    
    'master' => PATH_ROOT . 'vendor/imedia/administrator/config/login.config.php',
    
    /**
     * Assets
     */
    'assets' => [
        'remove' => [
            'realestate.css',
            'administrator.css',
            'administrator.js'
        ],
        'css' => [
            'login.css'      => __DIR__ . '/../public/css/login.css'
        ],
        'js' => [
            'login.js'       => __DIR__ . '/../public/js/login.js'
        ],
    ]
    
];