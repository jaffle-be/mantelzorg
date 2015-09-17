@if($message)

    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ $message }}
    </div>

@endif


@if($success)

    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ $success }}
    </div>

@endif


@if($error)
    <div class="alert alert-danger">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    	{{ $error }}
    </div>
@endif