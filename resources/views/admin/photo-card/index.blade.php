@extends('layouts.admin')

@section('page-title', 'ফটোকার্ড মেকার')

@push('styles')
<style>
    @font-face {
        font-family: 'SolaimanLipi';
        src: url('/fonts/SolaimanLipi.ttf') format('truetype');
        font-weight: 400;
        font-style: normal;
        font-display: block;
    }
    @font-face {
        font-family: 'SolaimanLipi';
        src: url('/fonts/SolaimanLipi-Bold.ttf') format('truetype');
        font-weight: 700;
        font-style: normal;
        font-display: block;
    }
    .size-btn.active { background: var(--md-sys-color-primary); color: var(--md-sys-color-on-primary); }
    .size-btn { transition: all 0.15s; }
    #photoCardCanvas { max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.12); }
    .upload-zone { border: 2px dashed var(--md-sys-color-outline-variant); border-radius: 12px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.2s; }
    .upload-zone:hover, .upload-zone.dragover { border-color: var(--md-sys-color-primary); background: rgba(var(--md-sys-color-primary-rgb, 103,80,164), 0.05); }
    .color-swatch { width: 32px; height: 32px; border-radius: 8px; cursor: pointer; border: 2px solid transparent; transition: all 0.15s; }
    .color-swatch.active { border-color: var(--md-sys-color-primary); transform: scale(1.15); }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        {{-- Left: Controls --}}
        <div class="col-lg-5 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                    <h5 class="mb-1 fw-bold"><i class="bi bi-card-image me-2"></i>ফটোকার্ড মেকার</h5>
                    <p class="text-muted small mb-0">সোশ্যাল মিডিয়ার জন্য ফটোকার্ড তৈরি করুন</p>
                </div>
                <div class="card-body px-4 pb-4">
                    {{-- Platform Size Selection --}}
                    <label class="form-label fw-semibold mb-2">প্ল্যাটফর্ম সাইজ</label>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button class="btn btn-sm size-btn rounded-pill px-3 border active" data-w="1080" data-h="1080" data-name="Instagram Post">
                            <i class="bi bi-instagram me-1"></i>পোস্ট
                        </button>
                        <button class="btn btn-sm size-btn rounded-pill px-3 border" data-w="1080" data-h="1920" data-name="Instagram/FB Story">
                            <i class="bi bi-phone me-1"></i>স্টোরি
                        </button>
                        <button class="btn btn-sm size-btn rounded-pill px-3 border" data-w="1200" data-h="630" data-name="Facebook Post">
                            <i class="bi bi-facebook me-1"></i>ফেসবুক
                        </button>
                        <button class="btn btn-sm size-btn rounded-pill px-3 border" data-w="1200" data-h="675" data-name="X/Twitter">
                            <i class="bi bi-twitter-x me-1"></i>টুইটার
                        </button>
                        <button class="btn btn-sm size-btn rounded-pill px-3 border" data-w="1280" data-h="720" data-name="YouTube Thumbnail">
                            <i class="bi bi-youtube me-1"></i>ইউটিউব
                        </button>
                    </div>
                    <p class="text-muted small mb-3" id="sizeLabel">সাইজ: 1080 × 1080px (Instagram Post)</p>

                    <hr>

                    {{-- Image Upload --}}
                    <label class="form-label fw-semibold mb-2">ছবি আপলোড করুন</label>
                    <div class="upload-zone mb-3" id="uploadZone">
                        <i class="bi bi-cloud-arrow-up fs-2 text-muted"></i>
                        <p class="mb-0 small text-muted">ক্লিক করুন বা ড্র্যাগ করুন</p>
                        <input type="file" id="imageUpload" accept="image/*" class="d-none" multiple>
                    </div>
                    <div id="uploadedImages" class="d-flex flex-wrap gap-2 mb-3"></div>

                    <hr>

                    {{-- Date with calendar picker --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">তারিখ</label>
                        <input type="date" id="cardDatePicker" class="form-control rounded-3">
                        <small class="text-muted">ক্যালেন্ডার থেকে তারিখ বাছাই করুন (বাংলায় কনভার্ট হবে)</small>
                    </div>

                    {{-- Headline --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">হেডলাইন</label>
                        <textarea id="cardHeadline" class="form-control rounded-3" rows="3" placeholder="নকশাবহির্ভূত ভবন নির্মাণের অভিযোগ, সাড়ে ৪০ লাখ টাকা জরিমানা"></textarea>
                    </div>

                    {{-- Bottom Text --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">নিচের টেক্সট</label>
                        <input type="text" id="cardBottomText" class="form-control rounded-3" value="বিস্তারিত খবর কমেন্টে.....">
                    </div>

                    <hr>

                    {{-- Color Theme --}}
                    <label class="form-label fw-semibold mb-2">কালার থিম</label>
                    <div class="d-flex gap-2 mb-3">
                        <div class="color-swatch active" style="background: #E53935;" data-primary="#E53935" data-secondary="#B71C1C" title="লাল"></div>
                        <div class="color-swatch" style="background: #1565C0;" data-primary="#1565C0" data-secondary="#0D47A1" title="নীল"></div>
                        <div class="color-swatch" style="background: #2E7D32;" data-primary="#2E7D32" data-secondary="#1B5E20" title="সবুজ"></div>
                        <div class="color-swatch" style="background: #F57F17;" data-primary="#F57F17" data-secondary="#E65100" title="কমলা"></div>
                        <div class="color-swatch" style="background: #6A1B9A;" data-primary="#6A1B9A" data-secondary="#4A148C" title="বেগুনি"></div>
                        <div class="color-swatch" style="background: #212121;" data-primary="#212121" data-secondary="#000000" title="কালো"></div>
                    </div>

                    {{-- Logo & Website info --}}
                    @if($seoSettings?->photo_card_logo)
                    <div class="alert alert-success alert-sm py-2 mb-3">
                        <small><i class="bi bi-check-circle me-1"></i>ফটোকার্ড লোগো সেট আছে</small>
                    </div>
                    @elseif($seoSettings?->logo)
                    <div class="alert alert-info alert-sm py-2 mb-3">
                        <small><i class="bi bi-info-circle me-1"></i>সাইট লোগো ব্যবহার হচ্ছে (আলাদা ফটোকার্ড লোগো সেট করতে:
                        <a href="{{ route('admin.settings') }}#logosSettings">সেটিংস → Logos & Images</a>)</small>
                    </div>
                    @else
                    <div class="alert alert-warning alert-sm py-2 mb-3">
                        <small><i class="bi bi-exclamation-triangle me-1"></i>লোগো সেট করুন:
                        <a href="{{ route('admin.settings') }}#logosSettings">সেটিংস → Logos & Images</a></small>
                    </div>
                    @endif

                    <hr>

                    {{-- Download --}}
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary rounded-pill flex-fill" id="downloadPng">
                            <i class="bi bi-download me-1"></i>PNG ডাউনলোড
                        </button>
                        <button class="btn btn-outline-primary rounded-pill flex-fill" id="downloadJpg">
                            <i class="bi bi-download me-1"></i>JPG ডাউনলোড
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Canvas Preview --}}
        <div class="col-lg-7 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-eye me-2"></i>প্রিভিউ</h6>
                    <span class="badge bg-light text-dark rounded-pill" id="previewSizeLabel">1080 × 1080</span>
                </div>
                <div class="card-body px-4 pb-4 text-center">
                    <canvas id="photoCardCanvas" width="1080" height="1080"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const canvas = document.getElementById('photoCardCanvas');
    const ctx = canvas.getContext('2d');

    let cardW = 1080, cardH = 1080;
    let primaryColor = '#E53935', secondaryColor = '#B71C1C';
    let uploadedImages = [];
    let logoImage = null;
    const siteUrl = @json($seoSettings->site_url ?? '');
    const photoCardLogoUrl = @json($seoSettings?->photo_card_logo ? asset('storage/' . $seoSettings->photo_card_logo) : ($seoSettings?->logo ? asset('storage/' . $seoSettings->logo) : null));

    // High quality rendering settings
    ctx.imageSmoothingEnabled = true;
    ctx.imageSmoothingQuality = 'high';

    // Preload SolaimanLipi fonts for canvas usage
    const fontNormal = new FontFace('SolaimanLipi', 'url(/fonts/SolaimanLipi.ttf)', { weight: '400' });
    const fontBold = new FontFace('SolaimanLipi', 'url(/fonts/SolaimanLipi-Bold.ttf)', { weight: '700' });
    Promise.all([fontNormal.load(), fontBold.load()]).then(fonts => {
        fonts.forEach(f => document.fonts.add(f));
        render();
    });

    // Load photo card logo from settings
    if (photoCardLogoUrl) {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => { logoImage = img; render(); };
        img.onerror = () => { console.warn('Logo load failed:', photoCardLogoUrl); };
        img.src = photoCardLogoUrl;
    }

    // Bengali date helpers
    const bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    const bnMonths = ['জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'];
    function toBn(n) { return String(n).split('').map(d => bnDigits[d] || d).join(''); }

    function dateToBengali(dateObj) {
        return toBn(String(dateObj.getDate()).padStart(2,'0')) + ' ' + bnMonths[dateObj.getMonth()] + ' ' + toBn(dateObj.getFullYear());
    }

    // Set today's date in the date picker
    const datePicker = document.getElementById('cardDatePicker');
    const today = new Date();
    datePicker.value = today.toISOString().split('T')[0];
    let currentBnDate = dateToBengali(today);

    datePicker.addEventListener('change', function() {
        if (this.value) {
            const parts = this.value.split('-');
            const d = new Date(+parts[0], +parts[1] - 1, +parts[2]);
            currentBnDate = dateToBengali(d);
        } else {
            currentBnDate = '';
        }
        render();
    });

    // ── Size buttons ──
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            cardW = +this.dataset.w;
            cardH = +this.dataset.h;
            canvas.width = cardW;
            canvas.height = cardH;
            document.getElementById('sizeLabel').textContent = `সাইজ: ${cardW} × ${cardH}px (${this.dataset.name})`;
            document.getElementById('previewSizeLabel').textContent = `${cardW} × ${cardH}`;
            render();
        });
    });

    // ── Color swatches ──
    document.querySelectorAll('.color-swatch').forEach(sw => {
        sw.addEventListener('click', function() {
            document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
            this.classList.add('active');
            primaryColor = this.dataset.primary;
            secondaryColor = this.dataset.secondary;
            render();
        });
    });

    // ── Image Upload ──
    const uploadZone = document.getElementById('uploadZone');
    const imageInput = document.getElementById('imageUpload');
    uploadZone.addEventListener('click', () => imageInput.click());
    uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('dragover'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
    uploadZone.addEventListener('drop', e => {
        e.preventDefault(); uploadZone.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });
    imageInput.addEventListener('change', e => handleFiles(e.target.files));

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => {
                const img = new Image();
                img.onload = () => {
                    uploadedImages.push(img);
                    renderThumbnails();
                    render();
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    function renderThumbnails() {
        const container = document.getElementById('uploadedImages');
        container.innerHTML = '';
        uploadedImages.forEach((img, i) => {
            const wrap = document.createElement('div');
            wrap.className = 'position-relative';
            wrap.style.cssText = 'width:64px;height:64px;';
            const thumb = document.createElement('img');
            thumb.src = img.src;
            thumb.className = 'rounded-3 w-100 h-100 object-fit-cover';
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle p-0';
            btn.style.cssText = 'width:20px;height:20px;font-size:10px;line-height:1;';
            btn.innerHTML = '&times;';
            btn.onclick = () => { uploadedImages.splice(i, 1); renderThumbnails(); render(); };
            wrap.append(thumb, btn);
            container.appendChild(wrap);
        });
    }

    // ── Text inputs trigger re-render ──
    ['cardHeadline', 'cardBottomText'].forEach(id => {
        document.getElementById(id).addEventListener('input', render);
    });

    // ── Main Render ──
    function render() {
        ctx.clearRect(0, 0, cardW, cardH);

        // High quality rendering (reapply after canvas resize)
        ctx.imageSmoothingEnabled = true;
        ctx.imageSmoothingQuality = 'high';

        // Background
        ctx.fillStyle = '#1a1a1a';
        ctx.fillRect(0, 0, cardW, cardH);

        const padding = Math.round(cardW * 0.03);
        const headlineAreaH = Math.round(cardH * 0.28);
        const bottomBarH = Math.round(cardH * 0.06);
        const imgAreaTop = 0;
        const imgAreaH = cardH - headlineAreaH - bottomBarH;

        // Draw uploaded images
        if (uploadedImages.length === 1) {
            drawCover(uploadedImages[0], 0, imgAreaTop, cardW, imgAreaH);
        } else if (uploadedImages.length >= 2) {
            const halfW = Math.floor(cardW / 2) - 2;
            drawCover(uploadedImages[0], 0, imgAreaTop, halfW, imgAreaH);
            drawCover(uploadedImages[1], halfW + 4, imgAreaTop, halfW, imgAreaH);
        } else {
            ctx.fillStyle = '#333';
            ctx.fillRect(0, imgAreaTop, cardW, imgAreaH);
            ctx.fillStyle = '#666';
            ctx.font = `${Math.round(cardW * 0.04)}px "SolaimanLipi", sans-serif`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('ছবি আপলোড করুন', cardW / 2, imgAreaTop + imgAreaH / 2);
        }

        // Dark gradient overlay at bottom of image area
        const gradH = Math.round(imgAreaH * 0.4);
        const grad = ctx.createLinearGradient(0, imgAreaTop + imgAreaH - gradH, 0, imgAreaTop + imgAreaH);
        grad.addColorStop(0, 'rgba(0,0,0,0)');
        grad.addColorStop(1, 'rgba(0,0,0,0.6)');
        ctx.fillStyle = grad;
        ctx.fillRect(0, imgAreaTop + imgAreaH - gradH, cardW, gradH);

        // ── Date tag (top-left) ──
        if (currentBnDate) {
            const dateFontSize = Math.round(cardW * 0.028);
            ctx.font = `700 ${dateFontSize}px "SolaimanLipi", sans-serif`;
            const dtw = ctx.measureText(currentBnDate).width;
            const datePadX = Math.round(cardW * 0.02);
            const datePadY = Math.round(cardW * 0.012);
            const rx = padding, ry = padding;
            const rw = dtw + datePadX * 2, rh = dateFontSize + datePadY * 2;
            const r = Math.round(rh * 0.25);

            ctx.fillStyle = primaryColor;
            roundRect(ctx, rx, ry, rw, rh, r);
            ctx.fill();

            ctx.fillStyle = '#FFFFFF';
            ctx.textAlign = 'left';
            ctx.textBaseline = 'middle';
            ctx.fillText(currentBnDate, rx + datePadX, ry + rh / 2 + 2);
        }

        // ── Headline area (primary color) ──
        const headY = imgAreaTop + imgAreaH;
        ctx.fillStyle = primaryColor;
        ctx.fillRect(0, headY, cardW, headlineAreaH);

        // ── Logo centered at the junction of image area and headline area ──
        if (logoImage) {
            const logoMaxH = Math.round(cardH * 0.1);
            const logoMaxW = Math.round(cardW * 0.3);
            const logoRatio = Math.min(logoMaxW / logoImage.width, logoMaxH / logoImage.height);
            const lw = logoImage.width * logoRatio;
            const lh = logoImage.height * logoRatio;
            const lx = (cardW - lw) / 2;
            const ly = headY - (lh / 2); // vertically centered at the junction line

            // White background behind logo
            const logoPadX = Math.round(lw * 0.15);
            const logoPadY = Math.round(lh * 0.2);
            ctx.fillStyle = 'rgba(255,255,255,0.92)';
            roundRect(ctx, lx - logoPadX, ly - logoPadY, lw + logoPadX * 2, lh + logoPadY * 2, Math.round(logoPadY * 0.6));
            ctx.fill();

            ctx.drawImage(logoImage, lx, ly, lw, lh);
        }

        // ── Headline text ──
        const headline = document.getElementById('cardHeadline').value;
        if (headline) {
            const headFontSize = Math.round(cardW * 0.048);
            ctx.font = `800 ${headFontSize}px "SolaimanLipi", sans-serif`;
            ctx.fillStyle = '#FFFFFF';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'top';
            const maxTextW = cardW - padding * 6;
            const lines = wrapText(ctx, headline, maxTextW);
            const lineH = headFontSize * 1.4;
            const totalTextH = lines.length * lineH;
            const textStartY = headY + (headlineAreaH - totalTextH) / 2;
            lines.forEach((line, i) => {
                ctx.fillText(line, cardW / 2, textStartY + i * lineH);
            });
        }

        // ── Bottom bar ──
        const botY = headY + headlineAreaH;
        ctx.fillStyle = secondaryColor;
        ctx.fillRect(0, botY, cardW, bottomBarH);

        const botFontSize = Math.round(cardW * 0.024);
        ctx.textBaseline = 'middle';
        const botMid = botY + bottomBarH / 2;

        // Left: arrow + bottom text
        const bottomText = document.getElementById('cardBottomText').value;
        if (bottomText) {
            const triSize = Math.round(botFontSize * 0.7);
            const triX = padding * 2;
            ctx.fillStyle = primaryColor;
            ctx.beginPath();
            ctx.moveTo(triX, botMid - triSize / 2);
            ctx.lineTo(triX + triSize, botMid);
            ctx.lineTo(triX, botMid + triSize / 2);
            ctx.closePath();
            ctx.fill();

            ctx.fillStyle = '#FFFFFF';
            ctx.font = `600 ${botFontSize}px "SolaimanLipi", sans-serif`;
            ctx.textAlign = 'left';
            ctx.fillText(bottomText, triX + triSize + Math.round(cardW * 0.01), botMid + 2);
        }

        // Right: website URL from settings (auto)
        if (siteUrl) {
            // Strip protocol for display
            const displayUrl = siteUrl.replace(/^https?:\/\//, '').replace(/\/$/, '');
            ctx.fillStyle = '#FFFFFF';
            ctx.font = `700 ${botFontSize}px "SolaimanLipi", sans-serif`;
            ctx.textAlign = 'right';
            const siteX = cardW - padding * 2;
            const siteTextW = ctx.measureText(displayUrl).width;

            ctx.fillText(displayUrl, siteX, botMid + 2);

            // Globe icon
            const globeR = Math.round(botFontSize * 0.45);
            const gx = siteX - siteTextW - globeR * 2 - 6;
            ctx.beginPath();
            ctx.arc(gx + globeR, botMid, globeR, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(255,255,255,0.25)';
            ctx.fill();
            ctx.fillStyle = '#FFFFFF';
            ctx.font = `${Math.round(globeR * 1.2)}px "SolaimanLipi", sans-serif`;
            ctx.textAlign = 'center';
            ctx.fillText('🌐', gx + globeR, botMid + 2);
        }
    }

    function drawCover(img, x, y, w, h) {
        const imgRatio = img.width / img.height;
        const boxRatio = w / h;
        let sx, sy, sw, sh;
        if (imgRatio > boxRatio) {
            sh = img.height; sw = sh * boxRatio;
            sx = (img.width - sw) / 2; sy = 0;
        } else {
            sw = img.width; sh = sw / boxRatio;
            sx = 0; sy = (img.height - sh) / 2;
        }
        ctx.drawImage(img, sx, sy, sw, sh, x, y, w, h);
    }

    function wrapText(ctx, text, maxWidth) {
        const words = text.split('');
        let lines = [], line = '';
        for (let ch of words) {
            const test = line + ch;
            if (ctx.measureText(test).width > maxWidth && line) {
                lines.push(line);
                line = ch;
            } else {
                line = test;
            }
        }
        if (line) lines.push(line);
        return lines;
    }

    function roundRect(ctx, x, y, w, h, r) {
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.lineTo(x + w - r, y);
        ctx.quadraticCurveTo(x + w, y, x + w, y + r);
        ctx.lineTo(x + w, y + h - r);
        ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
        ctx.lineTo(x + r, y + h);
        ctx.quadraticCurveTo(x, y + h, x, y + h - r);
        ctx.lineTo(x, y + r);
        ctx.quadraticCurveTo(x, y, x + r, y);
        ctx.closePath();
    }

    // ── Downloads ──
    document.getElementById('downloadPng').addEventListener('click', () => {
        const link = document.createElement('a');
        link.download = 'photocard-' + Date.now() + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });

    document.getElementById('downloadJpg').addEventListener('click', () => {
        const tmpCanvas = document.createElement('canvas');
        tmpCanvas.width = cardW; tmpCanvas.height = cardH;
        const tmpCtx = tmpCanvas.getContext('2d');
        tmpCtx.fillStyle = '#FFFFFF';
        tmpCtx.fillRect(0, 0, cardW, cardH);
        tmpCtx.drawImage(canvas, 0, 0);
        const link = document.createElement('a');
        link.download = 'photocard-' + Date.now() + '.jpg';
        link.href = tmpCanvas.toDataURL('image/jpeg', 1.0);
        link.click();
    });

    // Initial render
    render();
})();
</script>
@endpush
