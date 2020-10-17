<?php
    namespace Entity;
    use Spot\EntityInterface as Entity;
    use Spot\MapperInterface as Mapper;
    class Tweet extends \Spot\Entity
    {
        protected static $table = 'tweets';
        public static function fields()
        {
            return [
                'idtweets'                    => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
                'fecha'                => ['type' => 'datetime'],
                'texto'                => ['type' => 'string'],
                'src'                   => ['type' => 'string'],
                'rt'                    => ['type' => 'integer','required'=>false],
                'fav'                   => ['type' => 'integer','required'=>false],
                'sentimiento'           => ['type' => 'decimal','required'=>false],
                'magnitud'              => ['type' => 'decimal','required'=>false],
                'cuenta'                => ['type' => 'string','required'=>false],
                'country'               => ['type' => 'string','required'=>false],
                'type'                  => ['type' => 'string','required'=>false],
                'place'                 => ['type' => 'string','required'=>false],
                'desde'                 => ['type' => 'datetime','required'=>false],
                'sigue_a'               => ['type' => 'integer','required'=>false],
                'lo_siguen'             => ['type' => 'integer','required'=>false],
            ];
        }
    }
