<?php

namespace App\Search\Model;

use App\Search\SearchServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class SearchableTrait.
 */
trait SearchableTrait
{
    /**
     * @var SearchServiceInterface
     */
    protected static $searchableService;

    /**
     * @var string
     */
    protected static $searchableIndex;

    /**
     * @param SearchServiceInterface $client
     */
    public function setSearchableService(SearchServiceInterface $client)
    {
        static::$searchableService = $client;
    }

    /**
     * @return SearchServiceInterface
     */
    public function getSearchableService()
    {
        return static::$searchableService;
    }

    /**
     * @param $index
     */
    public function setSearchableIndex($index)
    {
        static::$searchableIndex = $index;
    }

    /**
     * @return string
     */
    public function getSearchableIndex()
    {
        return static::$searchableIndex;
    }

    /**
     * Return the type this model uses in Elasticsearch.
     *
     * @return mixed
     */
    public function getSearchableType()
    {
        return $this->getTable();
    }

    /**
     * @return mixed
     */
    public function getSearchableId()
    {
        $key = $this->getKeyName();

        return $this->$key;
    }

    /**
     * @return mixed
     */
    public function getSearchableDocument()
    {
        return $this->toArray();
    }

    /**
     * @param $event
     *
     * @return string
     */
    public function getSearchableEventname($event)
    {
        return "eloquent.{$event}: ".get_class($this);
    }

    /**
     * @return SearchServiceInterface
     */
    public function search()
    {
        return $this->getSearchableService();
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchableNewModel($data, array $with)
    {
        $base = array_except($data, array_keys($with));
        $base = $this->searchableShiftTranslations($base);

        $relations = array_only($data, array_keys($with));
        $relations = $this->searchableShiftTranslations($relations);

        unset($data);

        $this->unguard();
        $model = $this->newInstance();
        $model->fill($base);
        $this->reguard();

        //need to setup relations too :-)
        foreach ($with as $relation => $build) {
            $type = $this->getRelationType($relation, $model);

            if ($relation_data = $this->getSearchableNestedDocument($relations, $relation)) {
                if ($needsLoop = $this->relationNeedsLooping($type)) {
                    $relation_data = $this->getLoopedRelationData($build, $relation_data);
                } else {
                    $relation_data = $this->getSimpleRelationData($build, $relation_data);
                }

                $model->setRelation($relation, $relation_data);
            } else {
                if ($this->relationNeedsLooping($type)) {
                    $model->setRelation($relation, array());
                }
            }
        }

        return $model;
    }

    protected function getSearchableNestedDocument($relations, $relation)
    {
        if (isset($relations[$relation]) && !empty($relations[$relation])) {
            return $relations[$relation];
        }
    }

    public function getSearchableMapping(array $with)
    {
        $mapping = [];

        //the mapping we want to use should also include the mappings for the nested documents.
        if (property_exists(__CLASS__, 'searchableMapping')) {
            $mapping = static::$searchableMapping;
        }

        foreach ($with as $type => $config) {
            $related = new $config['class']();

            if ($type == 'translations') {
                if (!$related instanceof Searchable) {
                    throw new \Exception(sprintf('Translation model %s needs to be searchable', get_class($related)));
                }

                $locale_map = $related->getSearchableMapping([]);

                foreach (config('system.locales') as $locale) {
                    $nested_map[$locale] = [
                        'type' => 'nested',
                        'properties' => $locale_map,
                    ];
                }
            } elseif ($related instanceof Searchable) {
                $nested_map = $related->getSearchableMapping(array());
            }

            $mapping[$type] = [
                'type' => 'nested',
                'properties' => isset($nested_map) ? $nested_map : [],
            ];
        }

        return $mapping;
    }

    /**
     * @param $relation
     * @param $model
     *
     * @return array
     */
    protected function getRelationType($relation, $model)
    {
        /** @var Relation $foreign */
        $foreign = $model->$relation();

        $type = get_class($foreign);

        return $type;
    }

    /**
     * @param $type
     *
     * @return array|bool
     */
    protected function relationNeedsLooping($type)
    {
        $needsLoop = ['HasMany', 'BelongsToMany', 'MorphToMany'];

        foreach ($needsLoop as $loop) {
            if (ends_with($type, $loop)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $build
     * @param $relation_data
     *
     * @return Collection
     */
    protected function getLoopedRelationData($build, $relation_data)
    {
        $class = $build['class'];

        $class = new $class();

        $collection = $class->newCollection();

        foreach ($relation_data as $data) {
            $collection->push($this->getSimpleRelationData($build, $data));
        }

        return $collection;
    }

    /**
     * @param $build
     * @param $relation_data
     *
     * @return mixed
     */
    protected function getSimpleRelationData($build, $relation_data)
    {
        $class = $build['class'];

        $class = new $class();

        $class->unguard();

        $instance = $class->newInstance();

        $instance->fill($relation_data);

        $class->reguard();

        return $instance;
    }

    protected function searchableShiftTranslations(array $data)
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $value = $this->searchableShiftTranslations($value);
            }
        }

        if (isset($data['translations'])) {
            $data = array_merge($data, $data['translations']);
            unset($data['translations']);
        }

        return $data;
    }
}
