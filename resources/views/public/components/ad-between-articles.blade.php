@php
    $adService = new \App\Services\AdService();
    $betweenArticlesAdCode = $adService->getBetweenArticlesAdCode();
@endphp

@if($betweenArticlesAdCode && $adService->showBetweenArticlesAds())
    <div class="ad-container my-4" style="text-align: center; padding: 16px 0; background: #fff; border-radius: 8px;">
        {!! $betweenArticlesAdCode !!}
    </div>
@endif
