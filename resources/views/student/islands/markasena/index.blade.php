<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - 🏴 Markasena · Pengumuman</title>
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

        .modal-content::-webkit-scrollbar {
            width: 4px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Background -->
    <img src="{{ asset('images/background/markasena-bg.png') }}" alt="Background" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- Back Button -->
    <button onclick="goBack()" 
            class="back-btn fixed top-6 left-6 md:top-8 md:left-8 z-50 bg-[url('{{ asset('images/button/btn-back.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[200px] h-[70px] text-white text-[0.8rem] font-medium tracking-[0.05em] transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 inline-flex items-center justify-center text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[140px] max-sm:h-[50px] max-sm:text-[0.6rem]">
        ← Kembali
    </button>

    <!-- Profile Button -->
    <a href="{{ route('student.markasena.profile') }}" 
       class="profile-btn fixed top-6 right-6 md:top-8 md:right-8 z-50 bg-[url('{{ asset('images/button/btn-profile.png') }}')] bg-contain bg-center bg-no-repeat border-none w-[280px] h-[100px] text-white text-[0.8rem] font-medium tracking-[0.05em] transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95 inline-flex items-center justify-center gap-2 text-shadow-[0_2px_10px_rgba(0,0,0,0.7)] max-sm:w-[170px] max-sm:h-[60px] max-sm:text-[0.6rem] md:w-[200px] md:h-[70px] md:text-[0.7rem]">
        <i class="fas fa-user text-xs"></i>
        Profil
    </a>

    <!-- Main Content -->
    <main class="relative z-[2] w-full min-h-screen flex items-center justify-center px-5 md:px-10 py-20">
        <div class="w-full max-w-4xl mx-auto" id="contentWrapper">

            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15 border border-white/15 mb-5">
                    <i class="fas fa-flag text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-3 tracking-tight">
                    Markasena
                </h1>
                <p class="text-base text-white/70 max-w-md mx-auto leading-relaxed font-light">
                    Pusat informasi dan pengumuman untuk kelompokmu
                </p>
                @if($groupName)
                    <div class="mt-3 inline-flex items-center gap-2 text-sm text-white/50 bg-white/10 px-4 py-1.5 rounded-full">
                        <i class="fas fa-users text-xs"></i>
                        <span>{{ $groupName }}</span>
                    </div>
                @endif
            </div>

            <!-- Announcements List -->
            <div class="space-y-3 max-w-2xl mx-auto">
                @forelse($announcements ?? [] as $announcement)
                <div onclick="openModal('{{ $announcement->id }}')" 
                     class="announcement-card bg-[url('{{ asset('images/card/card-announcement.png') }}')] bg-[length:100%_100%] bg-center bg-no-repeat border-none p-5 md:p-6 min-h-[80px] md:min-h-[90px] flex items-center justify-between transition-all duration-300 hover:translate-x-1 hover:scale-[1.01] hover:brightness-110 cursor-pointer rounded-[20px] md:rounded-[24px]">
                    <div class="flex items-center gap-3 md:gap-4 flex-1 min-w-0">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/15 flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-bullhorn text-xs md:text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm md:text-base font-medium text-white truncate text-shadow-[0_1px_8px_rgba(0,0,0,0.5)]">
                                {{ $announcement->title }}
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[0.6rem] md:text-[0.65rem] text-white/50 text-shadow-[0_1px_8px_rgba(0,0,0,0.3)]">
                                    {{ $announcement->created_at->diffForHumans() }}
                                </span>
                                <span class="w-px h-3 bg-white/10"></span>
                                <span class="text-[0.55rem] md:text-[0.6rem] text-white/35 text-shadow-[0_1px_8px_rgba(0,0,0,0.3)] max-sm:hidden">
                                    oleh {{ $announcement->creator->name ?? 'Admin' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-2 text-white/40 hover:text-white transition-colors duration-300">
                        <i class="fas fa-chevron-right text-xs md:text-sm"></i>
                    </div>
                </div>
                @empty
                <div class="text-center text-white/50 py-16">
                    <i class="fas fa-inbox text-4xl block mb-4 opacity-30"></i>
                    <p class="text-sm">Belum ada pengumuman</p>
                    <p class="text-xs text-white/30 mt-1">Pengumuman dari mentor akan muncul di sini</p>
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

    <!-- Modal -->
    <div class="modal-overlay" id="announcementModal" onclick="closeModalOutside(event)">
        <div class="modal-content bg-[rgba(15,15,25,0.95)] border border-white/10 rounded-2xl p-8 md:p-10 max-w-[560px] w-full max-h-[80vh] overflow-y-auto relative mx-4">
            <button onclick="closeModal()" 
                    class="absolute top-4 right-5 bg-none border-none text-white/40 hover:text-white text-2xl transition-all duration-300 hover:rotate-90 cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
            <div class="text-xl md:text-2xl font-semibold text-white mb-2 pr-8" id="modalTitle">Judul Pengumuman</div>
            <div class="text-xs md:text-sm text-white/40 mb-4" id="modalMeta">1 jam yang lalu · oleh Admin</div>
            <div class="text-sm md:text-base text-white/75 leading-relaxed" id="modalBody">
                <p>Isi pengumuman akan muncul di sini...</p>
            </div>
        </div>
    </div>

    <script>
        // ============================================================
        //  GO BACK
        // ============================================================
        function goBack() {
            window.location.href = "{{ route('student.map') }}";
        }

        // ============================================================
        //  MODAL
        // ============================================================
        const announcementsData = @json($announcements ?? []);

        function openModal(id) {
            const announcement = announcementsData.find(a => a.id == id);
            if (!announcement) return;

            document.getElementById('modalTitle').textContent = announcement.title || 'Pengumuman';
            document.getElementById('modalMeta').textContent = 
                (announcement.created_at ? new Date(announcement.created_at).toLocaleDateString('id-ID', { 
                    day: 'numeric', month: 'long', year: 'numeric' 
                }) : '') + 
                ' · oleh ' + (announcement.creator?.name || 'Admin');
            
            document.getElementById('modalBody').innerHTML = 
                announcement.description || announcement.content || '<p>Tidak ada konten yang tersedia.</p>';

            document.getElementById('announcementModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('announcementModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        function closeModalOutside(event) {
            if (event.target === event.currentTarget) {
                closeModal();
            }
        }

        // ============================================================
        //  KEYBOARD SHORTCUTS
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (document.getElementById('announcementModal').classList.contains('active')) {
                    closeModal();
                } else {
                    goBack();
                }
            }
        });

        // ============================================================
        //  ANIMATIONS
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            gsap.from('.text-center h1, .text-center p, .text-center .inline-flex, .text-center .mt-3', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.05,
                stagger: 0.08
            });

            gsap.from('.announcement-card', {
                duration: 0.5,
                y: 15,
                opacity: 1,
                ease: "power2.out",
                delay: 0.2,
                stagger: 0.06
            });

            gsap.from('.flex.items-center.justify-center.gap-4.mt-12', {
                duration: 0.4,
                opacity: 1,
                ease: "power2.out",
                delay: 0.4
            });
        });

        console.log('🏴 Markasena - Pulau Markas siap!');
        console.log('📌 Shortcut: [Esc] Kembali ke peta / Tutup modal');
    </script>
</body>
</html>