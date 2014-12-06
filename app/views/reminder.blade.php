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
                <div class="picture col-md-6">
                    <img class="img-responsive" src="/images/sfeerfoto.png" alt="beta-banner"/>
                </div>
                <div class="betaform col-md-6">
                    <form id="reset-form" method="post" class="form-horizontal">

                        <p class='intro reminder'>
                            <?= Lang::get('front.reminder.intro') ?>
                        </p>

                        <p class="form-wrapper">

                            @include('layout.messages')

                            <label for="email"><?= Lang::get('front.reminder.email') ?></label>
                            <?= Form::text('email', null, array('class' => 'form-control'))?>
                            @if (Session::has('error'))
                                <p class="alert alert-danger">{{ trans(Session::get('reason')) }}</p>
                            @elseif (Session::has('success'))
                                <p class="alert alert-info">{{ Lang::get('reminders.success') }} </p>
                            @endif

                        <p class="form-actions text-center">
                            <input class="btn btn-lg btn-primary" type="submit" value="<?= Lang::get('front.reminder.send') ?>"/>
                        </p>

                        </p>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop