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
                transform: translate(-50%, 0);
                opacity: 0.7;
            }

            50% {
                transform: translate(-50%, 8px);
                opacity: 1;
            }
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

<body class="bg-[#0a0a1a] m-0 p-0 overflow-hidden h-screen font-sans">
    <div class="relative w-full h-screen overflow-hidden" id="mainContainer">

        <div class="absolute inset-0 z-0">
            <video id="bgVideoLoop"
                class="absolute top-1/2 left-1/2 min-w-full min-h-full w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 opacity-0 z-[1] transition-opacity duration-1000 ease-in-out"
                muted playsinline loop>
                <source src="{{ asset('videos/bg-login.webm') }}" type="video/webm">
            </video>

            <video id="bgVideoTransit"
                class="absolute top-1/2 left-1/2 min-w-full min-h-full w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 opacity-0 z-[1] transition-opacity duration-700 ease-in-out"
                muted playsinline>
                <source src="{{ asset('videos/berlayar.webm') }}" type="video/webm">
            </video>

            <video id="bgVideoNext"
                class="absolute top-1/2 left-1/2 min-w-full min-h-full w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 opacity-0 z-[1] transition-opacity duration-1000 ease-in-out"
                muted playsinline loop>
                <source src="{{ asset('videos/final.webm') }}" type="video/webm">
            </video>
        </div>

        <div
            class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/50 z-[3] pointer-events-none">
        </div>

        <div id="blackOverlay"
            class="absolute inset-0 bg-black opacity-0 z-[40] pointer-events-none transition-opacity duration-700 ease-in-out">
        </div>

        <!-- Logout Button dengan background image -->
        <div class="absolute top-6 right-6 md:top-8 z-[30] flex items-center">
            <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="m-0">
                @csrf
                <button type="submit"
                    class="bg-[url('{{ asset('images/button/btn-logout.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[160px] h-[60px] inline-flex items-center justify-center text-white text-[10px] font-semibold tracking-[0.2em] uppercase cursor-pointer transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[120px] max-sm:h-[42px] max-sm:text-[8px]">
                    Logout
                </button>
            </form>
        </div>

        <div id="interactiveZone"
            class="absolute inset-0 z-10 hidden flex-col justify-between items-center px-5 py-20 text-center opacity-0 pointer-events-none transition-all duration-1000 ease-in-out">

            <h1 class="font-normal text-white drop-shadow-[0_2px_40px_rgba(0,0,0,0.5)] tracking-[0.08em] leading-none m-0 text-[clamp(3.5rem,10vw,6rem)] mt-16 select-none"
                style="font-family: 'Battlesbridge', sans-serif;">
                SPARK
            </h1>

            <div class="flex flex-col gap-2 select-none">
                <p
                    class="font-sans text-[clamp(0.85rem,1.5vw,1.2rem)] font-semibold text-white/90 tracking-[0.2em] uppercase drop-shadow-[0_2px_10px_rgba(0,0,0,0.8)]">
                    PKKMB FASILKOM UNSRI 2026
                </p>
                <p
                    class="font-sans text-[clamp(0.6rem,1vw,0.8rem)] font-light text-white/50 tracking-[0.3em] uppercase drop-shadow-[0_2px_10px_rgba(0,0,0,0.8)]">
                    Mari Berlayar
                </p>
            </div>

            <div class="mb-5 w-full flex justify-center">
                <button id="btnBerlayar"
                    class="bg-[url('{{ asset('images/button/btn-berlayar.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[260px] h-[130px] inline-flex items-center justify-center text-white text-sm font-semibold tracking-[0.25em] uppercase cursor-pointer transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[220px] max-sm:h-[110px] max-sm:text-xs">
                    Berlayar
                </button>
            </div>
        </div>

        <div id="finalContent"
            class="absolute inset-0 z-[20] hidden flex-col justify-center items-center text-center opacity-0 pointer-events-none transition-all duration-[1000ms] ease-in-out bg-black/40 px-5 py-10">

            <h1 class="font-normal tracking-wide text-white text-3xl lg:text-[clamp(2.5rem,7vw,4rem)] mb-4"
                style="font-family: 'Battlesbridge', sans-serif;">
                Selamat Datang
            </h1>
            <p class="font-sans text-sm text-white/70 tracking-[0.15em] uppercase mb-8">
                Aye,Aye, Kapten! Ikuti Peta, Jelajahi Samudra!
            </p>

            <div class="flex flex-col sm:flex-row gap-4 items-center justify-center w-full">
                <a href="{{ route('student.map') }}"
                    class="bg-[url('{{ asset('images/button/btn-map.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[210px] h-[100px] inline-flex items-center justify-center text-white text-sm font-semibold tracking-[0.25em] uppercase no-underline cursor-pointer transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[220px] max-sm:h-[110px] max-sm:text-xs">
                    Buka Peta
                </a>

                <button id="btnBerlabuh"
                    class="bg-[url('{{ asset('images/button/btn-berlabuh.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[210px] h-[100px] inline-flex items-center justify-center text-white text-sm font-semibold tracking-[0.25em] uppercase cursor-pointer transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[220px] max-sm:h-[110px] max-sm:text-xs">
                    Berlabuh
                </button>
            </div>
            <p
                class="absolute bottom-6 left-1/2 -translate-x-1/2 text-sm font-light text-white/50 tracking-[0.3em] uppercase drop-shadow-[0_2px_10px_rgba(0,0,0,0.8)]">
                Mahakarya Tim Dev PKKMB FASILKOM UNSRI 2026
            </p>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const videoLoop = document.getElementById("bgVideoLoop");
            const videoTransit = document.getElementById("bgVideoTransit");
            const videoNext = document.getElementById("bgVideoNext");
            const btnBerlayar = document.getElementById("btnBerlayar");
            const btnBerlabuh = document.getElementById("btnBerlabuh");
            const interactiveZone = document.getElementById("interactiveZone");
            const finalContent = document.getElementById("finalContent");
            const blackOverlay = document.getElementById("blackOverlay");

            // --- STRATEGI UTAMA: INITIALISASI STATUS HALAMAN BERDASARKAN SESSION ---
            if (sessionStorage.getItem("isFinalPhase") === "true") {
                // Skenario A: User me-refresh saat berada di halaman Final
                if (videoNext) {
                    videoNext.classList.replace("opacity-0", "opacity-100");
                    videoNext.classList.replace("z-[1]", "z-[2]");
                    videoNext.play().catch(err => console.log(err));
                }

                finalContent.classList.remove("hidden");
                finalContent.classList.add("flex", "flex-col", "justify-center", "items-center");

                // Tampilkan secara instan tanpa kedipan visual
                finalContent.classList.remove("pointer-events-none");
                finalContent.classList.add("opacity-100");
            } else {
                // Skenario B: User berada di halaman Hero Awal (Normal/Baru Buka Browser)
                if (videoLoop) {
                    videoLoop.classList.replace("opacity-0", "opacity-100");
                    videoLoop.classList.replace("z-[1]", "z-[2]");
                    videoLoop.play().catch(err => console.log(err));
                }

                interactiveZone.classList.remove("hidden");
                interactiveZone.classList.add("flex", "flex-col", "justify-between", "items-center");

                // Tampilkan menu awal secara instan
                interactiveZone.classList.remove("pointer-events-none");
                interactiveZone.classList.add("opacity-100");
            }

            // --- PROSES BERLAYAR (MAJU KE FINAL) ---
            if (btnBerlayar) {
                btnBerlayar.addEventListener("click", function() {
                    btnBerlayar.disabled = true;
                    btnBerlayar.style.pointerEvents = "none";

                    sessionStorage.setItem("isFinalPhase", "true");

                    interactiveZone.classList.add("opacity-0", "pointer-events-none");
                    blackOverlay.classList.remove("opacity-0");
                    blackOverlay.classList.add("opacity-100");

                    setTimeout(() => {
                        interactiveZone.classList.add("hidden");
                        interactiveZone.classList.remove("flex", "flex-col", "justify-between",
                            "items-center");

                        if (videoTransit) {
                            videoLoop.pause();
                            videoLoop.classList.replace("opacity-100", "opacity-0");
                            videoLoop.classList.replace("z-[2]", "z-[1]");

                            videoTransit.currentTime = 0;
                            videoTransit.classList.replace("opacity-0", "opacity-100");
                            videoTransit.classList.replace("z-[1]", "z-[2]");

                            videoTransit.play().then(() => {
                                blackOverlay.classList.replace("opacity-100", "opacity-0");
                            }).catch(err => console.log(err));

                            videoTransit.onended = function() {
                                blackOverlay.classList.replace("opacity-0", "opacity-100");

                                setTimeout(() => {
                                    videoTransit.classList.replace("opacity-100",
                                        "opacity-0");
                                    videoTransit.classList.replace("z-[2]", "z-[1]");

                                    if (videoNext) {
                                        videoNext.currentTime = 0;
                                        videoNext.classList.replace("opacity-0",
                                            "opacity-100");
                                        videoNext.classList.replace("z-[1]", "z-[2]");
                                        videoNext.play();
                                    }

                                    finalContent.classList.remove("hidden");
                                    finalContent.classList.add("flex", "flex-col",
                                        "justify-center", "items-center");

                                    setTimeout(() => {
                                        finalContent.classList.remove(
                                            "pointer-events-none");
                                        finalContent.classList.add(
                                            "opacity-100");
                                        blackOverlay.classList.replace(
                                            "opacity-100", "opacity-0");
                                    }, 50);

                                    btnBerlayar.disabled = false;
                                    btnBerlayar.style.pointerEvents = "auto";
                                }, 700);
                            };
                        }
                    }, 700);
                });
            }

            // --- PROSES BERLABUH (KEMBALI KE MENU UTAMA) ---
            if (btnBerlabuh) {
                btnBerlabuh.addEventListener("click", function() {
                    btnBerlabuh.disabled = true;
                    btnBerlabuh.style.pointerEvents = "none";

                    sessionStorage.removeItem("isFinalPhase");

                    finalContent.classList.add("opacity-0", "pointer-events-none");
                    blackOverlay.classList.replace("opacity-0", "opacity-100");

                    setTimeout(() => {
                        finalContent.classList.add("hidden");
                        finalContent.classList.remove("flex", "flex-col", "justify-center",
                            "items-center");

                        if (videoNext) {
                            videoNext.pause();
                            videoNext.classList.replace("opacity-100", "opacity-0");
                            videoNext.classList.replace("z-[2]", "z-[1]");
                        }

                        if (videoLoop) {
                            videoLoop.currentTime = 0;
                            videoLoop.classList.replace("opacity-0", "opacity-100");
                            videoLoop.classList.replace("z-[1]", "z-[2]");
                            videoLoop.play();
                        }

                        interactiveZone.classList.remove("hidden");
                        interactiveZone.classList.add("flex", "flex-col", "justify-between",
                            "items-center");

                        setTimeout(() => {
                            interactiveZone.classList.remove("opacity-0",
                                "pointer-events-none");
                            interactiveZone.classList.add("opacity-100");
                            blackOverlay.classList.replace("opacity-100", "opacity-0");
                        }, 50);

                        btnBerlabuh.disabled = false;
                        btnBerlabuh.style.pointerEvents = "auto";
                    }, 700);
                });
            }
        });
    </script>
</body>
</html>