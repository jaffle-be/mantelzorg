<?= Form::model($user, array('route' => array('hulpverleners.update', $user->id), 'method' => 'put')) ?>


<div class="card shadow-z-1">

    <div class="card-body">

        <div class="row">
            <div class="col-md-5">


                <fieldset>
                    <legend><?= Lang::get('users.persoonlijk') ?></legend>

                    <div class="form-group">

                        <label class="control-label" for="firstname"><?= Lang::get('users.firstname') ?></label>

                        <div class="input-group">
                            <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>

                        @error('firstname')

                    </div>


                    <div class="form-group">

                        <label class="control-label" for="lastname"><?= Lang::get('users.lastname') ?></label>

                        <div class="input-group">
                            <?= Form::text('lastname', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>
                        @error('lastname')

                    </div>

                    <div class="form-group">

                        <label class="control-label">
                            {{ Lang::get('users.sex') }}
                        </label>

                        <div class="input-group">
                            <div class="radio radio-inline radio-success">
                                <label class="control-label" for="male">
                                    <input type="radio" name="male" id="male" value="1" {{ $user->male ? 'checked': '' }}>
                                    <?= Lang::get('users.male') ?>&nbsp;<i class="fa fa-male"></i>
                                </label>
                            </div>

                            <div class="radio radio-inline radio-success">
                                <label class="control-label" for="female">
                                    <input type="radio" name="male" id="female" value="0" {{ !$user->male ? 'checked': ''}}>
                                    <?= Lang::get('users.female') ?>&nbsp;<i class="fa fa-female"></i>
                                </label>
                            </div>

                        </div>

                        @error('male')

                    </div>


                    <div class="form-group">

                        <label class="control-label" for="phone"><?= Lang::get('users.phone') ?></label>

                        <div class="input-group">
                            <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>

                        @error('phone')

                    </div>

                </fieldset>

            </div>

            <div class="col-md-5 col-md-offset-1">
                <fieldset>
                    <legend><?= Lang::get('users.organisatie') ?></legend>

                    <div class="form-group">

                        <label class="control-label" for="organisation">
                            <?= Lang::get('users.organisatie') ?>
                        </label>

                        <div class="input-select">
                            <?= Form::select('organisation_id', $organisations, null, array(
                                    'id' => 'organisation',
                                    'class' => 'form-control',
                                    'data-original' => $user->organisation_id ? $user->organisation_id : null,
                            )) ?>
                        </div>

                    </div>

                    @error('organisation_id')

                    <div class="form-group">
                        <label class="control-label" for="locations"><?= Lang::get('users.locations') ?></label>
                        <?= Form::select('organisation_location_id', $locations, null, array(
                                'id' => 'location',
                                'class' => 'form-control',
                                'data-original' => $user->organisation_location_id ? $user->organisation_location_id : null,
                        )) ?>
                    </div>

                    @error('organisation_location_id')

                </fieldset>
            </div>

        </div>

        <br>

        <fieldset>
            <legend><?= Lang::get('users.beveiliging') ?></legend>

            <div class="row">

                <div class="col-md-5">

                    <div class="form-group">
                        <label class="control-label" for="email"><?= Lang::get('users.email') ?></label>

                        <div class="input-group">
                            <?= Form::text('email', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                        </div>

                        @error('email')
                    </div>

                </div>

                <div class="col-md-5 col-md-offset-1">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="control-label" for="active">
                                <input type="checkbox" name="active" id="active" value="1" {{ $user->active ? "checked" : "" }}>
                                <?= Lang::get('users.is_active') ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label class="control-label" for="admin">
                                <input type="checkbox" name="admin" id="admin" value="1" {{ $user->admin ? "checked" : "" }}>
                                <?= Lang::get('users.is_admin') ?>
                            </label>
                        </div>
                    </div>

                </div>

            </div>

        </fieldset>

        <br/>

        <button class="btn btn-primary" type="submit"><?= Lang::get('users.save') ?></button>

        @if(Auth::user()->admin)
            <a class="btn btn-warning" href="{{ route('hijack', ['user' => $user->id]) }}">{{ Lang::get('master.hijack.do' )}}</a>
        @endif


    </div>

</div>

</form>