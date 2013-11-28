@section('styles')
<link rel="stylesheet" href="<?= asset('css/users.min.css') ?>"/>
@stop

@section('content')


<?= Form::model($mantelzorger, array('route' => array('instellingen.{hulpverlener}.mantelzorgers.update', $hulpverlener->id, $mantelzorger->id))) ?>


<?= Form::close(); ?>


@stop