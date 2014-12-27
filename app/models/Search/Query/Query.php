<?php


namespace Search\Query;

use Exception;
use Search\Model\Searchable;
use Input;
use Search\SearchServiceInterface;

class Query implements Queryable
{

    /**
     * @var SearchServiceInterface
     */
    protected $service;

    /**
     * @var Searchable
     */
    protected $searchable;

    /**
     * Legal/supported booleans.
     *
     * @var array
     */
    protected $booleans = ['and', 'or'];

    /**
     * Legal/supported operators
     *
     * @var array
     */
    protected $operators = ['match', 'multi_match', 'fuzzy_like_this'];

    protected $match = [];

    protected $multi_match = [];

    protected $fuzzy_like_this = [];

    protected $pagination = 10;


    /**
     * @param SearchServiceInterface $service
     * @param Searchable             $searchable
     */
    public function __construct(SearchServiceInterface $service, Searchable $searchable)
    {
        $this->service = $service;
        $this->searchable = $searchable;
    }

    /**
     * All sort clauses.
     *
     * @var array
     */
    protected $sort = [];

    public function get()
    {
        $body = $this->body();

        $base = $this->base();

        $params = array_merge($base, ['body' => $body]);

        if ($needsPagination = $this->needsPagination())
        {
            $params['from'] = $this->skip();
            $params['size'] = $this->take();
        }

        $results = $this->service->search($params);

        return $this->response($results);
    }

    protected function response($results)
    {
        $collection = $this->asModels($results['hits']['hits']);

        if ($this->needsPagination())
        {
            /** @var \Illuminate\Pagination\Environment $paginator */
            $paginator = $this->service->getPaginator();

            $results = $paginator->make($collection, $results['hits']['total'], $this->pagination);
        }
        else
        {
            //did we run a find query? return only the model

        }

        return $results;
    }

    protected function asModels(array $results)
    {
        $items = [];

        $config = $this->service->getConfig($this->searchable);

        $with = $config['with'];

        foreach ($results as $result)
        {
            //need to also match the related models, which are for now specified into the config file.
            $model = $this->searchable->getSearchableNewModel($result['_source'], $with);

            $items[] = $model;
        }

        return $items;
    }

    public function paginate($amount = 10)
    {
        $this->pagination = $amount;

        return $this;
    }

    public function where($column, $operator, $value = null, $boolean = 'and')
    {
        if (func_num_args() == 2)
        {
            list($value, $operator) = [$operator, 'match'];
        }

        if ($this->invalidBoolean($boolean))
        {
            throw new Exception('Invalid boolean for search clause');
        }

        if ($this->invalidOperator($operator))
        {
            throw new Exception("Invalid operator for search clause. If it's, not supported. Use the gateway method.");
        }

        $method = 'where' . ucfirst($operator);

        if (method_exists($this, $method))
        {
            call_user_func_array([$this, $method], [$column, $value, $boolean]);
        }

        return $this;
    }

    public function whereMatch($column, $value, $boolean = 'and', $fuzziness = 0, $prefix_length = 2)
    {
        if (!empty($value))
        {
            $this->match[$column] = [
                'query'         => $value,
                'fuzziness'     => $fuzziness,
                'prefix_length' => 2
            ];
        }

        return $this;
    }

    public function whereMulti_match(array $columns, $value, $boolean = 'and', $fuzziness = 0, $prefix_length = 2, $analyzer = 'standard')
    {
        //if the value is empty, it wouldn't return a resultset when querying. which is quite weird to be honest.
        if (!empty($value))
        {
            $this->multi_match[] = [
                'fields'        => $columns,
                'query'         => $value,
                'fuzziness'     => $fuzziness,
                'prefix_length' => $prefix_length,
                'analyzer'      => $analyzer
            ];
        }

        return $this;
    }

    public function whereFuzzyLikeThis($fields, $value, $fuzziness = 2, $prefixLength = 1)
    {
        $this->fuzzy_like_this[] = [
            'fields'        => (array)$fields,
            'like_text'     => $value,
            'fuzziness'     => $fuzziness,
            'prefix_length' => $prefixLength,
        ];

        return $this;
    }

    /**
     * Determine if the given boolean is legal.
     *
     * @param $boolean
     *
     * @return bool
     */
    protected function invalidBoolean($boolean)
    {
        return !in_array($boolean, $this->booleans);
    }

    /**
     * Determine if the given operator is legal/supported.
     *
     * @param $operator
     *
     * @return bool
     */
    protected function invalidOperator($operator)
    {
        return !in_array($operator, $this->operators);
    }

    public function orderBy($field, $direction)
    {
        $this->sort[$field] = $direction;

        return $this;
    }

    private function base()
    {
        return [
            'index' => $this->searchable->getSearchableIndex(),
            'type'  => $this->searchable->getSearchableType()
        ];
    }

    private function body()
    {
        $body = [];

        foreach ($this->operators as $operation)
        {
            if (!empty($this->$operation))
            {
                $body['query'][$operation] = $this->$operation;
            }
        }

        if ($sort = $this->sort())
        {
            $body['sort'] = $sort;
        }

        return $body;
    }

    private function sort()
    {
        if (!empty($this->sort))
        {
            return $this->sort;
        }
    }

    private function skip()
    {
        $page = Input::has('page') ? Input::get('page') : 1;

        return $this->pagination * ($page - 1);
    }

    private function take()
    {
        return $this->pagination;
    }

    private function needsPagination()
    {
        return $this->pagination ? true : false;
    }

}