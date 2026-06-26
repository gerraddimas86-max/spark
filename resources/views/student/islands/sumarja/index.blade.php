<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - 📜 Sumarja · Pulau CTF</title>
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

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 100;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-content {
            animation: modalIn 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Background -->
    <img src="{{ asset('images/background/sumarja-bg.png') }}" alt="Background" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- Back Button -->
    <button onclick="goBack()" 
            class="fixed top-6 left-6 md:top-8 md:left-8 z-50 bg-[url('{{ asset('images/button/btn-back.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[200px] h-[70px] text-white text-[0.8rem] font-medium tracking-[0.05em] transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 inline-flex items-center justify-center text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[140px] max-sm:h-[50px] max-sm:text-[0.6rem]">
        ← Kembali
    </button>

    <!-- Quest Button -->
    <button onclick="openQuests()" 
            class="fixed top-6 right-6 md:top-8 md:right-8 z-50 bg-[url('{{ asset('images/button/btn-quest.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[220px] h-[80px] text-white text-[0.8rem] font-medium tracking-[0.05em] transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 inline-flex items-center justify-center gap-2 text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[150px] max-sm:h-[55px] max-sm:text-[0.6rem] md:w-[170px] md:h-[60px] md:text-[0.7rem]">
        <i class="fas fa-list-check text-xs"></i>
        Quest
        <span class="text-[0.65rem] max-sm:text-[0.5rem]">({{ $questCompletedCount ?? 0 }}/{{ $questTotalCount ?? 0 }})</span>
    </button>

    <!-- Main Content -->
    <main class="relative z-[2] w-full min-h-screen flex items-center justify-center px-5 md:px-10 py-20">
        <div class="w-full max-w-4xl mx-auto" id="contentWrapper">

            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15 border border-white/15 mb-5">
                    <i class="fas fa-scroll text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-3 tracking-tight">
                    Sumarja
                </h1>
                <p class="text-base text-white/70 max-w-md mx-auto leading-relaxed font-light">
                    Taklukkan tantangan dan kumpulkan poin
                </p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto mb-10">
                <div class="bg-[url('{{ asset('images/card/card-stat.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-4 min-h-[80px] flex flex-col items-center justify-center text-center transition-all duration-300 hover:-translate-y-1 hover:brightness-105 rounded-[16px] md:rounded-[20px]">
                    <div class="text-[1.8rem] md:text-[1.4rem] max-sm:text-[1.2rem] font-bold text-white text-shadow-[0_2px_10px_rgba(0,0,0,0.5)]">
                        {{ $totalCount ?? 0 }}
                    </div>
                    <div class="text-[0.7rem] md:text-[0.6rem] max-sm:text-[0.5rem] font-light text-white text-shadow-[0_2px_8px_rgba(0,0,0,0.3)] uppercase tracking-[0.1em] mt-1">
                        Total Challenge
                    </div>
                </div>
                <div class="bg-[url('{{ asset('images/card/card-stat.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-4 min-h-[80px] flex flex-col items-center justify-center text-center transition-all duration-300 hover:-translate-y-1 hover:brightness-105 rounded-[16px] md:rounded-[20px]">
                    <div class="text-[1.8rem] md:text-[1.4rem] max-sm:text-[1.2rem] font-bold text-white text-shadow-[0_2px_10px_rgba(0,0,0,0.5)]">
                        {{ $completedCount ?? 0 }}
                    </div>
                    <div class="text-[0.7rem] md:text-[0.6rem] max-sm:text-[0.5rem] font-light text-white text-shadow-[0_2px_8px_rgba(0,0,0,0.3)] uppercase tracking-[0.1em] mt-1">
                        Selesai
                    </div>
                </div>
                <div class="bg-[url('{{ asset('images/card/card-stat.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-4 min-h-[80px] flex flex-col items-center justify-center text-center transition-all duration-300 hover:-translate-y-1 hover:brightness-105 rounded-[16px] md:rounded-[20px]">
                    <div class="text-[1.8rem] md:text-[1.4rem] max-sm:text-[1.2rem] font-bold text-white text-shadow-[0_2px_10px_rgba(0,0,0,0.5)]">
                        {{ $totalPoints ?? 0 }}
                    </div>
                    <div class="text-[0.7rem] md:text-[0.6rem] max-sm:text-[0.5rem] font-light text-white text-shadow-[0_2px_8px_rgba(0,0,0,0.3)] uppercase tracking-[0.1em] mt-1">
                        Total Poin
                    </div>
                </div>
            </div>

            <!-- Challenges List -->
            <div class="space-y-3 max-w-2xl mx-auto">
                @forelse($challenges ?? [] as $challenge)
                <a href="{{ route('student.sumarja.show', $challenge->id) }}" 
                   class="block bg-[url('{{ asset('images/card/card-challenge.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-4 md:p-5 min-h-[70px] md:min-h-[60px] max-sm:min-h-[50px] flex items-center justify-between transition-all duration-300 hover:translate-x-1 hover:scale-[1.01] hover:brightness-110 cursor-pointer rounded-[16px] md:rounded-[20px] no-underline {{ $challenge->is_completed ? 'brightness-[1.05] hue-rotate-[10deg]' : '' }}">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-8 h-8 md:w-[32px] md:h-[32px] max-sm:w-6 max-sm:h-6 rounded-full bg-white/15 flex items-center justify-center text-white text-[0.75rem] md:text-[0.75rem] max-sm:text-[0.6rem] font-medium flex-shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[0.85rem] md:text-[0.75rem] max-sm:text-[0.7rem] font-medium text-white truncate text-shadow-[0_1px_8px_rgba(0,0,0,0.5)]">
                                    {{ $challenge->title }}
                                </span>
                                @php
                                    $diff = $challenge->difficulty ?? 'medium';
                                @endphp
                                <span class="text-[0.55rem] max-sm:text-[0.45rem] px-2 py-0.5 rounded-full font-medium tracking-[0.05em] uppercase flex-shrink-0 
                                    @if($diff == 'easy') bg-green-400/30
                                    @elseif($diff == 'medium') text-yellow-400
                                    @else @endif">
                                    {{ $diff }}
                                </span>
                            </div>
                            <div class="text-[0.7rem] max-sm:hidden font-light text-white/60 truncate text-shadow-[0_1px_8px_rgba(0,0,0,0.3)]">
                                {{ $challenge->description ?? '' }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0 ml-2">
                        @if($challenge->is_completed ?? false)
                            <span class="text-green-400 text-[0.8rem]">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        @else
                            <span class="text-[0.7rem] font-light text-white text-shadow-[0_1px_8px_rgba(0,0,0,0.3)] flex-shrink-0">
                                {{ $challenge->points ?? 0 }} pts
                            </span>
                            <span class="text-white/50 group-hover:text-white transition-colors duration-300 flex-shrink-0">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="text-center text-white/50 py-10">
                    <i class="fas fa-inbox text-3xl block mb-3 opacity-30"></i>
                    <p class="text-sm">Belum ada challenge tersedia</p>
                </div>
                @endforelse
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-center gap-4 mt-12">
                <div class="w-8 h-px bg-white/20"></div>
                <i class="fas fa-compass text-white/20 text-xs"></i>
                <div class="w-8 h-px bg-white/20"></div>
            </div>

        </div>
    </main>

    <!-- Quest Modal -->
    @include('student.islands.sumarja.quest')

    <script>
        // ============================================================
        //  GO BACK
        // ============================================================
        function goBack() {
            window.location.href = "{{ route('student.map') }}";
        }

        // ============================================================
        //  QUEST MODAL
        // ============================================================
        function openQuests() {
            document.getElementById('questModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeQuests() {
            document.getElementById('questModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        // ============================================================
        //  COMPLETE QUEST (AJAX)
        // ============================================================
        function completeQuest(questId) {
            fetch('/student/sumarja/quest/' + questId + '/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal menyelesaikan quest');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Terjadi kesalahan');
            });
        }

        // ============================================================
        //  KEYBOARD SHORTCUTS
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (document.getElementById('questModal').classList.contains('active')) {
                    closeQuests();
                } else {
                    goBack();
                }
            }
        });

        // ============================================================
        //  CLOSE MODAL ON OVERLAY CLICK
        // ============================================================
        document.getElementById('questModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuests();
            }
        });

        // ============================================================
        //  ANIMATIONS
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            gsap.from('.bg-\\[url\\(.+\\)\\].min-h-\\[80px\\]', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.1,
                stagger: 0.08
            });

            gsap.from('.bg-\\[url\\(.+\\)\\].min-h-\\[70px\\]', {
                duration: 0.5,
                y: 15,
                opacity: 1,
                ease: "power2.out",
                delay: 0.25,
                stagger: 0.06
            });

            gsap.from('.text-center h1, .text-center p, .text-center .inline-flex', {
                duration: 0.4,
                y: 15,
                opacity: 1,
                ease: "power2.out",
                delay: 0.05,
                stagger: 0.06
            });

            gsap.from('.flex.items-center.justify-center.gap-4.mt-12', {
                duration: 0.4,
                opacity: 1,
                ease: "power2.out",
                delay: 0.5
            });
        });

        console.log('📜 Sumarja - Pulau CTF siap!');
        console.log('📌 Shortcut: [Esc] Kembali ke peta / Tutup modal');
    </script>
</body>
</html>