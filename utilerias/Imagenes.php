<?php
namespace Tornado;
/**
* Clase que sirve para unificar el manejo de imagenes de manera mas sencilla
* @author geoskull
*/
class Imagenes {
    private $nombre;//nombre de la imagen
    private $hashNombre;//hash del nombre de la imagen
    private $tipo;//de que tipo de archivo es
    private $archivo;//objeto tipo file
    /**
     * Costructor vacio
     */
    public function __construct() {
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
}
