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
                'rt'                    => ['type' => 'integer'],
                'fav'                   => ['type' => 'integer'],
                'sentimiento'           => ['type' => 'decimal'],
                'magnitud'           => ['type' => 'decimal'],
                'cuenta'                 => ['type' => 'string']
            ];
        }
    }
