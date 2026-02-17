@php
    $settings = \App\Models\SeoSetting::first();
    $logoPath = $settings?->logo ? asset('storage/' . $settings->logo) : null;
@endphp

@if($logoPath)
    <img src="{{ $logoPath }}" alt="Logo" class="img-fluid" style="max-height: 60px; width: auto;" {{ $attributes }}>
@else
    <span class="fw-bold text-primary" style="font-size: 1.25rem;">সাজেব নিউজ</span>
@endif
