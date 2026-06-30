@extends('layouts.app')

@section('title', $stream->title . ' - Live')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        {{-- Main Stream Area --}}
        <div class="col-lg-8">
            {{-- Stream Player --}}
            <div class="card shadow mb-4" style="background-color: #000;" id="player-container">
                <div class="ratio ratio-16x9" id="stream-wrapper">
                    {{-- HLS Stream Embed (replace with your streaming server) --}}
                    <iframe id="stream-player" src="{{ $stream->stream_url ? 'about:blank' : '' }}" 
                            allow="autoplay" allowfullscreen style="border: none;"></iframe>
                </div>

                {{-- Player Controls Bar --}}
                <div class="bg-dark p-2 border-top border-secondary d-flex align-items-center justify-content-between flex-wrap gap-2">
                    {{-- Left Controls (Volume) --}}
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-light" id="volume-decrease" title="Decrease Volume">
                            <i class="fas fa-volume-minus"></i>
                        </button>
                        
                        <input type="range" id="volume-slider" class="form-range" min="0" max="100" value="100" 
                               style="width: 120px; height: 5px;" title="Volume Control">
                        
                        <button type="button" class="btn btn-sm btn-outline-light" id="volume-increase" title="Increase Volume">
                            <i class="fas fa-volume-up"></i>
                        </button>
                        
                        <span id="volume-label" class="text-light small ms-2" style="min-width: 35px;">100%</span>
                    </div>

                    {{-- Right Controls (Fullscreen) --}}
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-light" id="fullscreen-btn" title="Fullscreen">
                            <i class="fas fa-expand"></i> Fullscreen
                        </button>
                    </div>
                </div>

                {{-- Status Indicator --}}
                <div class="card-footer bg-dark text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            @if($stream->isLive())
                                <span class="badge bg-danger"><i class="fas fa-circle animate-pulse"></i> LIVE NOW</span>
                            @elseif($stream->isScheduled())
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Starts {{ $stream->scheduled_at->diffForHumans() }}</span>
                            @elseif($stream->hasEnded())
                                <span class="badge bg-secondary"><i class="fas fa-check"></i> Stream Ended</span>
                                <small class="text-muted ms-2">Duration: {{ $stream->getFormattedDuration() }}</small>
                            @endif
                        </div>
                        <div class="col-auto text-end">
                            <small class="text-muted">
                                <i class="fas fa-eye"></i> {{ number_format($stream->view_count) }} views
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stream Info --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-3">{{ $stream->title }}</h3>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="text-muted small">Streamed by</div>
                                <a href="{{ route('author.show', $stream->user->id) }}" class="text-decoration-none">
                                    @if($stream->user->avatar)
                                        <img src="{{ asset('storage/' . $stream->user->avatar) }}" alt="{{ $stream->user->name }}" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;" class="me-2">
                                    @else
                                        <i class="fas fa-user-circle fa-2x me-2"></i>
                                    @endif
                                    <span class="fw-bold">{{ $stream->user->name }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="mb-3">
                                <div class="text-muted small">{{ $stream->created_at->format('M d, Y H:i') }}</div>
                                <small class="text-muted">{{ $stream->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    @if($stream->description)
                        <hr>
                        <div class="mb-3">
                            <h6 class="mb-2">Description</h6>
                            <p class="text-muted mb-0">{{ $stream->description }}</p>
                        </div>
                    @endif

                    {{-- Category & Tags --}}
                    <div class="row">
                        @if($stream->category)
                            <div class="col-md-6">
                                <h6 class="mb-2">Category</h6>
                                <p class="mb-0">
                                    <span class="badge bg-secondary">{{ $stream->category }}</span>
                                </p>
                            </div>
                        @endif
                        @if($stream->stream_tags)
                            <div class="col-md-6">
                                <h6 class="mb-2">Tags</h6>
                                <div>
                                    @foreach($stream->stream_tags as $tag)
                                        <span class="badge bg-light text-dark">#{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Comments Section --}}
            @if($stream->allow_comments)
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-comments"></i> Comments (<span id="comment-count">0</span>)</h5>
                    </div>
                    <div class="card-body">
                        {{-- Comment Form --}}
                        <div class="mb-4">
                            <h6 class="mb-3">Leave a Comment</h6>
                            <form id="comment-form" class="needs-validation" novalidate>
                                @csrf
                                <div class="mb-3">
                                    <div id="facebook-login-area" class="text-center mb-3">
                                        <button type="button" class="btn btn-primary" id="facebook-login-btn" onclick="loginWithFacebook()">
                                            <i class="fab fa-facebook"></i> Login with Facebook to Comment
                                        </button>
                                    </div>
                                    <div id="user-info-area" style="display: none;" class="alert alert-info mb-3">
                                        <div class="d-flex align-items-center">
                                            <img id="user-avatar" src="" alt="Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            <div>
                                                <strong id="user-name"></strong><br>
                                                <small id="user-email" class="text-muted"></small>
                                                <button type="button" class="btn btn-link btn-sm" onclick="logoutFacebook()" style="padding: 0; margin-left: 10px;">Logout</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <textarea class="form-control" id="comment-text" name="comment_text" rows="3" 
                                              placeholder="What do you think about this stream?" required disabled></textarea>
                                    <div class="invalid-feedback">Please provide a comment.</div>
                                </div>

                                <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                                    <i class="fas fa-paper-plane"></i> Post Comment
                                </button>
                            </form>
                        </div>

                        <hr>

                        {{-- Comments List --}}
                        <div id="comments-container">
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-spinner fa-spin"></i> Loading comments...
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Facebook SDK --}}
                <div id="fb-root"></div>
                
                <script>
                    window.fbAsyncInit = function() {
                        FB.init({
                            appId: '{{ config("social.facebook.app_id") }}',
                            xfbml: true,
                            version: '{{ config("social.facebook.app_version", "v18.0") }}'
                        });

                        // Check login status
                        FB.getLoginStatus(function(response) {
                            if (response.status === 'connected') {
                                handleFacebookLogin(response);
                            }
                        });
                    };

                    // Load FB SDK
                    (function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "{{ config('social.facebook.sdk_url') }}";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));

                    function loginWithFacebook() {
                        FB.login(function(response) {
                            if (response.authResponse) {
                                handleFacebookLogin(response);
                            }
                        }, {scope: 'email,public_profile'});
                    }

                    function handleFacebookLogin(response) {
                        if (response.status === 'connected') {
                            FB.api('/me', {fields: 'id,name,email,picture'}, function(user) {
                                // Store user data in localStorage
                                localStorage.setItem('fbUser', JSON.stringify({
                                    id: user.id,
                                    name: user.name,
                                    email: user.email,
                                    avatar: user.picture.data.url,
                                    accessToken: response.authResponse.accessToken
                                }));

                                // Update UI
                                updateUserUI(user);
                            });
                        }
                    }

                    function updateUserUI(user) {
                        document.getElementById('facebook-login-area').style.display = 'none';
                        document.getElementById('user-info-area').style.display = 'block';
                        document.getElementById('user-name').textContent = user.name;
                        document.getElementById('user-email').textContent = user.email || 'Facebook User';
                        document.getElementById('user-avatar').src = user.picture.data.url;
                        document.getElementById('comment-text').disabled = false;
                        document.getElementById('submit-btn').disabled = false;
                    }

                    function logoutFacebook() {
                        FB.logout(function() {
                            localStorage.removeItem('fbUser');
                            document.getElementById('facebook-login-area').style.display = 'block';
                            document.getElementById('user-info-area').style.display = 'none';
                            document.getElementById('comment-text').value = '';
                            document.getElementById('comment-text').disabled = true;
                            document.getElementById('submit-btn').disabled = true;
                        });
                    }

                    // Handle comment form submission
                    document.getElementById('comment-form').addEventListener('submit', function(e) {
                        e.preventDefault();

                        const fbUser = JSON.parse(localStorage.getItem('fbUser'));
                        if (!fbUser) {
                            alert('Please login with Facebook first');
                            return;
                        }

                        const commentText = document.getElementById('comment-text').value.trim();
                        if (!commentText) {
                            alert('Please write a comment');
                            return;
                        }

                        // Show loading state
                        const submitBtn = document.getElementById('submit-btn');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';

                        // Submit comment
                        const formData = new FormData();
                        formData.append('comment_text', commentText);
                        formData.append('commenter_name', fbUser.name);
                        formData.append('commenter_email', fbUser.email);
                        formData.append('facebook_id', fbUser.id);
                        formData.append('facebook_profile_url', `https://facebook.com/${fbUser.id}`);
                        formData.append('commenter_avatar', fbUser.avatar);
                        formData.append('source', 'facebook');

                        fetch('{{ route("live.comments.store", $stream->slug) }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;

                            if (data.success) {
                                document.getElementById('comment-text').value = '';
                                loadComments();
                                showMessage('Comment posted successfully!', 'success');
                            } else {
                                showMessage(data.message || 'Failed to post comment', 'error');
                                if (data.reasons) {
                                    console.log('Spam reasons:', data.reasons);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                            showMessage('Error posting comment', 'error');
                        });
                    });

                    // Load comments
                    function loadComments() {
                        fetch('{{ route("live.comments.list", $stream->slug) }}')
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    displayComments(data.data);
                                }
                            })
                            .catch(error => console.error('Error loading comments:', error));
                    }

                    function showMessage(message, type = 'info') {
                        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                        const alertHtml = `
                            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                                ${type === 'success' ? '<i class="fas fa-check-circle me-2"></i>' : '<i class="fas fa-exclamation-circle me-2"></i>'}
                                ${message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        
                        const alertContainer = document.querySelector('.card:has(#comment-form)');
                        const existingAlert = alertContainer.querySelector('.alert');
                        if (existingAlert) existingAlert.remove();
                        
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = alertHtml;
                        alertContainer.querySelector('.card-body').insertBefore(tempDiv.firstElementChild, alertContainer.querySelector('#comment-form'));
                    }

                    function displayComments(comments) {
                        const container = document.getElementById('comments-container');
                        document.getElementById('comment-count').textContent = comments.length;

                        if (comments.length === 0) {
                            container.innerHTML = `
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-comments fa-2x mb-2 d-block opacity-50"></i>
                                    <p>Be the first to comment!</p>
                                </div>
                            `;
                            return;
                        }

                        container.innerHTML = comments.map(comment => `
                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                <img src="${comment.avatar}" alt="${comment.name}" class="rounded-circle" 
                                     style="width: 40px; height: 40px; object-fit: cover; flex-shrink: 0;">
                                <div class="flex-grow-1">
                                    <div class="mb-1">
                                        <strong>${comment.name}</strong>
                                        ${comment.is_verified ? '<span class="badge bg-success ms-1" style="font-size: 0.7rem;"><i class="fas fa-check"></i> Verified</span>' : ''}
                                        ${comment.is_pinned ? '<span class="badge bg-warning ms-1" style="font-size: 0.7rem;"><i class="fas fa-thumbtack"></i> Pinned</span>' : ''}
                                        <small class="text-muted ms-2">${comment.created_at}</small>
                                    </div>
                                    <p class="mb-2">${comment.text}</p>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-link btn-sm p-0" onclick="likeComment(${comment.id})">
                                            <i class="fas fa-thumbs-up"></i> Like <span id="likes-${comment.id}">${comment.likes}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }

                    function likeComment(commentId) {
                        fetch(`/live/comments/${commentId}/like`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`likes-${commentId}`).textContent = data.likes;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }

                    // Load comments on page load and set interval for refresh
                    document.addEventListener('DOMContentLoaded', function() {
                        loadComments();
                        setInterval(loadComments, 5000); // Refresh every 5 seconds
                    });

                    // Check if user was already logged in on page load
                    window.addEventListener('load', function() {
                        const fbUser = localStorage.getItem('fbUser');
                        if (fbUser) {
                            const user = JSON.parse(fbUser);
                            updateUserUI(user);
                        }
                    });
                </script>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Stream Stats --}}
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Stream Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-muted small">Views</div>
                            <div class="h5 fw-bold">{{ number_format($stream->view_count) }}</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-muted small">Current Viewers</div>
                            <div class="h5 fw-bold">{{ number_format($stream->viewer_count) }}</div>
                        </div>
                        @if($stream->peak_viewers > 0)
                            <div class="col-6 mb-3">
                                <div class="text-muted small">Peak Viewers</div>
                                <div class="h5 fw-bold">{{ number_format($stream->peak_viewers) }}</div>
                            </div>
                        @endif
                        @if($stream->duration_seconds > 0)
                            <div class="col-6 mb-3">
                                <div class="text-muted small">Duration</div>
                                <div class="h5 fw-bold">{{ $stream->getFormattedDuration() }}</div>
                            </div>
                        @endif
                    </div>

                    {{-- Share Buttons --}}
                    <hr>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('live.watch', $stream->slug)) }}" 
                           target="_blank" class="btn btn-sm btn-outline-primary" title="Share on Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($stream->title) }}&url={{ urlencode(route('live.watch', $stream->slug)) }}" 
                           target="_blank" class="btn btn-sm btn-outline-info" title="Share on Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary" title="Copy Link" 
                                onclick="copyStreamUrl()">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Related Streams --}}
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Other Streams</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        {{-- Add related streams here if available --}}
                        <a href="{{ route('live.index') }}" class="list-group-item list-group-item-action p-3 text-center text-muted">
                            <i class="fas fa-arrow-right"></i> View All Live Streams
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyStreamUrl() {
        const url = '{{ route("live.watch", $stream->slug) }}';
        navigator.clipboard.writeText(url).then(() => {
            alert('Stream URL copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    // Player Controls
    document.addEventListener('DOMContentLoaded', function() {
        const volumeSlider = document.getElementById('volume-slider');
        const volumeLabel = document.getElementById('volume-label');
        const volumeDecreaseBtn = document.getElementById('volume-decrease');
        const volumeIncreaseBtn = document.getElementById('volume-increase');
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const playerContainer = document.getElementById('player-container');
        const streamWrapper = document.getElementById('stream-wrapper');

        // Store volume in localStorage for persistence
        const savedVolume = localStorage.getItem('stream-volume');
        if (savedVolume) {
            volumeSlider.value = savedVolume;
            updateVolumeLabel();
        }

        // Volume Slider Change
        volumeSlider.addEventListener('input', function() {
            updateVolume();
            updateVolumeLabel();
            localStorage.setItem('stream-volume', this.value);
        });

        // Volume Decrease Button
        volumeDecreaseBtn.addEventListener('click', function() {
            volumeSlider.value = Math.max(0, parseInt(volumeSlider.value) - 10);
            updateVolume();
            updateVolumeLabel();
            localStorage.setItem('stream-volume', volumeSlider.value);
        });

        // Volume Increase Button
        volumeIncreaseBtn.addEventListener('click', function() {
            volumeSlider.value = Math.min(100, parseInt(volumeSlider.value) + 10);
            updateVolume();
            updateVolumeLabel();
            localStorage.setItem('stream-volume', volumeSlider.value);
        });

        // Update Volume Display
        function updateVolumeLabel() {
            volumeLabel.textContent = volumeSlider.value + '%';
        }

        // Apply Volume (via postMessage to iframe if needed)
        function updateVolume() {
            const volume = volumeSlider.value / 100;
            try {
                const iframe = document.getElementById('stream-player');
                // Try to apply volume if the iframe supports postMessage (HLS.js, Video.js, etc)
                if (iframe && iframe.contentWindow) {
                    iframe.contentWindow.postMessage({
                        type: 'set-volume',
                        volume: volume
                    }, '*');
                }
            } catch (e) {
                console.log('Volume control message sent');
            }
        }

        // Fullscreen Button
        fullscreenBtn.addEventListener('click', function() {
            if (!document.fullscreenElement) {
                // Enter fullscreen
                if (playerContainer.requestFullscreen) {
                    playerContainer.requestFullscreen();
                } else if (playerContainer.webkitRequestFullscreen) {
                    playerContainer.webkitRequestFullscreen();
                } else if (playerContainer.msRequestFullscreen) {
                    playerContainer.msRequestFullscreen();
                }
            } else {
                // Exit fullscreen
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        });

        // Update fullscreen button icon when fullscreen state changes
        document.addEventListener('fullscreenchange', updateFullscreenIcon);
        document.addEventListener('webkitfullscreenchange', updateFullscreenIcon);
        document.addEventListener('msfullscreenchange', updateFullscreenIcon);

        function updateFullscreenIcon() {
            const icon = fullscreenBtn.querySelector('i');
            if (document.fullscreenElement) {
                icon.classList.remove('fa-expand');
                icon.classList.add('fa-compress');
                fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i> Exit Fullscreen';
            } else {
                icon.classList.remove('fa-compress');
                icon.classList.add('fa-expand');
                fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i> Fullscreen';
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Check if the active element is not an input field
            if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                if (e.key === 'f' || e.key === 'F') {
                    // F for fullscreen
                    fullscreenBtn.click();
                } else if (e.key === 'ArrowUp') {
                    // Arrow up to increase volume
                    volumeIncreaseBtn.click();
                    e.preventDefault();
                } else if (e.key === 'ArrowDown') {
                    // Arrow down to decrease volume
                    volumeDecreaseBtn.click();
                    e.preventDefault();
                }
            }
        });
    });
</script>

<style>
    .animate-pulse {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    /* Player Controls Styling */
    #player-container {
        position: relative;
    }

    #player-container .form-range {
        accent-color: #0d6efd;
        cursor: pointer;
    }

    #player-container .form-range::-webkit-slider-thumb {
        background: #0d6efd;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    #player-container .form-range::-moz-range-thumb {
        background: #0d6efd;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    #player-container .btn-outline-light:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    #player-container .btn-outline-light:focus {
        background-color: #0d6efd;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Fullscreen mode styling */
    #player-container:-webkit-full-screen {
        width: 100vw;
        height: 100vh;
    }

    #player-container:-moz-full-screen {
        width: 100vw;
        height: 100vh;
    }

    #player-container:fullscreen {
        width: 100vw;
        height: 100vh;
    }

    /* Mobile responsive */
    @media (max-width: 576px) {
        #player-container .d-flex {
            flex-direction: column !important;
        }

        #volume-slider {
            width: 100px !important;
        }

        #player-container .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        #player-container .btn-sm i {
            margin-right: 0.25rem;
        }
    }
</style>
@endsection
