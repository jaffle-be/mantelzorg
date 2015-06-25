<?php namespace App\Search\Command;

use App\Search\SearchServiceInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class UpdateMapping extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'search:mapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a mapping for a specified type';

    /**
     * @var SearchServiceInterface
     */
    protected $search;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(SearchServiceInterface $search)
    {
        parent::__construct();

        $this->search = $search;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $type = $this->argument('type');

        if ($this->search->updateMapping($type)) {
            //we need to rebuild the type to make sure the new mapping is used.
            $this->search->build($type);
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
            array('type', InputArgument::REQUIRED, 'The type to remap'),
        );
    }
}
