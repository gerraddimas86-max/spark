<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - 🌊 Muaralaya · Pulau Pet</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Poppins', system-ui, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a5c2e 0%, #0d3b1a 100%);
            color: white;
            overflow-x: hidden;
        }

        /* Animasi background particles */
        .bg-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 215, 0, 0.3);
            border-radius: 50%;
            animation: floatUp linear infinite;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10vh) scale(1);
                opacity: 0;
            }
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(15px);
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            z-index: 50;
            border-bottom: 1px solid rgba(255,215,0,0.2);
        }

        .back-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,215,0,0.4);
            padding: 8px 20px;
            border-radius: 30px;
            color: #ffd966;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .back-btn:hover {
            background: rgba(255,215,0,0.2);
            transform: translateX(-3px);
            border-color: #ffd966;
        }

        .page-title {
            font-size: 1.3rem;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .page-title span {
            color: #ffd966;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 100px 25px 50px;
        }

        .pet-card {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(15px);
            border-radius: 40px;
            padding: 40px;
            text-align: center;
            border: 1px solid rgba(255,215,0,0.2);
            margin-bottom: 30px;
            opacity: 0;
            transform: translateY(-30px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }

        .pet-card.loaded {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .pet-image-wrapper {
            position: relative;
            display: inline-block;
        }

        .pet-image {
            font-size: 8rem;
            margin-bottom: 15px;
            filter: drop-shadow(0 10px 30px rgba(255,215,0,0.2));
            transition: transform 0.3s ease;
        }

        .pet-image:hover {
            transform: scale(1.1) rotate(-5deg);
        }

        .pet-level-badge {
            position: absolute;
            top: -5px;
            right: -15px;
            background: linear-gradient(135deg, #ffd966, #ffab00);
            color: #1a3c2c;
            font-weight: bold;
            font-size: 0.8rem;
            padding: 4px 12px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(255,215,0,0.3);
        }

        .pet-name {
            font-size: 2rem;
            font-weight: bold;
            color: #ffd966;
            margin-bottom: 8px;
        }

        .pet-stage {
            display: inline-block;
            padding: 5px 20px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 25px;
        }

        .stage-egg { background: rgba(156, 163, 175, 0.3); color: #d1d5db; }
        .stage-baby { background: rgba(59, 130, 246, 0.3); color: #93bbfc; }
        .stage-adult { background: rgba(147, 51, 234, 0.3); color: #c084fc; }
        .stage-legendary { background: rgba(234, 179, 8, 0.3); color: #fbbf24; }

        .level-container {
            margin: 25px 0;
        }

        .level-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .level-info .level-label {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .progress-bar {
            height: 12px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ffd966, #ffab00, #ff8c00);
            border-radius: 20px;
            width: 0%;
            transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .pet-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 25px 0;
        }

        .stat {
            background: rgba(255,255,255,0.08);
            padding: 15px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .stat:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #ffd966;
        }

        .stat-label {
            font-size: 0.75rem;
            opacity: 0.6;
            margin-top: 4px;
        }

        .feed-section {
            margin-top: 20px;
        }

        .feed-btn {
            background: linear-gradient(135deg, #ffd966, #ffab00);
            border: none;
            padding: 14px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #1a3c2c;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(255,215,0,0.2);
        }

        .feed-btn:hover:not(:disabled) {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(255,215,0,0.4);
        }

        .feed-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .feed-btn:active:not(:disabled) {
            transform: scale(0.95);
        }

        .food-points {
            margin-top: 15px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .food-points strong {
            color: #ffd966;
        }

        .history-section {
            background: rgba(0,0,0,0.3);
            border-radius: 30px;
            padding: 25px;
            opacity: 0;
            transform: translateY(30px);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .history-section.loaded {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s;
        }

        .history-title {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: #ffd966;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .history-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 0.9rem;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-item .user-name {
            color: #ffd966;
            font-weight: 500;
        }

        .history-item .history-date {
            opacity: 0.5;
            font-size: 0.8rem;
        }

        .history-empty {
            text-align: center;
            opacity: 0.5;
            padding: 20px 0;
        }

        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 16px 28px;
            border-radius: 16px;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            font-size: 0.95rem;
            z-index: 100;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
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
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 200;
            animation: fadeInOverlay 0.5s ease;
        }

        .level-up-overlay.show {
            display: flex;
        }

        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .level-up-content {
            text-align: center;
            animation: bounceIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes bounceIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .level-up-content .level-up-emoji {
            font-size: 6rem;
            display: block;
            margin-bottom: 15px;
        }

        .level-up-content .level-up-title {
            font-size: 3rem;
            font-weight: bold;
            color: #ffd966;
            margin-bottom: 10px;
        }

        .level-up-content .level-up-sub {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        .level-up-content .level-up-btn {
            margin-top: 25px;
            padding: 12px 40px;
            background: linear-gradient(135deg, #ffd966, #ffab00);
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            color: #1a3c2c;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .level-up-content .level-up-btn:hover {
            transform: scale(1.05);
        }

        .stage-evolution {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(10px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 200;
            animation: fadeInOverlay 0.5s ease;
        }

        .stage-evolution.show {
            display: flex;
        }

        .stage-evolution-content {
            text-align: center;
            animation: bounceIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .stage-evolution-content .evo-emoji {
            font-size: 7rem;
            display: block;
            margin-bottom: 15px;
        }

        .stage-evolution-content .evo-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #ffd966;
            margin-bottom: 10px;
        }

        .stage-evolution-content .evo-sub {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        .stage-evolution-content .evo-btn {
            margin-top: 25px;
            padding: 12px 40px;
            background: linear-gradient(135deg, #ffd966, #ffab00);
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            color: #1a3c2c;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .stage-evolution-content .evo-btn:hover {
            transform: scale(1.05);
        }

        @media (max-width: 640px) {
            .header {
                padding: 12px 15px;
                gap: 12px;
                flex-wrap: wrap;
            }
            .back-btn {
                font-size: 0.75rem;
                padding: 6px 14px;
            }
            .page-title {
                font-size: 1rem;
            }
            .pet-image { font-size: 5rem; }
            .pet-name { font-size: 1.4rem; }
            .pet-stats { gap: 10px; }
            .stat-value { font-size: 1.2rem; }
            .pet-card { padding: 25px; }
            .container { padding: 85px 15px 30px; }
            .feed-btn {
                padding: 12px 25px;
                font-size: 0.95rem;
            }
            .toast {
                bottom: 15px;
                right: 15px;
                left: 15px;
                max-width: none;
                padding: 14px 20px;
                font-size: 0.85rem;
            }
            .level-up-content .level-up-emoji { font-size: 4rem; }
            .level-up-content .level-up-title { font-size: 2rem; }
            .stage-evolution-content .evo-emoji { font-size: 4rem; }
            .stage-evolution-content .evo-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
    <!-- Background Particles -->
    <div class="bg-particles" id="bgParticles"></div>

    <!-- Header -->
    <div class="header">
        <button class="back-btn" onclick="goBack()">← Kembali ke Peta</button>
        <div class="page-title">🌊 <span>Muaralaya</span> · Pet</div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <span class="toast-icon" id="toastIcon">✅</span>
        <span id="toastMessage">Berhasil!</span>
    </div>

    <!-- Level Up Overlay -->
    <div class="level-up-overlay" id="levelUpOverlay">
        <div class="level-up-content">
            <span class="level-up-emoji" id="levelUpEmoji">🎉</span>
            <div class="level-up-title">LEVEL UP!</div>
            <div class="level-up-sub" id="levelUpSub">Pet sekarang level <strong id="levelUpLevel">2</strong></div>
            <button class="level-up-btn" onclick="closeLevelUp()">🎊 Lanjutkan</button>
        </div>
    </div>

    <!-- Stage Evolution Overlay -->
    <div class="stage-evolution" id="stageEvolution">
        <div class="stage-evolution-content">
            <span class="evo-emoji" id="evoEmoji">🌟</span>
            <div class="evo-title" id="evoTitle">EVOLUSI!</div>
            <div class="evo-sub" id="evoSub">Pet berevolusi menjadi <strong id="evoStageName">Bayi</strong></div>
            <button class="evo-btn" onclick="closeEvolution()">🎊 Lihat Perubahan!</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="pet-card" id="petCard">
            <div class="pet-image-wrapper">
                <div class="pet-image" id="petImage">🐙</div>
                <div class="pet-level-badge" id="petLevelBadge">Lv. 0</div>
            </div>
            <div class="pet-name" id="petName">Pet Kelompok</div>
            <div class="pet-stage" id="petStage">🥚 Telur</div>

            <div class="level-container">
                <div class="level-info">
                    <span class="level-label">⬆ Level <span id="petLevel">0</span></span>
                    <span><span id="petExp">0</span> / <span id="petExpNeeded">100</span> EXP</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="expBar" style="width: 0%"></div>
                </div>
            </div>

            <div class="pet-stats">
                <div class="stat">
                    <div class="stat-value" id="foodPoints">0</div>
                    <div class="stat-label">🍖 Food Points</div>
                </div>
                <div class="stat">
                    <div class="stat-value" id="petType">🐙</div>
                    <div class="stat-label">Tipe Pet</div>
                </div>
                <div class="stat">
                    <div class="stat-value" id="petStageBadge">🥚</div>
                    <div class="stat-label">Stage</div>
                </div>
            </div>

            <div class="feed-section">
                <button class="feed-btn" id="feedBtn" onclick="feedPet()">🍖 Beri Makan (10 FP)</button>
                <div class="food-points">Food Points tersisa: <strong id="userFoodPoints">0</strong></div>
            </div>
        </div>

        <div class="history-section" id="historySection">
            <div class="history-title">📜 Riwayat Makan</div>
            <div id="historyList">
                <div class="history-empty">Memuat riwayat...</div>
            </div>
        </div>
    </div>

    <script>
        // ============================================================
        //  BACKGROUND PARTICLES
        // ============================================================
        (function createParticles() {
            const container = document.getElementById('bgParticles');
            const count = 30;
            for (let i = 0; i < count; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.width = (2 + Math.random() * 4) + 'px';
                particle.style.height = particle.style.width;
                particle.style.animationDuration = (10 + Math.random() * 20) + 's';
                particle.style.animationDelay = (Math.random() * 20) + 's';
                particle.style.opacity = 0.2 + Math.random() * 0.3;
                container.appendChild(particle);
            }
        })();

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
            const overlay = document.getElementById('levelUpOverlay');
            document.getElementById('levelUpLevel').textContent = newLevel;
            overlay.classList.add('show');
        }

        function closeLevelUp() {
            document.getElementById('levelUpOverlay').classList.remove('show');
        }

        // ============================================================
        //  STAGE EVOLUTION
        // ============================================================
        function showEvolution(oldStage, newStage) {
            const overlay = document.getElementById('stageEvolution');
            const emojiMap = {
                'egg': '🥚',
                'baby': '🐣',
                'adult': '🦅',
                'legendary': '👑'
            };
            const nameMap = {
                'egg': 'Telur',
                'baby': 'Bayi',
                'adult': 'Dewasa',
                'legendary': 'Legendaris'
            };
            
            document.getElementById('evoEmoji').textContent = emojiMap[newStage] || '🌟';
            document.getElementById('evoTitle').textContent = '🌟 EVOLUSI!';
            document.getElementById('evoStageName').textContent = nameMap[newStage] || newStage;
            overlay.classList.add('show');
        }

        function closeEvolution() {
            document.getElementById('stageEvolution').classList.remove('show');
        }

        // ============================================================
        //  GO BACK
        // ============================================================
        function goBack() {
            gsap.to('body', {
                duration: 0.4,
                opacity: 0,
                y: 20,
                ease: "power2.in",
                onComplete: () => {
                    window.location.href = "{{ route('student.map') }}";
                }
            });
        }

        // ============================================================
        //  UPDATE PET UI
        // ============================================================
        function updatePetUI(data) {
            document.getElementById('petName').textContent = data.name;
            document.getElementById('petImage').textContent = data.emoji;
            document.getElementById('petLevelBadge').textContent = 'Lv. ' + data.level;

            const stageEl = document.getElementById('petStage');
            stageEl.textContent = data.stage_name;
            stageEl.className = 'pet-stage stage-' + data.stage;

            document.getElementById('petLevel').textContent = data.level;
            document.getElementById('petExp').textContent = data.experience;
            document.getElementById('petExpNeeded').textContent = data.exp_needed;
            document.getElementById('expBar').style.width = data.exp_progress + '%';

            document.getElementById('foodPoints').textContent = data.food_points;
            document.getElementById('userFoodPoints').textContent = data.food_points;
            document.getElementById('petType').textContent = data.emoji;
            document.getElementById('petStageBadge').textContent = data.stage_name;
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
                            <div class="history-item">
                                <span>
                                    <span class="user-name">${item.user_name}</span>
                                    memberi makan
                                    <strong>-${item.food_amount} FP</strong>
                                </span>
                                <span class="history-date">${item.date}</span>
                            </div>
                        `).join('');
                    } else {
                        list.innerHTML = '<div class="history-empty">Belum ada riwayat memberi makan</div>';
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    document.getElementById('historyList').innerHTML = '<div class="history-empty">Gagal memuat riwayat</div>';
                });
        }

        // ============================================================
        //  FEED PET (AJAX)
        // ============================================================
        function feedPet() {
            const btn = document.getElementById('feedBtn');
            btn.disabled = true;
            btn.textContent = '⏳ Memproses...';

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

                    // Update UI dengan data terbaru dari response
                    updatePetUI(data.pet);

                    // Update food points
                    document.getElementById('foodPoints').textContent = data.food_points;
                    document.getElementById('userFoodPoints').textContent = data.food_points;

                    // Cek level up
                    if (data.level_up) {
                        setTimeout(() => {
                            showLevelUp(data.pet.level);
                        }, 500);
                    }

                    // Cek stage change
                    if (data.stage_changed && data.new_stage) {
                        setTimeout(() => {
                            showEvolution(data.old_stage, data.new_stage);
                        }, 800);
                    }

                    // Refresh history
                    setTimeout(() => {
                        loadHistory();
                    }, 300);

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
                btn.textContent = '🍖 Beri Makan (10 FP)';
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
        //  INIT
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi masuk dengan GSAP
            gsap.fromTo('#petCard', 
                { y: -30, opacity: 0 }, 
                { duration: 0.6, y: 0, opacity: 1, ease: "back.out", delay: 0.2 }
            );
            gsap.fromTo('#historySection', 
                { y: 30, opacity: 0 }, 
                { duration: 0.6, y: 0, opacity: 1, ease: "back.out", delay: 0.4 }
            );

            // Load data dari API
            loadPetData();
            loadHistory();

            // Animate card
            const card = document.getElementById('petCard');
            card.classList.add('loaded');

            const history = document.getElementById('historySection');
            setTimeout(() => history.classList.add('loaded'), 300);
        });

        console.log('🌊 Muaralaya - Pulau Pet siap!');
        console.log('📌 Shortcut: [Spasi] Beri makan [Esc] Tutup overlay');
    </script>
</body>
</html>