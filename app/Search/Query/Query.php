<?php

namespace App\Search\Query;

use App\Search\Model\Searchable;
use App\Search\SearchResponder;
use App\Search\SearchServiceInterface;
use Exception;
use Input;

class Query implements Queryable
{
    use SearchResponder;

    /**
     * @var SearchServiceInterface
     */
    protected $service;

    /**
     * @var Searchable
     */
    protected $searchable;

    /**
     * @var array
     */
    protected $with = [];

    /**
     * Legal/supported booleans.
     *
     * @var array
     */
    protected $booleans = ['and', 'or'];

    /**
     * Legal/supported operators.
     *
     * @var array
     */
    protected $queryOperators = ['match', 'multi_match', 'fuzzy_like_this'];

    protected $match = [];

    protected $multi_match = [];

    protected $fuzzy_like_this = [];

    protected $filter_term = [];

    protected $filter_match = [];

    protected $filter_bool = [];

    protected $filter_multi_match = [];

    protected $pagination = 10;

    protected $filterOperators = ['filter_term', 'filter_match', 'filter_multi_match'];

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
        } else {
            $params['size'] = 9999;
        }

        $results = $this->service->search($params);

        return $this->response($results, $this->with, $this->pagination);
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = array($relations);
        }

        $this->with = $relations;

        return $this;
    }

    public function all()
    {
        return $this->paginate(false)->get();
    }

    public function paginate($amount = 10)
    {
        $this->pagination = $amount;

        return $this;
    }

    public function filterBool(array $bool)
    {
        $this->filter_bool = $bool;

        return $this;
    }

    public function filterTerm($column, $value)
    {
        $this->filter_term[$column] = $value;

        return $this;
    }

    public function filterMatch($column, $value, $fuzziness = 0, $prefix_length = 2, $analyzer = 'standard')
    {
        $this->filter_match[$column] = [
            'query' => $value,
            'fuzziness' => $fuzziness,
            'prefix_length' => $prefix_length,
            'analyzer' => $analyzer,
        ];

        return $this;
    }

    public function filterMulti_match(array $columns, $value)
    {
        if (!empty($value)) {
            $this->filter_multi_match = [
                'fields' => $columns,
                'query' => $value,
            ];
        }

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

        $method = 'where'.ucfirst($operator);

        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], [$column, $value, $boolean]);
        }

        return $this;
    }

    public function whereMatch($column, $value, $fuzziness = 0, $prefix_length = 2, $analyzer = 'standard')
    {
        if (!empty($value)) {
            $this->match[$column] = [
                'query' => $value,
                'fuzziness' => $fuzziness,
                'prefix_length' => $prefix_length,
                'analyzer' => $analyzer,
            ];
        }

        return $this;
    }

    public function whereMulti_match(array $columns, $value, $fuzziness = 0, $prefix_length = 2, $analyzer = 'standard')
    {
        //if the value is empty, it wouldn't return a resultset when querying. which is quite weird to be honest.
        if (!empty($value)) {
            $this->multi_match[] = [
                'fields' => $columns,
                'query' => $value,
                'fuzziness' => $fuzziness,
                'prefix_length' => $prefix_length,
                'analyzer' => $analyzer,
            ];
        }

        return $this;
    }

    public function whereFuzzyLikeThis($fields, $value, $fuzziness = 2, $prefixLength = 1)
    {
        $this->fuzzy_like_this[] = [
            'fields' => (array) $fields,
            'like_text' => $value,
            'fuzziness' => $fuzziness,
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

    public function orderBy($field, $direction, $missing = null)
    {
        if ($missing === null) {
            $missing = PHP_INT_MAX - 1;
        }

        $this->sort[$field] = array('order' => $direction, 'missing' => $missing);

        return $this;
    }

    protected function base()
    {
        return [
            'index' => $this->searchable->getSearchableIndex(),
            'type' => $this->searchable->getSearchableType(),
        ];
    }

    public function body()
    {
        $body = [];

        $queries = $this->getQueryBody();

        if ($filters = $this->getFilteredBody()) {
            $body['query']['filtered'] = $filters;
        }

        if (empty($queries) && empty($filters)) {
            $body['query'] = ['match_all' => []];
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

        return $queries;
    }

    protected function getFilteredBody()
    {
        $response = [];

        if (!empty($this->filter_multi_match)) {
            $response['query'] = [];
            $response['query']['multi_match'] = $this->filter_multi_match;
        }

        if (!empty($this->filter_match)) {
            if (!isset($response['query'])) {
                $response['query'] = [];
            }
            $response['query']['match'] = $this->filter_match;
        }

        if (!empty($this->filter_term)) {
            $response['filter']['term'] = $this->filter_term;
        }

        if (!empty($this->filter_bool)) {
            $response['filter']['bool'] = $this->filter_bool;
        }

        if (!empty($response)) {
            return $response;
        }

        return;
    }
}
