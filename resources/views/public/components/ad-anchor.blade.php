@php
    $adService = new \App\Services\AdService();
    $anchorAdCode = $adService->getAnchorAdCode();
@endphp

@if($anchorAdCode && $adService->showAnchorAds())
    <div class="container-fluid px-0" style="background: #f8f8f7;">
        <div class="ad-container" style="padding: 8px 0;">
            {!! $anchorAdCode !!}
        </div>
    </div>
@endif
