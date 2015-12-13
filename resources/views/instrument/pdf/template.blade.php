<div class="container">

    @include('instrument.pdf.personals', ['session' => $session])

    @foreach($session->questionnaire->panels as $panel)

        @include('instrument.pdf.panel')

    @endforeach

</div>

