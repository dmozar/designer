<?php


return [
    
    'routes' => [
        
        'login' => [
            'route' => '/{:login}',
            'constraints' => [
                    'submit' => false,
                    'status'=> null,
            ],
            'priority' => 1,
            'controller' => 'Imedia\User\Controller\UserController',
            'method' => 'login',
            'action' => 'http',
            'children' => [
                'submit' => [
                    'route' => '/submit',
                    'constraints' => [
                        'submit' => true,
                        'status' => null,
                    ],
                    'priority'  => 1,
                    'action' => 'http'
                ],
                'success' => [
                    'route' => '/success',
                    'constraints' => [
                        'submit' => false,
                        'status' => 'success'
                    ],
                    'priority'  => 1,
                    'action' => 'http'
                ],
                'error' => [
                    'route' => '/error',
                    'constraints' => [
                        'submit' => false,
                        'status' => 'error'
                    ],
                    'priority'  => 1,
                    'action' => 'http'
                ]
            ]
        ],
        'logout' => [
            'route' => '/logout',
            'constraints' => [
                'appKey' => ''
            ],
            'priority' => 1,
            'controller' => 'Imedia\User\Controller\UserController',
            'method' => 'logout',
            'action' => 'http',
            'children' => [
                'submit' => [
                    'route' => '/(?P<appKey>\w+)',
                    'constraints' => [
                        'appKey' => ''
                    ],
                    'priority'  => 1,
                    'action' => 'http'
                ]
            ],
        ],
        'registration' => [
            'route' => '/{:registration}',
            'constraints' => [
                    'submit' => false
            ],
            'priority' => 1,
            'controller' => 'Imedia\User\Controller\UserController',
            'method' => 'registration',
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
            ],
        ],
        
    ],
    'services' => [
        'UserService'           => 'Imedia\User\Service\UserService',
        'SessionUserService'    => 'Imedia\User\Service\SessionUserService'
    ],
    
    'repository' => [
        'UserRepository'        => 'Imedia\User\Repository\UserRepository'
    ],
    
    'views' => [
        'login'         => __DIR__ . '/../view/master/login.phtml',
        'registration'  => __DIR__ . '/../view/master/registration.phtml',
        'error'         => __DIR__ . '/../view/master/error.phtml',
        'success'       => __DIR__ . '/../view/master/success.phtml',
    ],
    'view_helpers' => [
        'LoginHelper'           => 'Imedia\User\View\LoginHelper',
        'RegistrationHelper'    => 'Imedia\User\View\RegistrationHelper'
    ],
    
    /**
     * Assets
     */
    'assets' => [
       
    ],
    
    
    'inputs' => [
        'first_name'    => ['name|required',                'ErrorFirstName',   'FirstName'],
        'last_name'     => ['name|required',                'ErrorLastName',    'LastName'],
        'email'         => ['email|required',               'ErrorEmail',       'Email'],
        'password'      => ['string|required|min6',         'ErrorPassword',    'Password'],
        'rpassword'     => ['string|required|@password',    'ErrorRPassword',   'RPassword'],
        'phone'         => ['phone|required|min9',          'ErrorPhone',       'Phone']
    ],
    
];