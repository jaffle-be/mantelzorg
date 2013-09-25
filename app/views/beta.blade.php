@section('styles')
<link rel="stylesheet" href="/css/beta.min.css"/>
@stop

@section('scripts')
<script type="text/javascript" src="/js/beta.min.js"></script>
@stop

@section('content')

<div class="beta-wrapper">

    <div class="banner">
        <div class="container">
            <h3><?= Lang::get('beta.sub-header') ?></h3>
            <div class="row">
                <div class="picture col-md-6">
                    <img class="img-responsive" src="/img/sfeerfoto.png" alt="beta-banner"/>
                </div>
                <div class="betaform col-md-6">
                    <form id="beta-registration" action="" method="post" class="form-horizontal">

                        <p class='intro'>
                            <?= Lang::get('beta.intro-intrested') ?>
                        </p>

                        <p class="form-wrapper">
                            <label for="beta-firstname"><?= Lang::get('beta.firstname') ?></label>
                            <span class="error"><?= $errors->first('firstname'); ?></span>
                            <?= Form::text('firstname', null, array('class' => 'form-control')) ?>

                            <label for="beta-lastname"><?= Lang::get('beta.lastname') ?></label>
                            <span class="error"><?= $errors->first('lastname'); ?></span>
                            <?= Form::text('lastname', null, array('class' => 'form-control')) ?>


                            <label for="beta-email"><?= Lang::get('beta.email') ?></label>
                            <span class="error"><?= $errors->first('email'); ?></span>
                            <?= Form::text('email', null, array('class' => 'form-control')) ?>

                            <label for="beta-organisation"><?= Lang::get('beta.organisation') ?></label>
                            <span class="error"><?= $errors->first('organisation') ? Lang::get('beta.error-organisation') : ''; ?></span>
                            <?= Form::text('organisation', null, array('class' => 'form-control')) ?>

                            <? if(Session::has('message')):?>
                                <?= Lang::get('beta.registration-thanks'); ?>
                            <? endif; ?>

                            <div class="form-actions text-center">
                                <input class="btn btn-lg btn-primary" type="submit" value="<?= Lang::get('beta.register') ?>"/>
                            </div>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop