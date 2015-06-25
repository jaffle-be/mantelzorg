<?php


namespace App\Search;

use App\Search\Model\Searchable;

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
    public function add(Searchable $type, array $with, $needsLoading = true);

    /**
     * Delete from index.
     *
     * @param Searchable $type
     */
    public function delete(Searchable $type, array $with);

    /**
     * Update a document in the index.
     *
     * @param Searchable $type
     */
    public function update(Searchable $type, array $with);

    /**
     * Search the index
     *
     * @param array $params
     *
     * @return mixed
     */
    public function search(array $params);

    /**
     * @return \Illuminate\Pagination\Factory
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

    /**
     * Boot the search service.
     *
     * This method should parse the configurations and set the auto indexing.
     *
     * @return mixed
     */
    public function boot();
}