<?php

namespace App\Search\Query;

interface Queryable
{
    public function get();

    /**
     * Add a where clause for the search.
     *
     * @param        $column
     * @param        $operator
     * @param        $value
     * @param string $boolean
     *
     * @return Queryable
     */
    public function where($column, $operator, $value = null, $boolean = 'and');

    /**
     * Return self.
     *
     * @param string $column
     * @param string $value
     *
     * @return mixed
     */
    public function orderBy($column, $value);
}
