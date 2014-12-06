@section('styles')
    <link rel="stylesheet" href="/css/beta.min.css"/>
@stop

@section('scripts')
    <!--<script type="text/javascript" src="/js/beta.min.js"></script>-->
@stop

@section('page-header')
    <h2><?= Lang::get('front.beta.sub-header') ?></h2>
@stop

@section('content')

    <div class="beta-wrapper">

        <div class="banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img class="img-responsive" src="/images/sfeerfoto.png" alt="beta-banner"/>
                    </div>
                    <div class="col-md-6">
                        <form method="post" class="form-horizontal">

                            <h3><?= Lang::get('front.reset.title') ?></h3>

                            <p>
                                <?= Lang::get('front.reset.intro') ?>
                            </p>

                            <p>
                                <input name="token" type="hidden" value="<?= $token ?>"/>

                                <label for="email"><?= Lang::get('front.reset.email') ?></label>
                                <?= Form::text('email', null, array('class' => 'form-control'))?>

                                <label for="password"><?= Lang::get('front.reset.password') ?></label>
                                <input type="password" name="password" id="password" class="form-control"/>

                                <label for="password_confirmation"><?= Lang::get('front.reset.confirmation') ?></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"/>

                                @include('layout.messages')

                            </p>

                            <p class="text-center">
                                <input class="btn btn-lg btn-primary" type="submit" value="<?= Lang::get('front.reset.do') ?>"/>
                            </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop