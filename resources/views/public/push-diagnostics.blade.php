@extends('public.layout')

@section('title', 'Push Notification Diagnostics')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-primary mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-stethoscope"></i> Push Notification Diagnostics
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Use this page to verify push notifications are configured correctly.
                        Click each button below to test components.
                    </p>

                    <!-- Step 1: Check VAPID Key -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-key"></i> Step 1: Check VAPID Public Key
                            </h5>
                        </div>
                        <div class="card-body">
                            <p>The VAPID public key should load from the page metadata.</p>
                            <div class="code-block bg-light p-3 rounded mb-3" style="border: 1px solid #ddd; font-family: monospace; word-break: break-all;">
                                <strong>VAPID Key:</strong> <span id="vapid-key-value">Loading...</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkVapidKey()">
                                <i class="fas fa-check"></i> Check VAPID Key
                            </button>
                            <div id="vapid-result" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Step 2: Check Browser Support -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-browser"></i> Step 2: Browser Support
                            </h5>
                        </div>
                        <div class="card-body">
                            <p>Verify your browser supports the necessary APIs.</p>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkBrowserSupport()">
                                <i class="fas fa-check"></i> Check Browser Support
                            </button>
                            <div id="support-result" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Step 3: Check Service Worker -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cog"></i> Step 3: Service Worker Registration
                            </h5>
                        </div>
                        <div class="card-body">
                            <p>The service worker handles background push notifications.</p>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkServiceWorker()">
                                <i class="fas fa-check"></i> Check Service Worker
                            </button>
                            <div id="sw-result" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Step 4: Check PushNotificationManager -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-bell"></i> Step 4: Push Notification Manager
                            </h5>
                        </div>
                        <div class="card-body">
                            <p>The manager handles subscription logic.</p>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkPushManager()">
                                <i class="fas fa-check"></i> Check Push Manager
                            </button>
                            <div id="pm-result" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Test Subscription -->
                    <div class="card mb-3 border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-play"></i> Manual Test: Subscribe
                            </h5>
                        </div>
                        <div class="card-body">
                            <p>If all checks pass, test the actual subscription.</p>
                            <button type="button" class="btn btn-lg btn-success" onclick="testSubscribe()">
                                <i class="fas fa-check-circle"></i> Test Subscribe Button
                            </button>
                            <div id="test-result" class="mt-3"></div>
                        </div>
                    </div>

                    <!-- Troubleshooting -->
                    <div class="accordion mt-4" id="troubleshooting">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    <i class="fas fa-exclamation-circle text-warning me-2"></i> Issue 1: VAPID key is empty
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#troubleshooting">
                                <div class="accordion-body">
                                    <strong>Solution:</strong>
                                    <ol>
                                        <li>Go to Admin Panel → Settings → Push Notifications & Ads</li>
                                        <li>Scroll to "Web Push Notification Configuration"</li>
                                        <li>Paste your VAPID Public Key</li>
                                        <li>Paste your VAPID Private Key</li>
                                        <li>Enable "Enable Push Notifications"</li>
                                        <li>Click Save</li>
                                        <li>Refresh this page</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    <i class="fas fa-exclamation-circle text-warning me-2"></i> Issue 2: Service Worker not registered
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#troubleshooting">
                                <div class="accordion-body">
                                    <strong>Solution:</strong>
                                    <ol>
                                        <li>Open Developer Tools (F12) → Application tab</li>
                                        <li>Check Service Workers section</li>
                                        <li>If empty, refresh the page</li>
                                        <li>Check Console tab for errors</li>
                                        <li>Verify /service-worker.js file exists</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    <i class="fas fa-exclamation-circle text-warning me-2"></i> Issue 3: Permission denied
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#troubleshooting">
                                <div class="accordion-body">
                                    <strong>Solution:</strong>
                                    <ol>
                                        <li><strong>Chrome/Edge:</strong> Click lock icon in address bar → Settings → Notifications → Allow</li>
                                        <li><strong>Firefox:</strong> Click lock icon → Permissions → Allow Notifications</li>
                                        <li><strong>Safari:</strong> Limited support for push notifications</li>
                                        <li>If you see "Block", reset the permission</li>
                                        <li>Refresh and try again</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                    <i class="fas fa-exclamation-circle text-warning me-2"></i> Issue 4: Subscribe button still not working
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#troubleshooting">
                                <div class="accordion-body">
                                    <strong>Debug Steps:</strong>
                                    <ol>
                                        <li>Open Developer Tools (F12) → Console tab</li>
                                        <li>Type: <code>typeof PushNotificationManager</code></li>
                                        <li>If "undefined", the script didn't load</li>
                                        <li>Type: <code>document.querySelector('meta[name="vapid-public-key"]').content</code></li>
                                        <li>If empty, database setting isn't being read</li>
                                        <li>Check for red error messages in Console</li>
                                        <li><strong>Contact support with the error message</strong></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Console Commands -->
                    <div class="alert alert-info mt-4">
                        <h5><i class="fas fa-terminal"></i> Manual Console Commands</h5>
                        <p>Run these in your browser console (F12 → Console):</p>
                        <pre class="bg-light p-2 rounded"><code>// Check VAPID key
document.querySelector('meta[name="vapid-public-key"]').content

// Check Service Worker
navigator.serviceWorker.getRegistrations()

// Check notification permission
Notification.permission

// Subscribe manually
if(typeof PushNotificationManager !== 'undefined') {
    PushNotificationManager.subscribe().then(sub => {
        console.log('Success:', sub);
    }).catch(err => {
        console.error('Error:', err);
    });
}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .code-block {
        font-size: 0.875rem;
        line-height: 1.5;
        max-height: 150px;
        overflow-y: auto;
    }

    .accordion-button {
        font-weight: 500;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f0f0f0;
    }

    pre {
        font-size: 0.85rem;
        margin: 0;
    }

    .result-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        padding: 12px;
        border-radius: .25rem;
        color: #155724;
        margin-top: 10px;
    }

    .result-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        padding: 12px;
        border-radius: .25rem;
        color: #721c24;
        margin-top: 10px;
    }

    .result-info {
        background-color: #d1ecf1;
        border: 1px solid #bee5eb;
        padding: 12px;
        border-radius: .25rem;
        color: #0c5460;
        margin-top: 10px;
    }
</style>

<script>
function checkVapidKey() {
    const vapidMeta = document.querySelector('meta[name="vapid-public-key"]');
    const vapidKey = vapidMeta?.content || '';
    
    document.getElementById('vapid-key-value').textContent = vapidKey || '(empty)';
    
    const resultDiv = document.getElementById('vapid-result');
    if (vapidKey && vapidKey.length > 20) {
        resultDiv.innerHTML = '<div class="result-success"><i class="fas fa-check-circle"></i> <strong>✓ VAPID key is loaded!</strong></div>';
    } else {
        resultDiv.innerHTML = '<div class="result-error"><i class="fas fa-times-circle"></i> <strong>✗ VAPID key is missing!</strong><br>Configure in Admin Settings → Push Notifications & Ads</div>';
    }
}

function checkBrowserSupport() {
    const resultDiv = document.getElementById('support-result');
    const checks = {
        'Service Worker': 'serviceWorker' in navigator,
        'Push API': 'PushManager' in window,
        'Notification API': 'Notification' in window,
        'IndexedDB': 'indexedDB' in window,
        'Cache API': 'caches' in window
    };

    let allSupported = true;
    let html = '';

    for (const [feature, supported] of Object.entries(checks)) {
        const icon = supported ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>';
        const status = supported ? 'Supported' : 'Not Supported';
        html += `<div>${icon} ${feature}: <strong>${status}</strong></div>`;
        if (!supported) allSupported = false;
    }

    if (allSupported) {
        resultDiv.innerHTML = `<div class="result-success"><strong>✓ All APIs supported!</strong><br>${html}</div>`;
    } else {
        resultDiv.innerHTML = `<div class="result-error"><strong>✗ Some APIs not supported!</strong><br>${html}<br><em>Try Chrome, Firefox, or Edge.</em></div>`;
    }
}

function checkServiceWorker() {
    const resultDiv = document.getElementById('sw-result');

    if (!('serviceWorker' in navigator)) {
        resultDiv.innerHTML = '<div class="result-error"><i class="fas fa-times-circle"></i> Service Worker not supported</div>';
        return;
    }

    navigator.serviceWorker.getRegistrations().then(registrations => {
        let found = false;
        let html = '';

        registrations.forEach(reg => {
            html += `<div class="result-info">
                <strong>Registered:</strong> ${reg.scope}<br>
                <strong>State:</strong> ${reg.active ? 'Active' : 'Pending'}
            </div>`;
            found = true;
        });

        if (found) {
            resultDiv.innerHTML = `<div class="result-success"><i class="fas fa-check-circle"></i> <strong>✓ Service Worker registered!</strong><br>${html}</div>`;
        } else {
            resultDiv.innerHTML = '<div class="result-error"><i class="fas fa-times-circle"></i> <strong>No service worker found</strong><br>Refresh the page.</div>';
        }
    }).catch(err => {
        resultDiv.innerHTML = `<div class="result-error"><i class="fas fa-times-circle"></i> Error: ${err.message}</div>`;
    });
}

function checkPushManager() {
    const resultDiv = document.getElementById('pm-result');

    if (typeof PushNotificationManager === 'undefined') {
        resultDiv.innerHTML = '<div class="result-error"><i class="fas fa-times-circle"></i> <strong>PushNotificationManager not loaded!</strong><br>Check if /js/push-notification-manager.js exists.</div>';
    } else if (typeof PushNotificationManager.subscribe === 'function') {
        resultDiv.innerHTML = '<div class="result-success"><i class="fas fa-check-circle"></i> <strong>✓ PushNotificationManager loaded!</strong></div>';
    } else {
        resultDiv.innerHTML = '<div class="result-error"><i class="fas fa-times-circle"></i> <strong>PushNotificationManager incomplete!</strong></div>';
    }
}

function testSubscribe() {
    const resultDiv = document.getElementById('test-result');
    resultDiv.innerHTML = '<div class="result-info"><i class="fas fa-spinner fa-spin"></i> Testing...</div>';

    if (typeof PushNotificationManager === 'undefined') {
        resultDiv.innerHTML = '<div class="result-error"><i class="fas fa-times-circle"></i> Manager not available!</div>';
        return;
    }

    PushNotificationManager.subscribe()
        .then(subscription => {
            console.log('Success:', subscription);
            resultDiv.innerHTML = `<div class="result-success">
                <i class="fas fa-check-circle"></i> <strong>✓ Subscription successful!</strong><br>
                Browser is now subscribed to push notifications
            </div>`;
        })
        .catch(err => {
            console.error('Error:', err);
            resultDiv.innerHTML = `<div class="result-error">
                <i class="fas fa-times-circle"></i> <strong>Subscription failed!</strong><br>
                Error: ${err.message}<br>
                <small>Check console (F12) for details.</small>
            </div>`;
        });
}

document.addEventListener('DOMContentLoaded', function() {
    checkVapidKey();
});
</script>
@endsection
