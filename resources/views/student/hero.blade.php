<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SPARK · Dunia Bajak Laut</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        /* ----- Custom Fonts & Keyframes Layer ----- */
        @font-face {
            font-family: 'Battlesbridge';
            src: url("{{ asset('font/BattlesbridgeDemo-AL126.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @keyframes bounceDown {

            0%,
            100% {
                /* Kunci posisi X tetap di tengah (-50%), hanya Y yang bergerak up/down */
                transform: translate(-50%, 0);
                opacity: 0.7;
            }

            50% {
                transform: translate(-50%, 8px);
                opacity: 1;
            }
        }

        /* Helper Class untuk Animasi Custom */
        .animate-bounce-down {
            animation: bounceDown 2.4s ease-in-out infinite;
        }

        /* Helper Class untuk Animasi Custom */
        .animate-bounce-down {
            animation: bounceDown 2.4s ease-in-out infinite;
        }

        /* Hide Scrollbar Utilities */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Transisi khusus teks agar pergerakan transform & opacity sinkron dan smooth */
        .smooth-text-transition {
            transition: opacity 0.5s cubic-bezier(0.25, 1, 0.5, 1),
                transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
            will-change: transform, opacity;
        }
    </style>
</head>

<body class="bg-[#0a0a0a] m-0 p-0 overflow-hidden h-screen font-sans">
    <div class="main-container no-scrollbar relative w-full h-screen overflow-x-hidden overflow-y-scroll scroll-smooth"
        id="mainContainer">
        <div class="frame-sticky sticky top-0 w-full h-screen overflow-hidden bg-[#0a0a0a]">
            <div
                class="frame-container absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full overflow-hidden">
                <div class="absolute top-6 right-6 md:top-8 right-8 z-[30] flex items-center">
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="m-0">
                        @csrf
                        <button type="submit"
                            class="px-5 py-2 border border-white/20 bg-black/20 text-white/70 hover:text-white hover:border-white/50 hover:bg-white/[0.05] rounded-none text-[10px] md:text-xs tracking-[0.2em] uppercase font-light transition-all duration-300 ease-in-out backdrop-blur-[2px] cursor-pointer active:scale-[0.97]">
                            Logout
                        </button>
                    </form>
                </div>
                <img id="frameCanvas" src="{{ asset('hero/0001.png') }}" alt="Hero Animation"
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full object-cover block opacity-100 transition-opacity duration-1000 ease-in-out z-[2] [image-rendering:auto]" />

                <img id="frameCanvasNext" alt="Hero Animation Next"
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full object-cover block opacity-0 transition-opacity duration-1000 ease-in-out z-[2] [image-rendering:auto]" />

                <div id="finalContent"
                    class="final-content absolute top-0 left-0 w-full h-full z-[1] flex flex-col justify-center items-center opacity-0 transition-all duration-[1200ms] ease-in-out bg-[#0a0a0a] px-5 py-10 bg-cover bg-center bg-no-repeat before:content-[''] before:absolute before:inset-0 before:bg-[#0a0a0a]/70 before:z-0 pointer-events-none"
                    style="background-image: url('/images/background/hero-final.png');">

                    <div id="decorativeLine"
                        class="decorative-line relative z-10 w-0 h-[1px] bg-white/30 my-2 md:my-[6px] mx-0 opacity-0 transition-all duration-1000 ease-in-out">
                    </div>

                    <h1 id="finalTitle"
                        class="final-title relative z-10 font-normal tracking-wide opacity-0 -translate-y-5 transition-all duration-[800ms] cubic-bezier(0.23,1,0.32,1) mb-10 md:mb-[30px] drop-shadow-[0_2px_40px_rgba(0,0,0,0.5)] leading-none text-center text-white text-[clamp(2.2rem,6vw,3.8rem)] md:text-[clamp(2rem,6vw,3rem)]"
                        style="font-family: 'Battlesbridge', sans-serif;">
                        SPARK
                    </h1>

                    <p id="finalSubtitle"
                        class="final-subtitle relative z-10 font-sans text-[clamp(0.85rem,1.5vw,1.3rem)] font-semibold text-white/80 tracking-[0.2em] uppercase opacity-0 -translate-y-[15px] transition-all duration-[800ms] cubic-bezier(0.23,1,0.32,1) mb-3 md:mb-[10px] drop-shadow-[0_2px_20px_rgba(0,0,0,0.5)]">
                        PKKMB FASILKOM UNSRI 2026
                    </p>

                    <p id="finalTagline"
                        class="final-tagline relative z-10 font-sans text-[clamp(0.6rem,1vw,0.85rem)] font-light text-white/40 tracking-[0.3em] uppercase opacity-0 -translate-y-[15px] transition-all duration-[800ms] cubic-bezier(0.23,1,0.32,1) mb-9 md:mb-[28px] drop-shadow-[0_2px_20px_rgba(0,0,0,0.5)]">
                        #SPARK2026#DYANTARA2026
                    </p>

                    <a href="{{ route('student.map') }}" id="ctaButton"
                        class="cta-button relative z-10 px-10 py-3 md:px-7 md:py-[10px] bg-white/[0.05] text-white border border-white/30 rounded-none text-xs md:text-[0.65rem] font-light tracking-[0.2em] uppercase cursor-pointer backdrop-blur-[4px] no-underline opacity-0 -translate-y-[15px] transition-all duration-[800ms] cubic-bezier(0.23,1,0.32,1) hover:bg-white/10 hover:border-white/60 hover:-translate-y-[2px] active:scale-[0.97]">
                        Buka Peta
                    </a>
                </div>
            </div>

            <div id="loadingIndicator"
                class="loading absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white/60 text-sm font-light tracking-wide z-[5] bg-black/50 px-8 py-4 border border-white/10 backdrop-blur-[4px] flex items-center gap-3">
                <span
                    class="spinner w-[18px] h-[18px] border-2 border-white/10 border-t-white/50 rounded-full animate-spin"></span>
                Memuat animasi
            </div>

            <div id="textOverlay"
                class="text-overlay absolute inset-0 flex flex-col justify-between items-center z-10 pointer-events-none text-center px-5 py-24 transition-opacity duration-[800ms] ease-in-out">

                <div></div>

                <h1 id="textSpark"
                    class="text-item spark font-normal text-white drop-shadow-[0_2px_40px_rgba(0,0,0,0.5)] opacity-0 -translate-y-[10px] scale-[0.98] tracking-[0.08em] leading-none m-0 text-[clamp(4rem,14vw,7.5rem)] md:text-[clamp(3rem,10vw,5rem)] smooth-text-transition mt-20"
                    style="font-family: 'Battlesbridge', sans-serif;">
                    SPARK
                </h1>

                <div class="relative w-full h-[50px] mb-10 flex justify-center items-center">
                    <p id="textBelajar"
                        class="text-item belajar absolute font-medium text-white/80 tracking-[0.15em] uppercase m-0 text-base md:text-xl smooth-text-transition opacity-0 translate-y-[15px] drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)]">
                        Belajar Bersama
                    </p>
                    <p id="textBertumbuh"
                        class="text-item bertumbuh absolute font-medium text-white/80 tracking-[0.15em] uppercase m-0 text-base md:text-xl smooth-text-transition opacity-0 translate-y-[15px] drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)]">
                        Bertumbuh Bersama
                    </p>
                    <p id="textBersinar"
                        class="text-item bersinar absolute font-medium text-white/80 tracking-[0.15em] uppercase m-0 text-base md:text-xl smooth-text-transition opacity-0 translate-y-[15px] drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)]">
                        Bersinar Bersama
                    </p>
                </div>
            </div>

            <div id="scrollIndicator"
                class="scroll-indicator animate-bounce-down absolute bottom-8 md:bottom-6 left-1/2 z-20 text-white text-sm md:text-[0.75rem] tracking-[0.3em] md:tracking-[0.25em] uppercase pointer-events-none font-normal drop-shadow-[0_2px_12px_rgba(0,0,0,0.8)] transition-opacity duration-500 ease-in-out font-sans">
                Scroll
            </div>
        </div>

        <div class="spacer h-[200vh] md:h-[150vh] w-full relative -z-[1]"></div>
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
        let hasReachedFinal = false;
        let isGoingBack = false;
        let isFirstScroll = true;

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
                loadingEl.innerHTML =
                    `<span class="spinner w-[18px] h-[18px] border-2 border-white/10 border-top-color:rgba(255,255,255,0.5) rounded-full animate-spin"></span> Memuat animasi ${pct}%`;
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

            scrollIndicator.style.opacity = '0';
            textOverlay.style.opacity = '0';

            finalContent.classList.remove('opacity-0', 'pointer-events-none');
            finalContent.classList.add('opacity-100', 'z-[3]', 'pointer-events-auto');

            setTimeout(() => {
                finalEls.line.classList.remove('opacity-0');
                finalEls.line.classList.add('w-24', 'opacity-100');
            }, 200);

            setTimeout(() => {
                finalEls.title.classList.remove('opacity-0', '-translate-y-5');
                finalEls.title.classList.add('opacity-100', 'translate-y-0');
            }, 400);

            setTimeout(() => {
                finalEls.subtitle.classList.remove('opacity-0', '-translate-y-[15px]');
                finalEls.subtitle.classList.add('opacity-100', 'translate-y-0');
            }, 600);

            setTimeout(() => {
                finalEls.tagline.classList.remove('opacity-0', '-translate-y-[15px]');
                finalEls.tagline.classList.add('opacity-100', 'translate-y-0');
            }, 750);

            setTimeout(() => {
                finalEls.cta.classList.remove('opacity-0', '-translate-y-[15px]');
                finalEls.cta.classList.add('opacity-100', 'translate-y-0');
            }, 900);
        }

        function hideFinalContent() {
            if (!hasReachedFinal || isGoingBack) return;
            isGoingBack = true;

            scrollIndicator.style.opacity = '1';
            textOverlay.style.opacity = '1';

            finalContent.classList.remove('opacity-100', 'z-[3]', 'pointer-events-auto');
            finalContent.classList.add('opacity-0', 'z-[1]', 'pointer-events-none');

            finalEls.line.classList.remove('w-24', 'opacity-100');
            finalEls.line.classList.add('w-0', 'opacity-0');

            finalEls.title.classList.remove('opacity-100', 'translate-y-0');
            finalEls.title.classList.add('opacity-0', '-translate-y-5');

            finalEls.subtitle.classList.remove('opacity-100', 'translate-y-0');
            finalEls.subtitle.classList.add('opacity-0', '-translate-y-[15px]');

            finalEls.tagline.classList.remove('opacity-100', 'translate-y-0');
            finalEls.tagline.classList.add('opacity-0', '-translate-y-[15px]');

            finalEls.cta.classList.remove('opacity-100', 'translate-y-0');
            finalEls.cta.classList.add('opacity-0', '-translate-y-[15px]');

            canvas.style.opacity = '1';

            hasReachedFinal = false;
            isGoingBack = false;

            const maxScroll = container.scrollHeight - window.innerHeight;
            let progress = maxScroll > 0 ? Math.min(container.scrollTop / maxScroll, 1) : 0;
            updateFrame(progress);
        }

        // ============================================================
        //  LOGIKA SEKUENSIAL TEKS (Bersama Selesai -> SPARK Muncul)
        // ============================================================
        function updateTextSlideshow(progress) {
            // 1. ATUR SUBTITLE (Belajar, Bertumbuh, Bersinar) -> Berjalan di awal (0.05 - 0.55)
            const subKeys = ['belajar', 'bertumbuh', 'bersinar'];
            const totalItems = subKeys.length;

            const startOffset = 0.05;
            const segmentDuration = 0.15;
            const gapDuration = 0.01;
            const endOfSlideshow = startOffset + (totalItems * (segmentDuration + gapDuration)); // Berakhir di ~0.53

            let activeIndex = -1;
            let fadeProgress = 0;

            for (let i = 0; i < totalItems; i++) {
                const start = startOffset + (i * (segmentDuration + gapDuration));
                const end = start + segmentDuration;

                if (progress >= start && progress < end) {
                    activeIndex = i;
                    fadeProgress = Math.min(Math.max((progress - start) / segmentDuration, 0), 1);
                    break;
                }
            }

            // Reset default subtitle state
            subKeys.forEach(key => {
                const el = textEls[key];
                el.style.opacity = '0';
                el.style.transform = 'translateY(15px)';
            });

            // Animasi Subtitle Aktif
            if (activeIndex >= 0 && activeIndex < totalItems) {
                const activeKey = subKeys[activeIndex];
                const activeEl = textEls[activeKey];
                let opacity = 0;
                let translateY = 15;

                if (fadeProgress < 0.20) {
                    const t = fadeProgress / 0.20;
                    opacity = t * t * (3 - 2 * t);
                    translateY = 15 - (15 * t);
                } else if (fadeProgress < 0.80) {
                    opacity = 1;
                    translateY = 0;
                } else {
                    const t = (fadeProgress - 0.80) / 0.20;
                    opacity = 1 - (t * t * (3 - 2 * t));
                    translateY = -10 * t;
                }

                activeEl.style.opacity = opacity;
                activeEl.style.transform = `translateY(${translateY}px)`;
            }

            // 2. ATUR TULISAN SPARK UTAMA -> Hanya muncul SELELAH slideshow selesai (0.58 - 0.80)
            if (progress >= 0.56 && progress < TRANSITION_START) {
                // Hitung fade-in halus khusus untuk SPARK saat scroll memasuki areanya
                const sparkFadeIn = Math.min((progress - 0.56) / 0.06, 1);
                textEls.spark.style.opacity = sparkFadeIn;
                textEls.spark.style.transform =
                    `translateY(${-10 + (10 * sparkFadeIn)}px) scale(${0.98 + (0.02 * sparkFadeIn)})`;
            } else {
                textEls.spark.style.opacity = '0';
                textEls.spark.style.transform = 'translateY(-10px) scale(0.98)';
            }
        }

        // ============================================================
        //  UPDATE FRAME
        // ============================================================
        function updateFrame(rawProgress) {
            if (!ready) return;

            let p = Math.min(Math.max(rawProgress, 0), 1);

            // Jika scroll ke atas kembali, sembunyikan konten final
            if (hasReachedFinal && p < TRANSITION_START - 0.03) {
                hideFinalContent();
                return;
            }

            // MEMASUKI TRANSISI FINAL CONTENT (0.80 - 1.00)
            if (p >= TRANSITION_START) {
                const fadeProgress = Math.min((p - TRANSITION_START) / (1 - TRANSITION_START), 1);
                canvas.style.opacity = 1 - fadeProgress;

                const lastFrameIdx = TOTAL_FRAMES - 1;
                if (frames[lastFrameIdx] && canvasNext.src !== frames[lastFrameIdx].src) {
                    canvasNext.src = frames[lastFrameIdx].src;
                    canvasNext.style.opacity = fadeProgress;
                }

                // Trigger konten final secara aman tanpa kunci boolean kaku
                if (p >= 0.92 && !hasReachedFinal) {
                    showFinalContent();
                }
                return;
            }

            // TIMELINE ANIMASI IMAGE SEQUENCE (0.00 - 0.80)
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

                updateTextSlideshow(p);
            }
        }

        // ============================================================
        //  SCROLL HANDLER
        // ============================================================
        let ticking = false;

        container.addEventListener('scroll', function() {
            if (isFirstScroll) {
                isFirstScroll = false;
                const maxScroll = container.scrollHeight - window.innerHeight;
                let progress = maxScroll > 0 ? Math.min(container.scrollTop / maxScroll, 1) : 0;
                updateFrame(progress);
                return;
            }

            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const maxScroll = container.scrollHeight - window.innerHeight;
                    let progress = maxScroll > 0 ? Math.min(container.scrollTop / maxScroll, 1) : 0;
                    updateFrame(progress);
                    ticking = false;
                });
                ticking = true;
            }
        }, {
            passive: true
        });

        // ============================================================
        //  KEYBOARD NAVIGATION
        // ============================================================
        document.addEventListener('keydown', (e) => {
            const step = 60;
            if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                container.scrollBy({
                    top: step,
                    behavior: 'smooth'
                });
                e.preventDefault();
            } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                container.scrollBy({
                    top: -step,
                    behavior: 'smooth'
                });
                e.preventDefault();
            }
        });

        // ============================================================
        //  INIT
        // ============================================================
        loadFrames();

        setTimeout(() => {
            if (!ready) {
                canvas.src = BASE_PATH + '0001' + EXT;
            }
        }, 1200);
    </script>
</body>

</html>
