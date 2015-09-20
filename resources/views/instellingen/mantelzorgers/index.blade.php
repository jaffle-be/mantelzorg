@extends('layout.admin.master')

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.instellingen'),
                    'href' => URL::route('instellingen.index')
            ),
            array(
                    'text' => Lang::get('master.navs.mantelzorgers'),
            )

    )) ?>
@stop

@section('content')

    <div class="page-actions">
        <a class="btn btn-primary" href="{{ route('instellingen.{hulpverlener}.mantelzorgers.create', array($hulpverlener->id)) }}"><i class="fa fa-plus"></i>&nbsp;{{ Lang::get('users.create_mantelzorger') }}
        </a>
    </div>

    @include('layout.easy-search-top', ['view' => 'instellingen.mantelzorgers.search', 'data' => $mantelzorgers])

    @if($mantelzorgers->count())

        <div class="mantelzorgers">

            <div class="row">

                @foreach($mantelzorgers as $mantelzorger)

                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-z-1">

                            <div class="card-height-indicator"></div>

                            <div class="card-content">
                                <div class="card-body">
                                    <div class="mantelzorger">
                                            <div class="header">
                                                <h5><i class="fa fa-user">&nbsp;</i>{{ Lang::get('users.mantelzorger') }}</h5>
                                            </div>
                                            <div class="body">
                                                <a href="<?= URL::route('instellingen.{hulpverlener}.mantelzorgers.edit', array($hulpverlener->id, $mantelzorger->id)) ?>">
                                                    <?= Form::text('name', $mantelzorger->displayName, array('class' => 'form-control')) ?>
                                                </a>
                                            </div>

                                            <div class="ouderen">
                                                <div class="header clearfix">
                                                    <h5>
                                                        <i class="fa fa-users">&nbsp;</i>{{ Lang::get('users.ouderen') }}
                                                        <a class="btn btn-default pull-right" href="<?= URL::route('instellingen.{mantelzorger}.oudere.create', array($mantelzorger->id)) ?>"><i class="fa fa-plus"></i></a>
                                                    </h5>
                                                </div>

                                                <div class="body">
                                                    <ul>
                                                        @foreach($mantelzorger->oudere as $oudere)
                                                            <li>
                                                                <a href="<?= route('instellingen.{mantelzorger}.oudere.edit', array($mantelzorger->id, $oudere->id)) ?>">
                                                                    <?= Form::text('name', $oudere->displayName, array('class' => 'form-control')) ?>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>

                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                @endforeach

            </div>


            @include('layout.easy-search-bottom', ['data' => $mantelzorgers])

        </div>
    @endif


@stop