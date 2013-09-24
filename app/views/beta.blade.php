@section('styles')
<link rel="stylesheet" href="/css/beta.min.css"/>
@stop

@section('title', Lang::get('beta.main-header'))

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
                    <form action="" method="post" class="form-horizontal">

                        <p class='intro'>
                            <?= Lang::get('beta.intro-intrested') ?>
                        </p>

                        <p class="form-wrapper">
                            <label for="beta-firstname"><?= Lang::get('beta.firstname') ?></label>
                            <span class="error"><?= $errors->first('firstname'); ?></span>
                            <input class="form-control" type="text" name="firstname" id="beta-firstname"/>

                            <label for="beta-sirname"><?= Lang::get('beta.sirname') ?></label>
                            <span class="error"><?= $errors->first('sirname'); ?></span>
                            <input class="form-control" type="text" name="sirname" id="beta-sirname"/>


                            <label for="beta-email"><?= Lang::get('beta.email') ?></label>
                            <span class="error"><?= $errors->first('email'); ?></span>
                            <input class="form-control" type="text" name="email" id="beta-email"/>

                            <label for="beta-organisation"><?= Lang::get('beta.organisation') ?></label>
                            <span class="error"><?= $errors->first('organisation') ? Lang::get('beta.error-organisation') : ''; ?></span>
                            <input class="form-control" type="text" name="organisation" id="beta-organisation"/>

                            <? if(isset($success) && $success === true):?>
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