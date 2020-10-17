<?php

class Dashboard extends Tornado\Controller {

    public function home($req,$res)  {
        $array=[];
    	$mapper=$this->spot->mapper("Entity\Tweet");
    	$data=$mapper->query("select * from getTweetData")->toArray();
    	foreach ($data as $key => $value) {
    		$aux=[];
    		$aux["query"]=str_replace("@","",$value["cuenta"]);
    		$aux["promedioRT"]=$value["avg_rt"];
    		$aux["promedioFV"]=$value["avg_fv"];
    		$aux["maxRT"]=$value["max_rt"];
    		$aux["maxFV"]=$value["max_fv"];
    		$aux["fecha"]=$value["fecha"];
    		array_push($array,$aux);
    	}
    	echo $this->renderView($res,["data"=>$array]);
    }
}