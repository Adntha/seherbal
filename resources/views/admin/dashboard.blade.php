<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin | SeHerbal</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <h2 class="brand">SeHerbal</h2>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="{{ route('admin.messages') }}" class="nav-item"><i class="fa-solid fa-envelope"></i> Pesan Masuk</a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-item logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Log Out</button>
                </form>
            </nav>
        </aside>

        <main class="main-content">
            <header class="content-header">
                <div>
                    <h1>Daftar Tanaman</h1>
                    <p>Manajemen Konten</p>
                </div>
                <a href="{{ route('admin.plants.create') }}" class="btn-add" style="text-decoration: none; display: inline-block;">
                    + Tambah Tanaman
                </a>
            </header>

            <section class="table-controls">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Cari">
                </div>
                <div class="stats-box">
                    <span class="stats-label">Total Tanaman</span>
                    <span class="stats-value">{{ $totalTanaman }}</span>
                </div>
                <div class="stats-box">
                    <span class="stats-label">Pending/Draft</span>
                    <span class="stats-value">{{ $totalDraft }}</span>
                </div>
            </section>

            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Tanaman</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan di-render oleh admin-dashboard.js -->
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #9ca3af;">
                                <i class="fa-solid fa-spinner fa-spin"></i> Memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/admin-dashboard.js') }}"></script>
</body>
</html>