<?php


namespace App\Search;

use App\Search\Model\Searchable;
use Elasticsearch\Client;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;

class SearchService implements SearchServiceInterface
{

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
     * @var array
     */
    protected $config;

    /**
     * The name of the index to use.
     *
     * @var string
     */
    protected $index;

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
    public function __construct(Container $container, Client $client, array $config)
    {
        $this->container = $container;

        $this->client = $client;

        $this->config = $config;
    }

    public function boot()
    {
        $this->parseConfig();

        $this->autoIndex();
    }

    protected function autoIndex()
    {
        foreach ($this->types as $type => $config) {

            $class = $config['class'];

            $class = new $class;

            $class->setSearchableService($this);

            $this->regularAutoIndex($class, $config['with']);
        }

        foreach ($this->invertedTypes as $updated => $config) {
            $this->invertedAutoIndex($updated, $config);
        }
    }

    protected function parseConfig()
    {
        $this->index = $this->config['index'];

        $this->types = $this->config['types'];

        $this->invertedTypes = $this->invertTypes($this->config['types']);
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

            $type->setSearchableIndex($me->index);

            if ($trigger) {
                $callback = function (Searchable $type) use ($me, $listener, $with) {
                    return $me->$listener($type, array_keys($with));
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
                $this->update($document, $with);
            }
        });
    }

    protected function getRelationsToLoad($parent)
    {
        $class = new $parent();

        return $this->types[$class->getSearchableType()]['with'];
    }

    /**
     * @inheritdoc
     */
    public function build($type)
    {
        $this->checkIndex();

        $config = $this->types[$type];

        list($type, $relations) = $this->getSearchable($type);

        $this->refreshType($type, $config['with']);

        $me = $this;

        $type->with($relations)->chunk(250, function ($documents) use ($me, $relations) {
            foreach ($documents as $document) {
                $me->add($document, $relations, false);
            }
        });
    }

    public function flush($type, $refresh = true)
    {
        $this->checkIndex();

        $config = $this->types[$type];

        list($type) = $this->getSearchable($type);

        if($refresh)
        {
            $this->refreshType($type, $config['with']);
        }
    }

    public function add(Searchable $type, array $with, $needsLoading = true)
    {
        /*
         * make sure the relations are initialised when creating a new object
         * else searching might fail since some relations expect to be an array and it would be indexed as null
         */
        if($needsLoading)
        {
            $type->load(array_values($with));
        }

        $this->client->index($this->data($type));
    }

    public function delete(Searchable $type, array $with)
    {
        $params = $this->data($type);

        $params = array_except($params, ['body']);

        $this->client->delete($params);
    }

    public function update(Searchable $type, array $with)
    {
        $params = $this->getBaseParams($type);

        $type->load($with);

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
    public function search(array $params)
    {
        return $this->client->search($params);
    }

    public function getPaginator()
    {
        return $this->container->make('Illuminate\Contracts\Pagination\Paginator');
    }

    public function getConfig(Searchable $type)
    {
        return $this->types[$type->getSearchableType()];
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

        $indices = $this->client->indices();

        $toggle = ['index' => $this->index];

        $indices->close($toggle);

        $settings = [
            'index' => $this->index,
            'body'  => $settings,
        ];

        $indices->putSettings($settings);

        $indices->open($toggle);

        $indices->refresh($toggle);
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
            $config = $this->types[$type];

            $classname = $this->getClassname($config);

            $with = $this->getWith($config);

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
            'index' => $this->index,
            'type'  => $type->getSearchableType()
        ];
    }

    protected function checkIndex()
    {
        $params = ['index' => $this->index];

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
            'index' => $this->index,
            'type'  => $type->getSearchableType(),
            'id'    => $type->getSearchableId(),
            'body'  => $type->getSearchableDocument(),
        ];
    }

    protected function getClassname($config)
    {
        if (!is_array($config)) {
            return $config;
        }

        return $config['class'];
    }

    protected function getWith($config)
    {
        if (!is_array($config)) {
            return array();
        }

        return isset($config['with']) ? $config['with'] : [];
    }

    /**
     * This method will save an inverted array of relations.
     * We can then use it to trigger nested document changes.
     *
     * @param $types
     *
     * @return array
     */
    private function invertTypes($types)
    {
        $inverted = [];

        foreach ($types as $type => $config) {
            foreach ($config['with'] as $relation => $class) {

                $key = $class['class'];

                if (!array_key_exists($key, $inverted)) {
                    $inverted[$key] = [];
                }

                $inverted[$key][] = [
                    'class'    => $config['class'],
                    'key'      => $class['key'],
                    'relation' => $relation
                ];
            }
        }

        return $inverted;
    }
}