@extends('layout.admin.master')

@section('scripts')
    <script src="/js/rapport.min.js"></script>
@stop


@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.rapport'),
            ),

            array('text' => Lang::get('master.navs.downloaden'))
    )) ?>

@stop

@section('content')

    <div class="row">

        <div class="col-xs-12 col-lg-offset-0 col-lg-4">

            @include('rapport.generator')

        </div>


        <div class="col-xs-12 col-lg-offset-0 col-lg-8">

            @include('rapport.downloader')

        </div>

    </div>

@stop