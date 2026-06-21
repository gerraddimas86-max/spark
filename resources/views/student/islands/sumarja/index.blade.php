<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - Pulau CTF</title>
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
            background: linear-gradient(135deg, #0a2e5c 0%, #0a4c6e 100%);
            color: white;
        }

        /* Header dengan tombol back */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.5);
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
            display: flex;
            align-items: center;
            gap: 8px;
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

        /* Container utama */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 100px 25px 50px;
        }

        /* Hero section pulau */
        .island-hero {
            text-align: center;
            margin-bottom: 50px;
            opacity: 0;
        }

        .island-icon {
            font-size: 5rem;
            margin-bottom: 15px;
        }

        .island-hero h1 {
            font-size: 2.5rem;
            color: #ffd966;
            margin-bottom: 10px;
        }

        .island-hero p {
            opacity: 0.8;
        }

        /* Daftar challenge */
        .challenges-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            opacity: 0;
        }

        .challenge-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .challenge-card:hover {
            transform: translateY(-5px);
            border-color: #ffd966;
            background: rgba(255,255,255,0.15);
        }

        .challenge-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #ffd966;
        }

        .challenge-desc {
            font-size: 0.9rem;
            opacity: 0.7;
            margin-bottom: 15px;
        }

        .challenge-points {
            display: inline-block;
            background: rgba(255,215,0,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #ffd966;
        }

        .challenge-status {
            float: right;
            font-size: 0.8rem;
        }

        .status-completed {
            color: #4caf50;
        }

        .status-pending {
            color: #ff9800;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px;
            background: rgba(255,255,255,0.05);
            border-radius: 30px;
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .challenges-grid { grid-template-columns: 1fr; }
            .island-hero h1 { font-size: 1.8rem; }
            .container { padding: 90px 15px 30px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">← Kembali ke Peta</button>
        <div class="page-title">🏴‍☠️ <span>Pulau CTF</span> 🏴‍☠️</div>
    </div>

    <div class="container">
        <div class="island-hero" id="heroSection">
            <div class="island-icon">🗿⚔️📜</div>
            <h1>Challenge & Flag</h1>
            <p>Pecahkan kode, temukan flag, buktikan kemampuanmu!</p>
        </div>

        <div class="challenges-grid" id="challengesGrid">
            <!-- Data challenge akan di-load via JavaScript -->
            <div class="empty-state">
                <div class="icon">🏝️</div>
                <h3>Belum Ada Challenge</h3>
                <p>Developer sedang menyiapkan tantangan baru.</p>
            </div>
        </div>
    </div>

    <script>
        // Animasi masuk
        gsap.fromTo('#heroSection', { y: -30, opacity: 0 }, { duration: 0.6, y: 0, opacity: 1, ease: "back.out" });
        gsap.fromTo('#challengesGrid', { y: 30, opacity: 0 }, { duration: 0.6, y: 0, opacity: 1, delay: 0.2, ease: "power2.out" });

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

        // TODO: Fetch challenges dari API nanti
        // fetch('/api/student/cft/challenges')
        //     .then(res => res.json())
        //     .then(data => renderChallenges(data));
    </script>
</body>
</html>