@if($data->count())
    <div class="text-center">

        @if(UI::isMobile())
            <? simple_paginator($data) ?>
        @else
            <?= $data->render() ?>
        @endif

    </div>
@endif