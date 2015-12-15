<?php

namespace App\Search;

use App\Search\Model\Searchable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait SearchResponder
{
    protected function response($results, $with, $paginated, Searchable $model = null)
    {
        $collection = $this->asModels($results['hits']['hits'], $model);

        /*
         * if we also want to lazy load relations, we'll create a collection and load them,
         * pass them on to the paginator if needed
         * heads up: i believe nested documents will always be loaded,
         * so developer should only pass with relations that aren't being indexed by Elasticsearch
         */
        if ($with) {
            $model->unguard();

            $collection = $model->newCollection($collection);

            $model->reguard();

            $collection->load($with);
        }

        if ($paginated) {

            /*
             * if we lazy loaded some relations, we need to get back an array to paginate.
             * not an optimal way of doing this, but i believe there isn't a better way at this point,
             * since the paginator only takes an array.
             */
            $collection = is_array($collection) ? $collection : $collection->all();

            $path = Paginator::resolveCurrentPath();

            //for some reason things do not work when passing in the options as an array like so
//            $results = new LengthAwarePaginator($collection, $results['hits']['total'], $this->pagination, ['path' => $path]);
            $results = new LengthAwarePaginator($collection, $results['hits']['total'], $paginated);
            $results->setPath($path);
            //only need transform into a collection when we didn't lazyload relations
        } elseif (is_array($collection)) {
            $results = $model->newCollection($collection);
        } else {
            $results = $collection;
        }

        return $results;
    }

    protected function asModels(array $results, Searchable $searchable = null)
    {
        $items = [];

        $config = $this->service->getConfig($searchable);

        $with = $config['with'];

        foreach ($results as $result) {
            //need to also match the related models, which are for now specified into the config file.
            $model = $searchable->getSearchableNewModel($result['_source'], $with);

            $items[] = $model;
        }

        return $items;
    }
}
