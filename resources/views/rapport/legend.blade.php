<!DOCTYPE html>
<html class="pdf">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('/css/master.min.css') }}"/>
    <link href='http://fonts.googleapis.com/css?family=Didact+Gothic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Libre+Baskerville:400,700,400italic' rel='stylesheet'
          type='text/css'>

    <style type="text/css">

        h1, h3 {
            margin-bottom: 15px;
        }

        .page {
            overflow: hidden;
            page-break-after: always;
        }
    </style>

</head>
<body>


<h1>{{ Lang::get('rapport.legend') }}</h1>


@foreach($metas as $meta)

    <div class="page">

        <h3>{{ str_replace(['_id', '_'], ['', ' '], $meta->context) }}</h3>

        <table class="table table-hover table-striped table-responsive">
            <thead>
            <tr>
                <th>
                    {{ Lang::get('rapport.context-value') }}
                </th>
                <th>{{ Lang::get('rapport.context-value-code') }}</th>
            </tr>
            </thead>

            <tbody>

            @foreach($meta->values as $value)
                <tr>
                    <td>{{ $value->value }}</td>
                    <td>{{ $value->id }}</td>
                </tr>

            @endforeach
            </tbody>

        </table>

    </div>
@endforeach


<? $teller = 0 ?>

@foreach($panels as $panel)
    @foreach($panel->questions as $question)

        @if($teller % 2 == 0)
            <div class="page">
                @endif

                <h3>{{ $question->title }}</h3>

                <table class="table table-hover table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>
                            {{ Lang::get('rapport.context-value') }}
                        </th>
                        <th>{{ Lang::get('rapport.context-value-code') }}</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($question->choises as $choise)
                        <tr>
                            <td>{{ $choise->title }}</td>
                            <td>{{ $choise->id }}</td>
                        </tr>

                    @endforeach
                    </tbody>

                </table>

                @if($teller % 2 == 1)
            </div>
        @endif

        <? $teller++ ?>

    @endforeach

@endforeach


</body>
</html>
