@props(['placement', 'random' => true, 'class' => ''])

@php
    $ads = $random 
        ? \App\Helpers\AdHelper::getRandomAdByPlacement($placement)
        : \App\Helpers\AdHelper::getAdsByPlacement($placement);
    
    if ($random && !$ads) {
        $ads = null;
    }
@endphp

@if($ads)
    @if($ads instanceof \Illuminate\Support\Collection || is_array($ads))
        @foreach($ads as $ad)
            <div class="ad-placement {{ $class }}" style="margin: 20px 0;">
                {!! \App\Helpers\AdHelper::renderAd($ad) !!}
            </div>
        @endforeach
    @else
        <div class="ad-placement {{ $class }}" style="margin: 20px 0;">
            {!! \App\Helpers\AdHelper::renderAd($ads) !!}
        </div>
    @endif
@endif
