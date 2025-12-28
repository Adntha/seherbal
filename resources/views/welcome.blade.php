<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loading-spinner"></div>
    </div>
    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="#home" class="logo-se">Se</a><a class="logo-herbal">Herbal</a>
                </div>
                
                <!-- Navigation -->
                <nav>  
                    <ul class="nav-menu" id="nav-menu">
                        <li class="nav-item">
                            <a href="#home" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="#cari" class="nav-link">Cari</a>
                        </li>
                        <li class="nav-item">
                            <a href="#kontak" class="nav-link">Kontak</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
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
                    <a href="#cari" class="cta-button">Selengkapnya</a>
                </div>

                <!-- Hero Image -->
                <div class="hero-image-container">
                    <div class="hero-image-circle">
                        <img src="{{ asset('images/leaf.png') }}" alt="Tanaman Herbal Alami">
                    </div>
                    <!-- Extended Leaf -->
                    <img src="{{ asset('images/leaf-large.png') }}" alt="Daun Herbal" class="hero-leaf-extend" style="--rotation: 25deg;">
                </div>
            </div>
        </div>

        <!-- Decorative Leaves -->
        <div class="decorative-leaves">
            <img src="{{ asset('images/leaf-large.png') }}" alt="" class="leaf-decoration leaf-top-right" style="--rotation: -30deg;">
            <img src="{{ asset('images/leaf-small.png') }}" alt="" class="leaf-decoration leaf-top-small" style="--rotation: 25deg;">
            <img src="{{ asset('images/leaf-large.png') }}" alt="" class="leaf-decoration leaf-bottom-left" style="--rotation: 10deg;">
            <img src="{{ asset('images/leaf-small.png') }}" alt="" class="leaf-decoration leaf-bottom-center" style="--rotation: -20deg;">
            <img src="{{ asset('images/leaf-small.png') }}" alt="" class="leaf-decoration leaf-bottom-right" style="--rotation: 45deg;">
        </div>
    </section>

    <!-- Cari Section -->
    <section id="cari" class="search-section">
        <div class="container">
            <div class="search-container">
                <div class="search-box">
                    <i class="search-icon">🔍</i>
                    <input type="text" id="searchInput" placeholder="Cari">
                </div>
            </div>

            <div class="herbal-grid" id="herbalGrid">
                @foreach($tanaman as $item)
                <a href="{{ route('tanaman.detail', $item->slug) }}" class="card-link">
                    <div class="herbal-card">
                        <img src="{{ asset('storage/plants/' . $item->image_path) }}" alt="{{ $item->name }}">
                        <div class="card-info">
                            <h3>{{ $item->name }}</h3>
                            <p>{{ $item->latin_name }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="search-footer" id="searchFooter">
                <a>Temukan Lebih Banyak</a>
                <div class="scroll-indicator">
                    <div class="scroll-arrow"></div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const herbalGrid = document.getElementById('herbalGrid');
            const footer = document.getElementById('searchFooter');

            // --- 1. LOGIKA LIVE SEARCH ---
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const term = searchInput.value.toLowerCase();
                    const cards = herbalGrid.getElementsByClassName('herbal-card');

                    Array.from(cards).forEach(card => {
                        const name = card.querySelector('h3').textContent.toLowerCase();
                        const latin = card.querySelector('p').textContent.toLowerCase();
                        
                        // Filter berdasarkan Nama Indonesia atau Nama Latin
                        if (name.includes(term) || latin.includes(term)) {
                            card.style.display = ""; 
                        } else {
                            card.style.display = "none";
                        }
                    });
                });
            }

            // --- 2. LOGIKA FETCH DATA (Load More) ---
            if (footer) {
                footer.onclick = function() {
                    // Pastikan route ini sesuai dengan di web.php
                    fetch('/plants/all')
                        .then(res => res.json())
                        .then(data => {
                            // Kosongkan grid sebelum mengisi 35 data baru
                            herbalGrid.innerHTML = '';
                            
                            data.forEach(item => {
                                herbalGrid.insertAdjacentHTML('beforeend', `
                                    <div class="herbal-card">
                                        <img src="/storage/plants/${item.image_path}" alt="${item.name}">
                                        <div class="card-info">
                                            <h3>${item.name}</h3>
                                            <p>${item.latin_name}</p>
                                        </div>
                                    </div>
                                `);
                            });
                            
                            // Sembunyikan tombol setelah semua data tampil
                            footer.style.display = 'none';
                        })
                        .catch(err => console.error("Gagal memuat data:", err));
                };
            }
        });
        </script>
    </section>

    <section id="kontak" class="contact-section">
        <div class="kontak-background"> 
            <img src="{{ asset('images/header-kontak.png') }}" alt="">
            <div class="background-overlay"></div>
        </div>
        <div class="contact-container">
            <div class="contact-header">
                <h2>Ada yang Bisa Kami Bantu?</h2>
                <p>Punya pertanyaan seputar manfaat tanaman atau butuh saran kesehatan alami? Kirimkan pesanmu, kami akan segera membalasnya.</p>
            </div>
            <div class="contact-card">
                <div class="contact-info">
                    <h3>Saluran Sehat</h3>
                    <p>Tim ahli kami dan Herbabot siap memberikan informasi terbaik untuk perjalanan sehat alamimu.</p>
                    

                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>+628123456789<br>+628987654321</span> 
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@seherbal.com</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-share-alt"></i>
                        <span>Bagikan Sehatmu</span>
                    </div>
                </div>

                <div class="contact-form">
                    <form action="#" id="formKontak">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama" id="inputNama" placeholder="Sudha Adi" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" id="inputEmail" placeholder="sudhaadi@gmail.com" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Subjek</label>
                            <input type="text" name="subjek" id="inputSubjek" required>
                        </div>

                        <div class="form-group">
                            <label class="label-green">Pesan</label>
                            <textarea name="pesan" id="inputPesan" placeholder="Tulis pesan atau pertanyaanmu di sini..." required></textarea>
                        </div>

                        <button type="submit" class="btn-kirim">Kirim Pesan</button>
                    </form>
                    
                    <script>
                    document.getElementById('formKontak').addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(this);
                        const submitBtn = this.querySelector('.btn-kirim');
                        const originalText = submitBtn.textContent;
                        
                        // Disable button dan ubah text
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Mengirim...';
                        
                        fetch('{{ route("contact.send") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content || '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                // Reset form
                                document.getElementById('formKontak').reset();
                            } else {
                                alert('Terjadi kesalahan. Silakan coba lagi.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
                        })
                        .finally(() => {
                            // Enable button kembali
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                        });
                    });
                    </script>
                </div>
            </div>

            <footer class="contact-footer">
                <p>@SeHerbal | 2025</p>
            </footer>
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
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
