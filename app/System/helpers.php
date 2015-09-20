<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\SimpleBootstrapThreePresenter;

function simple_paginator($data)
{
    $reflector = new ReflectionClass(LengthAwarePaginator::class);
    $query = $reflector->getProperty('query');
    $query->setAccessible(true);
    $query = $query->getValue($data);

    $paginator = new LengthAwarePaginator($data->items(), $data->total(), $data->perPage(), $data->currentPage(), [
        'path' => Paginator::resolveCurrentPath(),
        'query' => $query
    ]);

    $presenter = new SimpleBootstrapThreePresenter($paginator);

    echo $presenter->render();
}


function br2nl($text)
{
    $breaks = ["<br />","<br>","<br/>","<br />","&lt;br /&gt;","&lt;br/&gt;","&lt;br&gt;"];
    $text = str_ireplace($breaks, "\r\n", $text);
    return $text;
}