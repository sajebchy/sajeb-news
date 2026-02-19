@extends('layouts.admin')

@section('page-title', 'Create Advertisement')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">
            <i class="bi bi-plus-circle"></i> Create New Advertisement
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <form action="{{ route('admin.advertisements.store') }}" method="POST" class="needs-validation">
            @csrf

            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Advertisement Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., SoftDrink Banner" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="placement" class="form-label">Placement <span class="text-danger">*</span></label>
                            <select class="form-select @error('placement') is-invalid @enderror" id="placement" name="placement" required>
                                <option value="">Select Placement</option>
                                @foreach ($placements as $value => $label)
                                    <option value="{{ $value }}" {{ old('placement') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('placement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                @foreach ($types as $value => $label)
                                    <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="device_target" class="form-label">Device Target <span class="text-danger">*</span></label>
                            <select class="form-select @error('device_target') is-invalid @enderror" id="device_target" name="device_target" required>
                                @foreach ($deviceTargets as $value => $label)
                                    <option value="{{ $value }}" {{ old('device_target') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('device_target') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="ad_source" class="form-label">Ad Source <span class="text-danger">*</span></label>
                            <select class="form-select @error('ad_source') is-invalid @enderror" id="ad_source" name="ad_source" required>
                                <option value="">Select Ad Source</option>
                                @foreach ($adSources as $value => $label)
                                    <option value="{{ $value }}" {{ old('ad_source') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('ad_source') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ad_type" class="form-label">Ad Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('ad_type') is-invalid @enderror" id="ad_type" name="ad_type" required>
                                <option value="">Select Ad Type</option>
                                @foreach ($adTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('ad_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('ad_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                            @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image & URL -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-image"></i> Image & Link</h5>
                </div>
                <div class="card-body">
                    <!-- Image Upload Section -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h6 class="mb-3"><i class="bi bi-cloud-upload"></i> Image Upload</h6>
                        
                        <div class="mb-3">
                            <label for="image_file" class="form-label">Upload Image <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('image_file') is-invalid @enderror" id="image_file" name="image_file" accept="image/*" placeholder="Choose image...">
                                <button class="btn btn-outline-secondary" type="button" id="upload-btn">Upload</button>
                            </div>
                            @error('image_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> 
                                Supported formats: JPG, PNG, GIF, WebP (Max 5MB)
                            </small>
                        </div>

                        <!-- Upload Progress -->
                        <div id="upload-progress" class="progress" style="display: none;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>

                        <!-- Upload Status Message -->
                        <div id="upload-message" class="mt-2"></div>
                    </div>

                    <!-- Image URL (Auto or Manual) -->
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/ad-image.jpg" required>
                        @error('image_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-lightbulb"></i> Auto-filled after upload or paste URL directly
                        </small>
                    </div>

                    <!-- Image Alt Text -->
                    <div class="mb-3">
                        <label for="alt_text" class="form-label">Image Alt Text</label>
                        <input type="text" class="form-control @error('alt_text') is-invalid @enderror" id="alt_text" name="alt_text" value="{{ old('alt_text') }}" placeholder="Descriptive text for accessibility">
                        @error('alt_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Image Preview -->
                    <div id="image-preview" class="mb-3" style="display: none;">
                        <label>Image Preview:</label>
                        <div class="border rounded p-3" style="max-width: 300px; background: #f8f9fa;">
                            <img id="preview-img" src="" alt="Preview" style="max-width: 100%; max-height: 200px;">
                        </div>
                    </div>

                    <!-- Link/URL Section -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="mb-3"><i class="bi bi-link-45deg"></i> Destination Link</h6>
                        
                        <div class="mb-3">
                            <label for="ad_url" class="form-label">Destination URL <span class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('ad_url') is-invalid @enderror" id="ad_url" name="ad_url" value="{{ old('ad_url') }}" placeholder="https://example.com" required>
                            @error('ad_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> 
                                Where users will be directed when clicking the ad. Include http:// or https://
                            </small>
                        </div>

                        <!-- Link Preview with UTM Parameters -->
                        <div class="alert alert-light border">
                            <label class="form-label"><small><strong>Link Preview:</strong></small></label>
                            <div class="p-2 bg-white rounded border mt-1" style="font-size: 12px; word-break: break-all; font-family: monospace; min-height: 40px;">
                                <span id="link-preview" class="text-muted">No URL entered yet</span>
                            </div>
                        </div>

                        <!-- Open in New Tab Option -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="link_open_new_tab" name="link_open_new_tab" value="1" {{ old('link_open_new_tab') ? 'checked' : '' }}>
                            <label class="form-check-label" for="link_open_new_tab">
                                Open link in new tab
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Google AdSense Section (Conditional - Merged with Networks) -->
            <div class="card mb-4" id="networks-section">
                <div class="card-header bg-success bg-opacity-10">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up"></i> Monetization Networks
                        <span class="badge bg-success float-end">AdSense & Alternative Networks</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> <strong>Monetization Options:</strong> Choose from Google AdSense or 11 alternative ad networks (Media.net, Ezoic, PropellerAds, Mediavine, and more).
                    </div>

                    <div class="mb-3">
                        <label for="ad_network" class="form-label">Select Monetization Network <span class="text-danger">*</span></label>
                        <select class="form-select @error('ad_network') is-invalid @enderror" id="ad_network" name="ad_network">
                            <option value="">-- Select a network --</option>
                            @foreach ($adNetworks as $key => $label)
                                <option value="{{ $key }}" {{ old('ad_network') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('ad_network') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Dynamic Network Fields Container -->
                    <div id="network-fields-container"></div>

                    <!-- AdSense-specific policy warning (shown when adsense selected) -->
                    <div id="adsense-warning" style="display: none;" class="alert alert-warning mb-3">
                        <i class="bi bi-exclamation-circle"></i> <strong>Google AdSense Policy:</strong> 
                        <ul class="mb-0 mt-2 small">
                            <li><strong>Maximum 3 ads per page</strong> - Do not exceed this limit</li>
                            <li><strong>Minimum 300 words of content</strong> - Page must have sufficient content</li>
                            <li><strong>Valid publisher ID format:</strong> pub-XXXXXXXXXXXXXXXX (16 digits)</li>
                        </ul>
                    </div>
                </div>
            </div>
                            @php
                                $allNetworks = $adNetworks ?? [];
                                // Exclude 'adsense' as it has its own section
                                $networks = array_filter($allNetworks, fn($k) => $k !== 'adsense', ARRAY_FILTER_USE_KEY);
                            @endphp
                            @foreach ($networks as $value => $label)
                                <option value="{{ $value }}" {{ old('ad_network') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('ad_network') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Dynamic Network Fields Container -->
                    <div id="network-fields-container"></div>

                    <div class="alert alert-warning small">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Network Requirements:</strong> 
                        Each ad network has specific configuration requirements. Fill in all required fields before saving.
                    </div>
                </div>
            </div>

            <!-- UTM Parameters (UTM Builder) -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-link"></i> UTM Parameters (Campaign Tracking)
                    </h5>
                    <small class="text-muted">Add UTM parameters to track your advertisement performance in Google Analytics</small>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-lightbulb"></i> <strong>UTM Builder:</strong> These parameters will be automatically appended to your ad URL for tracking purposes.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="utm_source" class="form-label">UTM Source</label>
                            <input type="text" class="form-control @error('utm_source') is-invalid @enderror" id="utm_source" name="utm_source" value="{{ old('utm_source') }}" placeholder="e.g., facebook, google, newsletter">
                            <small class="text-muted">The referrer: e.g., facebook, google, newsletter</small>
                            @error('utm_source') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="utm_medium" class="form-label">UTM Medium</label>
                            <input type="text" class="form-control @error('utm_medium') is-invalid @enderror" id="utm_medium" name="utm_medium" value="{{ old('utm_medium') }}" placeholder="e.g., cpc, banner, email">
                            <small class="text-muted">The marketing medium: e.g., cpc, banner, email</small>
                            @error('utm_medium') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="utm_campaign" class="form-label">UTM Campaign</label>
                            <input type="text" class="form-control @error('utm_campaign') is-invalid @enderror" id="utm_campaign" name="utm_campaign" value="{{ old('utm_campaign') }}" placeholder="e.g., winter_sale_2026">
                            <small class="text-muted">The campaign name: e.g., winter_sale_2026</small>
                            @error('utm_campaign') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="utm_term" class="form-label">UTM Term (Keywords)</label>
                            <input type="text" class="form-control @error('utm_term') is-invalid @enderror" id="utm_term" name="utm_term" value="{{ old('utm_term') }}" placeholder="e.g., soft drinks, beverage">
                            <small class="text-muted">Keywords: e.g., soft drinks, beverage</small>
                            @error('utm_term') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="utm_content" class="form-label">UTM Content</label>
                        <input type="text" class="form-control @error('utm_content') is-invalid @enderror" id="utm_content" name="utm_content" value="{{ old('utm_content') }}" placeholder="e.g., sidebar_banner, footer_link">
                        <small class="text-muted">To differentiate ads: e.g., sidebar_banner, footer_link</small>
                        @error('utm_content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- URL Preview -->
                    <div class="mt-3 p-3 bg-light rounded">
                        <label class="font-weight-bold">Complete URL Preview:</label>
                        <div class="p-2 bg-white rounded border mt-2" style="font-size: 12px; word-break: break-all; font-family: monospace;">
                            <span id="url-preview">{{ old('ad_url', 'https://example.com') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Schedule</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date (Optional)</label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advertiser Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Advertiser Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="advertiser_name" class="form-label">Advertiser Name</label>
                            <input type="text" class="form-control @error('advertiser_name') is-invalid @enderror" id="advertiser_name" name="advertiser_name" value="{{ old('advertiser_name') }}" placeholder="Company name">
                            @error('advertiser_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="advertiser_email" class="form-label">Advertiser Email</label>
                            <input type="email" class="form-control @error('advertiser_email') is-invalid @enderror" id="advertiser_email" name="advertiser_email" value="{{ old('advertiser_email') }}" placeholder="contact@company.com">
                            @error('advertiser_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="advertiser_phone" class="form-label">Advertiser Phone</label>
                        <input type="text" class="form-control @error('advertiser_phone') is-invalid @enderror" id="advertiser_phone" name="advertiser_phone" value="{{ old('advertiser_phone') }}" placeholder="+880-XXXX-XXXXXX">
                        @error('advertiser_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-sliders"></i> Additional Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cpc_amount" class="form-label">Cost Per Click (CPC)</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" class="form-control @error('cpc_amount') is-invalid @enderror" id="cpc_amount" name="cpc_amount" value="{{ old('cpc_amount') }}" placeholder="0.00">
                            </div>
                            @error('cpc_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cpm_amount" class="form-label">Cost Per 1000 Impressions (CPM)</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" class="form-control @error('cpm_amount') is-invalid @enderror" id="cpm_amount" name="cpm_amount" value="{{ old('cpm_amount') }}" placeholder="0.00">
                            </div>
                            @error('cpm_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="daily_impression_limit" class="form-label">Daily Impression Limit</label>
                            <input type="number" class="form-control @error('daily_impression_limit') is-invalid @enderror" id="daily_impression_limit" name="daily_impression_limit" value="{{ old('daily_impression_limit') }}" min="1" placeholder="Leave empty for unlimited">
                            @error('daily_impression_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="max_clicks_per_day" class="form-label">Max Clicks Per Day</label>
                            <input type="number" class="form-control @error('max_clicks_per_day') is-invalid @enderror" id="max_clicks_per_day" name="max_clicks_per_day" value="{{ old('max_clicks_per_day') }}" min="1" placeholder="Leave empty for unlimited">
                            @error('max_clicks_per_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Internal notes about this advertisement">{{ old('notes') }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Activate this advertisement immediately
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Advertisement
                </button>
                <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Sidebar Help -->
    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-question-circle"></i> Help</h5>
            </div>
            <div class="card-body">
                <h6>Placement Guide</h6>
                <ul class="small">
                    <li><strong>Within News:</strong> Inside article content</li>
                    <li><strong>Homepage Banner:</strong> Top of homepage</li>
                    <li><strong>Homepage Popup:</strong> Popup dialog on homepage</li>
                    <li><strong>Category Page:</strong> Category listing pages</li>
                </ul>

                <hr>

                <h6>UTM Parameters</h6>
                <p class="small text-muted">
                    UTM parameters help you track the performance of your ads in Google Analytics. They are automatically appended to your destination URL.
                </p>

                <h6>All Fields Reference</h6>
                <ul class="small">
                    <li><strong>utm_source:</strong> Where traffic comes from</li>
                    <li><strong>utm_medium:</strong> Type of promotion</li>
                    <li><strong>utm_campaign:</strong> Campaign name</li>
                    <li><strong>utm_term:</strong> Search keywords</li>
                    <li><strong>utm_content:</strong> Ad identifier</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageUrlInput = document.getElementById('image_url');
    const previewDiv = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    imageUrlInput.addEventListener('change', function() {
        if (this.value) {
            previewImg.src = this.value;
            previewDiv.style.display = 'block';
        } else {
            previewDiv.style.display = 'none';
        }
    });

    // Handle Ad Source change (Offline vs Online)
    const adSourceSelect = document.getElementById('ad_source');
    const networksSection = document.getElementById('networks-section');

    function updateNetworksSectionVisibility() {
        const selectedSource = adSourceSelect.value;
        
        if (selectedSource === 'online') {
            networksSection.style.display = 'block';
        } else {
            networksSection.style.display = 'none';
        }
    }

    adSourceSelect.addEventListener('change', updateNetworksSectionVisibility);

    // Network fields configuration
    const networkFieldsConfig = @json($networkFields ?? []);
    const adNetworkSelect = document.getElementById('ad_network');

    const networkFieldsContainer = document.getElementById('network-fields-container');

    function generateNetworkFields() {
        const selectedNetwork = adNetworkSelect.value;
        networkFieldsContainer.innerHTML = '';

        if (!selectedNetwork || !networkFieldsConfig[selectedNetwork]) {
            return;
        }

        const fields = networkFieldsConfig[selectedNetwork];
        let fieldsHTML = '';

        Object.entries(fields).forEach(([fieldKey, fieldLabel]) => {
            const fieldName = `network_${fieldKey}`;
            const oldValue = document.querySelector(`input[name="${fieldName}"]`)?.value || '';
            
            if (fieldKey === 'code') {
                // Show textarea for code fields
                fieldsHTML += `
                    <div class="mb-3">
                        <label for="${fieldName}" class="form-label">${fieldLabel} <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="${fieldName}" name="${fieldName}" rows="5" placeholder="Paste your network code here...">${oldValue}</textarea>
                        <small class="text-muted d-block mt-1">Complete script code from your ${selectedNetwork.replace(/_/g, ' ')} account</small>
                    </div>
                `;
            } else {
                // Show text input for other fields
                fieldsHTML += `
                    <div class="mb-3">
                        <label for="${fieldName}" class="form-label">${fieldLabel} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="${fieldName}" name="${fieldName}" value="${oldValue}" placeholder="Enter your ${fieldLabel.toLowerCase()}">
                        <small class="text-muted">From your ${selectedNetwork.replace(/_/g, ' ')} account settings</small>
                    </div>
                `;
            }
        });

        networkFieldsContainer.innerHTML = fieldsHTML;
    }

    adNetworkSelect.addEventListener('change', function() {
        generateNetworkFields();
        
        // Show/hide AdSense warning
        const adsenseWarning = document.getElementById('adsense-warning');
        if (this.value === 'adsense') {
            adsenseWarning.style.display = 'block';
        } else {
            adsenseWarning.style.display = 'none';
        }
    });

    // Handle Image File Upload
    const imageFileInput = document.getElementById('image_file');
    const uploadBtn = document.getElementById('upload-btn');
    const uploadProgress = document.getElementById('upload-progress');
    const uploadMessage = document.getElementById('upload-message');
    const previewImg = document.getElementById('preview-img');
    const imagePreview = document.getElementById('image-preview');
    const imageUrlField = document.getElementById('image_url');

    // Show preview when file is selected
    imageFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show local preview
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);

            uploadBtn.disabled = false;
            uploadBtn.classList.remove('btn-outline-secondary');
            uploadBtn.classList.add('btn-primary');
        }
    });

    // Upload image via AJAX
    uploadBtn.addEventListener('click', function() {
        const file = imageFileInput.files[0];
        if (!file) {
            uploadMessage.innerHTML = '<div class="alert alert-warning">Please select an image first</div>';
            return;
        }

        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        uploadProgress.style.display = 'block';
        uploadBtn.disabled = true;
        uploadMessage.innerHTML = '';

        fetch('{{ route("admin.upload-advertisement-image") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            uploadProgress.style.display = 'none';
            
            if (data.success) {
                imageUrlField.value = data.url;
                uploadMessage.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> Image uploaded successfully!</div>';
                uploadBtn.classList.remove('btn-primary');
                uploadBtn.classList.add('btn-outline-secondary');
                uploadBtn.disabled = false;
            } else {
                uploadMessage.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> ' + (data.message || 'Upload failed') + '</div>';
                uploadBtn.disabled = false;
            }
        })
        .catch(error => {
            uploadProgress.style.display = 'none';
            uploadMessage.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Upload error: ' + error.message + '</div>';
            uploadBtn.disabled = false;
        });
    });

    // Handle Ad Type change - show/hide Image & URL fields
    const adTypeSelect = document.getElementById('ad_type');
    const altTextField = document.getElementById('alt_text');
    const adUrlField = document.getElementById('ad_url');

    function updateImageFieldsVisibility() {
        const adType = adTypeSelect.value;
        
        if (adType) {
            // Ad type selected - show image fields
            imageUrlField.parentElement.style.display = 'block';
            altTextField.parentElement.parentElement.style.display = 'block';
            adUrlField.parentElement.style.display = 'block';
            imageUrlField.required = true;
        } else {
            // No ad type selected - hide image fields
            imageUrlField.parentElement.style.display = 'none';
            altTextField.parentElement.parentElement.style.display = 'none';
            adUrlField.parentElement.style.display = 'none';
            imageUrlField.required = false;
        }
    }

    adTypeSelect.addEventListener('change', updateImageFieldsVisibility);

    // Link preview update
    const linkPreview = document.getElementById('link-preview');
    function updateLinkPreview() {
        const url = adUrlField.value;
        if (url) {
            linkPreview.textContent = url;
            linkPreview.classList.remove('text-muted');
        } else {
            linkPreview.textContent = 'No URL entered yet';
            linkPreview.classList.add('text-muted');
        }
    }

    adUrlField.addEventListener('input', updateLinkPreview);

    // Initialize on page load
    updateImageFieldsVisibility();
    updateNetworksSectionVisibility();
    if (adNetworkSelect.value) {
        generateNetworkFields();
        if (adNetworkSelect.value === 'adsense') {
            document.getElementById('adsense-warning').style.display = 'block';
        }
    }
    updateLinkPreview();

    // URL preview with UTM parameters
    function updateUrlPreview() {
        const baseUrl = document.getElementById('ad_url').value;
        const utm_source = document.getElementById('utm_source').value;
        const utm_medium = document.getElementById('utm_medium').value;
        const utm_campaign = document.getElementById('utm_campaign').value;
        const utm_term = document.getElementById('utm_term').value;
        const utm_content = document.getElementById('utm_content').value;

        let fullUrl = baseUrl;
        const params = [];

        if (utm_source) params.push('utm_source=' + encodeURIComponent(utm_source));
        if (utm_medium) params.push('utm_medium=' + encodeURIComponent(utm_medium));
        if (utm_campaign) params.push('utm_campaign=' + encodeURIComponent(utm_campaign));
        if (utm_term) params.push('utm_term=' + encodeURIComponent(utm_term));
        if (utm_content) params.push('utm_content=' + encodeURIComponent(utm_content));

        if (params.length > 0) {
            fullUrl += (fullUrl.includes('?') ? '&' : '?') + params.join('&');
        }

        document.getElementById('url-preview').textContent = fullUrl;
    }

    ['ad_url', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'].forEach(id => {
        document.getElementById(id).addEventListener('input', updateUrlPreview);
    });

    // Initial preview
    if (imageUrlInput.value) {
        previewImg.src = imageUrlInput.value;
        previewDiv.style.display = 'block';
    }
    updateUrlPreview();
});
</script>

<style>
.card {
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0 !important;
    border: none;
    padding: 15px;
}

.card-header h5 {
    color: white;
    margin-bottom: 0;
}

.card-header small {
    color: rgba(255, 255, 255, 0.9);
    display: block;
    margin-top: 5px;
}

.form-label {
    font-weight: 600;
    margin-bottom: 8px;
}

.text-danger {
    color: #dc3545 !important;
}

.btn-primary, .btn-secondary {
    padding: 10px 20px;
    font-weight: 600;
}
</style>
@endsection
