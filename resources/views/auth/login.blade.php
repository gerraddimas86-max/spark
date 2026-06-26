<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPARK-Login</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <style>
        /* =============================================
           CUSTOM CSS - Minimal
           ============================================= */
        
        /* Font Battlesbridge */
        @font-face {
            font-family: 'Battlesbridge';
            src: url('{{ asset("font/BattlesbridgeDemo-AL126.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        /* Reset & Base */
        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }
        
        /* Keyframes untuk animasi */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(3deg); }
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.85); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 0.2; }
            100% { transform: scale(0.85); opacity: 0.8; }
        }
        
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float-bubble {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.1); }
        }

        @keyframes glow-pulse {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.08); }
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes float-slow {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-10px) scale(1.02); }
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.1); }
        }
        
        /* Utility classes untuk animasi */
        .animate-float { animation: float 5s ease-in-out infinite; }
        .animate-pulse-ring { animation: pulse-ring 3s ease-in-out infinite; }
        .animate-fade-in-up { animation: fade-in-up 0.7s ease-out forwards; }
        .animate-float-bubble { animation: float-bubble 6s ease-in-out infinite; }
        .animate-glow-pulse { animation: glow-pulse 3s ease-in-out infinite; }
        .animate-slide-up { animation: slide-up 0.5s ease-out forwards; }
        .animate-scale-in { animation: scale-in 0.6s ease-out forwards; }
        .animate-float-slow { animation: float-slow 4s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
        
        /* Hide scrollbar */
        ::-webkit-scrollbar { display: none; }
        * { scrollbar-width: none; }
        
        /* Background */
        .bg-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
            background: #0a0a1a;
        }
        
        .bg-fallback-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/background/bg-login.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
            opacity: 1;
            transition: opacity 0.8s ease;
        }
        
        .bg-fallback-image.hidden {
            opacity: 0;
        }
        
        .bg-video-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
        }
        
        .bg-video {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            transition: opacity 0.8s ease-in-out;
        }
        
        .bg-video-active {
            opacity: 1;
            z-index: 2;
        }
        
        .bg-video-inactive {
            opacity: 0;
            z-index: 1;
        }
        
        .bg-overlay-light {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.2) 100%);
            z-index: 3;
            pointer-events: none;
        }
        
        .bg-decoration {
            position: absolute;
            z-index: 3;
            pointer-events: none;
        }
        
        .bg-decoration .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.15;
            filter: blur(40px);
        }
    </style>
</head>
<body>
    <!-- ========== BACKGROUND ========== -->
    <div class="bg-container">
        <div id="bgFallback" class="bg-fallback-image"></div>
        <div class="bg-video-wrapper">
            <video id="bgVideo1" class="bg-video bg-video-active" autoplay muted playsinline>
                <source src="{{ asset('videos/bg-login.webm') }}" type="video/webm">
            </video>
            <video id="bgVideo2" class="bg-video bg-video-inactive" muted playsinline>
                <source src="{{ asset('videos/bg-login.webm') }}" type="video/webm">
            </video>
        </div>
        <div class="bg-overlay-light"></div>
        <div class="bg-decoration" style="top: 10%; right: 5%; width: 200px; height: 200px;">
            <div class="circle" style="width: 100%; height: 100%; background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, transparent 70%);"></div>
        </div>
        <div class="bg-decoration" style="bottom: 15%; left: 5%; width: 150px; height: 150px;">
            <div class="circle" style="width: 100%; height: 100%; background: radial-gradient(circle, rgba(236, 72, 153, 0.2) 0%, transparent 70%);"></div>
        </div>
        <div class="bg-decoration" style="top: 50%; left: 20%; width: 100px; height: 100px;">
            <div class="circle animate-float-bubble" style="width: 100%; height: 100%; background: radial-gradient(circle, rgba(251, 191, 36, 0.15) 0%, transparent 70%);"></div>
        </div>
    </div>
    
    <!-- ========== DESKTOP VERSION ========== -->
    <div class="desktop-only relative z-10 h-screen w-full lg:flex flex-col lg:flex-row">
        
        <!-- LEFT: Pet Only -->
        <div class="w-full lg:w-3/5 relative flex flex-col h-full p-6 lg:p-8">
            
            <!-- SPARK Title -->
            <div class="flex-1 flex items-center justify-center">
                <div class="flex flex-col items-center justify-center h-full animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="text-[4.5rem] font-normal text-white/95 tracking-[0.08em] leading-none text-center mb-2" style="font-family: 'Battlesbridge', sans-serif;">
                        SPARK
                    </div>
                    <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-white/20 to-transparent rounded-full"></div>
                </div>
            </div>
            
            <!-- Pet Card -->
            <div class="flex-1 flex items-center justify-center">
                <div class="relative bg-white/5 border border-white/10 rounded-[2rem] p-6 pt-8 overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-0.5 animate-scale-in w-full max-w-[400px]" style="animation-delay: 0.2s;">
                    <div class="absolute w-16 h-16 -top-5 -right-5 rounded-full bg-white/5 animate-float-bubble pointer-events-none"></div>
                    <div class="absolute w-10 h-10 -bottom-2.5 -left-2.5 rounded-full bg-white/5 animate-float-bubble pointer-events-none" style="animation-delay: 1s;"></div>
                    <div class="absolute w-20 h-20 top-1/2 -right-7 rounded-full bg-white/5 animate-float-bubble pointer-events-none opacity-50" style="animation-delay: 2s;"></div>
                    
                    <div class="relative w-32 h-32 mx-auto z-10">
                        <div class="absolute inset-[-30px] rounded-full bg-white/5 animate-pulse-glow blur-[30px]"></div>
                        <div class="absolute inset-[-8px] rounded-full border border-white/10 animate-pulse-ring"></div>
                        <div class="absolute inset-[-16px] rounded-full border border-white/5 animate-pulse-ring" style="animation-delay: 1.5s;"></div>
                        
                        <img id="pet-image" 
                        src="{{ asset('images/pets/' . $randomPet['image']) }}" 
                        alt="{{ $randomPet['name'] }}"
                        class="relative w-full h-full object-contain drop-shadow-2xl animate-float-slow z-10 transition-all duration-500 hover:scale-105">
                    </div>
                    
                    <div class="text-center mt-4">
                        <h2 id="pet-name" class="text-lg font-semibold text-white tracking-tight mb-2 relative z-10 inline-block">
                            {{ $randomPet['name'] }}
                            <span class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-gradient-to-r from-transparent via-white/20 to-transparent rounded-full"></span>
                        </h2>
                        <p class="text-sm text-white font-normal leading-relaxed text-center max-w-[280px] mx-auto relative z-10 animate-slide-up mt-3" style="animation-delay: 0.3s;">
                            "{{ $randomPet['dialog'] }}"
                        </p>
                        <div class="flex justify-center gap-2 mt-4 relative z-10">
                            <span class="w-1 h-1 rounded-full bg-white/10 transition-all duration-300 hover:bg-white/20"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/20 transition-all duration-300 hover:bg-white/30"></span>
                            <span class="w-1 h-1 rounded-full bg-white/10 transition-all duration-300 hover:bg-white/20"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- RIGHT: Login Form -->
        <div class="w-full lg:w-2/5 flex flex-col items-center justify-center p-6 lg:p-8 h-full">
            <div class="w-[400px] h-[545px] bg-[url('{{ asset('images/card-bg.jpg') }}')] bg-cover bg-center bg-no-repeat rounded-2xl relative shadow-none animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="absolute inset-0 p-24 flex flex-col">
                    <!-- Info Box -->
                    <div class="bg-[url('{{ asset('images/info-bg.png') }}')] bg-cover bg-center bg-no-repeat rounded-xl px-4 py-2.5 mb-4 flex items-center gap-2.5">
                        <i class="fas fa-info-circle text-[10px] text-white/80 flex-shrink-0"></i>
                        <p class="text-[11px] text-white/90 leading-snug m-0">
                            Login dengan <span class="font-semibold text-white">NIM</span> (Mahasiswa) atau <span class="font-semibold text-white">Email</span> (Mentor/Developer)
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="flex-1 flex flex-col gap-3">
                        @csrf
                        <!-- NIM/Email -->
                        <div>
                            <label for="login" class="block text-xs font-semibold text-white/90 mb-1.5 drop-shadow-sm">
                                NIM atau Email
                            </label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-white/70 text-xs z-10"></i>
                                <input 
                                    id="login" 
                                    type="text" 
                                    name="login" 
                                    value="{{ old('login') }}"
                                    class="w-full bg-[url('{{ asset('images/input-bg.png') }}')] bg-cover bg-center bg-no-repeat border-none text-white text-sm px-9 py-2.5 rounded-xl transition-all duration-300 focus:outline-none focus:shadow-[0_0_0_2px_rgba(255,255,255,0.3)] placeholder:text-white/70"
                                    placeholder="Masukkan NIM atau email"
                                    required 
                                    autofocus
                                >
                            </div>
                            @error('login')
                                <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-semibold text-white/90 mb-1.5 drop-shadow-sm">
                                Password
                            </label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-white/70 text-xs z-10"></i>
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    class="w-full bg-[url('{{ asset('images/input-bg.png') }}')] bg-cover bg-center bg-no-repeat border-none text-white text-sm px-9 py-2.5 rounded-xl transition-all duration-300 focus:outline-none focus:shadow-[0_0_0_2px_rgba(255,255,255,0.3)] placeholder:text-white/70"
                                    placeholder="Masukkan password"
                                    required
                                >
                                <button type="button" id="togglePassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-white/60 hover:text-white/90 transition-colors z-10">
                                    <i class="fas fa-eye-slash text-xs"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center pt-1">
                            <label for="remember_me" class="flex items-center gap-1.5 cursor-pointer">
                                <input 
                                    id="remember_me" 
                                    type="checkbox" 
                                    name="remember"
                                    class="w-3.5 h-3.5 rounded border-white/40 bg-white/10 text-white focus:ring-white/40 focus:ring-offset-0"
                                >
                                <span class="text-[11px] text-white/80 drop-shadow-sm">Ingat saya</span>
                            </label>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="w-full h-[120px] bg-[url('{{ asset('images/button-bg.png') }}')] bg-cover bg-center bg-no-repeat border-none text-white font-semibold text-sm transition-transform duration-300 hover:translate-y-[-2px] hover:brightness-105 active:scale-95 flex items-center justify-center gap-2 mt-2 rounded-xl cursor-pointer">
                            <span>LOGIN</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ========== MOBILE VERSION ========== -->
    <div class="mobile-only" style="display: none;">
        <div class="min-h-screen flex items-center justify-center p-4 bg-[#0a0a1a]">
            <div class="w-[380px] h-[545px] bg-[url('{{ asset('images/card-bg.jpg') }}')] bg-cover bg-center bg-no-repeat rounded-2xl relative shadow-none mx-auto">
                <div class="absolute inset-0 p-24 flex flex-col">
                    <!-- SPARK Title Mobile -->
                    <div class="text-[2.8rem] font-normal text-white/95 tracking-[0.08em] leading-none text-center mb-6 hidden" style="font-family: 'Battlesbridge', sans-serif;">
                        SPARK
                    </div>

                    <!-- Info Box Mobile -->
                    <div class="bg-[url('{{ asset('images/info-bg.png') }}')] bg-cover bg-center bg-no-repeat rounded-xl px-4 py-2.5 mb-4 flex items-center gap-2.5">
                        <i class="fas fa-info-circle text-[10px] text-white/80 flex-shrink-0"></i>
                        <p class="text-[11px] text-white/90 leading-snug m-0">
                            Login dengan <span class="font-semibold text-white">NIM</span> (Mahasiswa) atau <span class="font-semibold text-white">Email</span> (Mentor/Developer)
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-3">
                        @csrf
                        <!-- NIM/Email Mobile -->
                        <div class="mb-3">
                            <label for="login_mobile" class="block text-xs font-semibold text-white/90 mb-1.5 drop-shadow-sm">
                                NIM atau Email
                            </label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-white/70 text-xs z-10"></i>
                                <input 
                                    id="login_mobile" 
                                    type="text" 
                                    name="login" 
                                    value="{{ old('login') }}"
                                    class="w-full bg-[url('{{ asset('images/input-bg.png') }}')] bg-cover bg-center bg-no-repeat border-none text-white text-sm px-9 py-2.5 rounded-xl transition-all duration-300 focus:outline-none focus:shadow-[0_0_0_2px_rgba(255,255,255,0.3)] placeholder:text-white/70"
                                    placeholder="Masukkan NIM atau email"
                                    required 
                                    autofocus
                                >
                            </div>
                            @error('login')
                                <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Mobile -->
                        <div class="mb-3">
                            <label for="password_mobile" class="block text-xs font-semibold text-white/90 mb-1.5 drop-shadow-sm">
                                Password
                            </label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-white/70 text-xs z-10"></i>
                                <input 
                                    id="password_mobile" 
                                    type="password" 
                                    name="password" 
                                    class="w-full bg-[url('{{ asset('images/input-bg.png') }}')] bg-cover bg-center bg-no-repeat border-none text-white text-sm px-9 py-2.5 rounded-xl transition-all duration-300 focus:outline-none focus:shadow-[0_0_0_2px_rgba(255,255,255,0.3)] placeholder:text-white/70"
                                    placeholder="Masukkan password"
                                    required
                                >
                                <button type="button" class="togglePasswordMobile absolute right-3.5 top-1/2 -translate-y-1/2 text-white/60 hover:text-white/90 transition-colors z-10">
                                    <i class="fas fa-eye-slash text-xs"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me Mobile -->
                        <div class="flex items-center pt-1">
                            <label for="remember_me_mobile" class="flex items-center gap-1.5 cursor-pointer">
                                <input 
                                    id="remember_me_mobile" 
                                    type="checkbox" 
                                    name="remember"
                                    class="w-3.5 h-3.5 rounded border-white/40 bg-white/10 text-white focus:ring-white/40 focus:ring-offset-0"
                                >
                                <span class="text-[11px] text-white/80 drop-shadow-sm">Ingat saya</span>
                            </label>
                        </div>

                        <!-- Login Button Mobile -->
                        <button type="submit" class="w-full h-[120px] bg-[url('{{ asset('images/button-bg.png') }}')] bg-cover bg-center bg-no-repeat border-none text-white font-semibold text-sm transition-transform duration-300 hover:translate-y-[-2px] hover:brightness-105 active:scale-95 flex items-center justify-center gap-2 mt-2 rounded-xl cursor-pointer">
                            <span>LOGIN</span>
                            <i class="fas fa-arrow-right text-xs"></i>
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
        // BACKGROUND VIDEO + FALLBACK
        // =============================================
        const video1 = document.getElementById('bgVideo1');
        const video2 = document.getElementById('bgVideo2');
        const fallback = document.getElementById('bgFallback');
        let currentVideo = 1;
        let isTransitioning = false;
        let videoLoaded = false;
        
        function showFallback() {
            if (fallback) fallback.classList.remove('hidden');
        }
        
        function hideFallback() {
            if (fallback) fallback.classList.add('hidden');
        }
        
        function switchVideo() {
            if (isTransitioning) return;
            isTransitioning = true;
            
            const activeVideo = currentVideo === 1 ? video1 : video2;
            const inactiveVideo = currentVideo === 1 ? video2 : video1;
            
            inactiveVideo.currentTime = 0;
            inactiveVideo.play().catch(() => {});
            
            activeVideo.classList.remove('bg-video-active');
            activeVideo.classList.add('bg-video-inactive');
            inactiveVideo.classList.remove('bg-video-inactive');
            inactiveVideo.classList.add('bg-video-active');
            
            hideFallback();
            currentVideo = currentVideo === 1 ? 2 : 1;
            
            setTimeout(() => {
                isTransitioning = false;
                setTimeout(() => activeVideo.pause(), 100);
            }, 900);
        }
        
        if (video1 && video2) {
            video1.addEventListener('canplay', () => { videoLoaded = true; hideFallback(); });
            video2.addEventListener('canplay', () => { videoLoaded = true; hideFallback(); });
            video1.addEventListener('error', showFallback);
            video2.addEventListener('error', showFallback);
            video1.addEventListener('ended', switchVideo);
            video2.addEventListener('ended', switchVideo);
            
            video1.play().catch(() => showFallback());
            video2.load();
            
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