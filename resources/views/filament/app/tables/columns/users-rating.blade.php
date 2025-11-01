@php
    $state = $getState();
@endphp

<div class="flex">
    @if (blank($state))
	    {{ ($this->leaveReviewAction)(['product' => $getRecord()->id]) }}
    @else
        @for($i = 1; $i < 6; $i++)
            <div
                @class([
                    'text-slate-300' => $state < $i,
                    'text-amber-500' => $state >= $i,					
                ])
            >
                <x-icon name="heroicon-s-star" class="w-6 h-6 pointer-events-none"></x-icon>
            </div>
        @endfor
    @endif
</div>
