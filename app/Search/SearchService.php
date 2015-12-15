<?php

namespace App\Search;

use App\Search\Model\Searchable;
use Elasticsearch\Client;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

class SearchService implements SearchServiceInterface
{

    use SearchResponder;

    /**
     * @var Container
     */
    protected $container;

    /**
     *
     * @var Client
     */
    protected $client;

    /**
     * @var Config
     */
    protected $config;

    protected $service;

    /**
     * @var array
     */
    protected $listeners = array(
        'created' => 'add',
        'updated' => 'update',
        'deleted' => 'delete',
    );

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var array
     */
    protected $invertedTypes = [];

    /**
     * @param Container $container
     * @param Client    $client
     * @param array     $config
     *
     */
    public function __construct(Container $container, Client $client, Config $config)
    {
        $this->container = $container;

        $this->client = $client;

        $this->config = $config;

        $this->service = $this;
    }

    public function boot()
    {
        $this->config->boot();

        $this->autoIndex();
    }

    protected function autoIndex()
    {
        foreach ($this->config->getTypes() as $type) {

            $class = $this->config->getClass($type);

            $class = new $class;

            $class->setSearchableService($this);

            $this->regularAutoIndex($class, $this->config->getWith($type));
        }

        foreach ($this->config->getInvertedTypes() as $updated => $config) {
            $this->invertedAutoIndex($updated, $config);
        }
    }

    /**
     * This method will bind all events to the eloquent model created, updated or deleted events.
     * Note the events are not the creating, updating or deleting events, as these would possibly
     * index data that might change due to a model observer adjusting data.
     *
     * @param Searchable $type
     * @param array      $with
     */
    public function regularAutoIndex(Searchable $type, array $with)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->container->make('events');

        $me = $this;

        foreach ($this->listeners as $event => $listener) {

            $trigger = $type->getSearchableEventname($event);

            $type->setSearchableService($me);

            $type->setSearchableIndex($me->config->getIndex());

            if ($trigger) {
                $callback = function (Searchable $type) use ($me, $listener, $with) {
                    return $me->$listener($type);
                };

                $dispatcher->listen($trigger, $callback, 15);
            }
        }
    }

    protected function invertedAutoIndex($updated, $inverted)
    {
        foreach ($inverted as $invert) {

            $parent = new $invert['class']();

            $relation = $invert['relation'];

            $key = $invert['key'];

            $config = $this->getConfig($parent);

            $this->addInvertedListener($parent, $updated, $relation, $key, array_keys($config['with']));
        }
    }

    protected function addInvertedListener(Searchable $parent, $updated, $relation, $key, array $with)
    {
        $dispatcher = $this->container->make('events');

        $event = 'eloquent.saved: ' . $updated;

        $dispatcher->listen($event, function ($model) use ($parent, $relation, $key, $with) {

            $result = $parent->with($with)->whereHas($relation, function ($query) use ($model, $key, $with) {
                $query->where($model->getKeyName(), '=', $model->getKey());
            });

            $documents = $result->get();

            foreach ($documents as $document) {
                $this->update($document);
            }
        });
    }

    protected function getRelationsToLoad($parent)
    {
        $class = new $parent();

        return $this->config->getWith($class->getSearchableType());
    }

    /**
     * @inheritdoc
     */
    public function build($type)
    {
        $this->checkIndex();

        list($type, $relations) = $this->getSearchable($type);

        $this->refreshType($type, $this->config->getWith($type->getSearchableType()));

        $me = $this;

        $type->with($relations)->chunk(250, function ($documents) use ($me, $relations) {
            foreach ($documents as $document) {
                $me->add($document, false);
            }
        });
    }

    public function flush($type, $refresh = true)
    {
        $this->checkIndex();

        $config = $this->config->getType($type);

        list($type) = $this->getSearchable($type);

        if ($refresh) {
            $this->refreshType($type, $config['with']);
        }
    }

    public function add(Searchable $type, $needsLoading = true)
    {
        //clone object so we do not touch original one.
        //this was messing with translatable library.
        //since the parent model saves before the translations,
        //translations wouldn't be in database and we overwrite them here by loading those translations
        $type = clone $type;
        /*
         * make sure the relations are initialised when creating a new object
         * else searching might fail since some relations expect to be an array and it would be indexed as null
         */
        if ($needsLoading) {
            $type->load(array_keys($this->config->getWith($type->getSearchableType())));
        }

        $this->client->index($this->data($type));
    }

    public function delete(Searchable $type)
    {
        $params = $this->data($type);

        $params = array_except($params, ['body']);

        $this->client->delete($params);
    }

    public function update(Searchable $type)
    {
        //clone object so we do not touch original one.
        //this was messing with translatable library.
        //since the parent model saves before the translations,
        //translations wouldn't be in database and we overwrite them here by loading those translations
        $type = clone $type;

        $params = $this->getBaseParams($type);

        $type->load(array_keys($this->config->getWith($type->getSearchableType())));

        $params = array_merge($params, [
            'id'   => $type->getSearchableId(),
            'body' => [
                'doc' => $type->getSearchableDocument()
            ]
        ]);

        $this->client->update($params);
    }

    /**
     * Search the index
     *
     * @param array $params
     *
     * @return mixed
     */
    public function search($type, array $params, $with = [], $paginated = 15, \Closure $highlighter = null)
    {
        if (isset($params['body']['sort'])) {
            $params = $this->cleanSort($params);
        }

        if ($paginated) {
            $params['from'] = (app('request')->get('page', 1) - 1) * $paginated;
            $params['size'] = $paginated;
        }

        $result = $this->client->search($params);

        if($highlighter)
        {
            //if we have a highlighter, we simply loop through the results and overwrite the original field.
            //this is dangerous though, a dev should not be using Elasticsearch results to manipulate data.
            //data should always be manipulated through your relational database.
            foreach($result['hits']['hits'] as &$hit)
            {
                if(isset($hit['highlight']))
                {
                    $hit["_source"] = $highlighter($hit['_source'], $hit['highlight']);
                }

            }
        }

        return $this->response($result, $with, $paginated, $this->container->make($this->config->getClass($type)));
    }

    public function aggregate(array $params)
    {
        $result = $this->client->search($params);

        //the resultset contains an array of aggregations.
        //if we only have one aggregation, we return an aggregation result,
        //if we have multiple, we'll return a collection of aggregation results.
        //but its always an array, so we can implement the same logic at first
        $aggregations = $result['aggregations'];

        return $aggregations;
    }

    public function getPaginator()
    {
        return $this->container->make('Illuminate\Contracts\Pagination\Paginator');
    }

    public function getConfig(Searchable $type)
    {
        return $this->config->getType($type->getSearchableType());
    }

    /**
     * Update the settings for the elasticsearch instance.
     *
     * @param array $settings
     *
     * @return bool
     */
    public function updateSettings(array $settings)
    {
        $this->checkIndex();

        sleep(2);

        $indices = $this->client->indices();

        $toggle = ['index' => $this->config->getIndex()];

        $indices->close($toggle);

        sleep(2);

        $settings = [
            'index' => $this->config->getIndex(),
            'body'  => $settings,
        ];

        $indices->putSettings($settings);

        sleep(2);

        $indices->open($toggle);

        sleep(2);

        $indices->refresh($toggle);

        sleep(2);
    }

    /**
     * Update the mapping for a elasticsearch type.
     *
     * @param $type
     *
     * @return mixed
     */
    public function updateMapping($type)
    {
        $this->flush($type, true);

        $this->build($type);
    }

    /**
     * Return the actual type.
     * People could have passed in a simple classname.
     *
     * @param $type
     *
     * @return array|mixed
     * @throws Exception
     */
    protected function getSearchable($type)
    {
        if (is_string($type)) {
            $classname = $this->config->getClass($type);

            $with = $this->config->getWith($type);

            $type = $this->container->make($classname);

            return array($type, array_keys($with));
        }

        if (!is_object($type) || !($type instanceof Searchable)) {
            throw new Exception('Invalid searchable provided, expecting something App\Search\\Searchable');
        }

        return $type;
    }

    protected function refreshType(Searchable $type, array $with)
    {
        $params = $this->getBaseParams($type);

        if ($this->client->indices()->getMapping($params)) {
            $params = array_merge($params, ['id' => '_mapping']);

            $this->client->delete($params);
        }

        if ($mapping = $type->getSearchableMapping($with)) {
            $params = $this->getBaseParams($type);

            $mapping = array_merge($params, [
                'body' => [
                    'properties' => $mapping
                ]
            ]);

            $this->client->indices()->putMapping($mapping);
        }
    }

    /**
     * @param Searchable $type
     *
     * @return array
     */
    protected function getBaseParams(Searchable $type)
    {
        return [
            'index' => $this->config->getIndex(),
            'type'  => $type->getSearchableType()
        ];
    }

    protected function checkIndex()
    {
        $params = ['index' => $this->config->getIndex()];

        $indices = $this->client->indices();

        if (!$indices->exists($params)) {
            $indices->create($params);
        }
    }

    /**
     * @param Searchable $type
     *
     * @return array
     */
    protected function data(Searchable $type)
    {
        return [
            'index' => $this->config->getIndex(),
            'type'  => $type->getSearchableType(),
            'id'    => $type->getSearchableId(),
            'body'  => $type->getSearchableDocument(),
        ];
    }

    public function getClient()
    {
        return $this->client;
    }

    protected function cleanSort($params)
    {
        //sorts best have a unmapped_type parameter, so queries won't fail for empty document sets.
        $sort = $params['body']['sort'];

        foreach ($sort as $key => $sorting) {
            //the initial value of sorting is always an array
            //either
            //['column_name' => 'sort_order']
            //or a complex one
            //['column_name' => [array]]

            $value_keys = array_keys($sorting);

            $column = array_pop($value_keys);

            if (is_array($sorting[$column])) {
                //the sorting is already set up as a object/array value (depends if you look at it from json or php)
                //so we only need to verify the existence of the unmapped_type parameter.
                if (!isset($sorting[$column]['unmapped_type'])) {
                    //lets take boolean, as its probably one of the fastest.
                    $sorting[$column]['unmapped_type'] = 'boolean';
                }
            } else {
                //the value represents the order in which to sort.
                $sorting[$column] = [
                    "order"         => $sorting[$column],
                    "unmapped_type" => 'boolean'
                ];
            }

            //update the original array
            $sort[$key] = $sorting;
        }

        //put the local reference back into the complete array
        $params['body']['sort'] = $sort;

        //return the entire array as a result.
        return $params;
    }

}