<?php

trait TestingTrait
{

    /**
     * @setUp need to flush elasticsearch
     * @priority 1
     */
    protected function resetElasticsearch()
    {
        $search = app('App\Search\SearchServiceInterface');

        $types = config('search.types');

        $types = array_keys($types);

        foreach ($types as $type) {
            $search->flush($type);
        }
    }


}