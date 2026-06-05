<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPARK - PKKMB Fasilkom UNSRI</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', sans-serif; overflow: hidden; }
        
        #ui-overlay {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 15px;
            z-index: 20;
            flex-wrap: wrap;
            padding: 0 20px;
        }
        
        .nav-btn {
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(10px);
            color: white;
            border: 1px solid rgba(255, 215, 0, 0.5);
            padding: 12px 28px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .nav-btn:hover {
            transform: scale(1.08);
            background: rgba(0, 0, 0, 0.9);
            border-color: #ffd700;
        }
        
        #info-top {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            text-shadow: 1px 1px 3px black;
            z-index: 20;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            padding: 12px 24px;
            border-radius: 60px;
            font-size: 14px;
            border: 1px solid rgba(255,215,0,0.3);
        }
        
        .spark-title { font-size: 1.5rem; font-weight: bold; letter-spacing: 2px; }
        .spark-title span { color: #ffd700; }
        .group-info { display: flex; gap: 20px; align-items: center; }
        .group-info-item { display: flex; align-items: center; gap: 8px; }
        .food-badge { background: rgba(255, 107, 53, 0.9); padding: 4px 12px; border-radius: 50px; font-weight: bold; }
        
        #popup-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            z-index: 100;
            justify-content: center;
            align-items: center;
        }
        
        .popup-container {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 24px;
            max-width: 550px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            border: 1px solid rgba(255, 215, 0, 0.4);
        }
        
        .popup-header {
            background: linear-gradient(135deg, #ffd700 0%, #ff8c00 100%);
            padding: 18px 20px;
            border-radius: 24px 24px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .popup-header h2 { color: #1a1a2e; margin: 0; font-size: 1.5rem; }
        .close-popup { background: rgba(0,0,0,0.3); border: none; color: white; font-size: 24px; cursor: pointer; width: 36px; height: 36px; border-radius: 50%; }
        .popup-content { padding: 24px; color: #e0e0e0; }
        .loading { text-align: center; padding: 40px; }
        .loading-spinner { width: 40px; height: 40px; border: 3px solid rgba(255,215,0,0.3); border-top-color: #ffd700; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 15px; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div id="canvas-container" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;"></div>
    
    <div id="info-top">
        <div class="spark-title">🏴‍☠️ <span>SPARK</span> | PKKMB Fasilkom UNSRI</div>
        <div class="group-info">
            <div class="group-info-item">👤 {{ $user->name }}</div>
            <div class="group-info-item">🏠 {{ $group->name ?? 'Belum ada kelompok' }}</div>
            <div class="group-info-item food-badge">🍖 {{ $user->food_points }}</div>
        </div>
    </div>
    
    <div id="ui-overlay">
        <button class="nav-btn" onclick="loadPopup('quests')">📜 QUEST</button>
        <button class="nav-btn" onclick="loadPopup('cft')">🏴 CFT</button>
        <button class="nav-btn" onclick="loadPopup('announcements')">📢 INFO</button>
        <button class="nav-btn" onclick="window.location.href='{{ route('student.dashboard') }}'">📊 DASHBOARD</button>
        <button class="nav-btn" onclick="resetCameraView()">🎥 RESET CAMERA</button>
    </div>
    
    <div id="popup-modal">
        <div class="popup-container">
            <div class="popup-header">
                <h2 id="popup-title">Loading...</h2>
                <button class="close-popup" onclick="closePopup()">✕</button>
            </div>
            <div class="popup-content" id="popup-content">
                <div class="loading"><div class="loading-spinner"></div><p>Memuat...</p></div>
            </div>
        </div>
    </div>

    @vite(['resources/js/three-scene.js'])
    
    <script>
        function resetCameraView() { if (window.resetCamera) window.resetCamera(); }
        
        async function loadPopup(page) {
            const modal = document.getElementById('popup-modal');
            const contentDiv = document.getElementById('popup-content');
            const titleDiv = document.getElementById('popup-title');
            modal.style.display = 'flex';
            contentDiv.innerHTML = `<div class="loading"><div class="loading-spinner"></div><p>Memuat ${page}...</p></div>`;
            const titles = { 'quests': '📜 Quest Harian', 'cft': '🏴 Capture The Flag', 'announcements': '📢 Pengumuman' };
            titleDiv.innerText = titles[page] || page;
            try {
                const response = await fetch(`/student/${page}`);
                const html = await response.text();
                contentDiv.innerHTML = html;
            } catch (error) {
                contentDiv.innerHTML = `<div class="text-center text-red-400"><p>⚠️ Gagal memuat data</p><button onclick="loadPopup('${page}')" class="mt-3 px-4 py-2 bg-yellow-600 rounded">Coba Lagi</button></div>`;
            }
        }
        
        function closePopup() { document.getElementById('popup-modal').style.display = 'none'; }
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePopup(); });
        document.getElementById('popup-modal').addEventListener('click', function(e) { if (e.target === this) closePopup(); });
    </script>
    
    <script type="module">
        import { initThreeScene, resetCamera } from '{{ Vite::asset('resources/js/three-scene.js') }}';
        document.addEventListener('DOMContentLoaded', function() { initThreeScene('canvas-container'); });
        window.resetCamera = resetCamera;
    </script>
</body>
</html>