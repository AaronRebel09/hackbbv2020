<?php

class Dashboard extends Tornado\Controller {

    public function home($req,$res)  {

    	echo $this->renderView($res,[
            "worstComments"=>$this->getWorstComments(),
            "bestComments"=>$this->getBestComments(),
            ]);
    }
    /**
    * Get WorstCOmments
    */
    private function getWorstComments() {
        $array=[];
        $mapper=$this->spot->mapper("Entity\Tweet");
        $banks=$mapper->query("select distinct cuenta from getTweetData")->toArray();
        foreach ($banks as $key => $banco) {
            $auxbanco=[];
            $data=$mapper->query("select * from getWorstComments where vectorSentimiento<0 and cuenta='".$banco["cuenta"]."' limit 10")->toArray();
            foreach ($data as $key => $value) {
                $aux=[];
                $aux["query"]=str_replace("@","",$value["cuenta"]);
                $aux["vectorSentimiento"]=$value["vectorSentimiento"];
                $aux["fecha"]=$value["fecha"];
                $aux["texto"]=$value["texto"];
                $aux["rt"]=$value["rt"];
                $aux["fav"]=$value["fav"];
                array_push($auxbanco,$aux);
            }
            $array=array_merge($array, [$banco["cuenta"]=>$auxbanco]);
        }
        
        
        // header('Content-Type: application/json');
        // echo json_encode($array);
        return $array;
    }

        /**
    * Get BestComments
    */
    private function getBestComments() {
        $array=[];
        $mapper=$this->spot->mapper("Entity\Tweet");
        $banks=$mapper->query("select distinct cuenta from getTweetData")->toArray();
        foreach ($banks as $key => $banco) {
            $auxbanco=[];
            $data=$mapper->query("select * from getWorstComments where vectorSentimiento>0 and cuenta='".$banco["cuenta"]."' order by vectorSentimiento desc,rt desc,fav desc limit 10")->toArray();
            foreach ($data as $key => $value) {
                $aux=[];
                $aux["query"]=str_replace("@","",$value["cuenta"]);
                $aux["vectorSentimiento"]=$value["vectorSentimiento"];
                $aux["fecha"]=$value["fecha"];
                $aux["texto"]=$value["texto"];
                $aux["rt"]=$value["rt"];
                $aux["fav"]=$value["fav"];
                array_push($auxbanco,$aux);
            }
            $array=array_merge($array, [$banco["cuenta"]=>$auxbanco]);

        }
        
        
        // header('Content-Type: application/json');
        // echo json_encode($array);
        return $array;
    }
}