<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARK - Tavern Island</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            color: white;
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

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: transparent;
            backdrop-filter: blur(10px);
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            z-index: 50;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .back-btn {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            padding: 10px 24px;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateX(-3px);
        }

        .page-title {
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 1px;
            color: white;
            opacity: 0.95;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 120px 40px 50px;
            position: relative;
            z-index: 2;
        }

        .content-wrapper {
            opacity: 0;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 80px;
            padding-top: 40px;
        }

        .welcome-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.8;
            font-weight: 400;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
        }

        .info-section {
            text-align: center;
            padding: 80px 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }

        .info-section i {
            font-size: 4rem;
            opacity: 0.6;
            margin-bottom: 25px;
        }

        .info-section h3 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .info-section p {
            opacity: 0.7;
            font-size: 1.1rem;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .page-title {
                font-size: 1rem;
            }

            .container {
                padding: 100px 20px 40px;
            }

            .welcome-title {
                font-size: 2.5rem;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            .info-section {
                padding: 50px 20px;
            }

            .info-section h3 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            .welcome-title {
                font-size: 2rem;
            }

            .back-btn {
                padding: 8px 16px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Background Image -->
    <img src="{{ asset('images/background/tavern-bg.png') }}" alt="Tavern Island Background" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- Header -->
    <div class="header">
        <button class="back-btn" onclick="goBack()">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Map</span>
        </button>
        <div class="page-title">Tavern Island</div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="content-wrapper" id="contentWrapper">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <h1 class="welcome-title">Welcome to the Tavern</h1>
                <p class="welcome-subtitle">
                    Take a break and enjoy the cozy atmosphere. More features coming soon!
                </p>
            </div>

            <!-- Info Section -->
            <div class="info-section">
                <i class="fas fa-mug-hot"></i>
                <h3>Relax & Unwind</h3>
                <p>The tavern is currently under renovation. Check back soon for exciting activities and mini games!</p>
            </div>
        </div>
    </div>

    <script>
        // Smooth entrance animation
        gsap.to('#contentWrapper', {
            duration: 0.8,
            opacity: 1,
            y: 0,
            ease: "power2.out",
            delay: 0.2
        });

        // Animate info section
        gsap.from('.info-section', {
            duration: 0.8,
            y: 30,
            opacity: 0,
            ease: "power2.out",
            delay: 0.5
        });

        function goBack() {
            gsap.to('#contentWrapper', {
                duration: 0.4,
                opacity: 0,
                y: -20,
                ease: "power2.in",
                onComplete: () => {
                    window.location.href = "{{ route('student.hero') }}";
                }
            });
        }
    </script>
</body>
</html>