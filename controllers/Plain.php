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
    	$data=$this->leerCsv(__TOR__.'/resources/etl5.csv');
        try{
            foreach ($data as $key => $tweet) {

        		if($key!=0){
                    
        			$data[$key][3]=$this->limpiarCadena($data[$key][3]);	
        			$aux=$this->analizarCadena($data[$key][3]);
                    //$aux=["score"=>0,"magnitude"=>0];
        			$formato = 'Y-m-d H:i:s';
                    //$data[$key][2]=$data[$key][2].":00";
                    //$data[$key][2]=str_replace("/","-",$data[$key][2]);

                    //$data[$key][10]=$data[$key][10].":00";
                    //$data[$key][10]=str_replace("/","-",$data[$key][10]);

    				$fecha = DateTime::createFromFormat($formato,$data[$key][2]);
                    $fecha2 = DateTime::createFromFormat($formato,$data[$key][11]);
                    $arr=[
                        'fecha'                => $fecha,
                        'texto'                => utf8_encode($data[$key][3]),
                        'src'                   => $data[$key][4],
                        'rt'                    => intval($data[$key][5]),
                        'fav'                   => intval($data[$key][6]),
                        'sentimiento'           => $aux['score'],
                        'magnitud'              => $aux['magnitude'],
                        'cuenta'                => $data[$key][1],
                        'country'               => $data[$key][8],
                        'type'                  => $data[$key][9],
                        'place'                 => $data[$key][10],
                        'desde'                 => $fecha2,
                        'sigue_a'               => intval($data[$key][12]),
                        'lo_siguen'             => intval($data[$key][13]),
                        'palabras'             => $data[$key][7]
                    ];
                   $mapper->create($arr);
                   echo "insertado ".$arr["texto"]."<br/>";
                   //$this->pr($arr);
        		}
        	}
        }
        catch (\Exception $e){
            $this->pr($e);
            exit;
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
