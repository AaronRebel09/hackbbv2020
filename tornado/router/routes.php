<?php
// EJEMPLO
//    "/path"=>[
//      'path'=>'path',
//      'get'=>array('Controller','method'),
//      'post'=>array('Controller','method'),
//    ]  
global $ROUTES;
$ROUTES=[
    "/api/getMaxDataByDay"=>[
        'path'=>'/api/getMaxDataByDay',
        'allow'=>true,
        'get'=>array('Api','getMaxDataByDay')
    ],
    "/api/getWorstComments"=>[
        'path'=>'/api/getWorstComments',
        'allow'=>true,
        'get'=>array('Api','getWorstComments')
    ],
    "/api/getBestComments"=>[
        'path'=>'/api/getBestComments',
        'allow'=>true,
        'get'=>array('Api','getBestComments')
    ],
    "/api/getSentimentOverview"=>[
        'path'=>'/api/getSentimentOverview',
        'allow'=>true,
        'get'=>array('Api','getSentimentOverview')
    ],
    "/api/getBancks"=>[
        'path'=>'/api/getBancks',
        'allow'=>true,
        'get'=>array('Api','getBancks')
    ],
    "/csv"=>[
        'path'=>'/csv',
        'allow'=>true,
        'get'=>array('Plain','home')
    ],  
    "/"=>[
        'path'=>'/',
        'allow'=>true,
        'get'=>array('Dashboard','home')
    ],
    "/comments"=>[
    'path'=>'/comments',
    'allow'=>true,
    'get'=>array('Dashboard','comments')
    ],
];
foreach ($ROUTES as $key => $route) {
    $router->addRoute($route);
}