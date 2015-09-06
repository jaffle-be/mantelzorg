<?php namespace App\Search\Command;

use App\Search\SearchServiceInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class FlushType extends Command
{

    
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'search:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush a type by it\'s elastisc search type.';

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
        $types = $this->argument('types');

        if (empty($types)) {
            $types = $this->laravel->make('config')->get('elasticsearch.types');

            $types = array_keys($types);
        }

        foreach ($types as $type) {
            $this->service->flush($type);
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('types', InputArgument::IS_ARRAY),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
