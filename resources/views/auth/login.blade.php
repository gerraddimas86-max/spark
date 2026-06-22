<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPARK-Login</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* 1. Deklarasikan @font-face terlebih dahulu */
        @font-face {
            font-family: 'BattlesbridgeCustom';
            /* Nama internal untuk CSS */
            src: url("{{ asset('font/BattlesbridgeDemo-AL126.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        /* 2. Set default font untuk body saja, JANGAN gunakan * (universal selector) */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <script>
        // 3. Daftarkan ke konfigurasi Tailwind
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        // Kita buat class custom: font-battlesbridge
                        battlesbridge: ['BattlesbridgeCustom', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<body>
    <!-- ========== BACKGROUND ========== -->
    <div class="fixed inset-0 z-0 overflow-hidden bg-[#0a0a1a]">
        <div id="bgFallback"
            class="absolute inset-0 z-[1] bg-cover bg-center bg-no-repeat opacity-100 transition-opacity duration-[800ms] ease-in-out"
            style="background-image: url('{{ asset('images/background/bg-login.png') }}');"></div>

        <div class="absolute inset-0 z-[2]">
            <video id="bgVideo1"
                class="absolute top-1/2 left-1/2 min-w-full min-h-full w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 opacity-100 z-[2]"
                autoplay muted playsinline loop>
                <source src="{{ asset('videos/bg-login.webm') }}" type="video/webm">
            </video>
        </div>

        <div class="absolute inset-0 bg-gradient-to-br from-black/15 to-black/20 z-[3] pointer-events-none"></div>

        <div class="absolute z-[3] pointer-events-none" style="top: 10%; right: 5%; width: 200px; height: 200px;">
            <div class="w-full h-full rounded-full opacity-15 blur-[40px]"
                style="background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, transparent 70%);"></div>
        </div>
        <div class="absolute z-[3] pointer-events-none" style="bottom: 15%; left: 5%; width: 150px; height: 150px;">
            <div class="w-full h-full rounded-full opacity-15 blur-[40px]"
                style="background: radial-gradient(circle, rgba(236, 72, 153, 0.2) 0%, transparent 70%);"></div>
        </div>
        <div class="absolute z-[3] pointer-events-none" style="top: 50%; left: 20%; width: 100px; height: 100px;">
            <div class="animate-float-bubble w-full h-full rounded-full opacity-15 blur-[40px]"
                style="background: radial-gradient(circle, rgba(251, 191, 36, 0.15) 0%, transparent 70%);"></div>
        </div>
    </div>

    <div class="hidden lg:flex relative z-10 h-screen w-full flex-col lg:flex-row">

        <div class="w-full lg:w-3/5 relative flex flex-col h-full p-6 lg:p-8">

            <div class="flex-1 flex items-center justify-center">
                <div class="animate-fade-in-up flex flex-col items-center justify-center h-full"
                    style="animation-delay: 0.1s;">
                    <div
                        class="font-battlesbridge text-[4.5rem] text-white/95 text-shadow-[0_4px_40px_rgba(255,255,255,0.08)] tracking-[4px] leading-none text-center mb-2">
                        SPARK
                    </div>
                    <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-white/20 to-transparent rounded-full">
                    </div>
                </div>
            </div>

            <div class="flex-1 flex items-center justify-center">
                <div class="relative bg-white/5 border border-white/10 rounded-[2rem] p-6 pt-8 overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-0.5 animate-scale-in w-full max-w-[400px]"
                    style="animation-delay: 0.2s;">
                    <div
                        class="absolute w-16 h-16 -top-5 -right-5 rounded-full bg-white/5 animate-float-bubble pointer-events-none">
                    </div>
                    <div class="absolute w-10 h-10 -bottom-2.5 -left-2.5 rounded-full bg-white/5 animate-float-bubble pointer-events-none"
                        style="animation-delay: 1s;"></div>
                    <div class="absolute w-20 h-20 top-1/2 -right-7 rounded-full bg-white/5 animate-float-bubble pointer-events-none opacity-50"
                        style="animation-delay: 2s;"></div>

                    <div class="relative w-32 h-32 mx-auto z-10">
                        <div class="absolute inset-[-30px] rounded-full bg-white/5 animate-pulse-glow blur-[30px]">
                        </div>
                        <div class="absolute inset-[-8px] rounded-full border border-white/10 animate-pulse-ring"></div>
                        <div class="absolute inset-[-16px] rounded-full border border-white/5 animate-pulse-ring"
                            style="animation-delay: 1.5s;"></div>

                        @if ($randomPet['type'] === 'ghost')
                            <img id="pet-image" src="{{ asset('images/pets/ghost/ghost.png') }}"
                                alt="{{ $randomPet['name'] }}"
                                class="relative w-full h-full object-contain drop-shadow-2xl animate-float-slow z-10 transition-all duration-500 hover:scale-105">
                        @else
                            <img id="pet-image" src="{{ asset('images/pets/' . $randomPet['image']) }}"
                                alt="{{ $randomPet['name'] }}"
                                class="relative w-full h-full object-contain drop-shadow-2xl animate-float-slow z-10 transition-all duration-500 hover:scale-105">
                        @endif
                    </div>

                    <div class="text-center mt-4">
                        <h2 id="pet-name"
                            class="text-lg font-semibold text-white tracking-tight mb-2 relative z-10 inline-block">
                            {{ $randomPet['name'] }}
                            <span
                                class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-gradient-to-r from-transparent via-white/20 to-transparent rounded-full"></span>
                        </h2>
                        <p class="text-sm text-white font-normal leading-relaxed text-center max-w-[280px] mx-auto relative z-10 animate-slide-up mt-3"
                            style="animation-delay: 0.3s;">
                            "{{ $randomPet['dialog'] }}"
                        </p>
                        <div class="flex justify-center gap-2 mt-4 relative z-10">
                            <span
                                class="w-1 h-1 rounded-full bg-white/10 transition-all duration-300 hover:bg-white/20"></span>
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-white/20 transition-all duration-300 hover:bg-white/30"></span>
                            <span
                                class="w-1 h-1 rounded-full bg-white/10 transition-all duration-300 hover:bg-white/20"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-2/5 flex flex-col items-center justify-center p-6 lg:p-8 h-full">
            <div class="animate-fade-in-up w-[400px] h-[545px] rounded-2xl relative shadow-none bg-cover bg-center bg-no-repeat"
                style="animation-delay: 0.2s; background-image: url('{{ asset('images/card-bg.jpg') }}');">
                <div class="absolute inset-0 p-24 flex flex-col">
                    <div class="rounded-xl py-[0.6rem] px-4 mb-4 flex items-center gap-[0.625rem] bg-cover bg-center bg-no-repeat"
                        style="background-image: url('{{ asset('images/info-bg.png') }}');">
                        <i class="fas fa-info-circle text-white/80 text-[10px] shrink-0"></i>
                        <p class="text-[11px] text-white/90 leading-relaxed m-0">
                            Login dengan <span class="font-semibold text-white">NIM</span>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="flex-1 flex flex-col gap-3">
                        @csrf
                        <div>
                            <label for="login"
                                class="block text-xs font-semibold text-white/90 mb-1.5 drop-shadow-sm">
                                NIM
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-white/70 text-[12px] z-[2]"></i>
                                <input id="login" type="text" name="login" value="{{ old('login') }}"
                                    class="w-full rounded-xl border-none text-white text-sm py-[0.7rem] pr-4 pl-10 bg-cover bg-center bg-no-repeat transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/30 placeholder:text-white/70"
                                    style="background-image: url('{{ asset('images/input-bg.png') }}');"
                                    placeholder="NIM" required autofocus>
                            </div>
                            @error('login')
                                <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password"
                                class="block text-xs font-semibold text-white/90 mb-1.5 drop-shadow-sm">
                                Password
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-white/70 text-[12px] z-[2]"></i>
                                <input id="password" type="password" name="password"
                                    class="w-full rounded-xl border-none text-white text-sm py-[0.7rem] pr-4 pl-10 bg-cover bg-center bg-no-repeat transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/30 placeholder:text-white/70"
                                    style="background-image: url('{{ asset('images/input-bg.png') }}');"
                                    placeholder="PW nya apa kapten?" required>
                                <button type="button" id="togglePassword"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-white/60 hover:text-white/90 transition-colors z-10">
                                    <i class="fas fa-eye-slash text-xs"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center pt-1">
                            <label for="remember_me" class="flex items-center gap-1.5 cursor-pointer">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="w-3.5 h-3.5 rounded border-white/40 bg-white/10 text-white focus:ring-white/40 focus:ring-offset-0">
                                <span class="text-[11px] text-white/80 drop-shadow-sm">Ingat saya</span>
                            </label>
                        </div>

                        <button type="submit"
                            class="relative text-white font-semibold border-none p-0 transition-transform duration-300 h-[120px] w-full cursor-pointer bg-transparent flex items-center justify-center bg-cover bg-no-repeat bg-[length:100%_100%] hover:-translate-y-0.5 hover:brightness-105 gap-2 text-xl mt-2"
                            style="background-image: url('{{ asset('images/button-bg.png') }}');">
                            <span class="mt-4">LOGIN</span>
                            <i class="fas fa-arrow-right
                                text-xs mt-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="block lg:hidden">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-[380px] h-[545px] margin-0-auto rounded-2xl relative shadow-none bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('images/card-bg.jpg') }}');">
                <div class="absolute inset-0 p-24 flex flex-col">
                    <div
                        class="hidden font-battlesbridge text-[2.8rem] text-white/95 text-shadow-[0_4px_40px_rgba(255,255,255,0.08)] tracking-[4px] leading-none text-center mb-6">
                        SPARK</div>

                    <div class="rounded-xl py-[0.6rem] px-4 mb-4 flex items-center gap-[0.625rem] bg-cover bg-center bg-no-repeat"
                        style="background-image: url('{{ asset('images/info-bg.png') }}');">
                        <i class="fas fa-info-circle text-white/80 text-[10px] shrink-0"></i>
                        <p class="text-[11px] text-white/90 leading-relaxed m-0">
                            Login dengan <span class="font-semibold text-white">NIM</span>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-3">
                        @csrf
                        <div class="mb-3">
                            <label for="login_mobile" class="block text-xs font-semibold text-white/90 mb-2">
                                NIM
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-white/70 text-[12px] z-[2]"></i>
                                <input id="login_mobile" type="text" name="login" value="{{ old('login') }}"
                                    class="w-full rounded-xl border-none text-white py-[0.7rem] pr-4 pl-10 bg-cover bg-center bg-no-repeat transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/30 placeholder:text-white/70"
                                    style="background-image: url('{{ asset('images/input-bg.png') }}');"
                                    placeholder="NIM" required autofocus>
                            </div>
                            @error('login')
                                <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_mobile" class="block text-xs font-semibold text-white/90 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-white/70 text-[12px] z-[2]"></i>
                                <input id="password_mobile" type="password" name="password"
                                    class="w-full rounded-xl border-none text-white py-[0.7rem] pr-4 pl-10 bg-cover bg-center bg-no-repeat transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/30 placeholder:text-white/70"
                                    style="background-image: url('{{ asset('images/input-bg.png') }}');"
                                    placeholder="Password" required>
                                <button type="button"
                                    class="togglePasswordMobile absolute right-3.5 top-1/2 -translate-y-1/2 text-white/60 hover:text-white/90 transition-colors z-10">
                                    <i class="fas fa-eye-slash text-xs"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center pt-1">
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input id="remember_me_mobile" type="checkbox" name="remember"
                                    class="w-3.5 h-3.5 rounded border-white/40 bg-white/10 text-white focus:ring-white/40 focus:ring-offset-0">
                                <span class="text-[11px] text-white/80">Ingat saya</span>
                            </label>
                        </div>

                        <button type="submit"
                            class="relative text-white font-semibold border-none p-0 transition-transform duration-300 h-[120px] w-full cursor-pointer bg-transparent flex items-center justify-center bg-cover bg-no-repeat bg-[length:100%_100%] hover:-translate-y-0.5 hover:brightness-105 mt-2"
                            style="background-image: url('{{ asset('images/button-bg.png') }}');">
                            <span>LOGIN</span>
                            <i class="fas fa-arrow-right text-xs ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // =============================================
        // PREVENT DOUBLE REFRESH
        // =============================================
        (function() {
            if (!sessionStorage.getItem('spark_loaded')) {
                sessionStorage.setItem('spark_loaded', 'true');
            } else {
                document.querySelectorAll('.animate-fade-in-up, .animate-scale-in, .animate-slide-up')
                    .forEach(el => {
                        el.style.animation = 'none';
                        el.style.opacity = '1';
                        el.style.transform = 'none';
                    });
                sessionStorage.removeItem('spark_loaded');
            }
        })();

        // =============================================
        // BACKGROUND VIDEO ONE-SHOT INFINITE LOOP
        // =============================================
        const video1 = document.getElementById('bgVideo1');
        const fallback = document.getElementById('bgFallback');
        let videoLoaded = false;

        function showFallback() {
            if (fallback) {
                fallback.style.opacity = '1';
                fallback.classList.remove('hidden');
            }
        }

        function hideFallback() {
            if (fallback) {
                fallback.style.opacity = '0';
                // Beri waktu fade out selesai baru sembunyikan total
                setTimeout(() => fallback.classList.add('hidden'), 800);
            }
        }

        if (video1) {
            // Ketika video sudah siap putar, hilangkan gambar fallback secara halus
            video1.addEventListener('canplay', () => {
                videoLoaded = true;
                hideFallback();
            });

            // Jika video error/tidak support di browser tersebut, baru tampilkan gambar
            video1.addEventListener('error', showFallback);

            // Jalankan video
            video1.play().catch(() => showFallback());

            // Fail-safe check dalam 5 detik jika video macet di awal
            setTimeout(() => {
                if (!videoLoaded && video1.paused) showFallback();
            }, 5000);
        }
        // =============================================
        // TOGGLE PASSWORD
        // =============================================
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });

        document.querySelectorAll('.togglePasswordMobile').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = document.getElementById('password_mobile');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                }
            });
        });

        // =============================================
        // MOBILE RESPONSIVE
        // =============================================
        function checkMobile() {
            const isMobile = window.innerWidth <= 768;
            const desktopEl = document.querySelector('.desktop-only');
            const mobileEl = document.querySelector('.mobile-only');

            if (desktopEl && mobileEl) {
                desktopEl.style.display = isMobile ? 'none' : 'flex';
                mobileEl.style.display = isMobile ? 'block' : 'none';
            }
        }

        window.addEventListener('load', checkMobile);
        window.addEventListener('resize', checkMobile);
    </script>
</body>

</html>
