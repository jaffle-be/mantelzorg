<?php namespace App\Mantelzorger\Commands;

use App\Commands\Command;
use App\Mantelzorger\Mantelzorger;
use App\Search\SearchServiceInterface;
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

    public function handle(SearchServiceInterface $search)
    {
        $mantelzorgers = $search->search('mantelzorgers', $this->query());

        $mantelzorgers->addQuery('query', $this->query);

        return $mantelzorgers;
    }

    protected function query()
    {
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

        $query = [
            'index' => env('ES_INDEX'),
            'type' => 'mantelzorgers',
            'body' => [
                'query' => [
                    'filtered' => [
                        'query' => [
                            'match_all' => []
                        ],
                        'filter' => [
                            'bool' => [
                                'must' => [
                                    [
                                        'term' => ['hulpverlener_id' => $this->user->id]
                                    ]
                                ],
                                'should' => [
                                    [
                                        'query' => ['match' => ['identifier.raw' => $this->query]],
                                    ],
                                    [
                                        'nested' => [
                                            'path' => 'oudere',
                                            'query' => [
                                                'match' => ['oudere.identifier' => $this->query]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'sort' => [
                    ['identifier.raw' => 'asc']
                ]
            ]
        ];

        if(empty($this->query))
        {
            unset($query['body']['query']['filtered']['filter']['bool']['should']);
        }

        return $query;
    }
}