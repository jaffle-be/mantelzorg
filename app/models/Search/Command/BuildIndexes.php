<?php namespace Search\Command;

use Illuminate\Console\Command;
use Search\SearchServiceInterface;
use Symfony\Component\Console\Input\InputArgument;

class BuildIndexes extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'search:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build your elastic search indexes';

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
            $started = microtime(true);

            $this->comment(sprintf('Starting to build index %s', $type));

            $this->service->build($type);

            $seconds = microtime(true) - $started;

            $this->comment(sprintf('%s built in %s seconds', $type, $seconds));
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
            array('types', InputArgument::IS_ARRAY, []),
        );
    }
}
