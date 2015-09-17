<?php namespace App\Search;

use App\System\Translatable\Translatable;
use Exception;

class Config
{

    protected $index;

    protected $types = [];

    protected $inverted = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function boot()
    {
        $this->index = $this->config['index'];

        $this->types = $this->config['types'];

        $this->invertTypes();
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getTypes()
    {
        return array_keys($this->types);
    }

    public function getType($type)
    {
        return $this->types[$type];
    }

    public function getWith($type)
    {
        $with = isset($this->types[$type]['with']) ? $this->types[$type]['with'] : [];

        if($this->usesTranslations($type))
        {
            $instance = $this->getInstance($type);

            $with = array_merge($with, ['translations' => [
                'class' => $instance->getTranslationModelName(),
                'key'   => $instance->translations()->getForeignKey(),
            ]]);
        }

        return $with;
    }

    public function getClass($type)
    {
        return $this->types[$type]['class'];
    }

    public function getInvertedTypes()
    {
        return $this->inverted;
    }

    /**
     * This method will save an inverted array of relations.
     * We can then use it to trigger nested document changes.
     *
     * @param $types
     *
     * @return array
     */
    protected function invertTypes()
    {
        foreach ($this->types as $type => $config) {

            $parent = $config['class'];

            foreach ($this->getWith($type) as $relation => $nestedConfig) {

                $nested = $nestedConfig['class'];

                $key = $nestedConfig['key'];

                $this->invert($nested, $parent, $key, $relation);
            }
        }
    }

    /**
     * @param $type
     *
     * @return array
     */
    protected function usesTranslations($type)
    {
        $trait = Translatable::class;
        $class = $this->getClass($type);
        $stuff = class_uses($class);

        return in_array($trait, $stuff);
    }

    /**
     * @param $key
     * @param $inverted
     * @param $config
     * @param $class
     * @param $relation
     *
     * @return mixed
     */
    protected function invert($nested, $parent, $key, $relation)
    {
        if (!array_key_exists($nested, $this->inverted)) {
            $this->inverted[$nested] = [];
        }

        $this->inverted[$nested][] = [
            'class'    => $parent,
            'key'      => $key,
            'relation' => $relation
        ];
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    protected function getInstance($type)
    {
        $class = $this->getClass($type);

        $object = new $class;

        return $object;
    }

}