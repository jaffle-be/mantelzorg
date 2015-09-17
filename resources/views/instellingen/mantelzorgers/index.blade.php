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
        <a class="btn btn-primary" href="{{ route('instellingen.{hulpverlener}.mantelzorgers.create', array($hulpverlener->id)) }}"><i class="fa fa-plus">&nbsp;</i>{{ Lang::get('users.create_mantelzorger') }}
        </a>
    </div>

    @include('layout.easy-search-top', ['view' => 'instellingen.mantelzorgers.search', 'data' => $mantelzorgers])

    @if($mantelzorgers->count())

        <div class="mantelzorgers">
            @foreach($mantelzorgers as $mantelzorger)
                <div class="row mantelzorger">
                    <div class="col-md-3">
                        <div class="header">
                            <span><i class="fa fa-user">&nbsp;</i>{{ Lang::get('users.mantelzorger') }}</span>
                        </div>
                        <div class="body">
                            <a href="<?= URL::route('instellingen.{hulpverlener}.mantelzorgers.edit', array($hulpverlener->id, $mantelzorger->id)) ?>">
                                <?= Form::text('name', $mantelzorger->displayName, array('class' => 'form-control')) ?>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-5 col-md-offset-1 ouderen">
                        <div>
                            <div class="header clearfix">
                                <span class="pull-left"><i class="fa fa-users">&nbsp;</i>{{ Lang::get('users.ouderen') }}</span>
                                <a class="btn btn-default pull-right" href="<?= URL::route('instellingen.{mantelzorger}.oudere.create', array($mantelzorger->id)) ?>"><i class="fa fa-plus"></i></a>
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

            @endforeach

            @include('layout.easy-search-bottom', ['data' => $mantelzorgers])

        </div>
    @endif


@stop