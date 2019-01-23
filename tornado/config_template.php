<?php
    //debuguer
    $whoops=new \Whoops\Run;//se inicializa
    $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);//se añade handeler
    $whoops->register();//se registra el handeler
    
    define("__TOR__",__DIR__."/..");//se define constante para el uso de directorios
    
    $cfg=new \Spot\Config();//se construye configuracion de db
    $cfg->addConnection('mysql',['dbname'=>'db','user'=>'user','password'=>'pass','host'=>'localhost','driver'=>'pdo_mysql']);//se añaden datos de conexion
    
    global $spot;//se define como global el objeto
    $spot = new \Spot\Locator($cfg);//se inicializa el objeto con la conexion
    //si no existe la sescion, inicia una sesion
    if(!isset($_SESSION)){
        session_start();
    }
    $aura_session=new \Aura\Session\SessionFactory;//se crea un objeto del patron factory
    global $session_handle;//se declara el manejador de sesiones
    $session_handle=$aura_session->newInstance($_SESSION);//se inicializa el manejador de sesiones
    
    include (__DIR__."/mail_config.php");
