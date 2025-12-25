<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SeHerbal - Tanaman herbal menyimpan manfaat untuk menjaga kesehatan, meredakan keluhan ringan, dan mendukung gaya hidup yang lebih alami.">
    <title>SeHerbal - Herbal Alami Setiap Hari</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <span class="logo-se">Se</span><span class="logo-herbal">Herbal</span>
                </div>
                
                <!-- Navigation -->
                <nav>
                    <ul>
                        <li><a href="/" class="active">Home</a></li>
                        <li><a href="#cari">Cari</a></li>
                        <li><a href="#kontak">Kontak</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <!-- Hero Text -->
                <div class="hero-text">
                    <p class="hero-subtitle">Sehat dari Alam,</p>
                    <h1 class="hero-title">
                        Herbal Alami
                        <span class="hero-title-highlight">Setiap Hari</span>
                    </h1>
                    <div class="hero-title-underline"></div>
                    <p class="hero-description">
                        Tanaman herbal menyimpan manfaat untuk menjaga kesehatan, meredakan keluhan ringan, dan mendukung gaya hidup yang lebih alami. Jelajahi beragam tanaman dan khasiatnya di SeHerbal.
                    </p>
                    <a href="#selengkapnya" class="cta-button">Selengkapnya</a>
                </div>

                <!-- Hero Image -->
                <div class="hero-image-container">
                    <div class="hero-image-circle">
                        <img src="{{ asset('images/leaf-hero.jpg') }}" alt="Tanaman Herbal Alami">
                    </div>
                    <!-- Extended Leaf -->
                    <img src="{{ asset('images/leaf-large.png') }}" alt="Daun Herbal" class="hero-leaf-extend">
                </div>
            </div>
        </div>

        <!-- Decorative Leaves -->
        <div class="decorative-leaves">
            <img src="{{ asset('images/leaf-large.png') }}" alt="" class="leaf-decoration leaf-top-right" style="--rotation: -15deg;">
            <img src="{{ asset('images/leaf-small.png') }}" alt="" class="leaf-decoration leaf-top-small" style="--rotation: 25deg;">
            <img src="{{ asset('images/leaf-large.png') }}" alt="" class="leaf-decoration leaf-bottom-left" style="--rotation: 10deg;">
            <img src="{{ asset('images/leaf-small.png') }}" alt="" class="leaf-decoration leaf-bottom-center" style="--rotation: -20deg;">
            <img src="{{ asset('images/leaf-small.png') }}" alt="" class="leaf-decoration leaf-bottom-right" style="--rotation: 45deg;">
        </div>
    </section>

    <!-- Chatbot Widget -->
    <div class="chatbot-widget">
        <a href="{{ url('/chatbot') }}" class="chatbot-button" id="chatbotButton">
            <span class="chatbot-label">Ada Pertanyaan?</span>
            <div class="chatbot-icon">
                <img src="{{ asset('images/chatbot-icon.png') }}" alt="Chatbot SeHerbal">
            </div>
        </a>
    </div>

    <!-- JavaScript for Smooth Interactions -->
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add subtle parallax effect to decorative leaves
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const leaves = document.querySelectorAll('.leaf-decoration');
            
            leaves.forEach((leaf, index) => {
                const speed = 0.1 + (index * 0.05);
                const yPos = -(scrolled * speed);
                leaf.style.transform = `translateY(${yPos}px) rotate(var(--rotation, 0deg))`;
            });
        });

        // Chatbot button pulse animation
        const chatbotButton = document.getElementById('chatbotButton');
        let pulseInterval;

        function startPulse() {
            pulseInterval = setInterval(() => {
                chatbotButton.style.animation = 'pulse 1.5s ease-in-out';
                setTimeout(() => {
                    chatbotButton.style.animation = '';
                }, 1500);
            }, 5000);
        }

        // Start pulse after 3 seconds
        setTimeout(startPulse, 3000);

        // Stop pulse on hover
        chatbotButton.addEventListener('mouseenter', () => {
            clearInterval(pulseInterval);
            chatbotButton.style.animation = '';
        });

        chatbotButton.addEventListener('mouseleave', () => {
            startPulse();
        });

        // Add entrance animations
        window.addEventListener('load', () => {
            const heroText = document.querySelector('.hero-text');
            const heroImage = document.querySelector('.hero-image-container');
            
            heroText.style.opacity = '0';
            heroImage.style.opacity = '0';
            heroText.style.transform = 'translateX(-30px)';
            heroImage.style.transform = 'translateX(30px)';
            
            setTimeout(() => {
                heroText.style.transition = 'all 0.8s ease-out';
                heroImage.style.transition = 'all 0.8s ease-out';
                heroText.style.opacity = '1';
                heroImage.style.opacity = '1';
                heroText.style.transform = 'translateX(0)';
                heroImage.style.transform = 'translateX(0)';
            }, 100);
        });
    </script>

    <!-- Add pulse animation to CSS -->
    <style>
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.08);
            }
        }
    </style>
</body>
</html>
