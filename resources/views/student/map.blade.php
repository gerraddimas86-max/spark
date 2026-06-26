<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK · Peta Dunia</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @font-face {
            font-family: 'Battlesbridge';
            src: url('{{ asset("font/BattlesbridgeDemo-AL126.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Segoe UI', 'Poppins', system-ui, sans-serif;
            min-height: 100vh;
            background: #0a0a0a;
            color: #ffffff;
            overflow: hidden;
        }

        /* ===== BACKGROUND FULL ===== */
        .map-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset("images/map/background-map.webp") }}') no-repeat center center;
            background-size: cover;
            z-index: 0;
        }

        /* Overlay gelap tipis */
        .map-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 10, 10, 0.3);
            z-index: 1;
        }

        /* ===== MAP CONTAINER ===== */
        .map-container {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== BACK BUTTON dengan background image ===== */
        .back-btn {
            position: fixed;
            top: 30px;
            left: 30px;
            z-index: 10;
            background: url('{{ asset("images/button/btn-back.png") }}') no-repeat center center;
            background-size: contain;
            border: none;
            width: 170px;
            height: 70px;
            color: #ffffff;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
        }

        .back-btn:hover {
            transform: translateX(-3px) scale(1.03);
            filter: brightness(1.1);
        }

        .back-btn:active {
            transform: scale(0.95);
        }

        /* ===== PULAU-PULAU ===== */
        .islands-wrapper {
            position: relative;
            width: 90%;
            max-width: 1100px;
            height: 80vh;
            max-height: 700px;
        }

        .island {
            position: absolute;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-align: center;
            filter: drop-shadow(0 8px 30px rgba(0, 0, 0, 0.6));
            opacity: 0;
            transform: scale(0.8) translateY(30px);
        }

        .island.visible {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        .island:hover {
            transform: scale(1.10) translateY(-10px);
            filter: drop-shadow(0 20px 50px rgba(255, 255, 255, 0.15));
        }

        .island:active {
            transform: scale(0.95);
        }

        .island img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .island .island-name {
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.9rem;
            font-weight: 500;
            color: #ffffff;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.9);
            white-space: nowrap;
            letter-spacing: 2px;
            opacity: 0.9;
        }

        .island .island-sub {
            position: absolute;
            bottom: -58px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.35);
            white-space: nowrap;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 300;
        }

        /* ===== POSISI PULAU ===== */
        .island-muaralaya {
            top: 15%;
            left: 5%;
            width: 18%;
        }

        .island-kertasaka {
            top: 55%;
            left: 2%;
            width: 16%;
        }

        .island-sumarja {
            top: 10%;
            right: 5%;
            width: 18%;
        }

        .island-markasena {
            bottom: 10%;
            right: 8%;
            width: 20%;
        }

        /* ===== TITLE ===== */
        .map-title {
            position: absolute;
            top: 35px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            text-align: center;
            pointer-events: none;
        }

        .map-title h1 {
            font-family: 'Battlesbridge', sans-serif;
            font-size: 2.8rem;
            color: #ffffff;
            text-shadow: 0 4px 40px rgba(0, 0, 0, 0.8);
            letter-spacing: 6px;
            font-weight: normal;
        }

        .map-title p {
            font-size: 0.7rem;
            opacity: 0.4;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-top: 6px;
            font-weight: 300;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .back-btn {
                top: 20px;
                left: 20px;
                width: 120px;
                height: 42px;
                font-size: 0.7rem;
            }

            .islands-wrapper {
                width: 95%;
                height: 75vh;
            }

            .island .island-name {
                font-size: 0.7rem;
                bottom: -28px;
                letter-spacing: 1px;
            }

            .island .island-sub {
                display: none;
            }

            .island-muaralaya {
                top: 12%;
                left: 3%;
                width: 22%;
            }

            .island-kertasaka {
                top: 55%;
                left: 0%;
                width: 20%;
            }

            .island-sumarja {
                top: 8%;
                right: 3%;
                width: 22%;
            }

            .island-markasena {
                bottom: 8%;
                right: 5%;
                width: 24%;
            }

            .map-title {
                top: 70px;
            }

            .map-title h1 {
                font-size: 2rem;
                letter-spacing: 4px;
            }

            .map-title p {
                font-size: 0.55rem;
                letter-spacing: 3px;
            }
        }

        @media (max-width: 480px) {
            .back-btn {
                top: 15px;
                left: 15px;
                width: 100px;
                height: 36px;
                font-size: 0.6rem;
            }

            .islands-wrapper {
                height: 70vh;
            }

            .island-muaralaya {
                top: 15%;
                left: 2%;
                width: 28%;
            }

            .island-kertasaka {
                top: 58%;
                left: -2%;
                width: 25%;
            }

            .island-sumarja {
                top: 10%;
                right: 2%;
                width: 28%;
            }

            .island-markasena {
                bottom: 5%;
                right: 2%;
                width: 30%;
            }

            .island .island-name {
                font-size: 0.55rem;
                bottom: -22px;
                letter-spacing: 0.5px;
            }

            .map-title {
                top: 60px;
            }

            .map-title h1 {
                font-size: 1.5rem;
                letter-spacing: 3px;
            }

            .map-title p {
                font-size: 0.45rem;
                letter-spacing: 2px;
            }
        }

        /* ===== ANIMASI FADE IN ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .island.animated {
            animation: fadeInUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
    </style>
</head>
<body>

    <!-- ===== BACKGROUND ===== -->
    <div class="map-background"></div>
    <div class="map-overlay"></div>

    <!-- ===== BACK BUTTON dengan background image ===== -->
    <button class="back-btn" onclick="goBack()">← Kembali</button>

    <!-- ===== TITLE ===== -->
    <div class="map-title">
        <h1>SPARK</h1>
        <p>Pilih Pulau</p>
    </div>

    <!-- ===== MAP ===== -->
    <div class="map-container">
        <div class="islands-wrapper">

            <!-- ===== PULAU 1: MUARALAYA ===== -->
            <div class="island island-muaralaya" onclick="goToIsland('muaralaya')" data-island="muaralaya">
                <img src="{{ asset('images/map/muaralaya.png') }}" alt="Muaralaya" loading="lazy">
                <div class="island-name">Muaralaya</div>
                <div class="island-sub">Pulau Pet</div>
            </div>

            <!-- ===== PULAU 2: KERTASAKA ===== -->
            <div class="island island-kertasaka" onclick="goToIsland('kertasaka')" data-island="kertasaka">
                <img src="{{ asset('images/map/kertasaka.png') }}" alt="Kertasaka" loading="lazy">
                <div class="island-name">Kertasaka</div>
                <div class="island-sub">Pulau Tavern</div>
            </div>

            <!-- ===== PULAU 3: SUMARJA ===== -->
            <div class="island island-sumarja" onclick="goToIsland('sumarja')" data-island="sumarja">
                <img src="{{ asset('images/map/sumarja.png') }}" alt="Sumarja" loading="lazy">
                <div class="island-name">Sumarja</div>
                <div class="island-sub">Pulau CTF & Quest</div>
            </div>

            <!-- ===== PULAU 4: MARKASENA ===== -->
            <div class="island island-markasena" onclick="goToIsland('markasena')" data-island="markasena">
                <img src="{{ asset('images/map/markasena.png') }}" alt="Markasena" loading="lazy">
                <div class="island-name">Markasena</div>
                <div class="island-sub">Pulau Markas</div>
            </div>

        </div>
    </div>

    <script>
        // ============================================================
        //  GO BACK
        // ============================================================
        function goBack() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.4s ease';
            setTimeout(() => {
                window.location.href = "{{ route('student.hero') }}";
            }, 400);
        }

        // ============================================================
        //  GO TO ISLAND
        // ============================================================
        const islandRoutes = {
            muaralaya: "{{ route('student.island.muaralaya') }}",
            kertasaka: "{{ route('student.island.kertasaka') }}",
            sumarja: "{{ route('student.island.sumarja') }}",
            markasena: "{{ route('student.island.markasena') }}",
        };

        function goToIsland(island) {
            const route = islandRoutes[island];
            if (!route) {
                console.error('Pulau tidak ditemukan:', island);
                return;
            }

            // Animasi klik
            const el = document.querySelector(`.island[data-island="${island}"]`);
            if (el) {
                el.style.transform = 'scale(0.92)';
                setTimeout(() => {
                    el.style.transform = '';
                }, 200);
            }

            // Redirect dengan animasi
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.4s ease';
            setTimeout(() => {
                window.location.href = route;
            }, 400);
        }

        // ============================================================
        //  KEYBOARD SHORTCUTS
        // ============================================================
        document.addEventListener('keydown', function(e) {
            // Escape untuk kembali
            if (e.key === 'Escape') {
                goBack();
            }

            // Angka 1-4 untuk langsung ke pulau
            const islandKeys = ['muaralaya', 'kertasaka', 'sumarja', 'markasena'];
            if (e.key >= '1' && e.key <= '4') {
                const index = parseInt(e.key) - 1;
                if (index < islandKeys.length) {
                    goToIsland(islandKeys[index]);
                }
            }
        });

        // ============================================================
        //  ANIMASI MASUK
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            const islands = document.querySelectorAll('.island');
            islands.forEach((island, index) => {
                setTimeout(() => {
                    island.classList.add('animated');
                }, 150 + (index * 120));
            });
        });

        console.log('🗺️ Peta Dunia SPARK');
        console.log('📌 Shortcut: [1] Muaralaya [2] Kertasaka [3] Sumarja [4] Markasena [Esc] Kembali');
    </script>
</body>
</html>