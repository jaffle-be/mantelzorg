<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title">{{ Lang::get('rapport.create') }}</h3>
    </div>


    <div class="panel-body" id="rapport-generator">

        <?= Form::open(['route' => 'report.store', 'class' => 'form-horizontal']) ?>

        <div class="form-group">
            <label for="survey" class="control-label col-xs-12 col-sm-4">{{ Lang::get('rapport.survey') }}</label>

            <div class="col-xs-12 col-sm-8">
                    <?= Form::select('survey', $questionnaires, null, [
                            'class' => 'form-control',
                            'id' => 'survey',
                    ]) ?>

            </div>

        </div>

        <div class="form-group">
            <label for="organisation_id" class="control-label col-xs-12 col-sm-4">{{ Lang::get('rapport.organisation') }}</label>

            <div class="col-xs-12 col-sm-8">
                <select class="form-control" name="organisation_id" id="organisation_id">
                    <option value="">{{ Lang::get('rapport.select-organisation') }}</option>
                    @foreach($organisations as $id => $name)
                        <option {{ Input::old('organisation_id') == $id ? 'selected': '' }} value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="hulpverlener_id" class="control-label col-xs-12 col-sm-4">{{ Lang::get('rapport.hulpverlener') }}</label>

            <div class="col-xs-12 col-sm-8">

                <select class="form-control" name="hulpverlener_id" id="hulpverlener_id">
                    <option value="">{{ Lang::get('rapport.select-hulpverlener') }}</option>
                    @foreach($hulpverleners as $id => $name)
                        <option {{ Input::old('hulpverlener_id') == $id ? 'selected': '' }} value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">

                @error('survey')

                <button class="btn btn-primary" type="submit">{{ Lang::get('rapport.generate') }}</button>

                <a href="{{ route('report.legend') }}" class="btn btn-info" type="button">{{ Lang::get('rapport.legend') }}</a>
            </div>
        </div>

        <?= Form::close() ?>

    </div>

</div>

