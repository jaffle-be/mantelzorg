@if($data->count())
    <div class="text-center">
        {{ $data->links(Agent::isMobile() ? 'pagination::simple' : null) }}
    </div>
@endif