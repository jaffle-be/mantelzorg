<?php

return array(

    'items' => array(
        //dashboard
        array(
            'href' =>  URL::action('DashController@getIndex'),

            'class' => 'glyphicon glyphicon-dashboard',

            'text' => Lang::get('master.navs.dashboard'),

        ),

        //gebruikers
        array(
            'href' => URL::action('HulpverlenerController@index'),

            'class' => 'glyphicons group',

            'text' => Lang::get('master.navs.gebruikers'),

            'condition' => function()
            {
                $user = Auth::user();
                return $user->admin === '1';
            },

            'sublinks' => array(
                array(
                    'text' => Lang::get('master.navs.hulpverleners'),

                    'href' => URL::route('hulpverleners.index')
                ),

                array(
                    'text' => Lang::get('master.navs.inschrijvingen'),

                    'href' => URL::route('inschrijvingen.index')
                )
            )
        ),

        array(
            'href' => URL::route('questionnaires.index'),

            'text' => Lang::get('master.navs.questionnaires'),

            'class' => 'glyphicons notes_2',

            'condition' => function()
            {
                $user = Auth::user();

                return $user->admin === '1';
            }
        ),

        //instrument
        array(
            'href' => URL::action('InstrumentController@getIndex'),

            'class' => 'glyphicon glyphicon-list-alt',

            'text' => Lang::get('master.navs.instrument'),
        ),

        //rapport
        array(
            'href' => Url::action('RapportController@getIndex'),

            'class' => 'glyphicon glyphicon-folder-close',

            'text' => Lang::get('master.navs.rapport'),
        ),

        //instellingen
        array(
            'href' => Url::action('Instelling\PersonController@index'),

            'class' => 'glyphicon glyphicon-wrench',

            'text' => Lang::get('master.navs.instellingen'),

            'sublinks' => array(
                array(
                    'text' => Lang::get('master.navs.profiel'),

                    'href' => URL::action('Instelling\PersonController@index'),
                ),

                array(
                    'text' => Lang::get('master.navs.mantelzorgers'),

                    'href' => URL::route('instellingen.{hulpverlener}.mantelzorgers.index', array(Auth::user()->id)),
                )
            )
        )
    )


);