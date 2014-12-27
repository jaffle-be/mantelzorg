<?php


namespace Search\Model;

use Search\Query\Queryable;
use Search\SearchServiceInterface;

interface Searchable
{

    /**
     * Set the client for searching.
     *
     * @param SearchServiceInterface $service
     *
     * @return mixed
     */
    public function setSearchableService(SearchServiceInterface $service);

    /**
     * Get the client for searching.
     *
     * @return SearchServiceInterface
     */
    public function getSearchableService();

    /**
     * Set the index for searching.
     *
     * @param string $index
     */
    public function setSearchableIndex($index);

    /**
     * Get the index for searching.
     *
     * @return string
     */
    public function getSearchableIndex();


    /**
     * Returns a Queryable object for the type.
     *
     * @return Queryable
     */
    public function search();

    /**
     * Returns the type to use for indexing.
     *
     * @return mixed
     */
    public function getSearchableType();

    /**
     * Return the document id to use for indexing.
     *
     * @return int|string
     */
    public function getSearchableId();

    /**
     * Return the full document for indexing.
     *
     * @return array
     */
    public function getSearchableDocument();

    /**
     * Get the corresponding model event to listen for when auto indexing.
     *
     * @param $event
     *
     * @return mixed
     */
    public function getSearchableEventname($event);

    /**
     * This is a 'hook' to a new model creation.
     * if it ever changes in eloquent, you only need to adjust this part.
     *
     * @param       $data
     * @param array $with
     *
     * @return
     */
    public function getSearchableNewModel($data, array $with);

    /**
     * Return the mappings to use to index our data.
     *
     * @return mixed
     */
    public function getSearchableMapping();

}