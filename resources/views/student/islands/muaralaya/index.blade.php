<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - 🌊 Muaralaya · Pulau Pet</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            color: #ffffff;
            position: relative;
            overflow-x: hidden;
        }

        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 16px 28px;
            border-radius: 16px;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 0.95rem;
            z-index: 100;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            border-color: rgba(74, 222, 128, 0.3);
        }

        .toast.error {
            border-color: rgba(248, 113, 113, 0.3);
        }

        .toast .toast-icon {
            margin-right: 10px;
        }

        .level-up-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 200;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
        }

        .level-up-overlay.show {
            display: flex;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .level-up-content {
            animation: bounceIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .stage-evolution {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 200;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
        }

        .stage-evolution.show {
            display: flex;
        }

        .stage-evolution-content {
            animation: bounceIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
        }

        .history-item {
            transition: all 0.3s ease;
        }

        .history-item:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .stage-egg { background: rgba(156, 163, 175, 0.3); color: #d1d5db; }
        .stage-baby { background: rgba(59, 130, 246, 0.3); color: #93bbfc; }
        .stage-adult { background: rgba(147, 51, 234, 0.3); color: #c084fc; }
        .stage-legendary { background: rgba(234, 179, 8, 0.3); color: #fbbf24; }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>
</head>
<body>
    <!-- Background -->
    <img src="{{ asset('images/background/muaralaya-bg.png') }}" alt="Background" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <span class="toast-icon" id="toastIcon">✅</span>
        <span id="toastMessage">Berhasil!</span>
    </div>

    <!-- Level Up Overlay -->
    <div class="level-up-overlay" id="levelUpOverlay">
        <div class="level-up-content text-center">
            <i class="fas fa-trophy text-7xl md:text-8xl text-yellow-400 block mb-4"></i>
            <div class="text-4xl md:text-5xl font-bold text-yellow-400 mb-3">LEVEL UP!</div>
            <div class="text-lg md:text-xl text-white/80" id="levelUpSub">Pet sekarang level <strong id="levelUpLevel" class="text-yellow-400">2</strong></div>
            <button onclick="closeLevelUp()" class="mt-6 px-8 py-3 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-full text-sm font-bold text-[#1a3c2c] hover:scale-105 transition-all duration-300">🎊 Lanjutkan</button>
        </div>
    </div>

    <!-- Stage Evolution Overlay -->
    <div class="stage-evolution" id="stageEvolution">
        <div class="stage-evolution-content text-center">
            <i class="fas fa-star text-8xl md:text-9xl text-yellow-400 block mb-4" id="evoEmoji"></i>
            <div class="text-4xl md:text-5xl font-bold text-yellow-400 mb-3" id="evoTitle">EVOLUSI!</div>
            <div class="text-lg md:text-xl text-white/80" id="evoSub">Pet berevolusi menjadi <strong id="evoStageName" class="text-yellow-400">Bayi</strong></div>
            <button onclick="closeEvolution()" class="mt-6 px-8 py-3 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-full text-sm font-bold text-[#1a3c2c] hover:scale-105 transition-all duration-300">🎊 Lihat Perubahan!</button>
        </div>
    </div>

    <!-- Back Button -->
    <button onclick="goBack()" 
            class="fixed top-6 left-6 md:top-8 md:left-8 z-50 bg-[url('{{ asset('images/button/btn-back.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[200px] h-[70px] text-white text-[0.8rem] font-medium tracking-[0.05em] transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 inline-flex items-center justify-center text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[140px] max-sm:h-[50px] max-sm:text-[0.6rem]">
        ← Kembali
    </button>

    <!-- Main Content -->
    <main class="relative z-[2] w-full min-h-screen flex items-center justify-center px-5 md:px-10 py-20">
        <div class="w-full max-w-4xl mx-auto" id="contentWrapper">

            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15 border border-white/15 mb-5">
                    <i class="fas fa-paw text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-3 tracking-tight">
                    Muaralaya
                </h1>
                <p class="text-base text-white/70 max-w-md mx-auto leading-relaxed font-light">
                    Pelihara dan rawat pet kelompokmu
                </p>
                @if($groupName ?? false)
                    <div class="mt-3 inline-flex items-center gap-2 text-sm text-white/50 bg-white/10 px-4 py-1.5 rounded-full">
                        <i class="fas fa-users text-xs"></i>
                        <span>{{ $groupName }}</span>
                    </div>
                @endif
            </div>

            <!-- Pet Display -->
            <div class="text-center mb-8">
                <div class="relative inline-block">
                    <img id="petImage" src="{{ asset('images/pets/gurita/telur/gurita.png') }}" 
                         alt="Pet" class="w-40 h-40 md:w-48 md:h-48 object-contain drop-shadow-[0_10px_30px_rgba(255,215,0,0.15)] hover:scale-110 hover:rotate-[-5deg] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 bg-gradient-to-r from-yellow-400 to-amber-500 text-[#1a3c2c] font-bold text-xs px-3 py-1 rounded-full shadow-lg shadow-yellow-400/30" id="petLevelBadge">
                        Lv. 0
                    </div>
                </div>
                <div class="text-2xl font-bold text-yellow-400 mb-1 mt-2" id="petName">Pet Kelompok</div>
                <div class="inline-block px-4 py-1.5 rounded-full text-sm font-medium stage-egg" id="petStage">Telur</div>

                <!-- Level Progress -->
                <div class="max-w-md mx-auto mt-4">
                    <div class="flex justify-between text-sm text-white/80 mb-1.5">
                        <span class="flex items-center gap-2">⬆ Level <span id="petLevel" class="font-bold text-white">0</span></span>
                        <span><span id="petExp" class="font-bold text-white">0</span> / <span id="petExpNeeded" class="font-bold text-white">100</span> EXP</span>
                    </div>
                    <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden relative">
                        <div class="h-full bg-gradient-to-r from-yellow-400 via-amber-400 to-orange-500 rounded-full transition-all duration-500 relative" 
                             id="expBar" style="width: 0%">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-[shimmer_2s_infinite]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto mb-8">
                <div class="stat-card bg-[url('{{ asset('images/card/card-stat.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-4 min-h-[80px] flex flex-col items-center justify-center text-center rounded-[16px] md:rounded-[20px]">
                    <div class="text-xl md:text-2xl font-bold text-yellow-400" id="foodPoints">0</div>
                    <div class="text-[0.6rem] text-white/50 font-light mt-0.5"><i class="fas fa-drumstick-bite mr-1"></i>Food Points</div>
                </div>
                <div class="stat-card bg-[url('{{ asset('images/card/card-stat.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-4 min-h-[80px] flex flex-col items-center justify-center text-center rounded-[16px] md:rounded-[20px]">
                    <div class="text-xl md:text-2xl font-bold text-yellow-400" id="petType"><i class="fas fa-paw"></i></div>
                    <div class="text-[0.6rem] text-white/50 font-light mt-0.5"><i class="fas fa-tag mr-1"></i>Tipe Pet</div>
                </div>
                <div class="stat-card bg-[url('{{ asset('images/card/card-stat.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-4 min-h-[80px] flex flex-col items-center justify-center text-center rounded-[16px] md:rounded-[20px]">
                    <div class="text-xl md:text-2xl font-bold text-yellow-400" id="petStageBadge"><i class="fas fa-egg"></i></div>
                    <div class="text-[0.6rem] text-white/50 font-light mt-0.5"><i class="fas fa-arrow-up mr-1"></i>Stage</div>
                </div>
            </div>

            <!-- Feed Button -->
            <div class="text-center mb-8">
                <button onclick="feedPet()" id="feedBtn" 
                        class="px-8 py-3 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-full text-sm font-bold text-[#1a3c2c] hover:scale-105 hover:shadow-lg hover:shadow-yellow-400/30 active:scale-95 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                    <i class="fas fa-utensils mr-2"></i>Beri Makan (10 FP)
                </button>
                <div class="text-sm text-white/60 mt-3">Food Points tersisa: <strong class="text-yellow-400" id="userFoodPoints">0</strong></div>
            </div>

            <!-- History -->
            <div class="max-w-2xl mx-auto">
                <div class="history-card bg-[url('{{ asset('images/card/card-history.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-5 md:p-6 rounded-[16px] md:rounded-[20px]">
                    <div class="flex items-center gap-2 text-yellow-400 text-sm font-medium mb-4">
                        <i class="fas fa-clock-rotate-left"></i>
                        <span>Riwayat Makan</span>
                    </div>
                    <div id="historyList">
                        <div class="text-center text-white/40 py-4 text-sm"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat riwayat...</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-center gap-4 mt-10">
                <div class="w-8 h-px bg-white/20"></div>
                <i class="fas fa-compass text-white/20 text-xs"></i>
                <div class="w-8 h-px bg-white/20"></div>
            </div>

        </div>
    </main>

    <script>
        // ============================================================
        //  TOAST NOTIFICATION
        // ============================================================
        let toastTimeout = null;

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toastIcon');
            const msg = document.getElementById('toastMessage');

            icon.textContent = type === 'success' ? '✅' : '❌';
            msg.textContent = message;
            toast.className = 'toast ' + type;

            void toast.offsetWidth;
            toast.classList.add('show');

            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }

        // ============================================================
        //  LEVEL UP
        // ============================================================
        function showLevelUp(newLevel) {
            document.getElementById('levelUpLevel').textContent = newLevel;
            document.getElementById('levelUpOverlay').classList.add('show');
        }

        function closeLevelUp() {
            document.getElementById('levelUpOverlay').classList.remove('show');
        }

        // ============================================================
        //  STAGE EVOLUTION
        // ============================================================
        function showEvolution(oldStage, newStage) {
            const emojiMap = { egg: '🥚', baby: '🐣', adult: '🦅', legendary: '👑' };
            const nameMap = { egg: 'Telur', baby: 'Bayi', adult: 'Dewasa', legendary: 'Legendaris' };
            
            document.getElementById('evoEmoji').textContent = emojiMap[newStage] || '🌟';
            document.getElementById('evoStageName').textContent = nameMap[newStage] || newStage;
            document.getElementById('stageEvolution').classList.add('show');
        }

        function closeEvolution() {
            document.getElementById('stageEvolution').classList.remove('show');
        }

        // ============================================================
        //  GO BACK
        // ============================================================
        function goBack() {
            window.location.href = "{{ route('student.map') }}";
        }

        // ============================================================
        //  UPDATE PET UI
        // ============================================================
        function updatePetUI(data) {
            document.getElementById('petName').textContent = data.name;
            
            const img = document.getElementById('petImage');
            if (data.image_url) {
                img.src = data.image_url;
            }
            
            document.getElementById('petLevelBadge').textContent = 'Lv. ' + data.level;

            const stageEl = document.getElementById('petStage');
            stageEl.textContent = data.stage_name;
            stageEl.className = 'inline-block px-4 py-1.5 rounded-full text-sm font-medium stage-' + data.stage;

            document.getElementById('petLevel').textContent = data.level;
            document.getElementById('petExp').textContent = data.experience;
            document.getElementById('petExpNeeded').textContent = data.exp_needed;
            document.getElementById('expBar').style.width = data.exp_progress + '%';

            document.getElementById('foodPoints').textContent = data.food_points;
            document.getElementById('userFoodPoints').textContent = data.food_points;
            
            document.getElementById('petType').innerHTML = data.icon || '<i class="fas fa-paw"></i>';
            document.getElementById('petStageBadge').innerHTML = data.stage_icon || '<i class="fas fa-egg"></i>';
        }

        // ============================================================
        //  LOAD PET DATA FROM API
        // ============================================================
        function loadPetData() {
            fetch('/student/api/pet-data')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        updatePetUI(data.data);
                    } else {
                        showToast('Gagal memuat data pet', 'error');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showToast('Terjadi kesalahan saat memuat data', 'error');
                });
        }

        // ============================================================
        //  LOAD HISTORY FROM API
        // ============================================================
        function loadHistory() {
            fetch('/student/api/pet-history')
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('historyList');
                    if (data.success && data.data.length > 0) {
                        list.innerHTML = data.data.map(item => `
                            <div class="history-item flex justify-between items-center py-2 border-b border-white/5 last:border-0">
                                <span>
                                    <span class="text-yellow-400 font-medium">${item.user_name}</span>
                                    <span class="text-white/60 text-sm">memberi makan</span>
                                    <strong class="text-white/80 text-sm">-${item.food_amount} FP</strong>
                                </span>
                                <span class="text-white/40 text-xs">${item.date}</span>
                            </div>
                        `).join('');
                    } else {
                        list.innerHTML = '<div class="text-center text-white/40 py-4 text-sm"><i class="fas fa-inbox mr-2"></i>Belum ada riwayat memberi makan</div>';
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    document.getElementById('historyList').innerHTML = '<div class="text-center text-white/40 py-4 text-sm"><i class="fas fa-exclamation-circle mr-2"></i>Gagal memuat riwayat</div>';
                });
        }

        // ============================================================
        //  FEED PET (AJAX)
        // ============================================================
        function feedPet() {
            const btn = document.getElementById('feedBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

            fetch('/student/api/pet-feed', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    updatePetUI(data.pet);
                    document.getElementById('foodPoints').textContent = data.food_points;
                    document.getElementById('userFoodPoints').textContent = data.food_points;

                    if (data.level_up) {
                        setTimeout(() => showLevelUp(data.pet.level), 500);
                    }

                    if (data.stage_changed && data.new_stage) {
                        setTimeout(() => showEvolution(data.old_stage, data.new_stage), 800);
                    }

                    setTimeout(() => loadHistory(), 300);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showToast('Terjadi kesalahan saat memberi makan', 'error');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-utensils mr-2"></i>Beri Makan (10 FP)';
            });
        }

        // ============================================================
        //  KEYBOARD SHORTCUTS
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLevelUp();
                closeEvolution();
            }
            if (e.key === ' ' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                const btn = document.getElementById('feedBtn');
                if (!btn.disabled) {
                    feedPet();
                }
            }
        });

        // ============================================================
        //  INIT - GSAP DIPERBAIKI
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Pakai selector sederhana, bukan complex
            gsap.from('#petImage', {
                duration: 0.6,
                y: 30,
                opacity: 1,
                ease: "power2.out",
                delay: 0.1
            });

            gsap.from('#petName', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.2
            });

            gsap.from('#petStage', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.25
            });

            gsap.from('.max-w-md', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.3
            });

            gsap.from('.stat-card', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.35,
                stagger: 0.08
            });

            gsap.from('#feedBtn', {
                duration: 0.4,
                y: 15,
                opacity: 1,
                ease: "power2.out",
                delay: 0.5
            });

            // ✅ Pakai class 'history-card' (tambahkan class di HTML)
            gsap.from('.history-card', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.6
            });

            gsap.from('.text-center h1, .text-center p, .text-center .inline-flex', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.05,
                stagger: 0.08
            });

            gsap.from('.flex.items-center.justify-center.gap-4.mt-10', {
                duration: 0.4,
                opacity: 1,
                ease: "power2.out",
                delay: 0.7
            });

            loadPetData();
            loadHistory();
        });

        console.log('🌊 Muaralaya - Pulau Pet siap!');
        console.log('📌 Shortcut: [Spasi] Beri makan [Esc] Tutup overlay');
    </script>
</body>
</html>