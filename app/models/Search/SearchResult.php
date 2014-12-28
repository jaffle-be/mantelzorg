<?php


namespace Search;

use Illuminate\Support\Collection;

class SearchResult extends Collection
{

    public function __construct($paginator, $items, $paginate)
    {
        $this->_shards = $result['_shards'];

        $this->timed_out = $result['timed_out'];

        $this->took = $result['took'];

        $this->max_score = $result['hits']['max_score'];

        $items = array_map(function ($item) {
            return $item['_source'];
        }, $result['hits']['hits']);

        parent::__construct($items);
    }
}