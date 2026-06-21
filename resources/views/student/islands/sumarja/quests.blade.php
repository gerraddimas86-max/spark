<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - Quest Harian</title>
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
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(10px);
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            z-index: 50;
            border-bottom: 1px solid rgba(255,215,0,0.3);
        }

        .back-btn {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,215,0,0.5);
            padding: 8px 20px;
            border-radius: 30px;
            color: #ffd966;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: rgba(255,215,0,0.2);
            transform: translateX(-3px);
        }

        .page-title {
            font-size: 1.5rem;
            letter-spacing: 2px;
        }

        .page-title span {
            color: #ffd966;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 100px 25px 50px;
        }

        /* Stats Card */
        .stats-card {
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid rgba(255,215,0,0.3);
            opacity: 0;
        }

        .stats-grid {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #ffd966;
        }

        .stat-label {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        /* Quest List */
        .quests-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            opacity: 0;
        }

        .quest-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .quest-card.completed {
            background: rgba(76, 175, 80, 0.15);
            border-color: #4caf50;
        }

        .quest-info {
            flex: 1;
        }

        .quest-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .quest-desc {
            font-size: 0.85rem;
            opacity: 0.7;
        }

        .quest-reward {
            background: rgba(255,215,0,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #ffd966;
        }

        .quest-status {
            font-size: 0.8rem;
        }

        .status-completed {
            color: #4caf50;
        }

        .status-pending {
            color: #ff9800;
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            background: rgba(255,255,255,0.05);
            border-radius: 30px;
        }

        @media (max-width: 640px) {
            .stats-grid { gap: 10px; }
            .stat-value { font-size: 1.5rem; }
            .quest-card { flex-direction: column; text-align: center; }
            .quest-info { text-align: center; }
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">← Kembali ke Peta</button>
        <div class="page-title">📜 <span>Quest Harian</span> 📜</div>
    </div>

    <div class="container">
        <!-- Stats Card -->
        <div class="stats-card" id="statsCard">
            <h3>🏆 Progress Quest 🏆</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">{{ $completedCount }}/{{ $totalCount }}</div>
                    <div class="stat-label">Quest Selesai</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $totalReward }}</div>
                    <div class="stat-label">Food Points Didapat</div>
                </div>
            </div>
        </div>

        <!-- Quest List -->
        <div class="quests-list" id="questsList">
            @forelse($quests as $quest)
            <div class="quest-card {{ $quest->completed ? 'completed' : '' }}">
                <div class="quest-info">
                    <div class="quest-name">
                        {{ $quest->name }}
                        @if($quest->completed)
                            ✅
                        @endif
                    </div>
                    <div class="quest-desc">{{ $quest->description }}</div>
                </div>
                <div class="quest-reward">
                    🍖 +{{ $quest->reward_food_points }} FP
                </div>
                <div class="quest-status">
                    @if($quest->completed)
                        <span class="status-completed">✓ Selesai</span>
                    @else
                        <span class="status-pending">◌ Belum</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="icon">📜</div>
                <h3>Belum Ada Quest</h3>
                <p>Quest harian akan segera tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>

    <script>
        // Animasi masuk
        gsap.fromTo('#statsCard', { y: -30, opacity: 0 }, { duration: 0.5, y: 0, opacity: 1, ease: "back.out" });
        gsap.fromTo('#questsList', { y: 30, opacity: 0 }, { duration: 0.5, y: 0, opacity: 1, delay: 0.2, ease: "power2.out" });

        function goBack() {
            gsap.to('body', {
                duration: 0.4,
                opacity: 0,
                y: 20,
                ease: "power2.in",
                onComplete: () => {
                    window.location.href = "{{ route('student.hero') }}";
                }
            });
        }
    </script>
</body>
</html>