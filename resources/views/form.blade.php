<form method="post">

    <h3><?= Lang::get('front.beta.intro-intrested') ?></h3>

    <div class="form-group">
        <label class="control-label" for="beta-firstname"><?= Lang::get('front.beta.firstname') ?></label>
        <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
    </div>

    @error('firstname')

    <div class="form-group">
        <label class="control-label" for="beta-lastname"><?= Lang::get('front.beta.lastname') ?></label>
        <?= Form::text('lastname', null, array('class' => 'form-control')) ?>
    </div>

    @error('lastname')

    <div class="form-group">
        <label class="control-label" for="beta-email"><?= Lang::get('front.beta.email') ?></label>
        <?= Form::text('email', null, array('class' => 'form-control')) ?>
    </div>

    @error('email')

    <div class="form-group">
        <label class="control-label" for="beta-organisation"><?= Lang::get('front.beta.organisation') ?></label>
        <?= Form::text('organisation', null, array('class' => 'form-control')) ?>
    </div>

    @error('organisation')

    <? if (Session::has('message')): ?>
    <div class="alert alert-success">
        {{ Lang::get('front.beta.registration-thanks') }}
    </div>
    <? endif; ?>

    <div class="text-center">
        <button class="btn btn-lg btn-primary" type="submit"><?= Lang::get('front.beta.register') ?></button>
    </div>

</form>