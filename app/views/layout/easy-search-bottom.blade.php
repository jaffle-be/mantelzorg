@if($data->count())
    <div class="text-center">
        {{ $data->links(UI::isMobile() ? 'pagination::simple' : null) }}
    </div>
@endif