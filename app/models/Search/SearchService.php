<?php


namespace Search;

use Elasticsearch\Client;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Mustache_Engine;
use Search\Model\Searchable;

class SearchService implements SearchServiceInterface
{

    protected $path;

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
    protected $types;

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

        $this->parseConfig($config);
    }

    protected function parseConfig($config)
    {
        $this->index = $config['index'];

        $this->types = $config['types'];

        $this->path = $config['path'];
    }

    public function addAutoIndexing(Searchable $type)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->container->make('events');

        $me = $this;

        foreach ($this->listeners as $event => $listener)
        {
            $trigger = $type->getSearchableEventname($event);

            $type->setSearchableService($me);

            $type->setSearchableIndex($me->index);

            if ($trigger)
            {
                $callback = function (Searchable $type) use ($me, $listener)
                {
                    return $me->$listener($type);
                };

                $dispatcher->listen($trigger, $callback, 15);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function build($type)
    {
        $this->checkIndex();

        list($type, $relations) = $this->getSearchable($type);

        $this->refreshType($type);

        $me = $this;

        $type->with($relations)->chunk(50, function ($documents) use ($me)
        {
            foreach ($documents as $document)
            {
                $me->add($document);
            }
        });
    }

    public function flush($type)
    {
        $this->checkIndex();

        list($type) = $this->getSearchable($type);

        $this->refreshType($type);
    }

    public function add(Searchable $type)
    {
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
        $params = $this->getBaseParams($type);

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
        return $this->container->make('paginator');
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
            'body' => $settings,
        ];

        $indices->putSettings($settings);

        $indices->refresh($toggle);

        $indices->open($toggle);
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
        /** @var Searchable $type */
        list($type, $relations) = $this->getSearchable($type);

        $mapping = [
            'index' => $this->index,
            'type' => $type->getSearchableType(),
            'body' => [
                $type->getSearchableType() => $type->getSearchableMapping(),
                ]
        ];

        $this->client->indices()->putMapping($mapping);
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
        if (is_string($type))
        {
            $config = $this->types[$type];

            $classname = $this->getClassname($config);

            $with = $this->getWith($config);

            $type = $this->container->make($classname);

            return array($type, $with);
        }

        if (!is_object($type) || !($type instanceof Searchable))
        {
            throw new Exception('Invalid searchable provided, expecting something Search\\Searchable');
        }

        return $type;
    }

    protected function refreshType(Searchable $type)
    {
        $params = $this->getBaseParams($type);

        if ($this->client->indices()->getMapping($params))
        {
            $params = array_merge($params, ['id' => '_mapping']);

            $this->client->delete($params);
        }

        if ($mapping = $type->getSearchableMapping())
        {
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

        if (!$indices->exists($params))
        {
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
        if (!is_array($config))
        {
            return $config;
        }

        return $config['class'];
    }

    protected function getWith($config)
    {
        if (!is_array($config))
        {
            return array();
        }

        return isset($config['with']) ? $config['with'] : [];
    }

}