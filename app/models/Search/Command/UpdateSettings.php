<?php namespace App\Search\Command;

use App\Search\SearchServiceInterface;
use Illuminate\Console\Command;

class UpdateSettings extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'search:settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the settings for the elasticsearch instance.';

    /**
     * @var SearchServiceInterface
     */
    protected $service;

    /**
     * Create a new command instance.
     *
     * @param SearchServiceInterface $service
     */
    public function __construct(SearchServiceInterface $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $config = $this->laravel->make('config');

        $settings = $config->get('elasticsearch.settings');

        $this->service->updateSettings($settings);
    }
}
