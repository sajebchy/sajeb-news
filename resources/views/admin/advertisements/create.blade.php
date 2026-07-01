@extends('layouts.admin')

@section('page-title', 'Create Advertisement')

@push('styles')
<style>
.ad-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,.07); overflow:hidden; margin-bottom:1.25rem; }
.ad-card-header { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:#fff; padding:12px 18px; display:flex; align-items:center; gap:8px; }
.ad-card-header h5 { margin:0; font-size:15px; color:#fff; font-weight:600; }
.ad-card-header small { color:rgba(255,255,255,.88); font-size:11px; display:block; margin-top:3px; }
.ad-card-body { padding:18px; }
.field-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
.field-label .req { color:#ef4444; }
.field-input {
    width:100%; padding:9px 12px;
    border:1.5px solid #d1d5db; border-radius:8px;
    font-size:14px; background:#fff; color:#111827;
    outline:none; transition:border-color .15s,box-shadow .15s;
    box-sizing:border-box;
}
.field-input:focus { border-color:#667eea; box-shadow:0 0 0 3px rgba(102,126,234,.15); }
.field-input.is-invalid { border-color:#ef4444; }
.invalid-msg { font-size:12px; color:#ef4444; margin-top:4px; }
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
@media(max-width:640px){ .two-col { grid-template-columns:1fr; } }
.form-btn { display:inline-flex; align-items:center; gap:6px; padding:10px 22px; border-radius:8px; font-size:14px; font-weight:600; border:none; cursor:pointer; transition:opacity .15s; }
.form-btn:hover { opacity:.88; }
.btn-submit { background:#004e9f; color:#fff; }
.btn-cancel { background:#6b7280; color:#fff; text-decoration:none; }
.help-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:16px; margin-bottom:1rem; }
.help-card h6 { font-size:13px; font-weight:700; color:#1f2937; margin:0 0 8px; }
.help-card ul { font-size:12px; color:#6b7280; padding-left:16px; margin:0; }
.help-card p { font-size:12px; color:#6b7280; margin:0; }
.section-divider { border:none; border-top:1px solid #e5e7eb; margin:16px 0; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:24px;">
    <h1 style="font-size:20px;font-weight:700;color:#111827;margin:0;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-plus-circle" style="color:#667eea;"></i> Create New Advertisement
    </h1>
    <a href="{{ route('admin.advertisements.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:#f3f4f6;border:1px solid #d1d5db;border-radius:8px;font-size:13px;font-weight:500;color:#374151;text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

{{-- Errors --}}
@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;padding:14px 16px;margin-bottom:16px;font-size:13px;color:#b91c1c;">
    <strong><i class="bi bi-exclamation-circle"></i> Please fix the errors below:</strong>
    <ul style="margin:6px 0 0;padding-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- Two column layout: form left, help right --}}
<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

{{-- ── LEFT: Main Form ── --}}
<div>

    {{-- Basic Information --}}
    <div class="ad-card">
        <div class="ad-card-header">
            <i class="bi bi-info-circle"></i>
            <h5>Basic Information</h5>
        </div>
        <div class="ad-card-body" style="display:flex;flex-direction:column;gap:14px;">

            <div>
                <label class="field-label" for="name">Advertisement Name <span class="req">*</span></label>
                <input class="field-input @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., SoftDrink Banner" required>
                @error('name')<p class="invalid-msg">{{ $message }}</p>@enderror
            </div>

            <div class="two-col">
                <div>
                    <label class="field-label" for="placement">Placement <span class="req">*</span></label>
                    <select class="field-input @error('placement') is-invalid @enderror" id="placement" name="placement" required>
                        <option value="">Select Placement</option>
                        @foreach ($placements as $value => $label)
                            <option value="{{ $value }}" {{ old('placement') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('placement')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="field-label" for="type">Type <span class="req">*</span></label>
                    <select class="field-input @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">Select Type</option>
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="two-col">
                <div>
                    <label class="field-label" for="device_target">Device Target <span class="req">*</span></label>
                    <select class="field-input @error('device_target') is-invalid @enderror" id="device_target" name="device_target" required>
                        @foreach ($deviceTargets as $value => $label)
                            <option value="{{ $value }}" {{ old('device_target') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('device_target')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="field-label" for="ad_source">Ad Source <span class="req">*</span></label>
                    <select class="field-input @error('ad_source') is-invalid @enderror" id="ad_source" name="ad_source" required>
                        <option value="">Select Source</option>
                        @foreach ($adSources as $value => $label)
                            <option value="{{ $value }}" {{ old('ad_source') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('ad_source')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="two-col">
                <div>
                    <label class="field-label" for="ad_type">Ad Type <span class="req">*</span></label>
                    <select class="field-input @error('ad_type') is-invalid @enderror" id="ad_type" name="ad_type" required>
                        <option value="">Select Ad Type</option>
                        @foreach ($adTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('ad_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('ad_type')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="field-label" for="display_order">Display Order</label>
                    <input class="field-input @error('display_order') is-invalid @enderror" type="number" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                    @error('display_order')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
            </div>

        </div>
    </div>

    {{-- Image & Link --}}
    <div class="ad-card">
        <div class="ad-card-header">
            <i class="bi bi-image"></i>
            <h5>Image & Link</h5>
        </div>
        <div class="ad-card-body" style="display:flex;flex-direction:column;gap:14px;">

            <div>
                <label class="field-label" for="image_file"><i class="bi bi-cloud-upload"></i> Upload Image</label>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <input type="file" class="field-input @error('image_file') is-invalid @enderror" id="image_file" name="image_file" accept="image/*" style="flex:1;min-width:0;">
                    <button class="form-btn" type="button" id="upload-btn" style="background:#6366f1;color:#fff;padding:9px 16px;flex-shrink:0;">
                        <i class="bi bi-upload"></i> Upload
                    </button>
                </div>
                @error('image_file')<p class="invalid-msg">{{ $message }}</p>@enderror
                <p style="font-size:12px;color:#9ca3af;margin-top:5px;">Supported: JPG, PNG, GIF, WebP (Max 5MB)</p>
            </div>

            <div id="upload-progress" class="progress" style="display:none;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%"></div>
            </div>
            <div id="upload-message"></div>

            <input type="hidden" id="image_url" name="image_url" value="{{ old('image_url', '') }}">

            <div>
                <label class="field-label" for="alt_text">Image Alt Text</label>
                <input class="field-input @error('alt_text') is-invalid @enderror" type="text" id="alt_text" name="alt_text" value="{{ old('alt_text') }}" placeholder="Descriptive text for accessibility">
                @error('alt_text')<p class="invalid-msg">{{ $message }}</p>@enderror
            </div>

            <div id="image-preview" style="display:none;">
                <label class="field-label">📷 Image Preview:</label>
                <div style="background:#f9fafb;border:1px dashed #d1d5db;border-radius:8px;padding:16px;text-align:center;min-height:160px;display:flex;align-items:center;justify-content:center;">
                    <img id="preview-img" src="" alt="Preview" style="max-width:100%;max-height:300px;border-radius:6px;display:block;margin:0 auto;">
                </div>
            </div>

            <hr class="section-divider">

            <div>
                <label class="field-label" for="ad_url"><i class="bi bi-link-45deg"></i> Destination URL <span class="req">*</span></label>
                <input class="field-input @error('ad_url') is-invalid @enderror" type="url" id="ad_url" name="ad_url" value="{{ old('ad_url') }}" placeholder="https://example.com" required>
                @error('ad_url')<p class="invalid-msg">{{ $message }}</p>@enderror
                <p style="font-size:12px;color:#9ca3af;margin-top:5px;">Where users will be directed when clicking the ad</p>
            </div>

            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;">
                <p style="font-size:12px;font-weight:600;color:#374151;margin:0 0 6px;">Link Preview:</p>
                <div style="font-family:monospace;font-size:11px;color:#6b7280;word-break:break-all;">
                    <span id="link-preview">No URL entered yet</span>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" id="link_open_new_tab" name="link_open_new_tab" value="1" {{ old('link_open_new_tab') ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#667eea;">
                <label for="link_open_new_tab" style="font-size:13px;color:#374151;cursor:pointer;">Open link in new tab</label>
            </div>

        </div>
    </div>

    {{-- Monetization Networks --}}
    <div class="ad-card" id="networks-section" style="display:none;">
        <div class="ad-card-header" style="background:linear-gradient(135deg,#10b981,#059669);">
            <i class="bi bi-graph-up"></i>
            <div>
                <h5>Monetization Networks</h5>
                <small>AdSense & Alternative Networks</small>
            </div>
        </div>
        <div class="ad-card-body" style="display:flex;flex-direction:column;gap:14px;">
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px;font-size:13px;color:#1e40af;">
                <i class="bi bi-info-circle"></i> Choose from Google AdSense or alternative ad networks (Media.net, Ezoic, PropellerAds, etc.)
            </div>
            <div>
                <label class="field-label" for="ad_network">Select Monetization Network</label>
                <select class="field-input @error('ad_network') is-invalid @enderror" id="ad_network" name="ad_network">
                    <option value="">-- Select a network --</option>
                    @foreach ($adNetworks as $key => $label)
                        <option value="{{ $key }}" {{ old('ad_network') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('ad_network')<p class="invalid-msg">{{ $message }}</p>@enderror
            </div>
            <div id="network-fields-container"></div>
            <div id="adsense-warning" style="display:none;background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:12px;font-size:13px;color:#92400e;">
                <i class="bi bi-exclamation-circle"></i> <strong>Google AdSense Policy:</strong>
                <ul style="margin:6px 0 0;padding-left:16px;">
                    <li>Maximum 3 ads per page</li>
                    <li>Minimum 300 words of content</li>
                    <li>Valid publisher ID: pub-XXXXXXXXXXXXXXXX (16 digits)</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- UTM Parameters --}}
    <div class="ad-card">
        <div class="ad-card-header">
            <i class="bi bi-link"></i>
            <div>
                <h5>UTM Parameters</h5>
                <small>Campaign Tracking for Google Analytics</small>
            </div>
        </div>
        <div class="ad-card-body" style="display:flex;flex-direction:column;gap:14px;">
            <div class="two-col">
                <div>
                    <label class="field-label" for="utm_source">UTM Source</label>
                    <input class="field-input" type="text" id="utm_source" name="utm_source" value="{{ old('utm_source') }}" placeholder="e.g., facebook, google">
                    <p style="font-size:11px;color:#9ca3af;margin-top:3px;">Where traffic comes from</p>
                </div>
                <div>
                    <label class="field-label" for="utm_medium">UTM Medium</label>
                    <input class="field-input" type="text" id="utm_medium" name="utm_medium" value="{{ old('utm_medium') }}" placeholder="e.g., cpc, banner">
                    <p style="font-size:11px;color:#9ca3af;margin-top:3px;">Type of promotion</p>
                </div>
            </div>
            <div class="two-col">
                <div>
                    <label class="field-label" for="utm_campaign">UTM Campaign</label>
                    <input class="field-input" type="text" id="utm_campaign" name="utm_campaign" value="{{ old('utm_campaign') }}" placeholder="e.g., winter_sale_2026">
                    <p style="font-size:11px;color:#9ca3af;margin-top:3px;">Campaign name</p>
                </div>
                <div>
                    <label class="field-label" for="utm_term">UTM Term</label>
                    <input class="field-input" type="text" id="utm_term" name="utm_term" value="{{ old('utm_term') }}" placeholder="e.g., soft drinks">
                    <p style="font-size:11px;color:#9ca3af;margin-top:3px;">Keywords</p>
                </div>
            </div>
            <div>
                <label class="field-label" for="utm_content">UTM Content</label>
                <input class="field-input" type="text" id="utm_content" name="utm_content" value="{{ old('utm_content') }}" placeholder="e.g., sidebar_banner">
                <p style="font-size:11px;color:#9ca3af;margin-top:3px;">To differentiate ads</p>
            </div>
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;">
                <p style="font-size:12px;font-weight:600;color:#374151;margin:0 0 6px;">Full URL Preview:</p>
                <div style="font-family:monospace;font-size:11px;color:#6b7280;word-break:break-all;">
                    <span id="url-preview">{{ old('ad_url', 'https://example.com') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Schedule --}}
    <div class="ad-card">
        <div class="ad-card-header">
            <i class="bi bi-calendar-event"></i>
            <h5>Schedule</h5>
        </div>
        <div class="ad-card-body">
            <div class="two-col">
                <div>
                    <label class="field-label" for="start_date">Start Date <span class="req">*</span></label>
                    <input class="field-input @error('start_date') is-invalid @enderror" type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="field-label" for="end_date">End Date <span style="font-size:11px;font-weight:400;color:#9ca3af;">(Optional)</span></label>
                    <input class="field-input @error('end_date') is-invalid @enderror" type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}">
                    @error('end_date')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Advertiser Information --}}
    <div class="ad-card">
        <div class="ad-card-header">
            <i class="bi bi-person-badge"></i>
            <h5>Advertiser Information</h5>
        </div>
        <div class="ad-card-body" style="display:flex;flex-direction:column;gap:14px;">
            <div class="two-col">
                <div>
                    <label class="field-label" for="advertiser_name">Advertiser Name</label>
                    <input class="field-input" type="text" id="advertiser_name" name="advertiser_name" value="{{ old('advertiser_name') }}" placeholder="Company name">
                </div>
                <div>
                    <label class="field-label" for="advertiser_email">Advertiser Email</label>
                    <input class="field-input" type="email" id="advertiser_email" name="advertiser_email" value="{{ old('advertiser_email') }}" placeholder="contact@company.com">
                </div>
            </div>
            <div>
                <label class="field-label" for="advertiser_phone">Advertiser Phone</label>
                <input class="field-input" type="text" id="advertiser_phone" name="advertiser_phone" value="{{ old('advertiser_phone') }}" placeholder="+880-XXXX-XXXXXX">
            </div>
        </div>
    </div>

    {{-- Additional Settings --}}
    <div class="ad-card">
        <div class="ad-card-header">
            <i class="bi bi-sliders"></i>
            <h5>Additional Settings</h5>
        </div>
        <div class="ad-card-body" style="display:flex;flex-direction:column;gap:14px;">
            <div class="two-col">
                <div>
                    <label class="field-label" for="cpc_amount">Cost Per Click (CPC)</label>
                    <div style="display:flex;align-items:center;border:1.5px solid #d1d5db;border-radius:8px;overflow:hidden;">
                        <span style="padding:9px 12px;background:#f9fafb;font-size:14px;color:#6b7280;border-right:1px solid #d1d5db;">৳</span>
                        <input type="number" step="0.01" id="cpc_amount" name="cpc_amount" value="{{ old('cpc_amount') }}" placeholder="0.00" style="flex:1;padding:9px 12px;border:none;outline:none;font-size:14px;">
                    </div>
                </div>
                <div>
                    <label class="field-label" for="cpm_amount">Cost Per 1000 Impressions (CPM)</label>
                    <div style="display:flex;align-items:center;border:1.5px solid #d1d5db;border-radius:8px;overflow:hidden;">
                        <span style="padding:9px 12px;background:#f9fafb;font-size:14px;color:#6b7280;border-right:1px solid #d1d5db;">৳</span>
                        <input type="number" step="0.01" id="cpm_amount" name="cpm_amount" value="{{ old('cpm_amount') }}" placeholder="0.00" style="flex:1;padding:9px 12px;border:none;outline:none;font-size:14px;">
                    </div>
                </div>
            </div>
            <div class="two-col">
                <div>
                    <label class="field-label" for="daily_impression_limit">Daily Impression Limit</label>
                    <input class="field-input" type="number" id="daily_impression_limit" name="daily_impression_limit" value="{{ old('daily_impression_limit') }}" min="1" placeholder="Unlimited">
                </div>
                <div>
                    <label class="field-label" for="max_clicks_per_day">Max Clicks Per Day</label>
                    <input class="field-input" type="number" id="max_clicks_per_day" name="max_clicks_per_day" value="{{ old('max_clicks_per_day') }}" min="1" placeholder="Unlimited">
                </div>
            </div>
            <div>
                <label class="field-label" for="notes">Notes</label>
                <textarea class="field-input" id="notes" name="notes" rows="3" placeholder="Internal notes about this advertisement" style="resize:vertical;">{{ old('notes') }}</textarea>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#667eea;">
                <label for="is_active" style="font-size:13px;color:#374151;cursor:pointer;">Activate this advertisement immediately</label>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap;padding-bottom:20px;">
        <button type="submit" class="form-btn btn-submit" style="flex:1;justify-content:center;">
            <i class="bi bi-check-circle"></i> Create Advertisement
        </button>
        <a href="{{ route('admin.advertisements.index') }}" class="form-btn btn-cancel" style="flex:1;justify-content:center;">
            <i class="bi bi-x-circle"></i> Cancel
        </a>
    </div>

</div>{{-- end left --}}

{{-- ── RIGHT: Help Sidebar ── --}}
<div>
    <div class="help-card">
        <h6><i class="bi bi-map" style="color:#667eea;"></i> Placement Guide</h6>
        <ul>
            <li><strong>Header Top:</strong> Above navigation</li>
            <li><strong>Homepage Top:</strong> Below nav on home</li>
            <li><strong>Sidebar:</strong> Article sidebars</li>
            <li><strong>Within Article:</strong> Inside content</li>
            <li><strong>Footer Banner:</strong> Bottom of page</li>
            <li><strong>Popup:</strong> Overlay popup ad</li>
            <li><strong>Bottom Sticky:</strong> Fixed bottom bar</li>
        </ul>
    </div>
    <div class="help-card">
        <h6><i class="bi bi-graph-up-arrow" style="color:#10b981;"></i> UTM Parameters</h6>
        <p>UTM parameters help track ad performance in Google Analytics. They are automatically appended to your destination URL.</p>
        <hr style="border-color:#e5e7eb;margin:10px 0;">
        <ul>
            <li><strong>utm_source:</strong> Where traffic comes from</li>
            <li><strong>utm_medium:</strong> Type of promotion</li>
            <li><strong>utm_campaign:</strong> Campaign name</li>
            <li><strong>utm_term:</strong> Search keywords</li>
            <li><strong>utm_content:</strong> Ad identifier</li>
        </ul>
    </div>
    <div class="help-card">
        <h6><i class="bi bi-lightbulb" style="color:#f59e0b;"></i> Tips</h6>
        <ul>
            <li>Use JPG for photos, PNG for logos</li>
            <li>728×90 for desktop banners</li>
            <li>320×50 for mobile sticky</li>
            <li>300×250 for sidebar ads</li>
            <li>Always set a start date</li>
        </ul>
    </div>
</div>{{-- end right --}}

</div>{{-- end grid --}}
</form>

{{-- Responsive: stack on mobile --}}
<style>
@media(max-width:900px){
    form > div[style*="grid-template-columns:1fr 300px"] {
        display:flex !important;
        flex-direction:column !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const adSourceSelect = document.getElementById('ad_source');
    const networksSection = document.getElementById('networks-section');

    function updateNetworksSectionVisibility() {
        networksSection.style.display = adSourceSelect.value === 'online' ? 'block' : 'none';
    }
    adSourceSelect.addEventListener('change', updateNetworksSectionVisibility);
    updateNetworksSectionVisibility();

    const networkFieldsConfig = @json($networkFields ?? []);
    const adNetworkSelect = document.getElementById('ad_network');
    const networkFieldsContainer = document.getElementById('network-fields-container');

    function generateNetworkFields() {
        const selectedNetwork = adNetworkSelect.value;
        networkFieldsContainer.innerHTML = '';
        if (!selectedNetwork || !networkFieldsConfig[selectedNetwork]) return;
        const fields = networkFieldsConfig[selectedNetwork];
        let html = '';
        Object.entries(fields).forEach(([key, label]) => {
            const name = `network_${key}`;
            if (key === 'code') {
                html += `<div style="margin-bottom:14px;"><label class="field-label">${label} <span class="req">*</span></label><textarea class="field-input" name="${name}" rows="5" placeholder="Paste your network code here..."></textarea></div>`;
            } else {
                html += `<div style="margin-bottom:14px;"><label class="field-label">${label} <span class="req">*</span></label><input class="field-input" type="text" name="${name}" placeholder="Enter your ${label.toLowerCase()}"></div>`;
            }
        });
        networkFieldsContainer.innerHTML = html;
    }

    adNetworkSelect.addEventListener('change', function() {
        generateNetworkFields();
        document.getElementById('adsense-warning').style.display = this.value === 'adsense' ? 'block' : 'none';
    });

    // Image upload
    const imageFileInput = document.getElementById('image_file');
    const uploadBtn = document.getElementById('upload-btn');
    const uploadProgress = document.getElementById('upload-progress');
    const uploadMessage = document.getElementById('upload-message');
    const previewImg = document.getElementById('preview-img');
    const imagePreview = document.getElementById('image-preview');
    const imageUrlField = document.getElementById('image_url');

    imageFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) { previewImg.src = ev.target.result; imagePreview.style.display = 'block'; };
            reader.readAsDataURL(file);
        }
    });

    uploadBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const file = imageFileInput.files[0];
        if (!file) { uploadMessage.innerHTML = '<div style="color:#92400e;font-size:13px;margin-top:6px;"><i class="bi bi-exclamation-triangle"></i> Please select an image first</div>'; return; }
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        uploadProgress.style.display = 'block';
        uploadBtn.disabled = true;
        uploadMessage.innerHTML = '';
        fetch('{{ route("admin.upload-advertisement-image") }}', { method:'POST', body:formData, headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r => r.json())
        .then(data => {
            uploadProgress.style.display = 'none';
            if (data.success) {
                imageUrlField.value = data.url;
                previewImg.src = '/' + data.url;
                imagePreview.style.display = 'block';
                uploadMessage.innerHTML = '<div style="color:#065f46;font-size:13px;margin-top:6px;"><i class="bi bi-check-circle"></i> Image uploaded! Click Save to keep changes.</div>';
            } else {
                uploadMessage.innerHTML = '<div style="color:#b91c1c;font-size:13px;margin-top:6px;"><i class="bi bi-x-circle"></i> ' + (data.message || 'Upload failed') + '</div>';
            }
            uploadBtn.disabled = false;
        })
        .catch(err => {
            uploadProgress.style.display = 'none';
            uploadMessage.innerHTML = '<div style="color:#b91c1c;font-size:13px;margin-top:6px;"><i class="bi bi-x-circle"></i> Upload error: ' + err.message + '</div>';
            uploadBtn.disabled = false;
        });
    });

    // Link preview
    const linkPreview = document.getElementById('link-preview');
    const adUrlField = document.getElementById('ad_url');
    function updateLinkPreview() {
        linkPreview.textContent = adUrlField.value || 'No URL entered yet';
        linkPreview.style.color = adUrlField.value ? '#374151' : '#9ca3af';
    }
    adUrlField.addEventListener('input', updateLinkPreview);
    updateLinkPreview();

    // UTM URL preview
    function updateUrlPreview() {
        const base = adUrlField.value;
        const params = [];
        ['utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(id => {
            const val = document.getElementById(id).value;
            if (val) params.push(id + '=' + encodeURIComponent(val));
        });
        let full = base;
        if (params.length) full += (full.includes('?') ? '&' : '?') + params.join('&');
        document.getElementById('url-preview').textContent = full || 'https://example.com';
    }
    ['ad_url','utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(id => {
        document.getElementById(id).addEventListener('input', updateUrlPreview);
    });

    if (imageUrlField.value) { previewImg.src = '/' + imageUrlField.value; imagePreview.style.display = 'block'; }
    updateUrlPreview();

    document.querySelector('form').addEventListener('submit', function() {
        if (imageUrlField.value && imageUrlField.value.startsWith('data:image/svg')) imageUrlField.value = '';
    });
});
</script>
@endsection
