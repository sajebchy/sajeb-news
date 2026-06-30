@extends('layouts.admin')

@section('title', 'OBS Configuration - ' . $stream->title)

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('admin.live-streams.show', $stream) }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Stream
            </a>
            <h2><i class="fas fa-cogs"></i> OBS Configuration Guide</h2>
            <p class="text-muted">Step-by-step guide to configure OBS Studio for {{ $stream->title }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Step 1 --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><span class="badge bg-light text-dark">Step 1</span> Download and Install OBS</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li>Go to <a href="https://obsproject.com/" target="_blank" class="text-decoration-none">obsproject.com</a></li>
                        <li>Download OBS Studio for your operating system (Windows, Mac, or Linux)</li>
                        <li>Install and launch OBS Studio</li>
                    </ol>
                    <p class="text-muted mb-0"><i class="fas fa-info-circle"></i> OBS is a free, open-source broadcasting software</p>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><span class="badge bg-light text-dark">Step 2</span> Configure Stream Settings</h5>
                </div>
                <div class="card-body">
                    <p><strong>In OBS, go to:</strong> File → Settings → Stream</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Stream Settings</h6>
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Service</strong></td>
                                        <td>Custom...</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Server</strong></td>
                                        <td><code class="bg-light p-1 rounded">{{ $rtmp_url }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stream Key</strong></td>
                                        <td><code class="bg-light p-1 rounded">{{ substr($stream_key, 0, 20) }}...</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Recommended Settings</h6>
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Bitrate</strong></td>
                                        <td>2500-4000 kbps</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Resolution</strong></td>
                                        <td>1920x1080 (1080p)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>FPS</strong></td>
                                        <td>30 or 60</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Important:</strong> Use the stream key provided below. Never share this key publicly!
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><span class="badge bg-light text-dark">Step 3</span> Set Up Your Scene</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li><strong>Create a new Scene</strong> (or use the default)</li>
                        <li><strong>Add Sources</strong>:
                            <ul>
                                <li>Window Capture (for computer screen)</li>
                                <li>Display Capture (for full monitor)</li>
                                <li>Media Source (for videos)</li>
                                <li>Text Source (for titles/overlays)</li>
                                <li>Webcam (for camera)</li>
                            </ul>
                        </li>
                        <li><strong>Arrange and position</strong> your sources as desired</li>
                        <li><strong>Add audio sources</strong> (microphone, system audio, etc.)</li>
                    </ol>
                    <p class="text-muted mb-0"><i class="fas fa-lightbulb"></i> Tip: Test your setup before going live!</p>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><span class="badge bg-light text-dark">Step 4</span> Start Broadcasting</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li><strong>Click "Start Streaming"</strong> button in OBS (bottom right)</li>
                        <li><strong>Check the log</strong> for any errors</li>
                        <li><strong>Verify your broadcast</strong> is working</li>
                        <li><strong>Share the stream link</strong> with your audience</li>
                        <li><strong>Click "Stop Streaming"</strong> when finished</li>
                    </ol>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-info-circle"></i> 
                        Your stream will be available at: <br>
                        <code class="bg-light p-2 d-block rounded text-break">{{ route('live.watch', $stream->slug) }}</code>
                    </div>
                </div>
            </div>

            {{-- Troubleshooting --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-tools"></i> Troubleshooting</h5>
                </div>
                <div class="card-body">
                    <h6>"Failed to connect to the streaming server"</h6>
                    <ul>
                        <li>Double-check the Server URL (RTMP URL)</li>
                        <li>Verify your Stream Key is correct</li>
                        <li>Check your internet connection</li>
                        <li>Ensure firewall isn't blocking port 1935</li>
                    </ul>

                    <h6 class="mt-3">"Encoding overloaded" warning</h6>
                    <ul>
                        <li>Lower your bitrate (2500-3000 kbps)</li>
                        <li>Reduce resolution to 1280x720 (720p)</li>
                        <li>Reduce FPS to 30</li>
                        <li>Close other applications</li>
                    </ul>

                    <h6 class="mt-3">"No audio in the stream"</h6>
                    <ul>
                        <li>Check audio devices in OBS settings</li>
                        <li>Ensure audio sources are added to your scene</li>
                        <li>Check volume levels aren't muted</li>
                        <li>Verify audio permissions in system settings</li>
                    </ul>

                    <h6 class="mt-3">"Stream lag/buffering"</h6>
                    <ul>
                        <li>Check your internet upload speed (should be at least 5 Mbps)</li>
                        <li>Reduce bitrate</li>
                        <li>Use a wired connection (ethernet) instead of WiFi</li>
                        <li>Close bandwidth-heavy applications</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Stream Details --}}
            <div class="card shadow mb-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-broadcast-tower"></i> Stream Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Stream Title</label>
                        <div class="fw-bold">{{ $stream->title }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Server URL (RTMP)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="server-url-input" class="form-control font-monospace" value="{{ $rtmp_url }}" readonly style="font-size: 0.8rem;">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyElement('server-url-input')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Stream Key</label>
                        <div class="input-group input-group-sm">
                            <input type="password" id="key-input" class="form-control font-monospace" value="{{ $stream_key }}" readonly style="font-size: 0.8rem;">
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleElement('key-input')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyElement('key-input')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-warning small mb-0">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Keep your stream key private!</strong> Never share it publicly or with untrusted users.
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-link"></i> Quick Links</h5>
                </div>
                <div class="card-body">
                    <a href="https://obsproject.com/wiki/Home" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                        <i class="fas fa-book"></i> OBS Documentation
                    </a>
                    <a href="https://www.youtube.com/results?search_query=OBS+Studio+tutorial" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                        <i class="fas fa-video"></i> OBS Tutorials
                    </a>
                    <a href="{{ route('admin.live-streams.show', $stream) }}" class="btn btn-sm btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Back to Stream
                    </a>
                </div>
            </div>

            {{-- System Requirements --}}
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-server"></i> Recommended System</h5>
                </div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-6">CPU</dt>
                        <dd class="col-6">Intel i7 or better</dd>
                        
                        <dt class="col-6">RAM</dt>
                        <dd class="col-6">8GB minimum</dd>
                        
                        <dt class="col-6">Internet</dt>
                        <dd class="col-6">5+ Mbps upload</dd>
                        
                        <dt class="col-6">OS</dt>
                        <dd class="col-6">Windows 10+, macOS, Linux</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyElement(elementId) {
        const element = document.getElementById(elementId);
        const originalType = element.type;
        element.type = 'text';
        element.select();
        document.execCommand('copy');
        element.type = originalType;
        alert('Copied to clipboard!');
    }

    function toggleElement(elementId) {
        const element = document.getElementById(elementId);
        element.type = element.type === 'password' ? 'text' : 'password';
    }
</script>

<style>
    .font-monospace {
        font-family: 'Courier New', monospace;
    }
</style>
@endsection
