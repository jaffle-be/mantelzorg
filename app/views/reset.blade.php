@section('styles')
<link rel="stylesheet" href="/css/beta.min.css"/>
@stop

@section('scripts')
<!--<script type="text/javascript" src="/js/beta.min.js"></script>-->
@stop

@section('content')

<div class="beta-wrapper">

    <div class="banner">
        <div class="container">
            <h3><?= Lang::get('front.beta.sub-header') ?></h3>
            <div class="row">
                <div class="picture col-md-6">
                    <img class="img-responsive" src="/images/sfeerfoto.png" alt="beta-banner"/>
                </div>
                <div class="betaform col-md-6">
                    <form id="reset-form" action="" method="post" class="form-horizontal">

                        <p class='intro reset'>
                            <?= Lang::get('front.reset.intro') ?>
                        </p>

                        <p class="form-wrapper">

                            <input name="token" type="hidden" value="<?= $token ?>"/>

                            <label for="email"><?= Lang::get('front.reset.email') ?></label>
                            <?= Form::text('email', null, array('class' => 'form-control'))?>

                            <label for="password"><?= Lang::get('front.reset.password') ?></label>
                            <input type="password" name="password" id="password" class="form-control"/>

                            <label for="password_confirmation"><?= Lang::get('front.reset.confirmation') ?></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"/>

                            @include('layout.messages')

                            <p class="form-actions text-center">
                                <input class="btn btn-lg btn-primary" type="submit" value="<?= Lang::get('front.reset.do') ?>"/>
                            </p>

                        </p>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop