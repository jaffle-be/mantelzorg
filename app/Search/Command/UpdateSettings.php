<?php

namespace App\Search\Command;

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
        $settings = config('search.settings');

        $this->service->updateSettings($settings);

        if(env('APP_ENV') == 'testing')
        {
            $client = $this->service->getClient();
            
            $params = [
                'index' => config('search.index'),
                'body' => [
                    'settings' => [
                        'refresh_interval' => "5ms"
                    ]
                ]
            ];
        }

        $response = $client->indices()->putSettings($params);
    }
}
