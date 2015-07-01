<?php namespace App\Mantelzorger\Commands;

use App\Commands\Command;
use App\Mantelzorger\Mantelzorger;
use App\User;
use Illuminate\Contracts\Bus\SelfHandling;

class SearchMantelzorgers extends Command implements SelfHandling
{

    protected $user;

    protected $query;

    public function __construct(User $user, $query)
    {
        $this->user = $user;
        $this->query = $query;
    }

    public function handle(Mantelzorger $mantelzorger)
    {
        $search = $mantelzorger->search();

        $bool['must'] = [
            ['term' => ['hulpverlener_id' => $this->user->id]]
        ];

        if ($this->query) {
            $bool['should'] = [
                ['query' => ['match' => ['identifier.raw' => $this->query]]],
                ['nested' => [
                    'path'  => 'oudere',
                    'query' => [
                        'match' => ['oudere.identifier.raw' => $this->query]
                    ]
                ]]
            ];
        }

        $mantelzorgers = $search
            ->filterBool($bool)
            ->orderBy('identifier.raw', 'asc')
            ->get();

        $mantelzorgers->addQuery('query', $this->query);

        return $mantelzorgers;
    }
}