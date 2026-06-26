<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - 👤 Profil Saya</title>
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

        /* ===== BACK BUTTON ===== */
        .back-btn {
            background: url('{{ asset("images/button/btn-back.png") }}') no-repeat center center;
            background-size: contain;
            border: none;
            width: 140px;
            height: 50px;
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

        /* ===== PROFILE CARD ===== */
        .profile-card {
            background: url('{{ asset("images/card/card-profile.png") }}') no-repeat center center;
            background-size: 100% 100%;
            border: none;
            padding: 88px 106px;
            border-radius: 24px;
            transition: all 0.3s ease;
            max-width: 520px;
            margin: 0 auto;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            filter: brightness(1.03);
        }

        .form-input {
            background: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 10px;
            padding: 8px 14px;
            color: #1a1a1a;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #888888;
            background: #ffffff;
        }

        .form-input::placeholder {
            color: rgba(0, 0, 0, 0.35);
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #ffffff;
            margin-bottom: 3px;
            display: block;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #888888;
            cursor: pointer;
            font-size: 1rem;
            padding: 4px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #444444;
        }

        .submit-btn {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 6px 18px;
            border-radius: 50px;
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.25);
        }

        .logout-btn {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.25);
            padding: 8px 24px;
            border-radius: 50px;
            color: #f87171;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            border-color: rgba(239, 68, 68, 0.35);
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.06);
            margin: 12px 0;
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

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .back-btn {
                width: 120px;
                height: 42px;
                font-size: 0.7rem;
            }
            .profile-card {
                padding: 88px 106px;
                max-width: 100%;
                border-radius: 24px;
            }
            .logout-wrapper {
                padding: 0 106px;
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .back-btn {
                width: 100px;
                height: 36px;
                font-size: 0.6rem;
            }
            .profile-card {
                padding: 88px 106px;
                max-width: 100%;
                border-radius: 24px;
            }
            .logout-wrapper {
                padding: 0 106px;
                max-width: 100%;
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }
            .form-input {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
            .form-label {
                font-size: 0.7rem;
            }
            .submit-btn {
                padding: 5px 14px;
                font-size: 0.65rem;
            }
            .logout-btn {
                padding: 6px 20px;
                font-size: 0.75rem;
            }
            .password-toggle {
                right: 10px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Background -->
    <img src="{{ asset('images/background/markasena-bg.png') }}" alt="Background" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <span class="toast-icon" id="toastIcon">✅</span>
        <span id="toastMessage">Berhasil!</span>
    </div>

    <!-- Back Button -->
    <button onclick="goBack()" class="back-btn fixed top-6 left-6 md:top-8 md:left-8 z-50">
        ← Kembali
    </button>

    <!-- Main Content -->
    <main class="relative z-[2] w-full min-h-screen flex items-center justify-center px-5 md:px-10 py-20">
        <div class="w-full max-w-2xl mx-auto" id="contentWrapper">

            <!-- Profile Card -->
            <div class="profile-card">

                <!-- Header tanpa avatar -->
                <div class="text-center mb-4">
                    <h2 class="text-xl font-bold text-white">{{ $user->name ?? 'User' }}</h2>
                    <p class="text-xs text-white/60">{{ $user->email ?? '' }}</p>
                    @if($group)
                        <div class="mt-2 inline-flex items-center gap-2 text-xs text-white/40 bg-white/10 px-3 py-1 rounded-full">
                            <i class="fas fa-users"></i>
                            <span>{{ $group->name }}</span>
                        </div>
                    @endif
                </div>

                <div class="divider"></div>

                <!-- Form Update Nama -->
                <form id="updateProfileForm" class="mb-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label">
                            <i class="fas fa-user text-xs mr-1.5"></i>Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ $user->name }}" 
                               class="form-input" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mt-2 flex justify-end">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-save text-xs mr-1.5"></i>Simpan
                        </button>
                    </div>
                </form>

                <div class="divider"></div>

                <!-- Form Update Password -->
                <form id="updatePasswordForm">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2">
                        <div>
                            <label class="form-label">
                                <i class="fas fa-lock text-xs mr-1.5"></i>Password Lama
                            </label>
                            <div class="password-wrapper">
                                <input type="password" name="current_password" class="form-input" 
                                       placeholder="Masukkan password lama" required id="currentPassword">
                                <button type="button" class="password-toggle" onclick="togglePassword('currentPassword', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">
                                <i class="fas fa-key text-xs mr-1.5"></i>Password Baru
                            </label>
                            <div class="password-wrapper">
                                <input type="password" name="password" class="form-input" 
                                       placeholder="Masukkan password baru (min 8 karakter)" required id="newPassword">
                                <button type="button" class="password-toggle" onclick="togglePassword('newPassword', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">
                                <i class="fas fa-check text-xs mr-1.5"></i>Konfirmasi Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password" name="password_confirmation" class="form-input" 
                                       placeholder="Konfirmasi password baru" required id="confirmPassword">
                                <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-end">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-key text-xs mr-1.5"></i>Ganti Password
                        </button>
                    </div>
                </form>

            </div>

            <!-- Logout - Diluar Card -->
            <div class="flex justify-between items-center mt-6 max-w-[520px] mx-auto px-[106px] logout-wrapper">
                <div>
                    <p class="text-xs text-white/60">Keluar dari akun</p>
                    <p class="text-[10px] text-white/30">Anda akan diarahkan ke halaman login</p>
                </div>
                <form action="{{ route('student.markasena.profile.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt text-xs mr-1.5"></i>Logout
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-center gap-4 mt-6">
                <div class="w-8 h-px bg-white/20"></div>
                <i class="fas fa-compass text-white/20 text-xs"></i>
                <div class="w-8 h-px bg-white/20"></div>
            </div>

        </div>
    </main>

    <script>
        // ============================================================
        //  TOGGLE PASSWORD VISIBILITY
        // ============================================================
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

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
        //  GO BACK - KE HALAMAN MARKASENA
        // ============================================================
        function goBack() {
            window.location.href = "{{ route('student.island.markasena') }}";
        }

        // ============================================================
        //  UPDATE PROFILE (AJAX)
        // ============================================================
        document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route("student.markasena.profile.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    const nameInput = document.querySelector('input[name="name"]');
                    document.querySelector('h2.text-xl').textContent = nameInput.value;
                } else {
                    showToast(data.message || 'Gagal mengupdate profil', 'error');
                }
            })
            .catch(err => {
                showToast('Terjadi kesalahan', 'error');
                console.error('Error:', err);
            });
        });

        // ============================================================
        //  UPDATE PASSWORD (AJAX)
        // ============================================================
        document.getElementById('updatePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route("student.markasena.profile.password") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    this.reset();
                } else {
                    showToast(data.message || 'Gagal mengupdate password', 'error');
                }
            })
            .catch(err => {
                showToast('Terjadi kesalahan', 'error');
                console.error('Error:', err);
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
        //  ANIMATIONS - opacity: 1 biar ga kedip
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            gsap.from('.profile-card', {
                duration: 0.5,
                y: 20,
                opacity: 1,
                ease: "power2.out",
                delay: 0.1
            });

            gsap.from('h2, p, .mt-2', {
                duration: 0.4,
                y: 15,
                opacity: 1,
                ease: "power2.out",
                delay: 0.2,
                stagger: 0.08
            });

            gsap.from('form, .divider', {
                duration: 0.4,
                y: 10,
                opacity: 1,
                ease: "power2.out",
                delay: 0.3,
                stagger: 0.08
            });

            gsap.from('.logout-wrapper', {
                duration: 0.4,
                y: 15,
                opacity: 1,
                ease: "power2.out",
                delay: 0.4
            });

            gsap.from('.flex.items-center.justify-center.gap-4.mt-6', {
                duration: 0.3,
                opacity: 1,
                ease: "power2.out",
                delay: 0.5
            });
        });

        console.log('👤 Markasena - Profil siap!');
        console.log('📌 Shortcut: [Esc] Kembali ke Markasena');
    </script>
</body>
</html>