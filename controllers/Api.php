<?php
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
class Api extends Tornado\Controller{
	/**
	* Get MaxDataByDate
	*/
    public function getMaxDataByDay($req,$res) {
    	$array=[];
    	$mapper=$this->spot->mapper("Entity\Tweet");
    	$data=$mapper->query("select * from getTweetData")->toArray();
    	foreach ($data as $key => $value) {
    		$aux=[];
    		$aux["query"]=strtolower(str_replace("@","",$value["cuenta"]));
    		$aux["promedioRT"]=$value["avg_rt"];
    		$aux["promedioFV"]=$value["avg_fv"];
    		$aux["maxRT"]=$value["max_rt"];
    		$aux["maxFV"]=$value["max_fv"];
    		$aux["fecha"]=$value["fecha"];
    		array_push($array,$aux);
    	}
        header('Content-Type: application/json');
    	echo json_encode($array);
    }
    /**
    * Get WorstCOmments
    */
    public function getWorstComments($req,$res) {
        $array=[];
        $mapper=$this->spot->mapper("Entity\Tweet");
        $data=$mapper->query("select * from getWorstComments where vectorSentimiento<0")->toArray();
        foreach ($data as $key => $value) {
            $aux=[];
            $aux["query"]=str_replace("@","",$value["cuenta"]);
            $aux["vectorSentimiento"]=$value["vectorSentimiento"];
            $aux["fecha"]=$value["fecha"];
            $aux["texto"]=$value["texto"];
            $aux["rt"]=$value["rt"];
            $aux["fav"]=$value["fav"];
            array_push($array,$aux);
        }
        header('Content-Type: application/json');
        echo json_encode($array);
    }
    /**
    * Get BestComments
    */
    public function getBestComments($req,$res) {
        $array=[];
        $mapper=$this->spot->mapper("Entity\Tweet");
        $data=$mapper->query("select * from getWorstComments where vectorSentimiento>0 order by vectorSentimiento desc,rt desc,fav desc")->toArray();
        foreach ($data as $key => $value) {
            $aux=[];
            $aux["query"]=str_replace("@","",$value["cuenta"]);
            $aux["vectorSentimiento"]=$value["vectorSentimiento"];
            $aux["fecha"]=$value["fecha"];
            $aux["texto"]=$value["texto"];
            $aux["rt"]=$value["rt"];
            $aux["fav"]=$value["fav"];
            array_push($array,$aux);
        }
        header('Content-Type: application/json');
        echo json_encode($array);
    }
}