<?php


namespace Search;

use Search\Model\Searchable;

interface SearchServiceInterface
{

    /**
     * Register the regular listeners for the given type.
     *
     * @param Searchable $type
     * @param array      $with
     *
     * @return
     */
    public function regularAutoIndex(Searchable $type, array $with);

    /**
     * Build the index for a type.
     *
     * @param string|Searchable $type
     *
     * @return
     */
    public function build($type);

    /**
     * Add to index.
     *
     * @param Searchable $type
     */
    public function add(Searchable $type);

    /**
     * Delete from index.
     *
     * @param Searchable $type
     */
    public function delete(Searchable $type);

    /**
     * Update a document in the index.
     *
     * @param Searchable $type
     */
    public function update(Searchable $type);

    /**
     * Search the index
     *
     * @param array $params
     *
     * @return mixed
     */
    public function search(array $params);

    /**
     * @return \Illuminate\Pagination\Environment
     */
    public function getPaginator();

    /**
     * Return the config for the searchable type.
     *
     * @param Searchable $type
     *
     * @return mixed
     */
    public function getConfig(Searchable $type);

    /**
     * Update the settings for the elasticsearch instance.
     *
     * @param array $settings
     *
     * @return bool
     */
    public function updateSettings(array $settings);

    /**
     * Update the mapping for a elasticsearch type.
     *
     * @param $type
     *
     * @return mixed
     */
    public function updateMapping($type);
}