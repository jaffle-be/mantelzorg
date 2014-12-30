<?php
namespace Search\Model;

use App;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Search\Query\Query;
use Search\SearchServiceInterface;

/**
 * Class SearchableTrait
 *
 * @package Search\Model
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
        return "eloquent.{$event}: " . get_class($this);
    }

    /**
     * @return Query
     */
    public function search()
    {
        return new Query(static::$searchableService, $this);
    }

    /**
     * This is the quick and dirty implementation using a json template rendered by mustache.
     *
     * @param       $view
     * @param array $params
     *
     * @return mixed
     */
    public function view($view, array $params)
    {
        return static::$searchableService->view($view, $params);
    }

    /**
     * @inheritdoc
     */
    public function getSearchableNewModel($data, array $with)
    {
        $base = array_except($data, $with);

        $relations = array_only($data, $with);

        unset($data);

        $model = $this->newFromBuilder($base);

        //need to setup relations too :-)
        foreach ($with as $relation) {
            if ($relation_data = $this->getSearchableNestedDocument($relations, $relation)) {
                /** @var Relation $foreign */
                $foreign = $model->$relation();

                $build = $foreign->getRelated();

                $type = get_class($foreign);

                $needsLoop = ['HasMany', 'BelongsToMany'];

                foreach ($needsLoop as $loop) {
                    if (ends_with($type, $loop)) {
                        $needsLoop = true;

                        break;
                    }
                }

                if ($needsLoop === true) {
                    $collection = new Collection();

                    foreach ($relation_data as $object) {
                        $collection->add(new $build($object));
                    }

                    $relation_data = $collection;

                } else {
                    $relation_data = new $build($relation_data);
                }
            }

            $model->setRelation($relation, $relation_data);
        }

        return $model;
    }

    protected function getSearchableNestedDocument($relations, $relation)
    {
        if (isset($relations[$relation]) && !empty($relations[$relation])) {
            return $relations[$relation];
        }
    }

    public function getSearchableMapping()
    {
        if (property_exists(__CLASS__, 'searchableMapping')) {
            return static::$searchableMapping;
        }
    }
}