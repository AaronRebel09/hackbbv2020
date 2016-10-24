<?php
namespace Tornado;
/**
 * Clase que sirve para obtener el id de una url de youtube
 * @author geoskull
 */
class VideosYoutube {
    private $video;
    private $videoId;
    /**
     * Costructor vacio de la clase
     */
    public function __construct() {
    }
    /**
     * Metodo get de video
     * @return type
     */
    function getVideo() {
        return $this->video;
    }
    /**
     * Metodo que devuelve el id del video
     * @return type
     */
    function getVideoId() {
        $aux=explode("=",$this->video);
        $this->videoId=$aux[1];
        return $this->videoId;
    }
    /**
     * Metodo que establece el video
     * @param type $video
     */
    function setVideo($video) {
        $this->video = $video;
    }
}