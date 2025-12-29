<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tanaman->name }} - SeHerbal</title>
    
    <link rel="stylesheet" href="{{ asset('css/tanaman-detail.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="page-wrapper">
        <header class="top-nav">
            <div class="container nav-container">
                <div class="logo">Se<span>Herbal</span></div>
                <nav class="main-menu">
                    <a href="/">Home</a>
                    <a href="/#cari" class="active">Cari</a>
                    <a href="/#kontak">Kontak</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <div class="navigation-container">
                <a href="{{ url('/') }}#cari" class="btn-back-modern">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Penjelajahan</span>
                </a>
            </div>

            <article class="plant-card">
                <div class="plant-flex-container">
                    <div class="plant-media-side">
                        <div class="image-wrapper">
                            <img src="{{ asset('storage/plants/' . $tanaman->image_path) }}" alt="{{ $tanaman->name }}">
                        </div>
                        
                        <!-- <div class="recipe-section">
                            <h3><i class="fa-solid fa-leaf"></i> Resep Ramuan Tradisional</h3>
                            <div class="recipe-content">
                                {!! nl2br(e($tanaman->usage)) !!}
                            </div>
                        </div> -->
                    </div>

                    <div class="plant-info-side">
                        <div class="plant-titles">
                            <h1 class="plant-name">{{ $tanaman->name }}</h1>
                            <span class="family-badge">{{ $tanaman->family ?? 'Zingiberaceae' }}</span>
                        </div>

                        <p class="description-text">
                            <strong>{{ $tanaman->name }}</strong> (<i>{{ $tanaman->latin_name }}</i>) 
                            {{ $tanaman->description }}
                        </p>

                        <div class="detail-box">
                            <div class="detail-header">
                                <i class="fa-solid fa-book-medical"></i> Detail Tanaman
                            </div>
                            <ul class="detail-list">
                                <li><strong>Nama Ilmiah:</strong> <i>{{ $tanaman->latin_name }}</i></li>
                                <li><strong>Famili:</strong> {{ $tanaman->family ?? '-' }}</li>
                                <li><strong>Manfaat Utama:</strong> {{ $tanaman->benefits }}</li>
                                <li><strong>Habitat:</strong> {{ $tanaman->habitat ?? 'Daerah Tropis' }}</li>
                                <li><strong>Bagian yang digunakan:</strong> {{ $tanaman->part_used ?? 'Rimpang' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </article>
        </main>
    </div>

    <script src="{{ asset('js/tanaman-detail.js') }}"></script>
</body>
</html>