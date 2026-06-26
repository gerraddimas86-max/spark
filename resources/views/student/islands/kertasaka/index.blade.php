<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - ⚔️ Kertasaka · Tavern</title>
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
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .back-btn {
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(-4px);
        }

        .content-wrapper {
            opacity: 0;
        }
    </style>
</head>
<body>
    <!-- Background -->
    <img src="{{ asset('images/background/kertasaka-bg.png') }}" alt="Background" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- Back Button -->
    <button onclick="goBack()" class="back-btn fixed top-6 left-6 md:top-8 md:left-8 z-50 bg-white/10 backdrop-blur-sm border border-white/15 px-5 py-2.5 rounded-full text-white/80 hover:text-white flex items-center gap-3 text-sm font-medium transition-all duration-300">
        <i class="fas fa-arrow-left text-xs"></i>
        <span>Kembali</span>
    </button>

    <!-- Main Content -->
    <main class="relative z-[2] w-full min-h-screen flex items-center justify-center px-5 md:px-10">
        <div class="content-wrapper w-full max-w-4xl mx-auto text-center" id="contentWrapper">
            <!-- Title -->
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-light text-white tracking-wide mb-4">
                Kertasaka Tavern
            </h1>
            <p class="text-base md:text-lg text-white/40 font-light tracking-wide max-w-md mx-auto">
                Tempat para bajak laut berkumpul
            </p>

            <!-- Decorative Line -->
            <div class="flex items-center justify-center gap-4 mt-8">
                <div class="w-12 h-px bg-white/10"></div>
                <i class="fas fa-circle text-white/10 text-[6px]"></i>
                <div class="w-12 h-px bg-white/10"></div>
            </div>
        </div>
    </main>

    <script>
        // ============================================================
        //  GO BACK
        // ============================================================
        function goBack() {
            gsap.to('#contentWrapper', {
                duration: 0.4,
                opacity: 0,
                y: -20,
                ease: "power2.in",
                onComplete: () => {
                    window.location.href = "{{ route('student.map') }}";
                }
            });
        }

        // ============================================================
        //  KEYBOARD SHORTCUTS
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                goBack();
            }
        });

        // ============================================================
        //  ANIMATIONS
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            gsap.to('#contentWrapper', {
                duration: 0.8,
                opacity: 1,
                y: 0,
                ease: "power2.out",
                delay: 0.2
            });

            gsap.from('h1, p', {
                duration: 0.6,
                y: 20,
                opacity: 0,
                ease: "power2.out",
                delay: 0.1,
                stagger: 0.1
            });

            gsap.from('.flex.items-center.justify-center.gap-4', {
                duration: 0.5,
                opacity: 0,
                ease: "power2.out",
                delay: 0.4
            });
        });

        console.log('⚔️ Kertasaka Tavern siap!');
        console.log('📌 Shortcut: [Esc] Kembali ke peta');
    </script>
</body>
</html>