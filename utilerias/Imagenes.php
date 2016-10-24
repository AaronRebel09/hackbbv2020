<?php
namespace Tornado;
/**
* Clase que sirve para unificar el manejo de imagenes de manera mas sencilla
* @author geoskull
*/
class Imagenes {
    private $RutaImagen = "/assets/img/";//ruta de la imagen normal
    private $RutaImagenSmall="/assets/img/small/";//ruta del thumbnail
    private $ruta;//definicion de ruta superior
    private $nombre;//nombre de la imagen
    private $hashNombre;//hash del nombre de la imagen
    private $tipo;//de que tipo de archivo es
    private $archivo;//objeto tipo file
    private $minAnc=250;//ancho del thumbnail
    private $minAlt=250;//alto del thumbnail
    
    /**
     * Costructor vacio
     */
    public function __construct() {
        $this->ruta=__TOR__;//se iniciliza ruta padre
        //si no existe el directorio se crea
        if(!is_dir($this->ruta.$this->RutaImagen)){
            mkdir($this->ruta.$this->RutaImagen);
        }
        //si no existe el directorio se crea
        if(!is_dir($this->ruta.$this->RutaImagenSmall)){
            mkdir($this->ruta.$this->RutaImagenSmall);
        }
    }
    /**
     * Metodo que devuelve una cadena con el nombre de la imagen
     * @return type
     */
    public function getNombre() {
        return $this->nombre;
    }
    /**
     * Metodo que devuelve el hash del nombre de la imagen
     * @return type
     */
    public function getHashNombre() {
        return $this->hashNombre;
    }
    /**
     * Metodo que devuelve el tipo de la imagen
     * @return type
     */
    public function getTipo() {
        return $this->tipo;
    }
    /**
     * Metodo que devuelve el objeto del archivo
     * @return type
     */
    public function getArchivo() {
        return $this->archivo;
    }
    /**
     * Metodo que setea el nombre de la imagen
     * @param type $nombre
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    /**
     * Metodo que setea el hash del nombre de la imagen
     * @param type $hashNombre
     */
    public function setHashNombre($hashNombre) {
        $this->hashNombre = $hashNombre;
    }
    /**
     * Metodo que establece el tipo de la imagen
     * @param type $tipo
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    /**
     * Metodo que establece el archivo
     * @param type $archivo
     */
    public function setArchivo($archivo) {
        $this->archivo = $archivo;
    }
    /**
     * Metodo que almacena la imagen en el directorio y devuelve
     * un objeto de Imagenes, con los datos necesarios para el registro
     * en la base de datos
     */
    public function guardarImagen(){
        if(!empty($this->archivo)){
            $this->tipo= pathinfo($this->archivo['name'],PATHINFO_EXTENSION);//se obtiene el tipo de archivo
            $this->nombre= $this->archivo['name'];
            $this->hashNombre= md5($this->archivo['name'].uniqid()).".".$this->tipo;
            $rutaImg= $this->ruta.$this->RutaImagen.$this->hashNombre;
            if(move_uploaded_file($this->archivo['tmp_name'], $this->ruta.$this->RutaImagen.$this->hashNombre)){
                $info_imagen= getimagesize($this->ruta.$this->RutaImagen.$this->hashNombre);
                $this->proporcion($info_imagen,$rutaImg);
            }
        }
        return $this;
    }
    /**
     * Metodo que genera el thumbnail de manera proporcional a la imagen en tamaño normal
     * @param type $info_imagen
     * @param type $rutaImg
     */
    private function proporcion($info_imagen,$rutaImg) {
        $img_alto=$info_imagen[0];
        $img_ancho=$info_imagen[1];
        $img_tipo=$info_imagen["mime"];
        $prop_img=$img_ancho/$img_alto;
        $prop_min= $this->minAnc/$this->minAlt;
        $min_anc=0;
        $min_alt=0;
        if($prop_img>$prop_min){
            $min_anc= $this->minAnc;
            $min_alt= $this->minAnc/$prop_img;
        }
        else if($prop_img<$prop_min){
            $min_anc= $this->minAnc*$prop_img;
            $min_alt= $this->minAlt;
        }else{
            $min_anc= $this->minAnc;
            $min_alt= $this->minAlt;
        }
        switch ($img_tipo){
            case "image/jpg":
            case "image/jpeg":
                $imagen= imagecreatefromjpeg($rutaImg);
            break;
            case "image/png":
                $imagen= imagecreatefrompng($rutaImg);
            break;
            case "image/gif":
                $imagen= imagecreatefromgif($rutaImg);
            break;
        }
        $lienzo= imagecreatetruecolor($min_alt, $min_anc);
        imagecopyresampled($lienzo, $imagen,0, 0, 0, 0, $min_anc,$min_alt,$img_ancho, $img_alto);
        switch ($img_tipo){
            case "image/jpg":
            case "image/jpeg":
                imagejpeg($lienzo, $this->ruta.$this->RutaImagenSmall.$this->hashNombre, 90);
            break;
            case "image/png":
                imagepng($lienzo, $this->ruta.$this->RutaImagenSmall.$this->hashNombre, 90);
            break;
            case "image/gif":
                imagegif($lienzo, $this->ruta.$this->RutaImagenSmall.$this->hashNombre);
            break;
        }
    }
    /**
     * Metodo que sirve para eliminar una imagen, pasando unicamente nombre con hash, de la imagen
     */
    public function eliminarImagen(){
        unlink($this->ruta.$this->RutaImagen.$this->hashNombre);
        unlink($this->ruta.$this->RutaImagenSmall.$this->hashNombre);
    }
}
