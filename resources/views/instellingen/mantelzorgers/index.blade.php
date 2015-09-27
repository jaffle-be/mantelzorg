@extends('layout.admin.master')

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.instellingen'),
                    'href' => route('instellingen.index')
            ),
            array(
                    'text' => Lang::get('master.navs.mantelzorgers'),
            )

    )) ?>
@stop

@section('content')

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
                                            <h5>
                                                <i class="fa fa-user">&nbsp;</i>{{ Lang::get('users.mantelzorger') }}
                                                <a href="{{ route('instellingen.{hulpverlener}.mantelzorgers.edit', array($hulpverlener->id, $mantelzorger->id)) }}" class="btn btn-default pull-right"><i class="fa fa-edit"></i></a>
                                            </h5>
                                        </div>
                                        <div class="body">

                                            <dl>
                                                <dt for="identifier">{{ Lang::get('users.identifier') }}</dt>
                                                <dd>{{ $mantelzorger->identifier ? : '/' }}</dd>
                                                <dt for="name">{{ Lang::get('users.fullname') }}</dt>
                                                <dd>{{ $mantelzorger->fullname ? : '/' }}</dd>
                                            </dl>

                                        </div>

                                        <div class="ouderen">
                                            <div class="header clearfix">
                                                <h5>
                                                    <i class="fa fa-users">&nbsp;</i>{{ Lang::get('users.ouderen') }}
                                                    <a class="btn btn-default pull-right" href="<?= route('instellingen.{mantelzorger}.oudere.create', array($mantelzorger->id)) ?>"><i class="fa fa-plus"></i></a>
                                                </h5>
                                            </div>

                                            <div class="body">

                                                <div class="table-responsive">
                                                    <table class="table table-hover table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>{{ Lang::get('users.identifier') }}</th>
                                                            <th>{{ Lang::get('users.fullname') }}</th>
                                                            <th>&nbsp;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($mantelzorger->oudere as $oudere)
                                                            <tr>
                                                                <td>{{ $oudere->identifier ? : '/' }}</td>
                                                                <td>{{ $oudere->fullname ? : '/' }}</td>
                                                                <td>
                                                                    <a href="{{ route('instellingen.{mantelzorger}.oudere.edit', array($mantelzorger->id, $oudere->id)) }}" class="btn btn-default"><i class="fa fa-edit"></i></a></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                @endforeach

            </div>

            <a class="btn btn-fab btn-primary" data-toggle="tooltip" data-original-title="{{ Lang::get('users.create_mantelzorger') }}" href="{{ route('instellingen.{hulpverlener}.mantelzorgers.create', array($hulpverlener->id)) }}"><i class="fa fa-plus"></i></a>

            @include('layout.easy-search-bottom', ['data' => $mantelzorgers])

        </div>
    @endif


@stop