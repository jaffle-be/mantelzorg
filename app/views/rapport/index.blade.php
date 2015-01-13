@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.rapport'),
            ),

            array('text' => Lang::get('master.navs.downloaden'))
    )) ?>

@stop

@section('content')

    {{ Form::open(['route' => 'rapport.download', 'class' => 'form-horizontal']) }}

    <div class="form-group">
        <label for="survey" class="control-label col-xs-12 col-sm-3">{{ Lang::get('rapport.survey') }}</label>

        <div class="col-xs-12 col-sm-6">
            {{ Form::select('survey', $questionnaires, null, [
                'class' => 'form-control'
            ]) }}
        </div>

    </div>

    @if($errors->has('survey'))
        <div class="form-group">
            <div class="col-xs-12 col-sm-offset-3 col-sm-6">
                <div class="alert alert-danger">{{$errors->first('survey')}}</div>
            </div>
        </div>
    @endif

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <input class="btn btn-primary" type="submit" value="{{ Lang::get('rapport.download') }}"/>
        </div>
    </div>

    {{ Form::close() }}

@stop