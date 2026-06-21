<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SPARK · Dunia Bajak Laut</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        /* ----- reset & base ----- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
            background: #0a0a0a;
            font-family: 'Segoe UI', 'Poppins', system-ui, -apple-system, sans-serif;
        }

        /* Font Battlesbridge */
        @font-face {
            font-family: 'Battlesbridge';
            src: url('{{ asset("font/BattlesbridgeDemo-AL126.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        /* ----- hide scrollbar ----- */
        .main-container {
            width: 100%;
            height: 100vh;
            overflow-y: scroll;
            overflow-x: hidden;
            scroll-behavior: smooth;
            position: relative;
        }

        .main-container::-webkit-scrollbar {
            display: none;
        }

        .main-container {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* sticky frame layer */
        .frame-sticky {
            position: sticky;
            top: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background: #0a0a0a;
        }

        /* container untuk crossfade effect */
        .frame-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        /* Hero Animation Layer */
        #frameCanvas {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            object-fit: cover;
            image-rendering: auto;
            display: block;
            opacity: 1;
            transition: opacity 1s ease-in-out;
            z-index: 2;
        }

        #frameCanvas.fade-out {
            opacity: 0;
        }

        #frameCanvasNext {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            object-fit: cover;
            image-rendering: auto;
            display: block;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            z-index: 2;
        }

        #frameCanvasNext.fade-in {
            opacity: 1;
        }

        /* Final Content Layer */
        .final-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
            background: #0a0a0a;
            padding: 40px 20px;
            background-image: url('/images/background/hero-final.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .final-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 10, 10, 0.7);
            z-index: 0;
        }

        .final-content.visible {
            opacity: 1;
            z-index: 3;
        }

        .final-content > * {
            position: relative;
            z-index: 1;
        }

        /* Title - pakai Battlesbridge - DIPERKECIL & DIPERJAUH */
        .final-content .final-title {
            font-family: 'Battlesbridge', sans-serif;
            font-size: clamp(2.2rem, 6vw, 3.8rem);
            font-weight: normal;
            color: #ffffff;
            letter-spacing: 0.05em;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1) 0.3s;
            margin-bottom: 40px;
            text-shadow: 0 2px 40px rgba(0, 0, 0, 0.5);
            line-height: 1;
        }

        .final-content .final-title.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Subtitle */
        .final-content .final-subtitle {
            font-family: 'Segoe UI', 'Poppins', sans-serif;
            font-size: clamp(0.85rem, 1.5vw, 1.3rem);
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1) 0.5s;
            margin-bottom: 12px;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
        }

        .final-content .final-subtitle.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Tagline tambahan */
        .final-content .final-tagline {
            font-family: 'Segoe UI', 'Poppins', sans-serif;
            font-size: clamp(0.6rem, 1vw, 0.85rem);
            font-weight: 300;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 0.3em;
            text-transform: uppercase;
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1) 0.7s;
            margin-bottom: 36px;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
        }

        .final-content .final-tagline.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Decorative line */
        .final-content .decorative-line {
            width: 0px;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
            margin: 8px 0 24px 0;
            opacity: 0;
            transition: all 1s ease 0.4s;
        }

        .final-content .decorative-line.visible {
            opacity: 1;
            width: 60px;
        }

        /* CTA Button */
        .final-content .cta-button {
            padding: 12px 40px;
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0;
            font-size: 0.75rem;
            font-weight: 300;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.4s ease;
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1) 0.9s;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(4px);
            text-decoration: none;
        }

        .final-content .cta-button.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .final-content .cta-button:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.6);
            transform: translateY(-2px);
        }

        .final-content .cta-button:active {
            transform: scale(0.97);
        }

        /* scroll spacer */
        .spacer {
            height: 200vh;
            width: 100%;
            position: relative;
            z-index: -1;
        }

        /* ----- loading ----- */
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            font-weight: 300;
            letter-spacing: 0.15em;
            z-index: 5;
            background: rgba(0, 0, 0, 0.5);
            padding: 16px 32px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .loading .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-top-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            flex-shrink: 0;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ----- text overlay untuk hero animation ----- */
        .text-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
            pointer-events: none;
            text-align: center;
            padding: 0 20px;
            transition: opacity 0.8s ease;
        }

        .text-overlay.hidden {
            opacity: 0;
        }

        .text-overlay .text-item {
            position: absolute;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 40px rgba(0, 0, 0, 0.5);
            opacity: 0;
            transform: translateY(30px) scale(0.95);
            transition: none;
            letter-spacing: 0.02em;
            line-height: 1.2;
            margin: 0;
            pointer-events: none;
        }

        .text-overlay .belajar {
            font-size: clamp(2.5rem, 8vw, 4.5rem);
            font-weight: 700;
        }

        .text-overlay .bertumbuh {
            font-size: clamp(2.5rem, 8vw, 4.5rem);
            font-weight: 700;
        }

        .text-overlay .bersinar {
            font-size: clamp(2.5rem, 8vw, 4.5rem);
            font-weight: 700;
        }

        .text-overlay .spark {
            font-family: 'Battlesbridge', sans-serif;
            font-size: clamp(4rem, 14vw, 7.5rem);
            font-weight: normal;
            letter-spacing: 0.08em;
            color: #ffffff;
            text-shadow: 0 2px 40px rgba(0, 0, 0, 0.5);
        }

        /* scroll indicator - DIPERBESAR & WARNA PUTIH */
        .scroll-indicator {
            position: absolute;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            color: #ffffff;
            font-size: 0.9rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            animation: bounceDown 2.4s ease-in-out infinite;
            pointer-events: none;
            font-weight: 400;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.8);
            transition: opacity 0.5s ease;
            font-family: 'Segoe UI', 'Poppins', sans-serif;
        }

        .scroll-indicator.hidden {
            opacity: 0 !important;
            animation: none !important;
            pointer-events: none !important;
        }

        @keyframes bounceDown {
            0%, 100% {
                transform: translateX(-50%) translateY(0);
                opacity: 0.7;
            }
            50% {
                transform: translateX(-50%) translateY(8px);
                opacity: 1;
            }
        }

        /* responsive */
        @media (max-width: 600px) {
            .scroll-indicator {
                bottom: 24px;
                font-size: 0.75rem;
                letter-spacing: 0.25em;
            }
            .final-content .cta-button {
                padding: 10px 28px;
                font-size: 0.65rem;
            }
            .spacer {
                height: 150vh;
            }
            .text-overlay .belajar {
                font-size: clamp(2rem, 7vw, 3.5rem);
            }
            .text-overlay .bertumbuh {
                font-size: clamp(2rem, 7vw, 3.5rem);
            }
            .text-overlay .bersinar {
                font-size: clamp(2rem, 7vw, 3.5rem);
            }
            .text-overlay .spark {
                font-size: clamp(3rem, 10vw, 5rem);
            }
            .final-content .final-title {
                font-size: clamp(2rem, 6vw, 3rem);
                margin-bottom: 30px;
            }
            .final-content .final-subtitle {
                margin-bottom: 10px;
            }
            .final-content .final-tagline {
                margin-bottom: 28px;
            }
            .final-content .decorative-line {
                width: 0px;
                margin: 6px 0 18px 0;
            }
            .final-content .decorative-line.visible {
                width: 40px;
            }
        }
    </style>
</head>
<body>

    <div class="main-container" id="mainContainer">
        <div class="frame-sticky">
            <div class="frame-container">
                <!-- Hero Animation Layer -->
                <img id="frameCanvas" src="{{ asset('hero/0001.png') }}" alt="Hero Animation" />
                <img id="frameCanvasNext" alt="Hero Animation Next" />
                
                <!-- Final Content Layer -->
                <div class="final-content" id="finalContent">
                    <div class="decorative-line" id="decorativeLine"></div>
                    <h1 class="final-title" id="finalTitle">SPARK</h1>
                    <p class="final-subtitle" id="finalSubtitle">PKKMB FASILKOM UNSRI 2026</p>
                    <p class="final-tagline" id="finalTagline">#SPARK2026#DYANTARA2026</p>
                    <!-- Tombol Buka Peta dengan link ke route student.map -->
                    <a href="{{ route('student.map') }}" class="cta-button" id="ctaButton">Buka Peta</a>
                </div>
            </div>

            <div class="loading" id="loadingIndicator">
                <span class="spinner"></span> Memuat animasi
            </div>

            <div class="text-overlay" id="textOverlay">
                <h1 class="text-item belajar" id="textBelajar">Belajar Bersama</h1>
                <h1 class="text-item bertumbuh" id="textBertumbuh">Bertumbuh Bersama</h1>
                <h1 class="text-item bersinar" id="textBersinar">Bersinar Bersama</h1>
                <h1 class="text-item spark" id="textSpark">SPARK</h1>
            </div>

            <!-- Scroll Indicator -->
            <div class="scroll-indicator" id="scrollIndicator">Scroll</div>
        </div>
        <div class="spacer"></div>
    </div>

    <script>
        // ============================================================
        //  CONFIG
        // ============================================================
        const TOTAL_FRAMES = 192;
        const BASE_PATH = "/hero/";
        const EXT = ".png";
        const TRANSITION_START = 0.80;

        // ============================================================
        //  DOM refs
        // ============================================================
        const canvas = document.getElementById('frameCanvas');
        const canvasNext = document.getElementById('frameCanvasNext');
        const container = document.getElementById('mainContainer');
        const loadingEl = document.getElementById('loadingIndicator');
        const scrollIndicator = document.getElementById('scrollIndicator');
        const textOverlay = document.getElementById('textOverlay');
        const finalContent = document.getElementById('finalContent');
        const ctaButton = document.getElementById('ctaButton');

        const textEls = {
            belajar: document.getElementById('textBelajar'),
            bertumbuh: document.getElementById('textBertumbuh'),
            bersinar: document.getElementById('textBersinar'),
            spark: document.getElementById('textSpark'),
        };

        const finalEls = {
            title: document.getElementById('finalTitle'),
            subtitle: document.getElementById('finalSubtitle'),
            tagline: document.getElementById('finalTagline'),
            line: document.getElementById('decorativeLine'),
            cta: document.getElementById('ctaButton'),
        };

        // ============================================================
        //  STATE
        // ============================================================
        let frames = [];
        let loaded = 0;
        let ready = false;
        let currentIndex = 1;
        let isTransitioning = false;
        let hasReachedFinal = false;
        let isGoingBack = false;
        let isFirstScroll = true;
        let currentActiveIndex = -1;

        // ============================================================
        //  LOAD FRAMES
        // ============================================================
        function loadFrames() {
            for (let i = 1; i <= TOTAL_FRAMES; i++) {
                const img = new Image();
                const num = String(i).padStart(4, '0');
                img.src = BASE_PATH + num + EXT;

                img.onload = () => {
                    loaded++;
                    updateLoadingProgress();
                    if (loaded === TOTAL_FRAMES) onAllLoaded();
                };
                img.onerror = () => {
                    loaded++;
                    updateLoadingProgress();
                    if (loaded === TOTAL_FRAMES) onAllLoaded();
                };
                frames.push(img);
            }
        }

        function updateLoadingProgress() {
            const pct = Math.round((loaded / TOTAL_FRAMES) * 100);
            if (loadingEl) {
                loadingEl.innerHTML = `<span class="spinner"></span> Memuat animasi ${pct}%`;
            }
        }

        function onAllLoaded() {
            ready = true;
            if (loadingEl) loadingEl.style.display = 'none';
            console.log('✅ Semua frame siap');
            updateFrame(0);
        }

        // ============================================================
        //  SHOW/HIDE FINAL CONTENT
        // ============================================================
        function showFinalContent() {
            if (hasReachedFinal) return;
            hasReachedFinal = true;
            isGoingBack = false;

            // Sembunyikan scroll indicator dengan force
            scrollIndicator.classList.add('hidden');
            scrollIndicator.style.display = 'none';
            
            canvas.classList.add('fade-out');
            textOverlay.classList.add('hidden');
            
            // Sembunyikan semua teks
            Object.values(textEls).forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px) scale(0.95)';
                el.classList.remove('visible');
            });

            finalContent.classList.add('visible');
            
            setTimeout(() => {
                finalEls.line.classList.add('visible');
            }, 200);
            
            setTimeout(() => {
                finalEls.title.classList.add('visible');
            }, 400);
            
            setTimeout(() => {
                finalEls.subtitle.classList.add('visible');
            }, 600);
            
            setTimeout(() => {
                finalEls.tagline.classList.add('visible');
            }, 700);
            
            setTimeout(() => {
                finalEls.cta.classList.add('visible');
            }, 900);
        }

        function hideFinalContent() {
            if (!hasReachedFinal) return;
            if (isGoingBack) return;
            
            isGoingBack = true;

            finalContent.classList.remove('visible');
            Object.values(finalEls).forEach(el => {
                el.classList.remove('visible');
            });

            canvas.classList.remove('fade-out');
            canvas.style.opacity = 1;
            textOverlay.classList.remove('hidden');
            
            // Munculkan kembali scroll indicator
            scrollIndicator.classList.remove('hidden');
            scrollIndicator.style.display = '';

            hasReachedFinal = false;
            isGoingBack = false;
            isTransitioning = false;
            currentActiveIndex = -1;
            
            const maxScroll = container.scrollHeight - window.innerHeight;
            let progress = maxScroll > 0 ? Math.min(container.scrollTop / maxScroll, 1) : 0;
            updateFrame(progress);
        }

        // ============================================================
        //  UPDATE TEXT WITH SLIDESHOW EFFECT - FIXED
        // ============================================================
        function updateTextSlideshow(progress) {
            const textKeys = ['belajar', 'bertumbuh', 'bersinar', 'spark'];
            const totalItems = textKeys.length;
            
            // Setiap teks muncul dengan durasi yang lebih panjang dan ada jeda
            // Total progress: 0.05 - 0.75 (0.70 total)
            const startOffset = 0.05;
            const segmentDuration = 0.165; // Durasi setiap teks
            const gapDuration = 0.02; // Jeda antar teks
            
            let activeIndex = -1;
            let fadeProgress = 0;
            
            for (let i = 0; i < totalItems; i++) {
                const start = startOffset + (i * (segmentDuration + gapDuration));
                const end = start + segmentDuration;
                
                if (progress >= start && progress < end) {
                    activeIndex = i;
                    // Hitung progress fade in/out dalam segment (0-1)
                    const segmentProgress = (progress - start) / segmentDuration;
                    fadeProgress = Math.min(Math.max(segmentProgress, 0), 1);
                    break;
                }
            }
            
            // Reset semua teks ke keadaan default (hidden)
            textKeys.forEach(key => {
                const el = textEls[key];
                // Jika tidak aktif, sembunyikan
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px) scale(0.95)';
                el.classList.remove('visible');
            });
            
            // Tampilkan teks yang aktif dengan efek
            if (activeIndex >= 0 && activeIndex < totalItems) {
                const activeKey = textKeys[activeIndex];
                const activeEl = textEls[activeKey];
                
                // Fase: 0-0.25 = fade in, 0.25-0.75 = stay, 0.75-1.0 = fade out
                let opacity = 0;
                let translateY = 30;
                let scale = 0.95;
                
                if (fadeProgress < 0.25) {
                    // Fade in
                    const t = fadeProgress / 0.25;
                    opacity = t * t * (3 - 2 * t); // Smooth step
                    translateY = 30 - (30 * t);
                    scale = 0.95 + (0.05 * t);
                } else if (fadeProgress < 0.75) {
                    // Stay
                    opacity = 1;
                    translateY = 0;
                    scale = 1;
                } else {
                    // Fade out
                    const t = (fadeProgress - 0.75) / 0.25;
                    opacity = 1 - (t * t * (3 - 2 * t)); // Smooth step reverse
                    translateY = -20 * t;
                    scale = 1 - (0.05 * t);
                }
                
                // Terapkan style
                activeEl.style.opacity = opacity;
                activeEl.style.transform = `translateY(${translateY}px) scale(${scale})`;
                
                if (opacity > 0.01) {
                    activeEl.classList.add('visible');
                } else {
                    activeEl.classList.remove('visible');
                }
            }
        }

        // ============================================================
        //  UPDATE FRAME
        // ============================================================
        function updateFrame(rawProgress) {
            if (!ready) return;

            let p = Math.min(Math.max(rawProgress, 0), 1);

            if (hasReachedFinal && p < TRANSITION_START - 0.05) {
                hideFinalContent();
                return;
            }

            if (p >= TRANSITION_START && !hasReachedFinal && !isGoingBack) {
                const fadeProgress = Math.min((p - TRANSITION_START) / (1 - TRANSITION_START), 1);
                canvas.style.opacity = 1 - fadeProgress;
                
                const lastFrameIdx = TOTAL_FRAMES - 1;
                if (frames[lastFrameIdx] && canvasNext.src !== frames[lastFrameIdx].src) {
                    canvasNext.src = frames[lastFrameIdx].src;
                    canvasNext.style.opacity = fadeProgress;
                }
                
                if (fadeProgress >= 0.95 && !isTransitioning) {
                    isTransitioning = true;
                    showFinalContent();
                }
            } 
            
            if (!hasReachedFinal && p < TRANSITION_START) {
                if (canvas.style.opacity !== '1') {
                    canvas.style.opacity = 1;
                    canvasNext.style.opacity = 0;
                }
                
                const floatIndex = p * TOTAL_FRAMES;
                let idx = Math.floor(floatIndex) % TOTAL_FRAMES;
                
                if (idx < 0) idx = 0;
                if (idx >= TOTAL_FRAMES) idx = TOTAL_FRAMES - 1;
                
                const frameIndex = idx + 1;
                
                if (frameIndex !== currentIndex && frames[idx]) {
                    currentIndex = frameIndex;
                    canvas.src = frames[idx].src;
                }

                // Update text dengan efek slideshow
                updateTextSlideshow(p);
            }
        }

        // ============================================================
        //  SCROLL HANDLER
        // ============================================================
        let ticking = false;
        let lastScrollTop = 0;

        container.addEventListener('scroll', function() {
            if (isFirstScroll) {
                isFirstScroll = false;
                // Mulai animasi text dari awal saat first scroll
                const maxScroll = container.scrollHeight - window.innerHeight;
                let progress = maxScroll > 0 ? Math.min(container.scrollTop / maxScroll, 1) : 0;
                updateFrame(progress);
                return;
            }

            if (!ticking) {
                requestAnimationFrame(() => {
                    const maxScroll = container.scrollHeight - window.innerHeight;
                    let progress = maxScroll > 0 ? Math.min(container.scrollTop / maxScroll, 1) : 0;
                    
                    const scrollTop = container.scrollTop;
                    lastScrollTop = scrollTop;
                    
                    updateFrame(progress);
                    ticking = false;
                });
                ticking = true;
            }
        });

        // ============================================================
        //  KEYBOARD NAVIGATION
        // ============================================================
        document.addEventListener('keydown', (e) => {
            const step = 60;
            if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                container.scrollBy({ top: step, behavior: 'smooth' });
                e.preventDefault();
            } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                container.scrollBy({ top: -step, behavior: 'smooth' });
                e.preventDefault();
            }
        });

        // ============================================================
        //  CTA BUTTON - Redirect ke halaman map
        // ============================================================
        // Sudah menggunakan tag <a> dengan href="{{ route('student.map') }}"
        // Tidak perlu event listener lagi

        // ============================================================
        //  INIT
        // ============================================================
        loadFrames();

        // Tampilkan frame pertama sebagai placeholder
        setTimeout(() => {
            if (!ready) {
                canvas.src = BASE_PATH + '0001' + EXT;
            }
        }, 1200);

        console.log('SPARK · Clean Design');
        console.log('Total frames: ' + TOTAL_FRAMES);
        console.log('Transisi di ' + (TRANSITION_START * 100) + '%');
        console.log('🗺️ Tombol "Buka Peta" mengarah ke: ' + '{{ route("student.map") }}');
    </script>
</body>
</html>