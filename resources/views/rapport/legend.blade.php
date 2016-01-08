<!DOCTYPE html>
<html class="pdf">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('/css/master.min.css') }}"/>
    <link href='http://fonts.googleapis.com/css?family=Didact+Gothic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Libre+Baskerville:400,700,400italic' rel='stylesheet'
          type='text/css'>

</head>
<body>


<h1>{{ Lang::get('report.legend') }}</h1>


@foreach($metas as $meta)

    <h3>{{ str_replace('_id', '', $meta->context) }}</h3>

    <table class="table table-hover table-striped table-responsive">
        <thead>
        <tr>
            <th>
                {{ Lang::get('report.context-value') }}
            </th>
            <th>{{ Lang::get('report.context-value-code') }}</th>
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
@endforeach


</body>
</html>
