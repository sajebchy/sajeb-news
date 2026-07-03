@extends('public.layout')

@section('title', $stream->title . ' - Live')

@section('content')
@php $embedSrc = $stream->getEmbedSrc(); @endphp
<main class="max-w-container-max mx-auto px-gutter py-stack-lg">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ═══════════ Main column ═══════════ --}}
    <div class="lg:col-span-2 space-y-stack-md">

      {{-- Player --}}
      <div id="player-container" class="rounded-xl overflow-hidden shadow-lg bg-black">
        <div class="relative w-full aspect-video">
          @if($embedSrc)
            <iframe id="stream-player" src="{{ $embedSrc }}"
                    class="absolute inset-0 w-full h-full"
                    allow="autoplay; encrypted-media; picture-in-picture; fullscreen"
                    allowfullscreen scrolling="no" style="border:0;"></iframe>
          @else
            <div class="absolute inset-0 flex items-center justify-center text-white/60 text-center px-4">
              <div>
                <span class="material-symbols-outlined text-5xl block mb-2">videocam_off</span>
                <p class="font-body-sm mb-0">স্ট্রিম লিংক পাওয়া যায়নি বা অসমর্থিত।</p>
              </div>
            </div>
          @endif
        </div>

        {{-- Player bar --}}
        <div class="flex items-center justify-between gap-3 bg-black px-3 py-2 flex-wrap">
          <div class="flex items-center gap-3 text-white text-sm">
            @if($stream->isLive())
              <span class="inline-flex items-center gap-1.5 bg-secondary text-white font-label-caps text-[11px] px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> LIVE NOW
              </span>
            @elseif($stream->isScheduled())
              <span class="inline-flex items-center gap-1 bg-amber-500 text-black font-label-caps text-[11px] px-2.5 py-1 rounded-full">
                <span class="material-symbols-outlined text-[14px]">schedule</span> Starts {{ $stream->scheduled_at->diffForHumans() }}
              </span>
            @elseif($stream->hasEnded())
              <span class="inline-flex items-center gap-1 bg-white/20 text-white font-label-caps text-[11px] px-2.5 py-1 rounded-full">
                <span class="material-symbols-outlined text-[14px]">check_circle</span> Ended
              </span>
            @endif
            <span class="inline-flex items-center gap-1 text-white/70 font-meta-data">
              <span class="material-symbols-outlined text-[16px]">visibility</span> {{ number_format($stream->view_count) }}
            </span>
          </div>
          <button type="button" id="fullscreen-btn"
                  class="inline-flex items-center gap-1 text-white/80 hover:text-white font-label-caps text-[11px] px-2 py-1 rounded transition-colors">
            <span class="material-symbols-outlined text-[18px]">fullscreen</span>
            <span class="hidden sm:inline">Fullscreen</span>
          </button>
        </div>
      </div>

      {{-- Title & meta --}}
      <div class="bg-surface-container-lowest border border-subtle rounded-xl p-stack-md">
        <h1 class="font-headline-lg text-headline-md md:text-headline-lg text-on-surface mb-3">{{ $stream->title }}</h1>

        <div class="flex flex-wrap items-center justify-between gap-3">
          <a href="{{ route('author.show', $stream->user->id) }}" class="flex items-center gap-2 group">
            @if($stream->user->avatar)
              <img src="{{ asset('storage/' . $stream->user->avatar) }}" alt="{{ $stream->user->name }}"
                   class="w-9 h-9 rounded-full object-cover">
            @else
              <span class="material-symbols-outlined text-[36px] text-outline">account_circle</span>
            @endif
            <span>
              <span class="block font-meta-data text-meta-data text-on-surface-variant leading-none">সম্প্রচারে</span>
              <span class="font-body-sm font-semibold text-on-surface group-hover:text-secondary transition-colors">{{ $stream->user->name }}</span>
            </span>
          </a>
          <div class="text-right">
            <span class="block font-body-sm text-on-surface">{{ $stream->created_at->format('d M, Y') }}</span>
            <span class="font-meta-data text-meta-data text-on-surface-variant">{{ $stream->created_at->diffForHumans() }}</span>
          </div>
        </div>

        @if($stream->description)
          <div class="mt-stack-md pt-stack-md border-t border-subtle">
            <p class="font-body-main text-body-sm text-on-surface-variant" style="white-space: pre-line;">{{ $stream->description }}</p>
          </div>
        @endif

        @if($stream->category || $stream->stream_tags)
          <div class="mt-stack-md pt-stack-md border-t border-subtle flex flex-wrap items-center gap-2">
            @if($stream->category)
              <span class="bg-secondary text-white font-label-caps text-[11px] px-2.5 py-1 rounded-full">{{ $stream->category }}</span>
            @endif
            @if($stream->stream_tags)
              @foreach($stream->stream_tags as $tag)
                <span class="bg-surface-container text-on-surface-variant font-meta-data text-meta-data px-2.5 py-1 rounded-full">#{{ $tag }}</span>
              @endforeach
            @endif
          </div>
        @endif
      </div>

      {{-- Comments --}}
      @if($stream->allow_comments)
        <div class="bg-surface-container-lowest border border-subtle rounded-xl overflow-hidden">
          <div class="px-stack-md py-3 border-b border-subtle flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">chat</span>
            <h2 class="font-headline-md text-headline-md text-on-surface mb-0">মন্তব্য (<span id="comment-count">0</span>)</h2>
          </div>
          <div class="p-stack-md">
            {{-- Message slot --}}
            <div id="comment-msg"></div>

            {{-- Comment form --}}
            <form id="comment-form" class="mb-stack-md">
              @csrf
              <div id="facebook-login-area" class="text-center mb-3">
                <button type="button" id="facebook-login-btn" onclick="loginWithFacebook()"
                        class="inline-flex items-center gap-2 bg-[#1877F2] text-white font-label-caps text-label-caps px-4 py-2.5 rounded-lg hover:opacity-90 transition-opacity">
                  <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z"/></svg>
                  মন্তব্য করতে Facebook দিয়ে লগইন করুন
                </button>
              </div>

              <div id="user-info-area" style="display:none;"
                   class="flex items-center gap-3 bg-surface-container-low border border-subtle rounded-lg p-3 mb-3">
                <img id="user-avatar" src="" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                <div class="min-w-0 flex-1">
                  <strong id="user-name" class="block font-body-sm text-on-surface truncate"></strong>
                  <small id="user-email" class="font-meta-data text-on-surface-variant"></small>
                </div>
                <button type="button" onclick="logoutFacebook()" class="font-label-caps text-[11px] text-secondary hover:underline">Logout</button>
              </div>

              <textarea id="comment-text" name="comment_text" rows="3" required disabled
                        placeholder="এই স্ট্রিম নিয়ে আপনার মতামত লিখুন..."
                        class="w-full px-4 py-3 rounded-lg border border-subtle focus:border-secondary outline-none transition-colors bg-white font-body-sm resize-none disabled:bg-surface-container disabled:cursor-not-allowed"></textarea>

              <button type="submit" id="submit-btn" disabled
                      class="mt-stack-sm inline-flex items-center gap-2 bg-primary text-white font-label-caps text-label-caps px-5 py-2.5 rounded-full hover:bg-secondary transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-[18px]">send</span> মন্তব্য পোস্ট করুন
              </button>
            </form>

            {{-- Comments list --}}
            <div id="comments-container" class="border-t border-subtle pt-stack-sm">
              <div class="text-center py-6 text-on-surface-variant">
                <span class="material-symbols-outlined animate-spin">progress_activity</span>
                <p class="font-body-sm mt-1">মন্তব্য লোড হচ্ছে...</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Facebook SDK + comment logic --}}
        <div id="fb-root"></div>
        <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId: '{{ config("social.facebook.app_id") }}',
              xfbml: true,
              version: '{{ config("social.facebook.app_version", "v18.0") }}'
            });
            FB.getLoginStatus(function(response) {
              if (response.status === 'connected') handleFacebookLogin(response);
            });
          };
          (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "{{ config('social.facebook.sdk_url') }}";
            fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));

          function loginWithFacebook() {
            FB.login(function(response) {
              if (response.authResponse) handleFacebookLogin(response);
            }, {scope: 'email,public_profile'});
          }

          function handleFacebookLogin(response) {
            if (response.status === 'connected') {
              FB.api('/me', {fields: 'id,name,email,picture'}, function(user) {
                localStorage.setItem('fbUser', JSON.stringify({
                  id: user.id, name: user.name, email: user.email,
                  avatar: user.picture.data.url, accessToken: response.authResponse.accessToken
                }));
                updateUserUI(user);
              });
            }
          }

          function updateUserUI(user) {
            document.getElementById('facebook-login-area').style.display = 'none';
            document.getElementById('user-info-area').style.display = 'flex';
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
              const t = document.getElementById('comment-text');
              t.value = ''; t.disabled = true;
              document.getElementById('submit-btn').disabled = true;
            });
          }

          document.getElementById('comment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const fbUser = JSON.parse(localStorage.getItem('fbUser') || 'null');
            if (!fbUser) { showMessage('অনুগ্রহ করে আগে Facebook দিয়ে লগইন করুন', 'error'); return; }
            const commentText = document.getElementById('comment-text').value.trim();
            if (!commentText) { showMessage('অনুগ্রহ করে একটি মন্তব্য লিখুন', 'error'); return; }

            const submitBtn = document.getElementById('submit-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span> পোস্ট হচ্ছে...';

            const formData = new FormData();
            formData.append('comment_text', commentText);
            formData.append('commenter_name', fbUser.name);
            formData.append('commenter_email', fbUser.email);
            formData.append('facebook_id', fbUser.id);
            formData.append('facebook_profile_url', `https://facebook.com/${fbUser.id}`);
            formData.append('commenter_avatar', fbUser.avatar);
            formData.append('source', 'facebook');

            fetch('{{ route("live.comments.store", $stream->slug) }}', {
              method: 'POST', body: formData,
              headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }
            })
            .then(r => r.json())
            .then(data => {
              submitBtn.disabled = false; submitBtn.innerHTML = originalText;
              if (data.success) {
                document.getElementById('comment-text').value = '';
                loadComments();
                showMessage('আপনার মন্তব্য পোস্ট হয়েছে!', 'success');
              } else {
                showMessage(data.message || 'মন্তব্য পোস্ট করা যায়নি', 'error');
              }
            })
            .catch(() => {
              submitBtn.disabled = false; submitBtn.innerHTML = originalText;
              showMessage('মন্তব্য পোস্টে ত্রুটি হয়েছে', 'error');
            });
          });

          function loadComments() {
            fetch('{{ route("live.comments.list", $stream->slug) }}')
              .then(r => r.json())
              .then(data => { if (data.success) displayComments(data.data); })
              .catch(err => console.error('Error loading comments:', err));
          }

          function showMessage(message, type) {
            const ok = type === 'success';
            const cls = ok ? 'bg-green-50 text-green-800 border-green-200' : 'bg-red-50 text-red-800 border-red-200';
            const icon = ok ? 'check_circle' : 'error';
            document.getElementById('comment-msg').innerHTML =
              `<div class="flex items-center gap-2 border ${cls} rounded-lg px-3 py-2 mb-3 font-body-sm">
                 <span class="material-symbols-outlined text-[18px]">${icon}</span>${escapeHtml(message)}
               </div>`;
            setTimeout(() => { const m = document.getElementById('comment-msg'); if (m) m.innerHTML = ''; }, 4000);
          }

          // Escape untrusted values before inserting into HTML (prevents stored XSS)
          function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"'`]/g, function (ch) {
              return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '`': '&#96;' }[ch];
            });
          }
          function safeUrl(value) {
            const url = String(value ?? '');
            return /^https?:\/\//i.test(url) ? escapeHtml(url) : '';
          }

          function displayComments(comments) {
            const container = document.getElementById('comments-container');
            document.getElementById('comment-count').textContent = comments.length;
            if (!comments.length) {
              container.innerHTML =
                `<div class="text-center py-6 text-on-surface-variant">
                   <span class="material-symbols-outlined text-3xl block mb-1 opacity-60">forum</span>
                   <p class="font-body-sm mb-0">প্রথম মন্তব্যটি আপনিই করুন!</p>
                 </div>`;
              return;
            }
            container.innerHTML = comments.map(c => `
              <div class="flex gap-3 py-3 border-b border-subtle last:border-0">
                <img src="${safeUrl(c.avatar)}" alt="${escapeHtml(c.name)}"
                     class="w-10 h-10 rounded-full object-cover flex-shrink-0 bg-surface-container"
                     onerror="this.style.visibility='hidden'">
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 flex-wrap mb-1">
                    <strong class="font-body-sm text-on-surface">${escapeHtml(c.name)}</strong>
                    ${c.is_verified ? '<span class="text-[10px] bg-green-600 text-white px-1.5 py-0.5 rounded-full">✓ Verified</span>' : ''}
                    ${c.is_pinned ? '<span class="text-[10px] bg-secondary text-white px-1.5 py-0.5 rounded-full">Pinned</span>' : ''}
                    <span class="font-meta-data text-meta-data text-on-surface-variant">${escapeHtml(c.created_at)}</span>
                  </div>
                  <p class="font-body-sm text-on-surface mb-1 break-words">${escapeHtml(c.text)}</p>
                  <button type="button" onclick="likeComment(${c.id})"
                          class="inline-flex items-center gap-1 font-meta-data text-on-surface-variant hover:text-secondary transition-colors">
                    <span class="material-symbols-outlined text-[16px]">thumb_up</span>
                    <span id="likes-${c.id}">${c.likes}</span>
                  </button>
                </div>
              </div>`).join('');
          }

          function likeComment(commentId) {
            fetch(`/live/comments/${commentId}/like`, {
              method: 'POST',
              headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Content-Type': 'application/json' }
            })
            .then(r => r.json())
            .then(data => { if (data.success) document.getElementById(`likes-${commentId}`).textContent = data.likes; })
            .catch(err => console.error('Error:', err));
          }

          document.addEventListener('DOMContentLoaded', function() {
            loadComments();
            setInterval(loadComments, 5000);
          });
          window.addEventListener('load', function() {
            const fbUser = localStorage.getItem('fbUser');
            if (fbUser) updateUserUI(JSON.parse(fbUser));
          });
        </script>
      @endif
    </div>

    {{-- ═══════════ Sidebar ═══════════ --}}
    <aside class="lg:col-span-1 space-y-stack-md">

      {{-- Stats --}}
      <div class="bg-surface-container-lowest border border-subtle rounded-xl overflow-hidden">
        <div class="px-stack-md py-3 border-b border-subtle flex items-center gap-2">
          <span class="material-symbols-outlined text-secondary text-[20px]">monitoring</span>
          <h3 class="font-headline-md text-headline-md mb-0">স্ট্রিম পরিসংখ্যান</h3>
        </div>
        <div class="p-stack-md grid grid-cols-2 gap-3 text-center">
          <div class="bg-surface-container-low rounded-lg py-3">
            <div class="font-headline-lg text-headline-md text-on-surface">{{ number_format($stream->view_count) }}</div>
            <div class="font-meta-data text-meta-data text-on-surface-variant mt-1">মোট ভিউ</div>
          </div>
          <div class="bg-surface-container-low rounded-lg py-3">
            <div class="font-headline-lg text-headline-md text-on-surface">{{ number_format($stream->viewer_count) }}</div>
            <div class="font-meta-data text-meta-data text-on-surface-variant mt-1">বর্তমান দর্শক</div>
          </div>
          @if($stream->peak_viewers > 0)
          <div class="bg-surface-container-low rounded-lg py-3">
            <div class="font-headline-lg text-headline-md text-on-surface">{{ number_format($stream->peak_viewers) }}</div>
            <div class="font-meta-data text-meta-data text-on-surface-variant mt-1">সর্বোচ্চ দর্শক</div>
          </div>
          @endif
          @if($stream->duration_seconds > 0)
          <div class="bg-surface-container-low rounded-lg py-3">
            <div class="font-headline-lg text-headline-md text-on-surface">{{ $stream->getFormattedDuration() }}</div>
            <div class="font-meta-data text-meta-data text-on-surface-variant mt-1">সময়কাল</div>
          </div>
          @endif
        </div>
      </div>

      {{-- Share --}}
      <div class="bg-surface-container-lowest border border-subtle rounded-xl p-stack-md">
        <h3 class="font-label-caps text-label-caps text-on-surface-variant mb-3">এই স্ট্রিমটি শেয়ার করুন</h3>
        <div class="flex flex-wrap gap-2">
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('live.watch', $stream->slug)) }}"
             target="_blank" rel="noopener" aria-label="ফেসবুকে শেয়ার"
             class="w-10 h-10 rounded-full bg-[#1877F2] text-white flex items-center justify-center hover:opacity-90 transition-opacity">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-[18px] h-[18px]"><path d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z"/></svg>
          </a>
          <a href="https://twitter.com/intent/tweet?text={{ urlencode($stream->title) }}&url={{ urlencode(route('live.watch', $stream->slug)) }}"
             target="_blank" rel="noopener" aria-label="এক্স-এ শেয়ার"
             class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center hover:opacity-90 transition-opacity">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-[16px] h-[16px]"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
          </a>
          <button type="button" onclick="copyStreamUrl(this)" aria-label="লিংক কপি"
                  class="w-10 h-10 rounded-full bg-surface-container text-on-surface flex items-center justify-center hover:bg-secondary hover:text-white transition-all">
            <span class="material-symbols-outlined text-[18px]">link</span>
          </button>
        </div>
      </div>

      {{-- Other streams --}}
      <div class="bg-surface-container-lowest border border-subtle rounded-xl overflow-hidden">
        <div class="px-stack-md py-3 border-b border-subtle flex items-center gap-2">
          <span class="material-symbols-outlined text-secondary text-[20px]">live_tv</span>
          <h3 class="font-headline-md text-headline-md mb-0">অন্যান্য স্ট্রিম</h3>
        </div>
        <a href="{{ route('live.index') }}"
           class="flex items-center justify-center gap-2 p-4 font-body-sm text-on-surface-variant hover:text-secondary hover:bg-surface-container-low transition-colors">
          <span>সব লাইভ স্ট্রিম দেখুন</span>
          <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </a>
      </div>
    </aside>
  </div>
</main>

<script>
  function copyStreamUrl(btn) {
    const url = '{{ route("live.watch", $stream->slug) }}';
    navigator.clipboard.writeText(url).then(() => {
      if (btn) {
        btn.classList.add('bg-secondary', 'text-white');
        setTimeout(() => btn.classList.remove('bg-secondary', 'text-white'), 1500);
      }
    }).catch(err => console.error('Failed to copy:', err));
  }

  // Fullscreen toggle for the embed player
  document.addEventListener('DOMContentLoaded', function() {
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const playerContainer = document.getElementById('player-container');
    if (!fullscreenBtn || !playerContainer) return;

    fullscreenBtn.addEventListener('click', function() {
      if (!document.fullscreenElement) {
        (playerContainer.requestFullscreen || playerContainer.webkitRequestFullscreen || playerContainer.msRequestFullscreen).call(playerContainer);
      } else {
        (document.exitFullscreen || document.webkitExitFullscreen || document.msExitFullscreen).call(document);
      }
    });

    function updateFullscreenIcon() {
      const icon = fullscreenBtn.querySelector('.material-symbols-outlined');
      if (icon) icon.textContent = document.fullscreenElement ? 'fullscreen_exit' : 'fullscreen';
    }
    document.addEventListener('fullscreenchange', updateFullscreenIcon);
    document.addEventListener('webkitfullscreenchange', updateFullscreenIcon);

    document.addEventListener('keydown', function(e) {
      const tag = document.activeElement.tagName;
      if (tag !== 'INPUT' && tag !== 'TEXTAREA' && (e.key === 'f' || e.key === 'F')) {
        fullscreenBtn.click();
      }
    });
  });
</script>
@endsection
