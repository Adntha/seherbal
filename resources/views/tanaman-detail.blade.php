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
            <div class="container">
                <a href="{{ url('/') }}#cari" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> 
                    <span>Kembali ke Jelajah</span>
                </a>
            </div>
        </header>

        <main class="container">
            <article class="plant-detail">
                <figure class="plant-banner">
                    <img src="{{ asset('storage/plants/' . $tanaman->image_path) }}" alt="{{ $tanaman->name }}">
                    <figcaption class="plant-header-text">
                        <h1 class="plant-name">{{ $tanaman->name }}</h1>
                        <p class="plant-latin"><i>{{ $tanaman->latin_name }}</i></p>
                    </figcaption>
                </figure>

                <div class="plant-body">
                    <section class="info-block main-desc">
                        <h2 class="section-title"><i class="fa-solid fa-leaf"></i> Deskripsi</h2>
                        <p class="text-content">{{ $tanaman->description ?? 'Deskripsi belum tersedia.' }}</p>
                    </section>

                    <div class="info-grid">
                        <section class="info-block card-style">
                            <h2 class="section-title"><i class="fa-solid fa-hand-holding-medical"></i> Manfaat</h2>
                            <p class="text-content">{{ $tanaman->benefits ?? 'Manfaat belum tersedia.' }}</p>
                        </section>

                        <section class="info-block card-style">
                            <h2 class="section-title"><i class="fa-solid fa-mortar-pestle"></i> Penggunaan</h2>
                            <p class="text-content">{{ $tanaman->usage ?? 'Cara penggunaan belum tersedia.' }}</p>
                        </section>
                    </div>
                </div>
            </article>
        </main>
    </div>

    <script src="{{ asset('js/tanaman-detail.js') }}"></script>
</body>
</html>