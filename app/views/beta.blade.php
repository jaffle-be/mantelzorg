@section('styles')
@stop

@section('scripts')
@stop

@section('page-header')
    <h2>{{ Lang::get('front.beta.sub-header') }}</h2>
@stop

@section('content')

    <div class="beta-wrapper">

        <div class="banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img class="img-responsive" src="/images/sfeer.png" alt="beta-banner"/>
                    </div>
                    <div class="col-md-6">
                        <form method="post" class="form-horizontal">

                            <h3><?= Lang::get('front.beta.intro-intrested') ?></h3>

                            <p>
                                <label for="beta-firstname"><?= Lang::get('front.beta.firstname') ?></label>
                                <span class="error"><?= $errors->first('firstname'); ?></span>
                                <?= Form::text('firstname', null, array('class' => 'form-control')) ?>

                                <label for="beta-lastname"><?= Lang::get('front.beta.lastname') ?></label>
                                <span class="error"><?= $errors->first('lastname'); ?></span>
                                <?= Form::text('lastname', null, array('class' => 'form-control')) ?>


                                <label for="beta-email"><?= Lang::get('front.beta.email') ?></label>
                                <span class="error"><?= $errors->first('email'); ?></span>
                                <?= Form::text('email', null, array('class' => 'form-control')) ?>

                                <label for="beta-organisation"><?= Lang::get('front.beta.organisation') ?></label>
                                <span class="error"><?= $errors->first('organisation') ? Lang::get('front.beta.error-organisation') : ''; ?></span>
                                <?= Form::text('organisation', null, array('class' => 'form-control')) ?>

                                <? if(Session::has('message')):?>
                                <?= Lang::get('front.beta.registration-thanks'); ?>
                                <? endif; ?>

                            <div class="text-center">
                                <input class="btn btn-lg btn-primary" type="submit" value="<?= Lang::get('front.beta.register') ?>"/>
                            </div>
                            </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop