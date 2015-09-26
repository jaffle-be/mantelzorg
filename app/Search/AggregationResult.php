<?php namespace App\Search;

use Illuminate\Support\Collection;

class AggregationResult extends Collection
{

    protected $document_count;

    public function __construct($items = [], $document_count)
    {
        $this->document_count = $document_count;

        parent::__construct($items);
    }

    public function toArray()
    {
        $items = parent::toArray();

        return [
            'document_count' => $this->document_count,
            'buckets' => $items
        ];
    }

}