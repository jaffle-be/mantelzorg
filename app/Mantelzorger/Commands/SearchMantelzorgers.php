<?php

namespace App\Mantelzorger\Commands;

use App\Commands\Command;
use App\Search\SearchServiceInterface;
use App\User;
use Illuminate\Support\Arr;

class SearchMantelzorgers extends Command
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
        $mantelzorgers = $search->search('mantelzorgers', $this->query(), [], 15, function ($source, $highlight) {

            foreach ($highlight as $light => $value) {
                Arr::set($source, $light, $value[0]);
            }

            return $source;
        });

        $mantelzorgers->addQuery('query', $this->query);

        return $mantelzorgers;
    }

    protected function query()
    {
        $query = [
            'index' => env('ES_INDEX'),
            'type' => 'mantelzorgers',
            'body' => [
                'query' => [
                    'filtered' => [
                        'query' => [
                            'bool' => [
                                'should' => [
                                    [
                                        'nested' => [
                                            'path' => 'oudere',
                                            'query' => [
                                                'multi_match' => [
                                                    'query' => $this->query,
                                                    'fields' => ['oudere.firstname', 'oudere.lastname', 'oudere.identifier.raw'],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'multi_match' => [
                                            'query' => $this->query,
                                            'fields' => ['firstname', 'lastname', 'identifier.raw'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'filter' => [
                            'bool' => [
                                'must' => [
                                    [
                                        'term' => [
                                            'hulpverlener_id' => $this->user->id,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'sort' => [
                    ['identifier.raw' => 'asc'],
                ]
                ,
                'highlight' => [
                    'pre_tags' => ['<strong>'],
                    'post_tags' => ['</strong>'],
                    'fields' => [
                        'identifier' => new \StdClass(),
                        'firstname' => new \StdClass(),
                        'lastname' => new \StdClass(),
                    ],
                ],
            ],
        ];

        if (empty($this->query)) {
            $query['body']['query']['filtered']['query'] = ['match_all' => []];
        }

        return $query;
    }
}
