@if($message)
    <p class="alert alert-info">
        {{ $message }}
    </p>
@endif

@if($success)
    <p class="alert alert-success">
        {{ $success }}
    </p>
@endif


@if($error)
    <p class="alert alert-danger">
        {{ $error }}
    </p>
@endif