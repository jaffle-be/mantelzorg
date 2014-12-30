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
    protected $queryOperators = ['match', 'multi_match', 'fuzzy_like_this'];

    protected $match = [];

    protected $multi_match = [];

    protected $fuzzy_like_this = [];

    protected $filter_match = [];

    protected $filter_multi_match = [];

    protected $pagination = 10;

    protected $filterOperators = ['filter_match', 'filter_multi_match'];

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

        if ($needsPagination = $this->needsPagination()) {
            $params['from'] = $this->skip();
            $params['size'] = $this->take();
        }

        $results = $this->service->search($params);

        return $this->response($results);
    }

    protected function response($results)
    {
        $collection = $this->asModels($results['hits']['hits']);

        if ($this->needsPagination()) {
            /** @var \Illuminate\Pagination\Environment $paginator */
            $paginator = $this->service->getPaginator();

            $results = $paginator->make($collection, $results['hits']['total'], $this->pagination);
        } else {
            //did we run a find query? return only the model
            //this is probably rather useless!
        }

        return $results;
    }

    protected function asModels(array $results)
    {
        $items = [];

        $config = $this->service->getConfig($this->searchable);

        $with = $config['with'];

        foreach ($results as $result) {
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

    public function filterMatch($column, $value, $fuzziness = 0, $prefix_length = 2, $analyzer = 'standard')
    {
        $this->filter_match[$column] = [
            'query'         => $value,
            'fuzziness'     => $fuzziness,
            'prefix_length' => $prefix_length,
            'analyzer'      => $analyzer,
        ];

        return $this;
    }

    public function filterMulti_match(array $columns, $value, $fuzziness = 0, $prefix_length = 2, $analyzer = 'standard')
    {
        $this->filter_multi_match[] = [
            'fields'        => $columns,
            'query'         => $value,
            'fuzziness'     => $fuzziness,
            'prefix_length' => $prefix_length,
            'analyzer'      => $analyzer
        ];

        return $this;
    }

    public function where($column, $operator, $value = null, $boolean = 'and')
    {
        if (func_num_args() == 2) {
            list($value, $operator) = [$operator, 'match'];
        }

        if ($this->invalidBoolean($boolean)) {
            throw new Exception('Invalid boolean for search clause');
        }

        if ($this->invalidOperator($operator)) {
            throw new Exception("Invalid operator for search clause. If it's, not supported. Use the gateway method.");
        }

        $method = 'where' . ucfirst($operator);

        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], [$column, $value, $boolean]);
        }

        return $this;
    }

    public function whereMatch($column, $value, $fuzziness = 0, $prefix_length = 2, $analyzer = 'standard')
    {
        if (!empty($value)) {
            $this->match[$column] = [
                'query'         => $value,
                'fuzziness'     => $fuzziness,
                'prefix_length' => $prefix_length,
                'analyzer'      => $analyzer,
            ];
        }

        return $this;
    }

    public function whereMulti_match(array $columns, $value, $fuzziness = 0, $prefix_length = 2, $analyzer = 'standard')
    {
        //if the value is empty, it wouldn't return a resultset when querying. which is quite weird to be honest.
        if (!empty($value)) {
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
        return !in_array($operator, $this->queryOperators);
    }

    public function orderBy($field, $direction)
    {
        $this->sort[$field] = $direction;

        return $this;
    }

    protected function base()
    {
        return [
            'index' => $this->searchable->getSearchableIndex(),
            'type'  => $this->searchable->getSearchableType()
        ];
    }

    protected function body()
    {
        $body = [];

        $body['query'] = $this->getQueryBody();

        if ($filters = $this->getFilterBody()) {
            $body['filter'] = $filters;
        }

        if ($sort = $this->sort()) {
            $body['sort'] = $sort;
        }

        return $body;
    }

    protected function sort()
    {
        if (!empty($this->sort)) {
            return $this->sort;
        }
    }

    protected function skip()
    {
        $page = Input::has('page') ? Input::get('page') : 1;

        return $this->pagination * ($page - 1);
    }

    protected function take()
    {
        return $this->pagination;
    }

    protected function needsPagination()
    {
        return $this->pagination ? true : false;
    }

    /**
     * @return array
     */
    protected function getQueryBody()
    {
        $queries = [];

        foreach ($this->queryOperators as $operator) {
            if (!empty($this->$operator)) {
                $queries[$operator] = $this->$operator;
            }
        }

        //if we do not provide a match all clause when no query provided, the get method will fail if nothing else
        // was set like sorting.
        if (empty($queries)) {
            $queries['match_all'] = [];
        }

        return $queries;
    }

    protected function getFilterBody()
    {
        $filters = [];

        foreach ($this->filterOperators as $operator) {
            if (!empty($this->$operator)) {
                $filters[str_replace('filter_', '', $operator)] = $this->$operator;
            }
        }

        if(!empty($filters))
        {
            return ['query' => $filters];
        }
    }
}