@props(['placement', 'device' => 'desktop', 'limit' => 3])

@php
    $adService = app(\App\Services\AdService::class);
    $ads = $adService->getAdsByPlacement($placement, $device, $limit);
@endphp

@if($ads->isNotEmpty())
    <div class="ad-container ad-{{ $placement }}" {{ $attributes->merge(['class' => 'my-4']) }}>
        @foreach($ads as $ad)
            @if($ad->image_url && $ad->ad_url)
                <div class="ad-wrapper" data-ad-id="{{ $ad->id }}" data-ad-placement="{{ $placement }}">
                    <a href="{{ $ad->full_url }}" 
                       target="_blank" 
                       rel="nofollow noopener" 
                       class="ad-link"
                       title="{{ $ad->alt_text ?? $ad->name }}"
                       onclick="recordAdClick({{ $ad->id }}, '{{ $placement }}')">
                        <img src="{{ asset($ad->image_url) }}" 
                             alt="{{ $ad->alt_text ?? $ad->name }}"
                             class="ad-image"
                             style="max-width: 100%; height: auto; border-radius: 6px;">
                    </a>
                </div>
            @endif
        @endforeach
    </div>

    <script>
    function recordAdClick(adId, placement) {
        fetch('/api/ads/' + adId + '/click', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                placement: placement,
                timestamp: new Date().toISOString()
            })
        }).catch(err => console.error('Error recording ad click:', err));
    }
    </script>

    <style>
        .ad-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .ad-wrapper {
            display: block;
            border-radius: 6px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .ad-wrapper:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .ad-link {
            display: block;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .ad-link:hover {
            opacity: 0.9;
        }

        .ad-image {
            display: block;
            width: 100%;
            height: auto;
        }

        /* Placement-specific styles */
        .ad-homepage_banner {
            margin: 20px 0;
        }

        .ad-homepage_popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            max-width: 300px;
            z-index: 1000;
        }

        .ad-sidebar {
            flex: 1;
        }

        .ad-between_comments {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        @media (max-width: 768px) {
            .ad-homepage_popup {
                max-width: 250px;
                bottom: 10px;
                right: 10px;
            }
        }
    </style>
@endif
