<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - Pulau Profil</title>
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
            background: linear-gradient(135deg, #1a237e 0%, #0d1b4a 100%);
            color: white;
        }

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
            max-width: 700px;
            margin: 0 auto;
            padding: 100px 25px 50px;
        }

        .profile-card {
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
            border-radius: 40px;
            padding: 40px;
            border: 1px solid rgba(255,215,0,0.3);
            margin-bottom: 25px;
            opacity: 0;
        }

        .profile-avatar {
            text-align: center;
            margin-bottom: 25px;
        }

        .avatar-icon {
            font-size: 5rem;
            background: rgba(255,215,0,0.2);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 2px solid #ffd966;
        }

        .info-group {
            margin-bottom: 20px;
        }

        .info-label {
            font-size: 0.8rem;
            opacity: 0.7;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1.2rem;
            font-weight: 500;
            background: rgba(255,255,255,0.1);
            padding: 10px 15px;
            border-radius: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 0.8rem;
            opacity: 0.7;
            margin-bottom: 5px;
            display: block;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 15px;
            color: white;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #ffd966;
            background: rgba(255,255,255,0.15);
        }

        .btn {
            padding: 12px 25px;
            border-radius: 30px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ffd966, #ffab00);
            color: #1a237e;
        }

        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(255,215,0,0.3);
        }

        .btn-danger {
            background: rgba(220, 53, 69, 0.8);
            color: white;
        }

        .btn-danger:hover {
            background: #dc3545;
        }

        .btn-secondary {
            background: rgba(255,255,255,0.15);
            color: white;
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.25);
        }

        .logout-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            display: none;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid #4caf50;
            color: #4caf50;
        }

        .alert-error {
            background: rgba(244, 67, 54, 0.2);
            border: 1px solid #f44336;
            color: #f44336;
        }

        h3 {
            margin-bottom: 20px;
            color: #ffd966;
        }

        hr {
            margin: 25px 0;
            border-color: rgba(255,255,255,0.1);
        }

        @media (max-width: 640px) {
            .profile-card { padding: 25px; }
            .btn { padding: 10px 20px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">← Kembali ke Peta</button>
        <div class="page-title">👤 <span>Pulau Profil</span> 👤</div>
    </div>

    <div class="container">
        <div class="profile-card" id="profileCard">
            <div class="profile-avatar">
                <div class="avatar-icon">⛵</div>
            </div>

            <div id="alertMessage" class="alert"></div>

            <!-- Informasi User -->
            <div class="info-group">
                <div class="info-label">NIM</div>
                <div class="info-value" id="userNim">{{ auth()->user()->nim ?? '-' }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Kelompok</div>
                <div class="info-value" id="userGroup">{{ auth()->user()->group->name ?? 'Belum ada kelompok' }}</div>
            </div>

            <hr>

            <!-- Ganti Nama -->
            <h3>✏️ Ganti Nama</h3>
            <form id="updateNameForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-input" id="name" name="name" value="{{ auth()->user()->name }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>

            <hr>

            <!-- Ganti Password -->
            <h3>🔒 Ganti Password</h3>
            <form id="updatePasswordForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Password Lama</label>
                    <input type="password" class="form-input" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" class="form-input" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-input" id="password_confirmation" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary">Ganti Password</button>
            </form>

            <hr>

            <!-- Logout -->
            <div class="logout-section">
                <form id="logoutForm" action="{{ route('student.island.profile.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">🚪 Logout</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        gsap.fromTo('#profileCard', { y: -30, opacity: 0 }, { duration: 0.6, y: 0, opacity: 1, ease: "back.out" });

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

        function showAlert(message, type = 'success') {
            const alert = document.getElementById('alertMessage');
            alert.textContent = message;
            alert.className = `alert alert-${type}`;
            alert.style.display = 'block';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 3000);
        }

        // Update Nama via AJAX
        document.getElementById('updateNameForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch("{{ route('student.island.profile.update') }}", {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name: formData.get('name') })
                });
                
                const data = await response.json();
                if (response.ok) {
                    showAlert(data.message, 'success');
                    document.getElementById('userName').textContent = formData.get('name');
                } else {
                    showAlert(data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                showAlert('Gagal menghubungi server', 'error');
            }
        });

        // Ganti Password via AJAX
        document.getElementById('updatePasswordForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch("{{ route('student.island.profile.password') }}", {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: formData.get('current_password'),
                        password: formData.get('password'),
                        password_confirmation: formData.get('password_confirmation')
                    })
                });
                
                const data = await response.json();
                if (response.ok) {
                    showAlert(data.message, 'success');
                    e.target.reset();
                } else {
                    showAlert(data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                showAlert('Gagal menghubungi server', 'error');
            }
        });
    </script>
</body>
</html>