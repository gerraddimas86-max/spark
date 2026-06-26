<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - 📜 Challenge Detail</title>
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
    </style>
</head>
<body>
    <!-- Background -->
    <img src="{{ asset('images/background/sumarja-bg.png') }}" alt="Background" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <span class="toast-icon" id="toastIcon">✅</span>
        <span id="toastMessage">Berhasil!</span>
    </div>

    <!-- Back Button -->
    <button onclick="goBack()" 
            class="fixed top-6 left-6 md:top-8 md:left-8 z-50 bg-[url('{{ asset('images/button/btn-back.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[140px] h-[50px] text-white text-[0.8rem] font-medium tracking-[0.05em] transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 inline-flex items-center justify-center text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[100px] max-sm:h-[36px] max-sm:text-[0.6rem]">
        ← Kembali
    </button>

    <!-- Main Content -->
    <main class="relative z-[2] w-full min-h-screen flex items-center justify-center px-5 md:px-10 py-20">
        <div class="w-full max-w-3xl mx-auto" id="contentWrapper">

            <!-- Challenge Card -->
            <div class="bg-[url('{{ asset('images/card/card-challenge-detail.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-8 md:p-10 rounded-[20px] md:rounded-[24px]">
                
                <!-- Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/15 flex items-center justify-center text-white text-sm md:text-base flex-shrink-0">
                                <i class="fas fa-terminal"></i>
                            </div>
                            <h1 class="text-xl md:text-2xl font-bold text-white truncate text-shadow-[0_1px_8px_rgba(0,0,0,0.5)]">
                                {{ $challenge->title ?? 'Challenge Title' }}
                            </h1>
                        </div>
                        @php
                            $diff = $challenge->difficulty ?? 'medium';
                        @endphp
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-[0.55rem] px-3 py-1 rounded-full font-medium tracking-[0.05em] uppercase 
                                @if($diff == 'easy') bg-green-400/30 text-green-400
                                @elseif($diff == 'medium') bg-yellow-400/30 text-yellow-400
                                @else bg-red-400/30 text-red-400 @endif">
                                {{ $diff }}
                            </span>
                            <span class="text-[0.7rem] text-white/50">
                                <i class="fas fa-star text-yellow-400/50 mr-1"></i>
                                {{ $challenge->points ?? 0 }} poin
                            </span>
                            <span class="text-[0.7rem] text-white/50">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $challenge->estimated_time ?? '15 menit' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <div class="text-[0.6rem] text-white/30 text-right">
                            <span class="block">Challenge #{{ $loop->iteration ?? 1 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6 p-4 md:p-5 bg-white/5 rounded-xl border border-white/5">
                    <p class="text-sm md:text-base text-white/80 leading-relaxed text-shadow-[0_1px_8px_rgba(0,0,0,0.3)]">
                        {{ $challenge->description ?? 'Deskripsi challenge akan muncul di sini. Jelaskan tantangan yang harus diselesaikan oleh peserta.' }}
                    </p>
                </div>

                <!-- Challenge Content -->
                <div class="mb-6 p-4 md:p-5 bg-black/30 rounded-xl border border-white/5">
                    <h3 class="text-sm font-semibold text-white/70 mb-3">
                        <i class="fas fa-code mr-2"></i>Instruksi
                    </h3>
                    <div class="text-sm text-white/60 leading-relaxed space-y-2">
                        {!! $challenge->instruction ?? '<p>Instruksi challenge akan muncul di sini.</p>' !!}
                    </div>
                </div>

                <!-- Flag Input -->
                <div class="mb-6 p-4 md:p-5 bg-black/30 rounded-xl border border-white/5">
                    <h3 class="text-sm font-semibold text-white/70 mb-3">
                        <i class="fas fa-flag mr-2"></i>Submit Flag
                    </h3>
                    <form id="submitForm" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <input type="text" 
                               name="answer" 
                               id="flagInput"
                               placeholder="Masukkan flag..." 
                               class="flex-1 bg-white/10 border border-white/15 rounded-xl px-4 py-3 text-white text-sm placeholder:text-white/30 focus:outline-none focus:border-white/30 transition-all duration-300">
                        <button type="submit" 
                                id="submitBtn"
                                class="bg-white/15 hover:bg-white/25 border border-white/15 px-6 py-3 rounded-xl text-white text-sm font-medium transition-all duration-300 hover:scale-105 active:scale-95 whitespace-nowrap">
                            <i class="fas fa-paper-plane mr-2"></i>Submit
                        </button>
                    </form>
                    <div id="feedbackMessage" class="mt-3 text-sm hidden"></div>
                </div>

                <!-- Hints -->
                @if(isset($challenge->hints) && $challenge->hints)
                <div class="p-4 md:p-5 bg-yellow-400/5 rounded-xl border border-yellow-400/10">
                    <h3 class="text-sm font-semibold text-yellow-400/70 mb-2">
                        <i class="fas fa-lightbulb mr-2"></i>Petunjuk
                    </h3>
                    <p class="text-sm text-white/50 leading-relaxed">
                        {{ $challenge->hints }}
                    </p>
                </div>
                @endif

            </div>

            <!-- Footer -->
            <div class="flex items-center justify-center gap-4 mt-8">
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
        //  GO BACK
        // ============================================================
        function goBack() {
            window.location.href = "{{ route('student.island.sumarja') }}";
        }

        // ============================================================
        //  SUBMIT FLAG (AJAX)
        // ============================================================
        document.getElementById('submitForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const input = document.getElementById('flagInput');
            const feedback = document.getElementById('feedbackMessage');
            const url = "{{ route('student.sumarja.submit', $challenge->id) }}";

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            feedback.className = 'mt-3 text-sm hidden';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ answer: input.value })
            })
            .then(res => res.json())
            .then(data => {
                feedback.className = 'mt-3 text-sm ' + (data.success ? 'text-green-400' : 'text-red-400');
                feedback.textContent = data.message;
                feedback.classList.remove('hidden');

                showToast(data.message, data.success ? 'success' : 'error');

                if (data.success) {
                    input.disabled = true;
                    btn.innerHTML = '<i class="fas fa-check mr-2"></i>Selesai!';
                    btn.disabled = true;
                    
                    // Redirect setelah 2 detik
                    setTimeout(() => {
                        window.location.href = "{{ route('student.island.sumarja') }}";
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit';
                    input.focus();
                }
            })
            .catch(err => {
                console.error('Error:', err);
                feedback.className = 'mt-3 text-sm text-red-400';
                feedback.textContent = 'Terjadi kesalahan, silakan coba lagi.';
                feedback.classList.remove('hidden');
                showToast('Terjadi kesalahan', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit';
            });
        });

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
            gsap.from('.bg-\\[url\\(.+\\)\\].p-8', {
                duration: 0.6,
                y: 30,
                opacity: 1,
                ease: "power2.out",
                delay: 0.1
            });

            gsap.from('.flex.items-start.justify-between, .mb-6.p-4, .mb-6.p-4.bg-black\\/30', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.2,
                stagger: 0.08
            });

            gsap.from('.flex.items-center.justify-center.gap-4.mt-8', {
                duration: 0.4,
                opacity: 1,
                ease: "power2.out",
                delay: 0.5
            });
        });

        console.log('📜 Challenge Detail siap!');
        console.log('📌 Shortcut: [Esc] Kembali ke daftar challenge');
    </script>
</body>
</html>