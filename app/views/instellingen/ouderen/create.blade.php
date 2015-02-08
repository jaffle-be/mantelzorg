@section('styles')
    <link rel="stylesheet" href="<?= asset('css/users.min.css') ?>"/>
@stop

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.instellingen'),
                    'href' => URL::action('Instelling\PersonController@index')
    ),
    array(
    'text' => Lang::get('master.navs.mantelzorgers'),
    'href' => URL::route('instellingen.{hulpverlener}.mantelzorgers.index', array(Auth::user()->id))
    ),
    array(
    'text' => Lang::get('master.navs.ouderen')
    ),
    array(
    'text' => Lang::get('master.navs.nieuw')
    )

    )) ?>
@stop

@section('content')

    <?= Form::open(array(
            'route'  => array('instellingen.{mantelzorger}.oudere.store', $mantelzorger->id),
    'method' => 'post',
    )) ?>

    <div class="row">

        <div class="col-md-12">

            <fieldset>

                <legend><?= Lang::get('users.persoonlijk') ?></legend>

                <div class="row">

                    <div class="col-md-6">

                        <label for="identifier"><?= Lang::get('users.identifier') ?></label>
                        @if($errors->has('identifier'))
                            <span class="errors">{{ $errors->first('identifier') }}</span>
                        @endif
                        <div class="input-group">
                            <?= Form::text('identifier', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="glyphicons tag"></i></span>
                        </div>

                        <label for="firstname"><?= Lang::get('users.firstname') ?></label>
                        @if($errors->has('firstname'))
                            <span class="errors">{{ $errors->first('firstname') }}</span>
                        @endif
                        <div class="input-group">
                            <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="glyphicons user"></i></span>
                        </div>

                        <label for="lastname"><?= Lang::get('users.lastname') ?></label>
                        @if($errors->has('lastname'))
                            <span class="errors">{{ $errors->first('lastname') }}</span>
                        @endif
                        <div class="input-group">
                            <?= Form::text('lastname', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="glyphicons user"></i></span>
                        </div>

                        <div class="row">

                            <div class="col-md-6">

                                <label for="birthday"><?= Lang::get('users.birthday') ?></label>

                                <div class="input-group">
                                    <?= Form::text('birthday', null, array('class' => 'form-control datepicker')) ?>
                                    <span class="input-group-addon"><i class="glyphicons birthday_cake"></i></span>
                                </div>

                                @if($errors->has('birthday'))
                                    <span class="errors">
                                {{ $errors->first('birthday') }}
                            </span>
                                @endif

                            </div>

                            <div class="col-md-6 gender">
                                <div>
                                    <div class="radio-inline">
                                        <label>
                                            <?= Form::radio('male', 1) ?><?= Lang::get('users.male') ?>&nbsp;<i class="glyphicons male"></i>
                                        </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label>
                                            <?= Form::radio('male', 0) ?><?= Lang::get('users.female') ?>&nbsp;<i class="glyphicons female"></i>
                                        </label>
                                    </div>
                                </div>

                                <span class="block errors"><?= $errors->first('male') ?></span>
                            </div>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label for="street"><?= Lang::get('users.street') ?></label>
                        @if($errors->has('street'))
                            <span class="errors">{{ $errors->first('street') }}</span>
                        @endif
                        <div class="input-group">
                            <?= Form::text('street', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="glyphicons road"></i></span>
                        </div>

                        <div class="row">
                            <div class="col-md-4">

                                <label for="postal"><?= Lang::get('users.postal') ?></label>

                                <div class="input-group">
                                    <?= Form::text('postal', null, array('class' => 'form-control')) ?>
                                    <span class="input-group-addon"><i class="glyphicons road"></i></span>
                                </div>
                                @if($errors->has('postal'))
                                    <span class="errors">{{ $errors->first('postal') }}</span>
                                @endif

                            </div>

                            <div class="col-md-8">

                                <label for="city"><?= Lang::get('users.city') ?></label>

                                <div class="input-group">
                                    <?= Form::text('city', null, array('class' => 'form-control')) ?>
                                    <span class="input-group-addon"><i class="glyphicons road"></i></span>
                                </div>
                                @if($errors->has('city'))
                                    <span class="errors">{{ $errors->first('city') }}</span>
                                @endif

                            </div>
                        </div>

                        <label for="email"><?= Lang::get('users.email') ?></label>
                        @if($errors->has('email'))
                            <span class="errors">{{ $errors->first('email') }}</span>
                        @endif
                        <div class="input-group">
                            <?= Form::text('email', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon">@</span>
                        </div>

                        <label for="phone"><?= Lang::get('users.phone') ?></label>

                        @if($errors->has('phone'))
                            <span class="errors">{{ $errors->first('phone') }}</span>
                        @endif

                        <div class="input-group">

                            <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>

                        </div>


                    </div>

                </div>

            </fieldset>


            <fieldset>
                <legend><?= Lang::get('users.dossier_details') ?></legend>
                <div class="row">
                    <div class="col-xs-12 col-md-6">

                        <span class="block errors"><?= $errors->first('mantelzorger_relation') ?></span>

                        <label for="mantelzorger_relation"><?= Lang::get('users.relatie_mantelzorger') ?></label>

                        <?= Form::select('mantelzorger_relation', $relations_mantelzorger, null, array('id' => 'mantelzorger_relation', 'class' => 'form-control')) ?>

                        <label for="mantelzorger_relation_alternate"><?= Lang::get('users.relatie_mantelzorger_alternate') ?></label>

                        <?= Form::text('mantelzorger_relation_alternate', null, array(
                                'id'    => 'mantelzorger_relation_alternate',
                        'class' => 'form-control'
                        )) ?>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label for="woonsituatie">{{ Lang::get('users.woonsituatie') }}</label>

                        <?= Form::select('woonsituatie', $woonsituaties, null, array('id' => 'woonsituatie', 'class' => 'form-control')) ?>

                        <span class="errors">{{$errors->first('woonsituatie')}}</span>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label for="oorzaak_hulpbehoefte"><?= Lang::get('users.oorzaak_hulpbehoefte') ?></label>

                        <?= Form::select('oorzaak_hulpbehoefte', $hulpbehoeftes, null, array('id' => 'oorzaak_hulpbehoefte', 'class' => 'form-control')) ?>

                        <span class="errors">{{$errors->first('oorzaak_hulpbehoefte')}}</span>

                        <label for="oorzaak_hulpbehoefte_alternate"><?= Lang::get('users.oorzaak_hulpbehoefte_alternate') ?></label>

                        <?= Form::text('oorzaak_hulpbehoefte_alternate', null, array(
                                'id'    => 'oorzaak_hulpbehoefte_alternate',
                        'class' => 'form-control'
                        )) ?>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label for="bel_profiel"><?= Lang::get('users.bel_profiel') ?></label>

                        <?= Form::select('bel_profiel', $belprofielen, null, array('id' => 'bel_profiel', 'class' => 'form-control')) ?>
                    </div>
                </div>
            </fieldset>

        </div>

    </div>

    <div>
        <br/>
        <input class="btn btn-primary" type="submit" value="<?= Lang::get('users.save') ?>"/>
    </div>

    <?= Form::close() ?>


@stop