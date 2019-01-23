<?php

namespace Tornado;

/**
 * Class para realizar un backup o un reload values
 * @package Tornado
 *
 * Usage:
 * 
 * $router->attach('\Tornado\Migrator');
 *     
 * 
 */

class Migrator extends \Zaphpa\BaseMiddleware {

  private $dir = "/migration";

  function preprocess(&$router) {

    $router->addRoute(array(
      'path'     => '/migrate/up',
      'get'      => array('\Tornado\Migrator','migrate')
    ));

    $router->addRoute(array(
      'path'     => '/getData/{entity}',
      'get'      => array('\Tornado\Migrator','data')
    ));

    $router->addRoute(array(
      'path'     => '/migrate/data',
      'get'      => array('\Tornado\Migrator','data')
    ));
  }


  public function data( $req , $res ) {
    global $spot;
    echo "<pre>";
    if( isset( $req->params["entity"] ) ){
      $entMapper = $spot->mapper( "Entity\\".$req->params["entity"] );  
    }else{
      if( !is_dir( __TOR__.$this->dir )){mkdir( __TOR__.$this->dir );}
      $migration = date("YmdHis");
      echo "Creating dataFixtures for migrations {$migration}:\n";
      mkdir( __TOR__.$this->dir."/".$migration );
      foreach( scandir( __TOR__.'/model' ) as $model ){
        $bffmodel = explode("." , $model);
        if( end( $bffmodel ) == "php" ){
          array_pop($bffmodel);
          if( str_replace("Mapper" , "" ,implode("." , $bffmodel ) ) == implode("." , $bffmodel )){
            echo "\n\t Exporting data for Entity ".implode("." , $bffmodel ).":";
            $entMapper = $spot->mapper("Entity\\".implode("." , $bffmodel ) );
            file_put_contents(__TOR__.$this->dir."/".$migration."/".implode("." , $bffmodel ).".json" , 
                              serialize( $entMapper->all()->toArray() ));
            echo "done";
            echo "\n\t\t File:/".$migration."/".implode("." , $bffmodel ).".json was successfully created !";
          }
        }
        
      }

    }
    
  }

  public function migrate() {
    global $spot;
    $entities = [];
    echo "<pre>";
    foreach( scandir( __TOR__.'/model' ) as $model ){
      $bffmodel = explode("." , $model);
      if( end( $bffmodel ) == "php" ){
        array_pop($bffmodel);
        if( str_replace("Mapper" , "" ,implode("." , $bffmodel ) ) == implode("." , $bffmodel )){
          $entMapper = $spot->mapper("Entity\\".implode("." , $bffmodel ) );
          $entity = $entMapper->entity();
          echo "Creating table ".$entity::table()." for ".$entity."\n";
          flush();
          $entMapper->dropTable();
          $entMapper->migrate();
          
          if( is_dir( __TOR__.$this->dir ) ){
            foreach( scandir( __TOR__.$this->dir , SCANDIR_SORT_DESCENDING)  as $migration ){
              if( str_replace(".","",$migration)==$migration && is_dir( __TOR__.$this->dir."/".$migration ) ){
                foreach( scandir( __TOR__.$this->dir."/".$migration )  as $dataEntity ){
                  if( implode("." , $bffmodel ).".json" == $dataEntity){
                    $fixures = unserialize(file_get_contents(__TOR__.$this->dir."/".$migration."/".$dataEntity) );
                    $fields = $entMapper->fields();
                    foreach ($fixures as $fix) {
                      foreach ($fix as $key => $val) {
                        if( !isset( $fields[$key] ) ){
                          unset( $fix[$key] );
                        }
                      }
                      $entMapper->eventEmitter()->removeAllListeners();
                      echo "\t\tId ".$entMapper->create( $fix )->id."  loaded and inserted into table ".$entity::table()."\n" ;
                    }
                  }
                }
                break;
              }
            }
          }else{
            if(method_exists( $entity , "dataFixtures" )){
              echo "\tLoading data fixures: \n";
              $fixures = $entity::dataFixtures();
              if( !is_array( $fixures ) ){
                $fixures = unserialize($fixures);
              }
              foreach ($fixures as $fix) {
                
                echo "\t\tId ".$entMapper->create( $fix )->id."  loaded and inserted into table ".$entity::table()."\n" ;
              }
            }
          }
          
        }
        
      }
    }
    
  }

  function prerender( &$buffer ) {


  }


}
?>