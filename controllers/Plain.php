<?php
require 'utilerias/Imagenes.php';
/**
 * Controlador para paginas estaticas, por ejemplo el login
 * La seleccion de vista se hace de la siguiente forma  
 * $res->m = $res->mustache->loadTemplate("Carpeta/archivo.mustache");
 *
 * La obtencion de parametros de la siguiente manera:
 * $req->data["campo"] (Dessde el formulario)
 * $req->params["seccion"] (Desde la url)
 *    
 * Para pasar datos, poner un arreglo, de tal manera que:
 * echo $this->renderWiew(array_merge(["datos"=>datos]),$res);
 *
 * @author geoskull
 */
class Plain extends Tornado\Controller{
    public function home($req,$res) {
    	$mapper=$this->spot->mapper("Entity\Tweet");
    	$data=$this->leerCsv(__TOR__.'/resources/etl.csv');
    	foreach ($data as $key => $tweet) {
    		if($key!=0){
    			$data[$key][2]=$this->limpiarCadena($data[$key][2]);	
    			$data[$key][6]=$this->analizarCadena($data[$key][2]);
    			$formato = 'Y-m-d H:i:s';
				$fecha = DateTime::createFromFormat($formato,$data[$key][1]);
    			$mapper->create([
    				'fecha'                => $fecha,
                	'texto'                => utf8_encode($data[$key][2]),
                	'src'                   => $data[$key][3],
                	'rt'                    => $data[$key][4],
                	'fav'                   => $data[$key][5],
                	'sentimiento'           => $data[$key][6]['score'],
                	'magnitud'           	=> $data[$key][6]['magnitude'],
                	'cuenta'                 => "@BBVA"
    			]);
    		}
    	}
    }


    private function analizarCadena($cadena){
    	$language = new \Google\Cloud\Language\LanguageClient(['keyFile' => json_decode(file_get_contents(__TOR__.'/resources/pruebas-memo-285403-860e2641f51d.json'), true)]);
    	$annotation = $language->annotateText($cadena,	['features' => ['syntax', 'sentiment']]);
    	return $annotation->sentiment();
    }

    /*limpiar cadena*/
    private function limpiarCadena($cadena){
    	$blackList=["@","#","，", "。", "！", "~", "@", "#", "￥", "%", "……", "&", "*", "（", "）", "？", "：", "；", ".", "...", "，", "。", "!", "～", "(", ")"];
    	foreach ($blackList as $value) {
    		$cadena=str_replace($blackList,"",$cadena);
    	}
    	return $cadena;
    }
    /*leer fichero*/
    private function leerCsv($file){
    	$array=[];
    	$file = fopen($file, 'r');
		while (($line = fgetcsv($file)) !== FALSE) {
  			//$line is an array of the csv elements
  			array_push($array, $line);
		}
		fclose($file);
		return $array;
    } 

}
