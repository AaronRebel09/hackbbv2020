<?php
    //MiddleWares By TwisterSystems 
    require_once (__DIR__."/../Mustache.php");
    require_once (__DIR__."/../documentor.php");
    require_once (__DIR__."/../SessionLogin.php");
    require_once (__DIR__."/../migrator.php");
    require_once (__DIR__."/../oauth.php");
    //inicializacion de router
    $router=new \Zaphpa\Router();
    //se añaden middlewares
    $router->attach('\Tornado\Mustache');
    $router->attach('\Tornado\SessionLogin');
    $router->attach('\Tornado\Migrator');
    $router->attach('\Tornado\AutoDocumentator','/apidocs',$details=true);
    $router->attach('\Tornado\OAuth');
    require_once (__TOR__.'/tornado/router/routes.php');
    //se añaden los controladores
    require_once( __TOR__.'/controllers/Controller.php' );
    //se añaden los modelos
    foreach( scandir( __TOR__.'/model' ) as $model ){
        $bffmodel = explode("." , $model);
        if( end( $bffmodel ) == "php" ){
            require_once( __TOR__.'/model/'.$model );
        }
    }
    //se define slug inicial
    global $BASE;
    $BASE="";
    try {
        if($BASE!=""){
            $tokens= parse_url('http://twister.com'.str_replace($BASE,"",$_SERVER["REQUEST_URI"]));
        }else{
            $tokens= parse_url('http://twister.com'.$_SERVER["REQUEST_URI"]);
        }
        
        $uri= rawurldecode(isset($tokens["path"])?$tokens["path"]:"/");
        $router->route($uri);
    } catch (\Zaphpa\Exceptions\InvalidPathException $ex) {
        header("Content-Type: application/json;",TRUE,404);
        $out=array("error"=>"not found");
        die(json_encode($out));
    }