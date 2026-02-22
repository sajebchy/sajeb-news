@php
    $adService = new \App\Services\AdService();
    $sidebarAdCode = $adService->getSidebarAdCode();
@endphp

@if($sidebarAdCode && $adService->showSidebarAds())
    <div class="card shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="ad-container" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                {!! $sidebarAdCode !!}
            </div>
        </div>
    </div>
@endif
