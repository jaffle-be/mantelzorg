@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="/js/stats.index.min.js"></script>
@stop

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@stop

@section('page-header')
    <?= Template::crumb(array(
            array('text' => Lang::get('master.navs.stats')),
            array('text' => Lang::get('master.navs.stats'))
    )) ?>
@stop

@section('content')

    <div class="row">

        <div class="chart">
            <div id="mantelzorger_relation"></div>
        </div>

        <div class="chart">
            <div id="woonsituatie"></div>
        </div>

        <div class="chart">
            <div id="oorzaak_hulpbehoefte"></div>
        </div>

        <div class="chart">
            <div id="bel_profiel"></div>
        </div>

    </div>

    <div id="errors">

    </div>

@stop